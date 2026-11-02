@extends('web_frontend.layouts.auth')

@section('title', 'Choose a New Password | More Than Giftz')
@section('meta')
  <meta name="description" content="Choose a new password for your corporate portal account.">
  <meta name="robots" content="noindex">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/cart-auth.css') }}">
@endsection

@section('content')
<div class="auth-fullpage">
    <div class="auth-side">
      <h2>Choose a new <span class="auth-gold">Password</span></h2>
      <p>Enter a new password for {{ $email }}.</p>
      <div class="auth-side-img">
        <img src="{{ asset('web_frontend/assets/images/product-main.jpg') }}" alt="More Than Giftz">
      </div>
    </div>
    <div class="auth-form-side">
      @if($errors->any())
        <div class="auth-alert auth-alert-error">
          @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form action="{{ route('corporate.password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="auth-field">
          <i class="bi bi-lock"></i>
          <input type="password" name="password" placeholder="New Password (min 6 characters)" required autofocus>
        </div>

        <div class="auth-field">
          <i class="bi bi-lock"></i>
          <input type="password" name="password_confirmation" placeholder="Confirm New Password" required>
        </div>

        <button type="submit" class="auth-submit">Save New Password <i class="bi bi-arrow-right"></i></button>

        <div class="auth-divider"><span>OR</span></div>

        <a href="{{ route('corporate.login') }}" class="auth-google">
          <i class="bi bi-box-arrow-in-right" style="font-size: 18px; color: #b28c46;"></i>
          Back to Login
        </a>
      </form>
    </div>
</div>
@endsection
