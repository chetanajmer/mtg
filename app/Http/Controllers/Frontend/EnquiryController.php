<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enquiry;

class EnquiryController extends Controller
{
    public function sendEnquiry(Request $request)
    {
        $enquiry = new Enquiry();
        $enquiry->name = $request->name ?? 'Guest User';
        $enquiry->email = $request->email ?? 'guest@example.com';
        $enquiry->phone = $request->phone ?? '';
        $enquiry->company = $request->company ?? '';
        $enquiry->message = $request->message ?? '';
        $enquiry->subtotal = $request->subtotal ?? 0;
        $enquiry->tax = $request->tax ?? 0;
        $enquiry->total = $request->total ?? 0;
        $enquiry->cart_items = $request->cart_items ?? '';
        $enquiry->save();

        return response()->json(['success' => true, 'message' => 'Enquiry sent successfully!']);
    }
}
