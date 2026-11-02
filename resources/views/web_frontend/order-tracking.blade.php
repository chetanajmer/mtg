@extends('web_frontend.layouts.app')

@section('title', 'Track Your Order | More Than Giftz')
@section('meta')
  <meta name="description" content="Track your More Than Giftz order with your order number and email.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/order-progress.css') }}">
@endsection

@section('content')
<div class="container-fluid px-4 px-xl-5">
  <nav class="breadcrumb-row" aria-label="Breadcrumb">
    <a href="{{ url('/') }}">Home</a><i class="bi bi-chevron-right"></i>
    <span>Track order</span>
  </nav>
</div>

<section class="container-fluid px-4 px-xl-5 ot">
  <div class="ot-card">
    <div class="ot-intro">
      <h1>Track your order</h1>
      <p>See where your corporate gifts are, from design approval to delivery.</p>
      <ul>
        <li><i class="bi bi-list-check"></i><span>All 10 steps of your order, with dates</span></li>
        <li><i class="bi bi-images"></i><span>Your design mockups and sample photos</span></li>
        <li><i class="bi bi-truck"></i><span>Courier and tracking number once dispatched</span></li>
      </ul>
    </div>

    <form class="ot-form" method="POST" action="{{ route('track.lookup') }}" novalidate>
      @csrf
      <h2>Find your order</h2>
      <p>Use the order number from your confirmation email and the email address you ordered with.</p>

      @if(session('info'))
        <div class="op-flash info" role="status"><i class="bi bi-info-circle-fill"></i>{{ session('info') }}</div>
      @endif
      @if($errors->any())
        <div class="op-flash bad" role="alert"><i class="bi bi-exclamation-circle-fill"></i>{{ $errors->first() }}</div>
      @endif

      <div class="ot-field @error('orderid') has-error @enderror">
        <label for="orderid">Order number</label>
        <input id="orderid" name="orderid" type="text" inputmode="numeric" autocomplete="off" required maxlength="30" placeholder="e.g. 10161" value="{{ old('orderid') }}">
      </div>
      <div class="ot-field @error('email') has-error @enderror">
        <label for="email">Email address</label>
        <input id="email" name="email" type="email" autocomplete="email" required maxlength="191" placeholder="you@company.com" value="{{ old('email') }}">
      </div>
      <button class="op-btn dark full" type="submit"><i class="bi bi-search"></i>Track order</button>

      <p class="ot-alt">
        @if(session('userid'))
          You're logged in: <a href="{{ url('/orders') }}">go to My Orders</a>
        @else
          Have an account? <a href="{{ route('corporate.login') }}">Log in</a> to approve designs and see all orders.
        @endif
      </p>
    </form>
  </div>
</section>
@endsection
