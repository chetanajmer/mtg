@extends('mail.layouts.brand')

@section('title', 'Thank you, ' . $enquiry->name)
@section('preheader', 'We have received your quote request #' . $enquiry->orderid . '.')

@section('content')
  <p style="margin:0 0 10px;">We have received your quote request <strong>#{{ $enquiry->orderid }}</strong>. Our team will review it and get back to you with a confirmed quote.</p>
  <p style="margin:0 0 4px;font-size:13px;color:#6a6e75;">Prices below are estimates; the final quote may change with branding options and delivery.</p>

  @include('mail.partials.quote_items')

  @include('mail.partials.button', ['url' => url('/orders/' . $enquiry->orderid), 'label' => 'Follow my order'])
@endsection
