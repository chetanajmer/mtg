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

<!-- Send Enquiry Modal -->
<div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="enquiryModalLabel">Send Enquiry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="modal-description">Please fill in your details and we'll get back to you.</p>

        <form id="enquiryForm">
          <div class="mb-3">
            <label for="fullName" class="form-label">Full Name <span class="required">*</span></label>
            <input type="text" class="form-control" id="fullName" placeholder="Enter your full name" required>
          </div>

          <div class="mb-3">
            <label for="companyName" class="form-label">Company Name</label>
            <input type="text" class="form-control" id="companyName" placeholder="Enter your company name">
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email Address <span class="required">*</span></label>
            <input type="email" class="form-control" id="email" placeholder="Enter your email address" required>
          </div>

          <div class="mb-3">
            <label for="mobile" class="form-label">Mobile Number <span class="required">*</span></label>
            <input type="tel" class="form-control" id="mobile" placeholder="Enter your mobile number" required>
          </div>

          <div class="mb-3">
            <label for="message" class="form-label">Message / Enquiry <span class="required">*</span></label>
            <textarea class="form-control" id="message" rows="4" placeholder="Tell us about your requirements..." required></textarea>
          </div>

          <div class="enquiry-summary">
            <h6>Enquiry Summary</h6>
            <p>Cart Items: <span id="cartItemsCountModal">0</span> products</p>
            <p class="text-muted">Your selected cart products will be included in this enquiry.</p>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-submit-enquiry">Submit Enquiry</button>
      </div>
    </div>
  </div>
</div>

<!-- Toaster Notification -->
<div class="toaster-notification" id="toasterNotification">
  <div class="toaster-icon">
    <i class="bi bi-check-circle"></i>
  </div>
  <div class="toaster-message">
    <h4>Success!</h4>
    <p>Your enquiry has been sent successfully. We'll get back to you soon.</p>
  </div>
  <button class="toaster-close" onclick="hideToaster()">
    <i class="bi bi-x"></i>
  </button>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Dummy cart data
const dummyCart = [
  {
    id: 1,
    name: 'Stainless Steel Bottle',
    sku: 'SKU-001',
    price: 45.00,
    quantity: 50,
    image: 'https://via.placeholder.com/100'
  },
  {
    id: 2,
    name: 'Notebook & Pen Set',
    sku: 'SKU-002',
    price: 25.00,
    quantity: 100,
    image: 'https://via.placeholder.com/100'
  },
  {
    id: 3,
    name: 'Ceramic Coffee Mug',
    sku: 'SKU-003',
    price: 15.00,
    quantity: 75,
    image: 'https://via.placeholder.com/100'
  },
  {
    id: 4,
    name: 'Laptop Backpack',
    sku: 'SKU-004',
    price: 85.00,
    quantity: 25,
    image: 'https://via.placeholder.com/100'
  },
  {
    id: 5,
    name: 'Corporate Cap',
    sku: 'SKU-005',
    price: 20.00,
    quantity: 150,
    image: 'https://via.placeholder.com/100'
  }
];

let enquiryModal;

document.addEventListener('DOMContentLoaded', function() {
  loadCart();
  // Initialize Bootstrap modal
  enquiryModal = new bootstrap.Modal(document.getElementById('enquiryModal'));

  // Enquiry button click handler
  document.querySelector('.enquiry-btn').addEventListener('click', function() {
    // Update cart items count in modal
    document.getElementById('cartItemsCountModal').textContent = dummyCart.length;
    // Show modal
    enquiryModal.show();
  });

  // Submit enquiry button click handler
  document.querySelector('.btn-submit-enquiry').addEventListener('click', function() {
    submitEnquiry();
  });
});

function loadCart() {
  const container = document.getElementById('cartItemsContainer');
  const emptyCart = document.getElementById('emptyCart');
  const itemsCount = document.getElementById('cartItemsCount');
  const subtotalItems = document.getElementById('subtotalItems');

  // Load dummy data
  const cart = dummyCart;

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

// Initial cart count update
const totalItems = dummyCart.reduce((sum, item) => sum + item.quantity, 0);
const cartCount = document.getElementById('cartCount');
if (cartCount) {
  cartCount.textContent = totalItems;
}

function updateQuantity(index, change) {
  dummyCart[index].quantity += change;
  if (dummyCart[index].quantity < 50) {
    dummyCart[index].quantity = 50;
  }
  loadCart();
}

function setQuantity(index, value) {
  const qty = parseInt(value) || 50;
  dummyCart[index].quantity = qty < 50 ? 50 : qty;
  loadCart();
}

function removeItem(index) {
  dummyCart.splice(index, 1);
  loadCart();
}

function clearCart() {
  if (confirm('Are you sure you want to clear your cart?')) {
    dummyCart.length = 0;
    loadCart();
  }
}

function updateSummary(subtotal) {
  const tax = subtotal * 0.05;
  const total = subtotal + tax;

  document.getElementById('subtotal').textContent = 'AED ' + subtotal.toFixed(2);
  document.getElementById('tax').textContent = 'AED ' + tax.toFixed(2);
  document.getElementById('total').textContent = 'AED ' + total.toFixed(2);

  // Update cart count in header
  const totalItems = dummyCart.reduce((sum, item) => sum + item.quantity, 0);
  const cartCount = document.getElementById('cartCount');
  if (cartCount) {
    cartCount.textContent = totalItems;
  }
}

// Submit Enquiry
function submitEnquiry() {
  const form = document.getElementById('enquiryForm');
  const fullName = document.getElementById('fullName').value.trim();
  const companyName = document.getElementById('companyName').value.trim();
  const email = document.getElementById('email').value.trim();
  const mobile = document.getElementById('mobile').value.trim();
  const message = document.getElementById('message').value.trim();

  // Form validation
  if (!fullName || !email || !mobile || !message) {
    alert('Please fill in all required fields.');
    return;
  }

  // Email validation
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    alert('Please enter a valid email address.');
    return;
  }

  const cartItems = JSON.stringify(dummyCart);
  const subtotal = dummyCart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  const tax = subtotal * 0.05;
  const total = subtotal + tax;

  fetch('/send-enquiry', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
      name: fullName,
      email: email,
      phone: mobile,
      company: companyName,
      message: message,
      subtotal: subtotal,
      tax: tax,
      total: total,
      cart_items: cartItems
    })
  })
  .then(response => {
    console.log('Response status:', response.status);
    return response.json();
  })
  .then(data => {
    console.log('Response data:', data);
    if (data.success) {
      // Hide modal
      enquiryModal.hide();
      // Show toaster notification
      showToaster();
      // Clear form
      form.reset();
      // Clear cart
      dummyCart.length = 0;
      loadCart();
    } else {
      alert('Error sending enquiry: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error sending enquiry: ' + error.message);
  });
}

// Show Toaster Notification
function showToaster() {
  const toaster = document.getElementById('toasterNotification');
  toaster.classList.add('show');

  // Auto hide after 5 seconds
  setTimeout(() => {
    hideToaster();
  }, 5000);
}

// Hide Toaster Notification
function hideToaster() {
  const toaster = document.getElementById('toasterNotification');
  toaster.classList.remove('show');
}
</script>
@endsection
