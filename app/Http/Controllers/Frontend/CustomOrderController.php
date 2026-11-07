<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cartorder;
use App\Models\Enquiry;
use App\Services\OrderWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * "Request a Quote": a customer asks for something custom. It becomes a normal
 * tracked order with no price yet; staff set the price when they confirm step 1.
 */
class CustomOrderController extends Controller
{
    const BRANDING = ['yes' => 'Yes, with our logo', 'no' => 'No branding needed', 'not_sure' => 'Not sure yet'];

    public function create(Request $request)
    {
        if (!session()->has('userid')) {
            return redirect()->route('corporate.login', ['redirect' => $request->getRequestUri()]);
        }

        return view('web_frontend.custom-order', [
            'user' => DB::table('storeusers')->where('id', session()->get('userid'))->first(),
            'branding' => self::BRANDING,
            'product' => mb_substr((string) $request->query('product', ''), 0, 150),
        ]);
    }

    public function store(Request $request, CartController $cart)
    {
        if (!session()->has('userid')) {
            return redirect()->route('corporate.login', ['redirect' => route('custom-order.create', [], false)]);
        }

        $data = $request->validate([
            'title' => 'required|string|min:3|max:150',
            'details' => 'required|string|max:3000',
            'quantity' => 'required|integer|min:1|max:1000000',
            'budget' => 'nullable|numeric|min:0|max:10000000',
            'needed_by' => 'nullable|date|after_or_equal:today',
            'branding' => 'required|in:' . implode(',', array_keys(self::BRANDING)),
            'phone' => 'nullable|string|max:30',
        ], [
            'title.required' => 'Please tell us what you would like.',
            'details.required' => 'Please add some details so we can quote accurately.',
            'needed_by.after_or_equal' => 'The date you need it by can\'t be in the past.',
        ]);

        $user = DB::table('storeusers')->where('id', session()->get('userid'))->first();
        $name = trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')) ?: 'Customer';
        $phone = trim((string) ($data['phone'] ?? '')) ?: ($user->mobile ?? '');

        $summary = array_filter([
            trim($data['details']),
            !empty($data['budget']) ? 'Budget per piece: AED ' . number_format($data['budget'], 2) : null,
            !empty($data['needed_by']) ? 'Needed by: ' . \Carbon\Carbon::parse($data['needed_by'])->format('d M Y') : null,
            'Custom branding: ' . self::BRANDING[$data['branding']],
        ]);
        $message = implode("\n", $summary);

        [$enquiry, $rows] = DB::transaction(function () use ($data, $user, $name, $phone, $message) {
            $last = DB::table('orderids')->lockForUpdate()->max('orderid');
            $orderid = $last ? ((int) $last + 1) : 10001;
            DB::table('orderids')->insert(['orderid' => $orderid]);

            $enquiry = new Enquiry();
            $enquiry->orderid = $orderid;
            $enquiry->name = $name;
            $enquiry->email = $user->email ?? '';
            $enquiry->phone = $phone;
            $enquiry->company = $user->company ?? '';
            $enquiry->message = $message;
            $enquiry->subtotal = 0;
            $enquiry->tax = 0;
            $enquiry->total = 0;
            $enquiry->cart_items = json_encode([['name' => trim($data['title']), 'modelno' => Cartorder::CUSTOM_MODEL, 'quantity' => (int) $data['quantity'], 'price' => 0, 'image' => null]]);
            $enquiry->save();

            $order = new Cartorder();
            $order->orderid = $orderid;
            $order->userid = $user->id ?? null;
            $order->productid = null;
            $order->modelno = Cartorder::CUSTOM_MODEL;
            $order->name = trim($data['title']);
            $order->price = 0;
            $order->quantity = (int) $data['quantity'];
            $order->subtotal = 0;
            $order->ordertotal = 0;
            $order->orderstatus = 'Pending';
            $order->customer_name = $name;
            $order->customer_email = $user->email ?? null;
            $order->customer_phone = $phone;
            $order->customer_company = $user->company ?? null;
            $order->additional_info = $message;
            $order->save();

            $rows = Cartorder::where('orderid', $orderid)->get();
            OrderWorkflow::createForCartorders($orderid, $rows);

            return [$enquiry, $rows];
        });

        $cart->sendQuoteEmails($enquiry, $rows);
        $request->session()->push('my_enquiries', $enquiry->id);

        return redirect()->route('enquiry.success', $enquiry->id);
    }
}
