@extends('web_frontend.layouts.auth')

@section('title', 'Corporate Portal Login | More Than Giftz')
@section('meta')
  <meta name="description" content="Login to access your corporate gifting portal.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/cart-auth.css') }}">
@endsection

@section('content')
<div class="auth-fullpage">
    <div class="auth-side">
      <h2>Welcome Back</h2>
      <p>Login to continue your corporate journey</p>
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

      <form action="{{ route('corporate.login.submit') }}" method="POST">
        @csrf

        <div class="auth-field">
          <i class="bi bi-envelope"></i>
          <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="auth-field">
          <i class="bi bi-lock"></i>
          <input type="password" name="password" id="loginPassword" placeholder="Password" required>
          <i class="bi bi-eye-slash auth-eye" onclick="togglePassword('loginPassword', this)"></i>
        </div>

        <div class="auth-row">
          <label class="auth-check">
            <input type="checkbox" name="remember">
            <span>Remember me</span>
          </label>
          <a href="{{ route('corporate.forgot.password') }}" class="auth-link">Forgot Password?</a>
        </div>

        <button type="submit" class="auth-submit">Login <i class="bi bi-arrow-right"></i></button>

        <div class="auth-divider"><span>OR</span></div>

        <a href="{{ route('corporate.google', ['redirect' => route('corporate.portal')]) }}" class="auth-google">
          <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.1H42V20H24v8h11.3C33.7 32.7 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 13 4 4 13 4 24s9 20 20 20 20-9 20-20c0-1.3-.1-2.7-.4-3.9z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15.1 19 12 24 12c3.1 0 5.9 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.1 26.7 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.1H42V20H24v8h11.3c-.8 2.2-2.2 4.2-4.1 5.6l6.2 5.2C36.9 39.2 44 34 44 24c0-1.3-.1-2.7-.4-3.9z"/></svg>
          Continue with Google
        </a>

        <p class="auth-switch">Don't have an account? <a href="{{ route('corporate.register') }}">Register Now</a></p>
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
