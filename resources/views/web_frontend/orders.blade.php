@extends('web_frontend.layouts.app')

@section('title', 'My Orders | More Than Giftz')
@section('meta')
  <meta name="description" content="View and manage your corporate orders.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/orders.css') }}">
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/order-progress.css') }}">
@endsection

@section('content')
@php
  $orders = $orders ?? collect();
  $steps = config('order_steps');
  $labels = \App\Services\OrderWorkflow::CUSTOMER_LABELS;
  $waiting = $orders->filter(function ($o) { return $o->step_state === 'waiting_customer' || ($o->current_step == 10 && $o->step_state === 'in_progress'); });
@endphp

<div class="dashboard-wrapper">

<aside class="sidebar">
  <button class="company"><i class="bi bi-buildings"></i><span><strong>{{ session()->get('fullname') ?? session()->get('username') ?? 'User' }}</strong><small>Switch Company <i class="bi bi-arrow-right"></i></small></span></button>
  <nav>
    <a href="{{ route('corporate.portal') }}"><i class="bi bi-gift"></i>Dashboard</a>
    <a class="active" href="/orders"><i class="bi bi-cart3"></i>Orders @if($waiting->count())<span class="count">{{ $waiting->count() }}</span>@endif</a>
    <a href="#addresses"><i class="bi bi-geo-alt"></i>Addresses</a>
    <a href="#branding"><i class="bi bi-image"></i>Branding Assets</a>
    <a href="/collections-all"><i class="bi bi-bookmark"></i>Saved Collections</a>
    <a href="#employees"><i class="bi bi-people-fill"></i>Employees</a>
    <a href="{{ route('corporate.reports') }}"><i class="bi bi-bar-chart-line"></i>Reports</a>
    <a href="/corporate-account-settings"><i class="bi bi-gear"></i>Account Settings</a>
    <a href="{{ route('corporate.logout') }}" style="color: #dc3545;"><i class="bi bi-box-arrow-right"></i>Logout</a>
  </nav>
  <div class="help"><i class="bi bi-headset"></i><div><strong>Need Help?</strong><p>Your dedicated account manager is here.</p></div><button class="outline full" data-toast="Contact request started.">Contact Us <i class="bi bi-arrow-right"></i></button></div>
</aside>

<main class="content">
  <section class="hero" style="height:auto;min-height:140px;">
    <div>
      <h1>My Orders</h1>
      <p>Follow every order step by step, approve designs and samples, and see delivery details.</p>
    </div>
  </section>

  <section class="orders-section" style="padding:0;">
    @if($waiting->count())
      <div class="op-flash info" style="margin-top:16px;" role="status"><i class="bi bi-bell-fill"></i>
        <span>{{ $waiting->count() == 1 ? '1 order needs' : $waiting->count() . ' orders need' }} your response. Open {{ $waiting->count() == 1 ? 'it' : 'them' }} to approve or rate.</span>
      </div>
    @endif

    @if($orders->count())
      <div class="opl">
        @foreach($orders as $o)
          @php
            $first = $o->items->first();
            $more = $o->items->count() - 1;
            $done = $o->current_step == 10 && $o->step_state === 'done';
            $attention = $waiting->contains('id', $o->id);
          @endphp
          <a class="opl-row {{ $attention ? 'attention' : '' }}" href="{{ route('customer.orders.show', $o->orderid) }}">
            <div class="opl-num"><strong>#{{ $o->orderid }}</strong><small>{{ $o->created_at->format('d M Y') }}</small></div>
            <div class="opl-items"><strong>{{ optional($first)->name ?: 'Order' }}</strong><small>@if($more > 0)+ {{ $more }} more product{{ $more > 1 ? 's' : '' }} &middot; @endif{{ $o->items->sum('quantity') }} pcs</small></div>
            <div class="opl-status">
              <strong>{{ $done ? 'Order completed' : 'Step ' . $o->current_step . ' of 10 · ' . $steps[$o->current_step]['customer'] }}</strong>
              <span class="op-pill {{ $done ? 'done' : $o->step_state }}">{{ $done ? 'Completed' : $labels[$o->step_state] }}</span>
            </div>
            <div class="opl-total">AED {{ number_format($o->total, 2) }}</div>
            <span class="opl-go">{{ $attention ? 'Respond' : 'View order' }} <i class="bi bi-arrow-right"></i></span>
          </a>
        @endforeach
      </div>
    @else
      <div class="opl-empty" style="margin-top:16px;">
        <i class="bi bi-cart-x"></i>
        <h2>No orders yet</h2>
        <p>When you send a quote request it shows up here, so you can follow it step by step.</p>
        <a class="op-btn dark" href="{{ url('/') }}">Browse products</a>
      </div>
    @endif
  </section>
</main>
</div>
@endsection
