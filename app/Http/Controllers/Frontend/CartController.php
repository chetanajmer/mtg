<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Cartorder;
use App\Models\Enquiry;
use App\Models\Product;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    /**
     * Number of lines in the current cart (the number the header cart badge shows).
     */
    public function lineCount()
    {
        return $this->cartQuery()->count();
    }

    /**
     * Whether the current cart already has a line for this product.
     */
    public function hasProduct($productId)
    {
        return $this->cartQuery()->where('product_id', $productId)->exists();
    }

    private function cartQuery()
    {
        if (session()->has('userid')) {
            return CartItem::where('user_id', session()->get('userid'));
        }

        return CartItem::where('session_id', session()->getId());
    }

    /**
     * Re-price every cart line from the product so the cart always uses the
     * current tier price for its quantity.
     */
    private function refreshPrices($items)
    {
        $products = Product::whereIn('id', $items->pluck('product_id'))->get()->keyBy('id');

        foreach ($items as $item) {
            $product = $products->get($item->product_id);
            if (!$product) {
                continue;
            }

            $price = $product->priceForQuantity($item->quantity);
            if ((float) $item->price !== $price) {
                $item->price = $price;
                $item->save();
            }
        }

        return $items;
    }

    private function taxRate()
    {
        return (float) config('app.cart_tax_rate', 0.05);
    }

    public function index(Request $request)
    {
        $items = $this->refreshPrices($this->cartQuery()->orderBy('id')->get());
        $user = session()->has('userid')
            ? DB::table('storeusers')->where('id', session()->get('userid'))->first()
            : null;

        $subtotal = $items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $tax = round($subtotal * $this->taxRate(), 2);
        $total = $subtotal + $tax;

        return view('web_frontend.cart', compact('items', 'user', 'subtotal', 'tax', 'total'));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $this->addProductToCart(Product::find($request->product_id), $request->quantity);

        $count = $this->cartQuery()->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Added to Cart!',
            'count' => $count,
        ]);
    }

    /**
     * Add a product to the current cart (or raise its quantity), never below
     * the product's minimum order quantity, at the tier price for the new total.
     * Also used to send a saved collection to the cart.
     */
    public function addProductToCart(Product $product, $quantity)
    {
        $quantity = max((int) ($quantity ?: 1), $product->minOrderQuantity());

        $existing = $this->cartQuery()->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->quantity += $quantity;
            $existing->price = $product->priceForQuantity($existing->quantity);
            $existing->save();
            return $existing;
        }

        $item = new CartItem();
        $item->user_id = session()->get('userid');
        $item->session_id = session()->has('userid') ? null : session()->getId();
        $item->product_id = $product->id;
        $item->name = $product->name;
        $item->modelno = $product->modelno;
        $item->price = $product->priceForQuantity($quantity);
        $item->quantity = $quantity;
        $item->image = $product->image1;
        $item->save();
        return $item;
    }

    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = $this->cartQuery()->where('id', $id)->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        $item->quantity = (int) $request->quantity;
        $product = Product::find($item->product_id);
        if ($product) {
            // Never less than the product's minimum order quantity
            $item->quantity = max($item->quantity, $product->minOrderQuantity());
            $item->price = $product->priceForQuantity($item->quantity);
        }
        $item->save();

        return response()->json(['success' => true, 'price' => (float) $item->price, 'quantity' => (int) $item->quantity]);
    }

    public function removeItem($id)
    {
        $this->cartQuery()->where('id', $id)->delete();

        return response()->json(['success' => true]);
    }

    public function clearCart()
    {
        $this->cartQuery()->delete();

        return response()->json(['success' => true]);
    }

    public function sendEnquiry(Request $request)
    {
        if (!session()->has('userid')) {
            return redirect()->route('cart.index');
        }

        $items = $this->refreshPrices($this->cartQuery()->orderBy('id')->get());

        if ($items->isEmpty()) {
            $request->session()->flash('error', 'Your cart is empty.');
            return redirect()->route('cart.index');
        }

        $user = DB::table('storeusers')->where('id', session()->get('userid'))->first();

        $subtotal = $items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $tax = round($subtotal * $this->taxRate(), 2);
        $total = $subtotal + $tax;

        $customerName = trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')) ?: 'Customer';

        $enquiry = DB::transaction(function () use ($request, $items, $user, $customerName, $subtotal, $tax, $total) {
            // Same order numbers as the old enquiry flow (orderids counter)
            $last = DB::table('orderids')->lockForUpdate()->max('orderid');
            $orderid = $last ? ((int) $last + 1) : 10001;
            DB::table('orderids')->insert(['orderid' => $orderid]);

            $enquiry = new Enquiry();
            $enquiry->orderid = $orderid;
            $enquiry->name = $customerName;
            $enquiry->email = $user->email ?? '';
            $enquiry->phone = $user->mobile ?? '';
            $enquiry->company = $user->company ?? '';
            $enquiry->message = $request->message ?? 'Enquiry for cart items';
            $enquiry->subtotal = $subtotal;
            $enquiry->tax = $tax;
            $enquiry->total = $total;
            $enquiry->cart_items = $items->map(function ($item) {
                return [
                    'name' => $item->name,
                    'modelno' => $item->modelno,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'image' => $item->image,
                ];
            })->toJson();
            $enquiry->save();

            // One Store Enquiries row per product, so staff see it and can track it
            $products = Product::whereIn('id', $items->pluck('product_id'))->get()->keyBy('id');
            foreach ($items as $item) {
                $product = $products->get($item->product_id);
                $order = new Cartorder();
                $order->orderid = $orderid;
                $order->userid = $user->id ?? null;
                $order->productid = $item->product_id;
                $order->slug = $product->slug ?? null;
                $order->modelno = $item->modelno;
                $order->name = $item->name;
                $order->thumbnail = $product->thumbnail ?? $item->image;
                $order->price = $item->price;
                $order->quantity = $item->quantity;
                $order->subtotal = round($item->price * $item->quantity, 2);
                $order->ordertotal = $total;
                $order->orderstatus = 'Pending';
                $order->customer_name = $customerName;
                $order->customer_email = $user->email ?? null;
                $order->customer_phone = $user->mobile ?? null;
                $order->customer_company = $user->company ?? null;
                $order->additional_info = $request->message;
                $order->save();
            }

            // The tracked order (10 steps + timeline) for this enquiry
            \App\Services\OrderWorkflow::createForCartorders($orderid, Cartorder::where('orderid', $orderid)->orderBy('id')->get());

            return $enquiry;
        });

        $this->cartQuery()->delete();
        $this->sendQuoteEmails($enquiry, $items);

        // Only this browser may open the confirmation page
        $request->session()->push('my_enquiries', $enquiry->id);

        return redirect()->route('enquiry.success', $enquiry->id);
    }

    /**
     * Tell staff and the customer about a new quote request. A mail problem is
     * logged but never stops the customer's request.
     */
    private function sendQuoteEmails(Enquiry $enquiry, $items)
    {
        $staff = staff_email();
        $data = ['enquiry' => $enquiry, 'items' => $items];

        $send = function ($to, $view, $subject) use ($data) {
            try {
                Mail::send($view, $data, function ($message) use ($to, $subject) {
                    $message->to($to)->subject($subject);
                });
            } catch (\Throwable $e) {
                Log::error('Quote request email to ' . $to . ' failed: ' . $e->getMessage());
            }
        };

        if (!empty($staff)) {
            $send($staff, 'mail.quote_request_staff', 'New quote request #' . $enquiry->orderid . ' from ' . $enquiry->name);
        } else {
            Log::warning('No QUOTE_REQUEST_EMAIL or header email set; staff were not emailed about quote #' . $enquiry->orderid);
        }
        if (!empty($enquiry->email)) {
            $send($enquiry->email, 'mail.quote_request_customer', 'We received your quote request #' . $enquiry->orderid);
        }
    }

    public function enquirySuccess($id)
    {
        $enquiry = Enquiry::findOrFail($id);

        // Only the session or customer that sent the enquiry may see it
        $ownSession = in_array((int) $id, array_map('intval', session()->get('my_enquiries', [])), true);
        $user = session()->has('userid') ? DB::table('storeusers')->where('id', session()->get('userid'))->first() : null;
        $ownAccount = $user && !empty($user->email) && strcasecmp($user->email, $enquiry->email) === 0;
        if (!$ownSession && !$ownAccount) {
            abort(404);
        }

        $items = collect(json_decode($enquiry->cart_items, true) ?: []);
        $totalItems = $items->count();
        $totalQuantity = $items->sum('quantity');

        return view('web_frontend.enquiry-success', compact('enquiry', 'items', 'totalItems', 'totalQuantity'));
    }
}
