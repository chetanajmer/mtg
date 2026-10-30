@extends('web_frontend.layouts.app')

@section('title', 'Shopping Cart | More Than Giftz')
@section('meta')
  <meta name="description" content="View your shopping cart items and proceed to checkout.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/cart-auth.css') }}">
@endsection

@section('content')
<!-- BREADCRUMB -->
<div class="container-fluid px-4 px-xl-5">
  <div class="breadcrumb-row">
    <span>Home</span><i class="bi bi-chevron-right"></i>
    <span>Cart</span>
  </div>
</div>

<!-- CART SECTION -->
<section class="container-fluid px-4 px-xl-5 cart-section">
  <div class="cart-notification" id="cartNotification" style="display: none;">
    <i class="bi bi-check-circle"></i> Added to Cart!
  </div>

  <div class="row g-4">
    <!-- CART ITEMS -->
    <div class="col-lg-8">
      <div class="cart-header">
        <h2>Your Cart Items <span id="cartItemsCount">({{ $items->count() }})</span></h2>
        @if($items->count() > 0)
        <button class="clear-cart-btn" onclick="clearCart()">
          <i class="bi bi-trash"></i> Clear Cart
        </button>
        @endif
      </div>

      <div class="cart-items-container" id="cartItemsContainer">
        @foreach($items as $item)
        <div class="cart-item">
          <div class="item-image">
            @if($item->image)
              <img src="{{ asset('upload/product/' . $item->image) }}" alt="{{ $item->name }}">
            @else
              <img src="{{ asset('web_frontend/assets/images/product-main.jpg') }}" alt="{{ $item->name }}">
            @endif
          </div>
          <div class="item-details">
            <h4>{{ $item->name }}</h4>
            <span class="item-sku">SKU: {{ $item->modelno ?? 'N/A' }}</span>
            <p class="item-description">Premium quality corporate gift item with custom branding options available.</p>
            <div class="item-price">AED {{ number_format($item->price, 2) }}</div>
          </div>
          <div class="item-quantity">
            <button class="qty-btn minus" onclick="changeQuantity({{ $item->id }}, -1, {{ $item->quantity }})">−</button>
            <input type="number" class="qty-input" value="{{ $item->quantity }}" min="1" onchange="setQuantity({{ $item->id }}, this.value)">
            <button class="qty-btn plus" onclick="changeQuantity({{ $item->id }}, 1, {{ $item->quantity }})">+</button>
          </div>
          <div class="item-total">
            <span>AED {{ number_format($item->price * $item->quantity, 2) }}</span>
            <button class="delete-btn" onclick="removeItem({{ $item->id }})">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
        @endforeach
      </div>

      <div class="empty-cart" id="emptyCart" style="display: {{ $items->count() ? 'none' : 'block' }};">
        <i class="bi bi-cart-x"></i>
        <h3>Your cart is empty</h3>
        <p>Browse our products and add items to your cart.</p>
        <a href="/" class="btn btn-dark">Continue Shopping</a>
      </div>
    </div>

    <!-- ORDER SUMMARY -->
    <div class="col-lg-4">
      <div class="order-summary">
        <h3>Order Summary</h3>

        <div class="summary-row">
          <span>Subtotal <small id="subtotalItems">({{ $items->count() }} items)</small></span>
          <span id="subtotal">AED {{ number_format($subtotal, 2) }}</span>
        </div>

        <div class="summary-row">
          <span>Shipping</span>
          <span class="free-shipping">Free</span>
        </div>

        <div class="summary-row">
          <span>Tax (5%)</span>
          <span id="tax">AED {{ number_format($tax, 2) }}</span>
        </div>

        <div class="summary-divider"></div>

        <div class="summary-row total">
          <span>Total</span>
          <span id="total">AED {{ number_format($total, 2) }}</span>
        </div>

        <div class="cart-actions">
          @if(session()->has('userid'))
            @if($items->count() > 0)
              <form action="{{ route('cart.send-enquiry') }}" method="POST" style="width: 100%;">
                @csrf
                <button type="submit" class="btn btn-outline-secondary enquiry-btn" style="width: 100%;">Send Enquiry</button>
              </form>
            @else
              <button type="button" class="btn btn-outline-secondary enquiry-btn" onclick="showCartToast()">Send Enquiry</button>
            @endif
          @else
            <button type="button" class="btn btn-outline-secondary enquiry-btn" onclick="openAuthModal('login')">Send Enquiry</button>
          @endif
          <a href="/" class="btn btn-outline-secondary" style="width: 100%; text-align: center; margin-top: 10px; display: block;">Continue Shopping</a>
        </div>

        <div class="help-box">
          <i class="bi bi-question-circle"></i>
          <div>
            <strong>Need help or have a question about these products?</strong>
            <p>Send us an enquiry and our team will get back to you soon.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Empty Cart Toaster -->
<div class="toaster-notification" id="cartToast">
  <div class="toaster-icon warning">
    <i class="bi bi-exclamation-circle"></i>
  </div>
  <div class="toaster-message">
    <h4>Cart is empty</h4>
    <p>Your cart is empty. Please add a product to continue</p>
  </div>
  <button class="toaster-close" onclick="hideCartToast()">
    <i class="bi bi-x"></i>
  </button>
</div>

<!-- LOGIN MODAL -->
<div class="auth-overlay" id="loginModal">
  <div class="auth-modal">
    <button type="button" class="auth-close" onclick="closeAuthModal()">&times;</button>
    <div class="auth-side">
      <h2>Welcome Back</h2>
      <p>Login to continue your corporate journey</p>
      <div class="auth-side-img">
        <img src="{{ asset('web_frontend/assets/images/product-main.jpg') }}" alt="More Than Giftz">
      </div>
    </div>
    <div class="auth-form-side">
      @if(session('error'))
        <div class="auth-alert auth-alert-error">{{ session('error') }}</div>
      @endif
      <form action="{{ route('corporate.login.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="redirect" value="/cart">
        <input type="hidden" name="auth_modal" value="1">

        <div class="auth-field">
          <i class="bi bi-envelope"></i>
          <input type="email" name="email" placeholder="Email Address" required>
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

        <a href="{{ route('corporate.google', ['redirect' => '/cart']) }}" class="auth-google">
          <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.1H42V20H24v8h11.3C33.7 32.7 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 13 4 4 13 4 24s9 20 20 20 20-9 20-20c0-1.3-.1-2.7-.4-3.9z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15.1 19 12 24 12c3.1 0 5.9 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.1 26.7 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.1H42V20H24v8h11.3c-.8 2.2-2.2 4.2-4.1 5.6l6.2 5.2C36.9 39.2 44 34 44 24c0-1.3-.1-2.7-.4-3.9z"/></svg>
          Continue with Google
        </a>

        <p class="auth-switch">Don't have an account? <a href="javascript:void(0)" onclick="switchAuthModal('register')">Register Now</a></p>
      </form>
    </div>
  </div>
</div>

<!-- REGISTER MODAL -->
<div class="auth-overlay" id="registerModal">
  <div class="auth-modal">
    <button type="button" class="auth-close" onclick="closeAuthModal()">&times;</button>
    <div class="auth-side">
      <h2>Create Your <span class="auth-gold">Account</span></h2>
      <p>Register to unlock exclusive offers, save your favourite corporate gifts and explore more for your business needs.</p>
      <div class="auth-side-img">
        <img src="{{ asset('web_frontend/assets/images/product-main.jpg') }}" alt="More Than Giftz">
      </div>
    </div>
    <div class="auth-form-side">
      @if(session('auth_form') === 'register' && $errors->any())
        <div class="auth-alert auth-alert-error">
          @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif
      <form action="{{ route('cart.register') }}" method="POST">
        @csrf
        <input type="hidden" name="redirect" value="/cart">

        <div class="auth-field-row">
          <div class="auth-field">
            <i class="bi bi-person"></i>
            <input type="text" name="first_name" placeholder="First Name *" required>
          </div>
          <div class="auth-field">
            <i class="bi bi-person"></i>
            <input type="text" name="last_name" placeholder="Last Name *">
          </div>
        </div>

        <div class="auth-field">
          <i class="bi bi-building"></i>
          <input type="text" name="company" placeholder="Company Name *">
        </div>

        <div class="auth-field">
          <i class="bi bi-envelope"></i>
          <input type="email" name="email" placeholder="Email Address *" required>
        </div>

        <div class="auth-field">
          <i class="bi bi-geo-alt"></i>
          <input type="text" name="address" placeholder="Address">
        </div>

        <div class="auth-field-row">
          <div class="auth-field auth-field-code">
            <select name="country_code">
              <option value="+91" selected>&#x1F1EE;&#x1F1F3; +91</option>
              <option value="+971">&#x1F1E6;&#x1F1EA; +971</option>
              <option value="+1">&#x1F1FA;&#x1F1F8; +1</option>
              <option value="+44">&#x1F1EC;&#x1F1E7; +44</option>
            </select>
          </div>
          <div class="auth-field">
            <i class="bi bi-telephone"></i>
            <input type="tel" name="mobile" placeholder="Mobile Number *">
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

        <a href="{{ route('corporate.google', ['redirect' => '/cart']) }}" class="auth-google">
          <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.1H42V20H24v8h11.3C33.7 32.7 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 13 4 4 13 4 24s9 20 20 20 20-9 20-20c0-1.3-.1-2.7-.4-3.9z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15.1 19 12 24 12c3.1 0 5.9 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.1 26.7 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.1H42V20H24v8h11.3c-.8 2.2-2.2 4.2-4.1 5.6l6.2 5.2C36.9 39.2 44 34 44 24c0-1.3-.1-2.7-.4-3.9z"/></svg>
          Continue with Google
        </a>

        <p class="auth-switch">Already have an account? <a href="javascript:void(0)" onclick="switchAuthModal('login')">Login</a></p>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

function changeQuantity(itemId, delta, current) {
  const newQty = parseInt(current) + delta;
  if (newQty < 1) return;
  setQuantity(itemId, newQty);
}

function setQuantity(itemId, quantity) {
  const qty = parseInt(quantity);
  if (!qty || qty < 1) return;

  fetch('/cart/item/' + itemId, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    body: JSON.stringify({ quantity: qty })
  }).then(() => location.reload());
}

function removeItem(itemId) {
  fetch('/cart/item/' + itemId, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': csrfToken }
  }).then(() => location.reload());
}

function clearCart() {
  if (!confirm('Are you sure you want to clear your cart?')) return;
  fetch('/cart/clear', {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': csrfToken }
  }).then(() => location.reload());
}

// Empty cart toaster
function showCartToast() {
  const toast = document.getElementById('cartToast');
  toast.classList.add('show');
  setTimeout(hideCartToast, 4000);
}

function hideCartToast() {
  document.getElementById('cartToast').classList.remove('show');
}

@if(session('error'))
document.addEventListener('DOMContentLoaded', showCartToast);
@endif

// Auth modals
function openAuthModal(type) {
  closeAuthModal();
  document.getElementById(type === 'register' ? 'registerModal' : 'loginModal').classList.add('show');
  document.body.style.overflow = 'hidden';
}

function switchAuthModal(type) {
  openAuthModal(type);
}

function closeAuthModal() {
  document.querySelectorAll('.auth-overlay').forEach(el => el.classList.remove('show'));
  document.body.style.overflow = '';
}

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

// Close on overlay click
document.querySelectorAll('.auth-overlay').forEach(overlay => {
  overlay.addEventListener('click', function(e) {
    if (e.target === this) closeAuthModal();
  });
});

// Re-open modal after failed auth attempt
@if(session('auth_form'))
  document.addEventListener('DOMContentLoaded', function() {
    openAuthModal('{{ session('auth_form') }}');
  });
@elseif($errors->any())
  document.addEventListener('DOMContentLoaded', function() {
    openAuthModal('login');
  });
@endif
</script>
@endsection
