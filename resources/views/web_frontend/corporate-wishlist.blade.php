@extends('web_frontend.layouts.app')

@section('title', 'Corporate Wishlist | More Than Giftz')
@section('meta')
  <meta name="description" content="Manage your corporate wishlists and collaborate with your team.">
@endsection

@section('content')
<div class="dashboard-wrapper">

<aside class="sidebar">
  <button class="company"><i class="bi bi-buildings"></i><span><strong>ABC Technologies</strong><small>Switch Company <i class="bi bi-arrow-right"></i></small></span></button>
  <nav>
    <a href="/corporate-portal"><i class="bi bi-gift"></i>Dashboard</a>
    <a href="#orders"><i class="bi bi-cart3"></i>Orders</a>
    <a href="#approvals"><i class="bi bi-check-circle"></i>Approvals<span class="count">3</span></a>
    <a href="#recipients"><i class="bi bi-people"></i>Recipients</a>
    <a href="#addresses"><i class="bi bi-geo-alt"></i>Addresses</a>
    <a href="#branding"><i class="bi bi-image"></i>Branding Assets</a>
    <a href="#collections"><i class="bi bi-bookmark"></i>Saved Collections</a>
    <a class="active" href="/corporate-portal/wishlist"><i class="bi bi-heart"></i>Wishlists</a>
    <a href="#employees"><i class="bi bi-people-fill"></i>Employees</a>
    <a href="#reports"><i class="bi bi-bar-chart-line"></i>Reports</a>
    <a href="#settings"><i class="bi bi-gear"></i>Account Settings</a>
  </nav>
  <div class="help"><i class="bi bi-headset"></i><div><strong>Need Help?</strong><p>Your dedicated account manager is here.</p></div><button class="outline full" data-toast="Contact request started.">Contact Us <i class="bi bi-arrow-right"></i></button></div>
</aside>

<main class="content wishlist-content">
  <!-- Breadcrumb -->
  <div class="wishlist-breadcrumb">
    <span>Home</span><i class="bi bi-chevron-right"></i>
    <span>Corporate Portal</span><i class="bi bi-chevron-right"></i>
    <span>Wishlists</span><i class="bi bi-chevron-right"></i>
    <span>Diwali 2025</span>
  </div>

  <!-- Wishlist Header -->
  <div class="wishlist-header">
    <div class="header-left">
      <h1>Diwali 2025 Wishlist <i class="bi bi-pencil"></i></h1>
      <p class="wishlist-description">Curated gift ideas for our Diwali celebration. Team can add, review and finalize together.</p>
      <div class="wishlist-meta">
        <span><i class="bi bi-person"></i> Created by Neha Verma (HR)</span>
        <span><i class="bi bi-clock"></i> Last updated 12 Sep 2025</span>
        <span><i class="bi bi-box-seam"></i> 12 items</span>
      </div>
    </div>
    <div class="header-right">
      <span class="collaboration-badge">In Collaboration</span>
      <button class="btn-share"><i class="bi bi-share"></i> Share</button>
      <button class="btn-move-order">Move to Order <i class="bi bi-arrow-right"></i></button>
    </div>
  </div>

  <!-- Tabs -->
  <div class="wishlist-tabs">
    <button class="tab active" data-tab="products">Products (12)</button>
    <button class="tab" data-tab="comments">Comments (4)</button>
    <button class="tab" data-tab="activity">Activity</button>
  </div>

  <!-- Main Content Grid -->
  <div class="wishlist-grid">
    <!-- Left: Products Section -->
    <div class="wishlist-products">
      <!-- Toolbar -->
      <div class="products-toolbar">
        <label class="select-all">
          <input type="checkbox" id="selectAll">
          <span>Select All</span>
        </label>
        <div class="toolbar-actions">
          <select class="toolbar-select">
            <option>Bulk Actions</option>
            <option>Remove Selected</option>
            <option>Move to Order</option>
          </select>
          <select class="toolbar-select">
            <option>Filter</option>
            <option>All Products</option>
            <option>Favorites</option>
            <option>On Sale</option>
          </select>
          <select class="toolbar-select">
            <option>Sort by</option>
            <option>Name A-Z</option>
            <option>Name Z-A</option>
            <option>Price Low to High</option>
            <option>Price High to Low</option>
          </select>
          <button class="view-toggle active" data-view="grid"><i class="bi bi-grid"></i></button>
          <button class="view-toggle" data-view="list"><i class="bi bi-list"></i></button>
        </div>
      </div>

      <!-- Product Grid -->
      <div class="product-grid" id="productGrid">
        <!-- Product 1 -->
        <div class="product-card" data-id="1">
          <div class="card-header">
            <input type="checkbox" class="product-checkbox">
            <button class="favorite-btn"><i class="bi bi-heart"></i></button>
          </div>
          <div class="product-image">
            <img src="https://via.placeholder.com/200x200?text=Bottle" alt="Premium Vacuum Bottle">
          </div>
          <div class="product-info">
            <h4>Premium Vacuum Bottle</h4>
            <p class="product-desc">500ml | 12h Hot & 24h Cold</p>
            <div class="price-section">
              <span class="current-price">AED 28</span>
              <span class="original-price">AED 45</span>
              <span class="discount-badge">38% off</span>
            </div>
            <div class="quantity-control">
              <button class="qty-btn minus">−</button>
              <input type="number" class="qty-input" value="100" min="1">
              <button class="qty-btn plus">+</button>
            </div>
            <input type="text" class="product-note" value="Great for employees" placeholder="Add a note...">
            <div class="card-footer">
              <div class="collaborators-avatars">
                <span class="avatar">NV</span>
                <span class="avatar">RG</span>
              </div>
              <span class="comment-count"><i class="bi bi-chat"></i> 3 comments</span>
            </div>
          </div>
        </div>

        <!-- Product 2 -->
        <div class="product-card" data-id="2">
          <div class="card-header">
            <input type="checkbox" class="product-checkbox">
            <button class="favorite-btn"><i class="bi bi-heart"></i></button>
          </div>
          <div class="product-image">
            <img src="https://via.placeholder.com/200x200?text=Hamper" alt="Executive Gift Hamper">
          </div>
          <div class="product-info">
            <h4>Executive Gift Hamper</h4>
            <p class="product-desc">Premium Festive Box</p>
            <div class="price-section">
              <span class="current-price">AED 85</span>
              <span class="original-price">AED 120</span>
              <span class="discount-badge">29% off</span>
            </div>
            <div class="quantity-control">
              <button class="qty-btn minus">−</button>
              <input type="number" class="qty-input" value="50" min="1">
              <button class="qty-btn plus">+</button>
            </div>
            <input type="text" class="product-note" value="For leadership team" placeholder="Add a note...">
            <div class="card-footer">
              <div class="collaborators-avatars">
                <span class="avatar">NV</span>
                <span class="avatar">AS</span>
              </div>
              <span class="comment-count"><i class="bi bi-chat"></i> 2 comments</span>
            </div>
          </div>
        </div>

        <!-- Product 3 -->
        <div class="product-card" data-id="3">
          <div class="card-header">
            <input type="checkbox" class="product-checkbox">
            <button class="favorite-btn favorite"><i class="bi bi-heart-fill"></i></button>
          </div>
          <div class="product-image">
            <img src="https://via.placeholder.com/200x200?text=Notebook" alt="Premium Notebook Set">
          </div>
          <div class="product-info">
            <h4>Premium Notebook Set</h4>
            <p class="product-desc">A5 | Hard Cover | With Pen</p>
            <div class="price-section">
              <span class="current-price">AED 20</span>
              <span class="original-price">AED 28</span>
              <span class="discount-badge">29% off</span>
            </div>
            <div class="quantity-control">
              <button class="qty-btn minus">−</button>
              <input type="number" class="qty-input" value="200" min="1">
              <button class="qty-btn plus">+</button>
            </div>
            <input type="text" class="product-note" value="Add our logo" placeholder="Add a note...">
            <div class="card-footer">
              <div class="collaborators-avatars">
                <span class="avatar">NV</span>
              </div>
              <span class="comment-count"><i class="bi bi-chat"></i> 1 comment</span>
            </div>
          </div>
        </div>

        <!-- Product 4 -->
        <div class="product-card" data-id="4">
          <div class="card-header">
            <input type="checkbox" class="product-checkbox">
            <button class="favorite-btn"><i class="bi bi-heart"></i></button>
          </div>
          <div class="product-image">
            <img src="https://via.placeholder.com/200x200?text=Speaker" alt="Wireless Bluetooth Speaker">
          </div>
          <div class="product-info">
            <h4>Wireless Bluetooth Speaker</h4>
            <p class="product-desc">Compact | 10h Playtime</p>
            <div class="price-section">
              <span class="current-price">AED 38</span>
              <span class="original-price">AED 60</span>
              <span class="discount-badge">37% off</span>
            </div>
            <div class="quantity-control">
              <button class="qty-btn minus">−</button>
              <input type="number" class="qty-input" value="100" min="1">
              <button class="qty-btn plus">+</button>
            </div>
            <input type="text" class="product-note" value="For key clients" placeholder="Add a note...">
            <div class="card-footer">
              <div class="collaborators-avatars">
                <span class="avatar">PK</span>
              </div>
              <span class="comment-count"><i class="bi bi-chat"></i> 1 comment</span>
            </div>
          </div>
        </div>

        <!-- Product 5 -->
        <div class="product-card" data-id="5">
          <div class="card-header">
            <input type="checkbox" class="product-checkbox">
            <button class="favorite-btn"><i class="bi bi-heart"></i></button>
          </div>
          <div class="product-image">
            <img src="https://via.placeholder.com/200x200?text=Plant" alt="Desk Plant with Self-Watering Pot">
          </div>
          <div class="product-info">
            <h4>Desk Plant with Self-Watering Pot</h4>
            <p class="product-desc">Eco Friendly | Office Desk</p>
            <div class="price-section">
              <span class="current-price">AED 18</span>
              <span class="original-price">AED 25</span>
              <span class="discount-badge">28% off</span>
            </div>
            <div class="quantity-control">
              <button class="qty-btn minus">−</button>
              <input type="number" class="qty-input" value="300" min="1">
              <button class="qty-btn plus">+</button>
            </div>
            <input type="text" class="product-note" value="Sustainable choice" placeholder="Add a note...">
            <div class="card-footer">
              <div class="collaborators-avatars">
                <span class="avatar">PK</span>
                <span class="avatar">AS</span>
              </div>
              <span class="comment-count"><i class="bi bi-chat"></i> 2 comments</span>
            </div>
          </div>
        </div>

        <!-- Product 6 -->
        <div class="product-card" data-id="6">
          <div class="card-header">
            <input type="checkbox" class="product-checkbox">
            <button class="favorite-btn"><i class="bi bi-heart"></i></button>
          </div>
          <div class="product-image">
            <img src="https://via.placeholder.com/200x200?text=Gift+Box" alt="Custom Gift Box">
          </div>
          <div class="product-info">
            <h4>Custom Gift Box</h4>
            <p class="product-desc">With Branding</p>
            <div class="price-section">
              <span class="current-price">AED 12</span>
              <span class="original-price">AED 18</span>
              <span class="discount-badge">33% off</span>
            </div>
            <div class="quantity-control">
              <button class="qty-btn minus">−</button>
              <input type="number" class="qty-input" value="500" min="1">
              <button class="qty-btn plus">+</button>
            </div>
            <input type="text" class="product-note" value="Include thank you card" placeholder="Add a note...">
            <div class="card-footer">
              <div class="collaborators-avatars">
                <span class="avatar">NV</span>
              </div>
              <span class="comment-count"><i class="bi bi-chat"></i> 1 comment</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right: Collaboration Panel -->
    <div class="wishlist-sidebar">
      <!-- Collaborators Card -->
      <div class="sidebar-card collaborators-card">
        <div class="card-header">
          <h3>Collaborators (5)</h3>
          <a href="#" class="manage-link">Manage</a>
        </div>
        <div class="collaborators-list">
          <div class="collaborator-item">
            <span class="collaborator-avatar">NV</span>
            <div class="collaborator-info">
              <h4>Neha Verma (You)</h4>
              <p>HR Manager</p>
            </div>
            <span class="permission-badge owner">Owner</span>
          </div>
          <div class="collaborator-item">
            <span class="collaborator-avatar">RG</span>
            <div class="collaborator-info">
              <h4>Rohan Gupta</h4>
              <p>Procurement</p>
            </div>
            <span class="permission-badge edit">Can Edit</span>
          </div>
          <div class="collaborator-item">
            <span class="collaborator-avatar">AS</span>
            <div class="collaborator-info">
              <h4>Aman Sharma</h4>
              <p>Finance</p>
            </div>
            <span class="permission-badge approve">Can Approve</span>
          </div>
          <div class="collaborator-item">
            <span class="collaborator-avatar">PK</span>
            <div class="collaborator-info">
              <h4>Pooja Khanna</h4>
              <p>Marketing</p>
            </div>
            <span class="permission-badge edit">Can Edit</span>
          </div>
          <div class="collaborator-item">
            <span class="collaborator-avatar">SK</span>
            <div class="collaborator-info">
              <h4>Suresh Kumar</h4>
              <p>Operations</p>
            </div>
            <span class="permission-badge view">Can View</span>
          </div>
        </div>
        <button class="invite-btn"><i class="bi bi-person-plus"></i> Invite Team Member</button>
      </div>

      <!-- Recent Comments Card -->
      <div class="sidebar-card comments-card">
        <div class="card-header">
          <h3>Recent Comments</h3>
          <a href="#" class="view-all-link">View All</a>
        </div>
        <div class="comments-list">
          <div class="comment-item">
            <span class="comment-avatar">NV</span>
            <div class="comment-content">
              <h4>Neha Verma</h4>
              <span class="comment-time">2 hours ago</span>
              <p>Let's add our logo on the bottles.</p>
            </div>
          </div>
          <div class="comment-item">
            <span class="comment-avatar">AS</span>
            <div class="comment-content">
              <h4>Aman Sharma</h4>
              <span class="comment-time">5 hours ago</span>
              <p>Looks good. Please check if we can get a better price for 500+ quantity.</p>
            </div>
          </div>
          <div class="comment-item">
            <span class="comment-avatar">PK</span>
            <div class="comment-content">
              <h4>Pooja Khanna</h4>
              <span class="comment-time">1 day ago</span>
              <p>Love the plant option! Very unique.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Ready to Order Panel -->
      <div class="sidebar-card order-panel">
        <div class="order-icon">
          <i class="bi bi-cart3"></i>
        </div>
        <h3>Ready to Place Order?</h3>
        <p>Convert this wishlist into an order request and send for approval.</p>
        <button class="btn-proceed-order">Proceed to Order <i class="bi bi-arrow-right"></i></button>
      </div>
    </div>
  </div>
</main>
<div class="toast" id="toast" role="status"></div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('web_frontend/assets/js/script.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Tab switching
  const tabs = document.querySelectorAll('.tab');
  tabs.forEach(tab => {
    tab.addEventListener('click', function() {
      tabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      // Add tab content switching logic here
    });
  });

  // Select All checkbox
  const selectAll = document.getElementById('selectAll');
  const productCheckboxes = document.querySelectorAll('.product-checkbox');
  
  selectAll.addEventListener('change', function() {
    productCheckboxes.forEach(checkbox => {
      checkbox.checked = this.checked;
    });
  });

  // Quantity controls
  document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const input = this.parentElement.querySelector('.qty-input');
      let value = parseInt(input.value);
      
      if (this.classList.contains('minus')) {
        if (value > 1) {
          value--;
        }
      } else if (this.classList.contains('plus')) {
        value++;
      }
      
      input.value = value;
    });
  });

  // Favorite buttons
  document.querySelectorAll('.favorite-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      this.classList.toggle('favorite');
      const icon = this.querySelector('i');
      if (this.classList.contains('favorite')) {
        icon.classList.remove('bi-heart');
        icon.classList.add('bi-heart-fill');
      } else {
        icon.classList.remove('bi-heart-fill');
        icon.classList.add('bi-heart');
      }
    });
  });

  // View toggles
  const viewToggles = document.querySelectorAll('.view-toggle');
  viewToggles.forEach(toggle => {
    toggle.addEventListener('click', function() {
      viewToggles.forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      
      const productGrid = document.getElementById('productGrid');
      if (this.dataset.view === 'list') {
        productGrid.classList.add('list-view');
      } else {
        productGrid.classList.remove('list-view');
      }
    });
  });

  // Share button
  document.querySelector('.btn-share').addEventListener('click', function() {
    alert('Share functionality - Opens share dialog');
  });

  // Move to Order button
  document.querySelector('.btn-move-order').addEventListener('click', function() {
    const selected = document.querySelectorAll('.product-checkbox:checked').length;
    if (selected === 0) {
      alert('Please select at least one product to move to order.');
    } else {
      alert(`Moving ${selected} selected products to order...`);
    }
  });

  // Proceed to Order button
  document.querySelector('.btn-proceed-order').addEventListener('click', function() {
    const selected = document.querySelectorAll('.product-checkbox:checked').length;
    if (selected === 0) {
      alert('Please select at least one product to proceed to order.');
    } else {
      alert(`Proceeding to order with ${selected} products...`);
    }
  });

  // Invite Team Member button
  document.querySelector('.invite-btn').addEventListener('click', function() {
    alert('Invite team member - Opens invitation dialog');
  });
});
</script>
@endsection
