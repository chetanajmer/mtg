@php
  $itemCount = collect($items)->count();
  $qty = collect($items)->sum('quantity');
  $productNames = collect($items)->pluck('name')->filter()->take(3)->implode(', ') . ($itemCount > 3 ? ' +' . ($itemCount - 3) . ' more' : '');
@endphp
@extends('mail.layouts.brand', ['hero' => [
  'variant' => 'dark',
  'title' => 'New Quote',
  'accent' => 'Request Received!',
  'text' => 'A customer has requested a quote for your products. Here are the details:',
  'icon' => '&#127873;',
]])

@section('title', 'New quote request #' . $enquiry->orderid)
@section('preheader', $enquiry->name . ($enquiry->company ? ' (' . $enquiry->company . ')' : '') . ' sent a quote request.')
@section('reason', 'Staff notification from the ' . config('company.name') . ' website.')
@section('signoff')@endsection
@section('closing', 'Please get back to the customer shortly.')

@section('content')
  @include('mail.partials.details', ['rows' => [
    ['&#128203;', 'Request ID', '#' . $enquiry->orderid],
    ['&#128100;', 'Name', $enquiry->name],
    ['&#127970;', 'Company', $enquiry->company],
    ['&#9993;', 'Email', $enquiry->email],
    ['&#128222;', 'Phone', $enquiry->phone],
    ['&#127873;', 'Products', $productNames],
    ['&#128230;', 'Quantity', $qty . ' pcs' . ($itemCount > 1 ? ' (' . $itemCount . ' products)' : '')],
    ['&#128197;', 'Submitted On', optional($enquiry->created_at)->format('d M Y, h:i A')],
    ['&#128172;', 'Message', $enquiry->message],
  ]])

  @include('mail.partials.quote_items')

  @include('mail.partials.button', ['url' => route('orders.index'), 'label' => 'View Request'])
@endsection
