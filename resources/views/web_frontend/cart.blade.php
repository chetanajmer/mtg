@extends('web_frontend.layouts.app')

@section('title', 'Shopping Cart | More Than Giftz')
@section('meta')
  <meta name="description" content="View your shopping cart items and proceed to checkout.">
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
        <h2>Your Cart Items <span id="cartItemsCount">(0)</span></h2>
        <button class="clear-cart-btn" onclick="clearCart()">
          <i class="bi bi-trash"></i> Clear Cart
        </button>
      </div>

      <div class="cart-items-container" id="cartItemsContainer">
        <!-- Cart items will be loaded here via JavaScript -->
      </div>

      <div class="empty-cart" id="emptyCart" style="display: none;">
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
          <span>Subtotal <small id="subtotalItems">(0 items)</small></span>
          <span id="subtotal">AED 0.00</span>
        </div>

        <div class="summary-row">
          <span>Shipping</span>
          <span class="free-shipping">Free</span>
        </div>

        <div class="summary-row">
          <span>Tax (5%)</span>
          <span id="tax">AED 0.00</span>
        </div>

        <div class="summary-divider"></div>

        <div class="summary-row total">
          <span>Total</span>
          <span id="total">AED 0.00</span>
        </div>

        <div class="cart-actions">
          
          <button class="btn btn-outline-secondary enquiry-btn">Send Enquiry</button>
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  loadCart();
});

function loadCart() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  const container = document.getElementById('cartItemsContainer');
  const emptyCart = document.getElementById('emptyCart');
  const itemsCount = document.getElementById('cartItemsCount');
  const subtotalItems = document.getElementById('subtotalItems');

  if (cart.length === 0) {
    container.style.display = 'none';
    emptyCart.style.display = 'block';
    itemsCount.textContent = '(0)';
    subtotalItems.textContent = '(0 items)';
    updateSummary(0);
    return;
  }

  container.style.display = 'block';
  emptyCart.style.display = 'none';
  itemsCount.textContent = '(' + cart.length + ')';
  subtotalItems.textContent = '(' + cart.length + ' items)';

  let html = '';
  let subtotal = 0;

  cart.forEach((item, index) => {
    const itemTotal = item.price * item.quantity;
    subtotal += itemTotal;

    html += `
      <div class="cart-item" data-index="${index}">
        <div class="item-image">
          <img src="${item.image}" alt="${item.name}">
        </div>
        <div class="item-details">
          <h4>${item.name}</h4>
          <span class="item-sku">${item.sku}</span>
          <p class="item-description">Premium quality corporate gift item with custom branding options available.</p>
          <div class="item-price">AED ${item.price.toFixed(2)}</div>
        </div>
        <div class="item-quantity">
          <button class="qty-btn minus" onclick="updateQuantity(${index}, -50)">−</button>
          <input type="number" class="qty-input" value="${item.quantity}" min="50" step="50" onchange="setQuantity(${index}, this.value)">
          <button class="qty-btn plus" onclick="updateQuantity(${index}, 50)">+</button>
        </div>
        <div class="item-total">
          <span>AED ${itemTotal.toFixed(2)}</span>
          <button class="delete-btn" onclick="removeItem(${index})">
            <i class="bi bi-trash"></i>
          </button>
        </div>
      </div>
    `;
  });

  container.innerHTML = html;
  updateSummary(subtotal);
}

function updateQuantity(index, change) {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  if (cart[index]) {
    cart[index].quantity += change;
    if (cart[index].quantity < 50) {
      cart[index].quantity = 50;
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart();
    updateCartCount();
  }
}

function setQuantity(index, value) {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  if (cart[index]) {
    const qty = parseInt(value) || 50;
    cart[index].quantity = qty < 50 ? 50 : qty;
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart();
    updateCartCount();
  }
}

function removeItem(index) {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  cart.splice(index, 1);
  localStorage.setItem('cart', JSON.stringify(cart));
  loadCart();
  updateCartCount();
}

function clearCart() {
  if (confirm('Are you sure you want to clear your cart?')) {
    localStorage.removeItem('cart');
    loadCart();
    updateCartCount();
  }
}

function updateSummary(subtotal) {
  const tax = subtotal * 0.05;
  const total = subtotal + tax;

  document.getElementById('subtotal').textContent = 'AED ' + subtotal.toFixed(2);
  document.getElementById('tax').textContent = 'AED ' + tax.toFixed(2);
  document.getElementById('total').textContent = 'AED ' + total.toFixed(2);
}

function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
  const cartCount = document.getElementById('cartCount');
  if (cartCount) {
    cartCount.textContent = totalItems;
  }
}
</script>
@endsection
