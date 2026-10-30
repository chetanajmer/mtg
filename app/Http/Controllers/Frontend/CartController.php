<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Enquiry;
use App\Models\Product;
use DB;

class CartController extends Controller
{
    private function cartQuery()
    {
        if (session()->has('userid')) {
            return CartItem::where('user_id', session()->get('userid'));
        }

        return CartItem::where('session_id', session()->getId());
    }

    public function index(Request $request)
    {
        $items = $this->cartQuery()->orderBy('id')->get();
        $user = session()->has('userid')
            ? DB::table('storeusers')->where('id', session()->get('userid'))->first()
            : null;

        $subtotal = $items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $tax = round($subtotal * 0.05, 2);
        $total = $subtotal + $tax;

        return view('web_frontend.cart', compact('items', 'user', 'subtotal', 'tax', 'total'));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $quantity = (int) ($request->quantity ?: 1);

        $query = $this->cartQuery()->where('product_id', $product->id);
        $existing = $query->first();

        if ($existing) {
            $existing->quantity += $quantity;
            $existing->save();
        } else {
            $item = new CartItem();
            $item->user_id = session()->get('userid');
            $item->session_id = session()->has('userid') ? null : session()->getId();
            $item->product_id = $product->id;
            $item->name = $product->name;
            $item->modelno = $product->modelno;
            $item->price = $product->price ?? 0;
            $item->quantity = $quantity;
            $item->image = $product->image1;
            $item->save();
        }

        $count = $this->cartQuery()->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Added to Cart!',
            'count' => $count,
        ]);
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

        $item->quantity = $request->quantity;
        $item->save();

        return response()->json(['success' => true]);
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

        $items = $this->cartQuery()->orderBy('id')->get();

        if ($items->isEmpty()) {
            $request->session()->flash('error', 'Your cart is empty.');
            return redirect()->route('cart.index');
        }

        $user = DB::table('storeusers')->where('id', session()->get('userid'))->first();

        $subtotal = $items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $tax = round($subtotal * 0.05, 2);
        $total = $subtotal + $tax;

        $enquiry = new Enquiry();
        $enquiry->name = trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')) ?: 'Customer';
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

        $this->cartQuery()->delete();

        return redirect()->route('enquiry.success', $enquiry->id);
    }

    public function enquirySuccess($id)
    {
        $enquiry = Enquiry::findOrFail($id);

        $items = collect(json_decode($enquiry->cart_items, true) ?: []);
        $totalItems = $items->count();
        $totalQuantity = $items->sum('quantity');

        return view('web_frontend.enquiry-success', compact('enquiry', 'items', 'totalItems', 'totalQuantity'));
    }
}
