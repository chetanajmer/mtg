@extends('web_frontend.layouts.app')

@section('title', 'My Orders | More Than Giftz')
@section('meta')
  <meta name="description" content="View and manage your corporate orders.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/orders-new.css') }}">
@endsection

@section('content')
@php
  $orders = $orders ?? collect();
  $steps = config('order_steps');
  $labels = \App\Services\OrderWorkflow::CUSTOMER_LABELS;
  $waiting = $orders->filter(function ($o) { return $o->step_state === 'waiting_customer' || ($o->current_step == 10 && $o->step_state === 'in_progress'); });
@endphp

<div class="orders-page">
  <div class="container-fluid px-4 px-xl-5">

    <nav class="account-breadcrumb" aria-label="Breadcrumb">
      <a href="/">Home</a>
      <i class="bi bi-chevron-right"></i>
      <a href="{{ route('corporate.portal') }}">My Account</a>
      <i class="bi bi-chevron-right"></i>
      <span>My Orders</span>
    </nav>

    <div class="account-layout">

      @include('web_frontend.partials.corporate-sidebar', ['activePage' => 'orders'])

      <main class="account-main">

        <div class="orders-head">
          <h1>My Orders</h1>
          <p>Track and manage all your orders in one place.</p>
        </div>

        @if($waiting->count())
          <div class="attention-flash">
            <i class="bi bi-bell-fill"></i>
            <span>{{ $waiting->count() == 1 ? '1 order needs' : $waiting->count() . ' orders need' }} your response. Open {{ $waiting->count() == 1 ? 'it' : 'them' }} to approve or rate.</span>
          </div>
        @endif

        @if($orders->count())
          <div class="orders-list">
            @foreach($orders as $o)
              @php
                $first = $o->items->first();
                $more = $o->items->count() - 1;
                $done = $o->current_step == 10 && $o->step_state === 'done';
                $attention = $waiting->contains('id', $o->id);
                $statusClass = $done ? 'completed' : ($o->step_state === 'in_progress' ? 'in-progress' : ($o->step_state === 'waiting_customer' ? 'pending' : 'in-progress'));
                $statusText = $done ? 'Completed' : $labels[$o->step_state] ?? 'In Progress';
              @endphp
              <div class="order-card">
                <div class="order-card-inner">
                  <!-- Section 1: Order Info -->
                  <div class="order-card-section order-info">
                    <strong>#{{ $o->orderid }}</strong>
                    <small>{{ $o->created_at->format('d M Y') }}</small>
                  </div>

                  <!-- Section 2: Product Info -->
                  <div class="order-card-section product-info">
                    @if($first && $first->productImageUrl())
                      <img src="{{ $first->productImageUrl() }}" alt="{{ $first->name }}" class="product-image">
                    @else
                      <div class="product-image" style="display:grid;place-items:center;background:#f5f5f5;">
                        <i class="bi bi-gift" style="font-size:24px;color:#ccc;"></i>
                      </div>
                    @endif
                    <div class="product-details">
                      <strong>{{ optional($first)->name ?: 'Order' }}</strong>
                      <small>
                        @if($more > 0)+ {{ $more }} more product{{ $more > 1 ? 's' : '' }} @endif
                        · {{ $o->items->sum('quantity') }} pcs
                      </small>
                    </div>
                  </div>

                  <!-- Section 3: Workflow -->
                  <div class="order-card-section workflow-info">
                    <strong>{{ $done ? 'Order completed' : 'Step ' . $o->current_step . ' of 10 - ' . ($steps[$o->current_step]['customer'] ?? 'Processing') }}</strong>
                    <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                  </div>

                  <!-- Section 4: Amount -->
                  <div class="order-card-section order-amount">
                    {{ $o->totalLabel() }}
                  </div>

                  <!-- Section 5: Action -->
                  <div class="order-card-section">
                    <a href="{{ route('customer.orders.show', $o->orderid) }}" class="btn-view-order">
                      {{ $attention ? 'Respond' : 'View order' }} <i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="orders-empty">
            <div class="orders-empty-icon"><i class="bi bi-cart-x"></i></div>
            <h2>No orders yet</h2>
            <p>When you send a quote request it shows up here, so you can follow it step by step.</p>
            <a href="{{ url('/') }}" class="btn-continue-shopping">Continue Shopping <i class="bi bi-arrow-right"></i></a>
          </div>
        @endif

      </main>
    </div>
  </div>
</div>
@endsection
