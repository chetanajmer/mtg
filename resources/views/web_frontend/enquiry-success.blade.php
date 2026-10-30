@extends('web_frontend.layouts.app')

@section('title', 'Enquiry Submitted | More Than Giftz')
@section('meta')
  <meta name="description" content="Your enquiry has been submitted successfully.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/enquiry-success.css') }}">
@endsection

@section('content')
@php
  $enquiryNumber = '#MTG-' . $enquiry->created_at->format('Y') . '-' . str_pad($enquiry->id, 5, '0', STR_PAD_LEFT);
@endphp

<!-- BREADCRUMB -->
<div class="container-fluid px-4 px-xl-5">
  <div class="breadcrumb-row">
    <span>Home</span><i class="bi bi-chevron-right"></i>
    <span>Enquiry Cart</span><i class="bi bi-chevron-right"></i>
    <span>Order Success</span>
  </div>
</div>

<section class="container-fluid px-4 px-xl-5 es-section">
  <div class="es-card">
    <!-- LEFT IMAGE -->
    <div class="es-hero">
      <img src="{{ asset('web_frontend/assets/images/Thoughtful-Gifts.png') }}" alt="More Than Giftz">
    </div>

    <!-- CENTER MESSAGE -->
    <div class="es-message">
      <div class="es-check"><i class="bi bi-check-lg"></i></div>
      <h1>Thank You!</h1>
      <p class="es-sub">Your enquiry has been submitted successfully.</p>
      <p class="es-desc">We have received your product details and our team will get back to you with a customized quotation soon.</p>
      <div class="es-chips">
        <div class="es-chip">
          <i class="bi bi-envelope"></i>
          <div><small>Enquiry Number</small><strong>{{ $enquiryNumber }}</strong></div>
        </div>
        <div class="es-chip">
          <i class="bi bi-calendar3"></i>
          <div><small>Submitted On</small><strong>{{ $enquiry->created_at->format('d-M-Y, h:i A') }}</strong></div>
        </div>
      </div>
    </div>

    <!-- RIGHT SUMMARY -->
    <aside class="es-summary">
      <div class="es-summary-head">
        <h3>Enquiry Summary</h3>
        <a href="#">View Enquiry Details <i class="bi bi-arrow-right"></i></a>
      </div>
      <div class="es-summary-items">
        @foreach($items as $item)
        <div class="es-item">
          <div class="es-item-img">
            @if(!empty($item['image']))
              <img src="{{ asset('upload/product/' . $item['image']) }}" alt="{{ $item['name'] }}">
            @else
              <i class="bi bi-image"></i>
            @endif
          </div>
          <div class="es-item-info">
            <strong>{{ $item['name'] }}</strong>
            <small>Model No: {{ $item['modelno'] ?? 'N/A' }}</small>
            <small>Quantity: {{ $item['quantity'] }} pcs</small>
          </div>
          <div class="es-item-price">
            AED {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1)) }}
            <small>(est.)</small>
          </div>
        </div>
        @endforeach
      </div>
      <div class="es-summary-totals">
        <div><span>Total Items</span><span>{{ $totalItems }}</span></div>
        <div><span>Total Quantity</span><span>{{ $totalQuantity }} pcs</span></div>
        <div class="es-grand"><span>Estimated Total</span><span>AED {{ number_format($enquiry->total, 2) }}</span></div>
      </div>
    </aside>
  </div>

  <!-- WHAT HAPPENS NEXT + NEED HELP -->
  <div class="es-bottom">
    <div class="es-steps-card">
      <h2>What Happens Next?</h2>
      <div class="es-steps">
        <div class="es-step">
          <div class="es-step-icon"><i class="bi bi-envelope"></i></div>
          <strong>1. Review</strong>
          <p>Our team will review your enquiry details.</p>
        </div>
        <div class="es-step">
          <div class="es-step-icon"><i class="bi bi-file-earmark-text"></i></div>
          <strong>2. Get Quotation</strong>
          <p>You will receive a customized quotation on your registered email or via call.</p>
        </div>
        <div class="es-step">
          <div class="es-step-icon"><i class="bi bi-chat-dots"></i></div>
          <strong>3. Discussion</strong>
          <p>Our team will discuss options, customization and final pricing with you.</p>
        </div>
        <div class="es-step">
          <div class="es-step-icon"><i class="bi bi-gear"></i></div>
          <strong>4. Confirmation</strong>
          <p>Once you approve, we will proceed with the next steps for production and delivery.</p>
        </div>
      </div>
    </div>

    <div class="es-help-card">
      <div class="es-help-head">
        <i class="bi bi-headset"></i>
        <strong>Need Help?</strong>
      </div>
      <p>Feel free to contact our support team for any questions.</p>
      <a href="tel:+971557863450" class="es-btn-gold"><i class="bi bi-telephone"></i> Call / WhatsApp</a>
      <a href="mailto:support@morethangiftz.com" class="es-btn-outline"><i class="bi bi-envelope"></i> Email Us</a>
    </div>
  </div>

  <!-- ACTION BUTTONS -->
  <div class="es-actions">
    <a href="/" class="es-btn-gold es-btn-lg">Continue Shopping <i class="bi bi-arrow-right"></i></a>
    <a href="{{ route('corporate.portal') }}" class="es-btn-outline es-btn-lg"><i class="bi bi-file-text"></i> View My Enquiries</a>
  </div>
</section>
@endsection
