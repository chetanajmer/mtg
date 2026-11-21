@extends('mail.layouts.brand', ['hero' => [
  'variant' => 'success',
  'title' => 'Enquiry',
  'accent' => 'Received!',
  'text' => 'Thank you, ' . $enquiry->name . '. Your quote request has been sent successfully.',
  'icon' => '&#10004;',
]])

@section('title', 'Thank you, ' . $enquiry->name)
@section('preheader', 'We have received your quote request #' . $enquiry->orderid . '.')
@section('closing', 'Thank you for choosing ' . config('company.name') . '! We\'ll get back to you with a confirmed quote.')

@section('content')
  @include('mail.partials.id_badge', [
    'label' => 'Request ID',
    'id' => '#' . $enquiry->orderid,
    'dateLabel' => 'Request Date',
    'date' => optional($enquiry->created_at)->format('d M Y, h:i A'),
  ])

  <p style="margin:0 0 4px;">Our team will review your request and send you a confirmed quote.</p>
  <p style="margin:0 0 4px;font-size:13px;color:#6a6e75;">Prices below are estimates; the final quote may change with branding options and delivery.</p>

  @include('mail.partials.quote_items')

  @include('mail.partials.button', ['url' => url('/orders/' . $enquiry->orderid), 'label' => 'View Request Details'])
@endsection
