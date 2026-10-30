@extends('web_frontend.layouts.auth')

@section('title', 'Forgot Password | More Than Giftz')
@section('meta')
  <meta name="description" content="Reset your corporate portal password.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/cart-auth.css') }}">
@endsection

@section('content')
<div class="auth-fullpage">
    <div class="auth-side">
      <h2>Forgot <span class="auth-gold">Password?</span></h2>
      <p>Enter your email address and we'll send you a link to reset your password.</p>
      <div class="auth-side-img">
        <img src="{{ asset('web_frontend/assets/images/product-main.jpg') }}" alt="More Than Giftz">
      </div>
    </div>
    <div class="auth-form-side">
      @if(session('success'))
        <div class="auth-alert auth-alert-error" style="background:#e4f4e8;color:#17752c;border-color:#b7e2c1;">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="auth-alert auth-alert-error">{{ session('error') }}</div>
      @endif
      @if($errors->any())
        <div class="auth-alert auth-alert-error">
          @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form action="{{ route('corporate.forgot.password.submit') }}" method="POST">
        @csrf

        <div class="auth-field">
          <i class="bi bi-envelope"></i>
          <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
        </div>

        <button type="submit" class="auth-submit">Send Reset Link <i class="bi bi-arrow-right"></i></button>

        <div class="auth-divider"><span>OR</span></div>

        <a href="{{ route('corporate.login') }}" class="auth-google">
          <i class="bi bi-box-arrow-in-right" style="font-size: 18px; color: #b28c46;"></i>
          Back to Login
        </a>

        <p class="auth-switch">Don't have an account? <a href="{{ route('corporate.register') }}">Register Now</a></p>
      </form>
    </div>
</div>
@endsection
