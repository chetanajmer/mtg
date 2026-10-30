@extends('web_frontend.layouts.auth')

@section('title', 'Corporate Portal Register | More Than Giftz')
@section('meta')
  <meta name="description" content="Register to access your corporate gifting portal.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/cart-auth.css') }}">
@endsection

@section('content')
<div class="auth-fullpage">
    <div class="auth-side">
      <h2>Create Your <span class="auth-gold">Account</span></h2>
      <p>Register to unlock exclusive offers, save your favourite corporate gifts and explore more for your business needs.</p>
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

      <form action="{{ route('cart.register') }}" method="POST">
        @csrf
        <input type="hidden" name="redirect" value="{{ route('corporate.portal') }}">

        <div class="auth-field-row">
          <div class="auth-field">
            <i class="bi bi-person"></i>
            <input type="text" name="first_name" placeholder="First Name *" value="{{ old('first_name') }}" required>
          </div>
          <div class="auth-field">
            <i class="bi bi-person"></i>
            <input type="text" name="last_name" placeholder="Last Name *" value="{{ old('last_name') }}">
          </div>
        </div>

        <div class="auth-field">
          <i class="bi bi-building"></i>
          <input type="text" name="company" placeholder="Company Name *" value="{{ old('company') }}">
        </div>

        <div class="auth-field">
          <i class="bi bi-envelope"></i>
          <input type="email" name="email" placeholder="Email Address *" value="{{ old('email') }}" required>
        </div>

        <div class="auth-field">
          <i class="bi bi-geo-alt"></i>
          <input type="text" name="address" placeholder="Address" value="{{ old('address') }}">
        </div>

        <div class="auth-field-row">
          <div class="auth-field auth-field-code">
            <select name="country_code">
              <option value="+91" {{ old('country_code', '+91') == '+91' ? 'selected' : '' }}>&#x1F1EE;&#x1F1F3; +91</option>
              <option value="+971" {{ old('country_code') == '+971' ? 'selected' : '' }}>&#x1F1E6;&#x1F1EA; +971</option>
              <option value="+1" {{ old('country_code') == '+1' ? 'selected' : '' }}>&#x1F1FA;&#x1F1F8; +1</option>
              <option value="+44" {{ old('country_code') == '+44' ? 'selected' : '' }}>&#x1F1EC;&#x1F1E7; +44</option>
            </select>
          </div>
          <div class="auth-field">
            <i class="bi bi-telephone"></i>
            <input type="tel" name="mobile" placeholder="Mobile Number *" value="{{ old('mobile') }}">
          </div>
        </div>

        <div class="auth-field-row">
          <div class="auth-field">
            <i class="bi bi-lock"></i>
            <input type="password" name="password" id="regPassword" placeholder="Password *" required>
            <i class="bi bi-eye-slash auth-eye" onclick="togglePassword('regPassword', this)"></i>
          </div>
          <div class="auth-field">
            <i class="bi bi-lock"></i>
            <input type="password" name="password_confirmation" id="regPasswordConfirm" placeholder="Confirm Password *" required>
            <i class="bi bi-eye-slash auth-eye" onclick="togglePassword('regPasswordConfirm', this)"></i>
          </div>
        </div>

        <label class="auth-check auth-terms">
          <input type="checkbox" required>
          <span>I agree to the <u>Terms &amp; Conditions</u> and <u>Privacy Policy</u></span>
        </label>

        <button type="submit" class="auth-submit">Create Account <i class="bi bi-arrow-right"></i></button>

        <div class="auth-divider"><span>OR</span></div>

        <a href="{{ route('corporate.google', ['redirect' => route('corporate.portal')]) }}" class="auth-google">
          <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.1H42V20H24v8h11.3C33.7 32.7 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 13 4 4 13 4 24s9 20 20 20 20-9 20-20c0-1.3-.1-2.7-.4-3.9z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15.1 19 12 24 12c3.1 0 5.9 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.1 26.7 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.1H42V20H24v8h11.3c-.8 2.2-2.2 4.2-4.1 5.6l6.2 5.2C36.9 39.2 44 34 44 24c0-1.3-.1-2.7-.4-3.9z"/></svg>
          Continue with Google
        </a>

        <p class="auth-switch">Already have an account? <a href="{{ route('corporate.login') }}">Login</a></p>
      </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(fieldId, icon) {
  const field = document.getElementById(fieldId);
  if (field.type === 'password') {
    field.type = 'text';
    icon.classList.replace('bi-eye-slash', 'bi-eye');
  } else {
    field.type = 'password';
    icon.classList.replace('bi-eye', 'bi-eye-slash');
  }
}
</script>
@endsection
