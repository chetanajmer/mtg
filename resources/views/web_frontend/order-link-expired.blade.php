@extends('web_frontend.layouts.app')

@section('title', 'Link expired | More Than Giftz')
@section('meta')
  <meta name="robots" content="noindex">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/order-progress.css') }}">
@endsection

@section('content')
<section class="container-fluid px-4 px-xl-5 ot">
  <div class="opl-empty" style="max-width:640px;margin:24px auto 0;">
    <i class="bi bi-link-45deg"></i>
    <h2>This link has expired or is not valid</h2>
    <p>Links in our order emails work for {{ \App\Services\OrderWorkflow::LINK_DAYS }} days. You can still see and answer your order by logging in, or track it with your order number and email.</p>
    <div class="op-btns" style="justify-content:center;">
      <a class="op-btn dark" href="{{ route('corporate.login') }}"><i class="bi bi-box-arrow-in-right"></i>Log in</a>
      <a class="op-btn ghost" href="{{ route('track.form') }}"><i class="bi bi-search"></i>Track order</a>
    </div>
  </div>
</section>
@endsection
