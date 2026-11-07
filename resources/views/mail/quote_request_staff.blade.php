@extends('mail.layouts.brand')

@section('title', 'New quote request #' . $enquiry->orderid)
@section('preheader', $enquiry->name . ($enquiry->company ? ' (' . $enquiry->company . ')' : '') . ' sent a quote request.')
@section('reason', 'Staff notification from the ' . config('company.name') . ' website.')
@section('signoff')@endsection

@section('content')
  <p style="margin:0 0 14px;font-size:13px;color:#6a6e75;">Received {{ $enquiry->created_at->format('d M Y, H:i') }}</p>
  <p style="margin:0;"><strong>{{ $enquiry->name }}</strong>@if($enquiry->company) &middot; {{ $enquiry->company }}@endif</p>
  <p style="margin:2px 0 0;">{{ $enquiry->email }}@if($enquiry->phone) &middot; {{ $enquiry->phone }}@endif</p>

  @include('mail.partials.quote_items')

  @if($enquiry->message)
    <p style="margin:0 0 4px;"><strong>Customer message</strong></p>
    <p style="margin:0 0 14px;white-space:pre-line;">{{ $enquiry->message }}</p>
  @endif

  @include('mail.partials.button', ['url' => route('orders.index'), 'label' => 'Open Store Enquiries'])
@endsection
