@extends('web_frontend.layouts.app')

@section('title', 'Corporate Portal Dashboard | More Than Giftz')
@section('meta')
  <meta name="description" content="Manage your corporate gifting, all in one place.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/myaccount.css') }}">
@endsection

@section('content')
@php
  $userdetails = $userdetails ?? null;
  $items_array = $items_array ?? [];
  $footerlevel1 = $footerlevel1 ?? null;
  $contact = $contact ?? null;
  $stats = array_merge(['orders' => 0, 'ordersThisYear' => 0, 'waiting' => 0, 'waitingOrder' => null, 'itemsDelivered' => 0, 'ordersDelivered' => 0,
    'active' => 0, 'completed' => 0, 'spend' => 0, 'confirmedOrders' => 0, 'awaitingConfirmation' => 0], $stats ?? []);
  $plural = function ($n, $word) { return number_format($n) . ' ' . $word . ($n == 1 ? '' : 's'); };
  $recentOrders = $recentOrders ?? collect();
  $cartCount = $cartCount ?? 0;
  $collections = $collections ?? collect();
  $user = $user ?? null;
  $userName = session()->get('fullname') ?? session()->get('username') ?? 'User';
@endphp

<div class="dashboard-wrapper">

<aside class="sidebar">
  <button class="company"><i class="bi bi-buildings"></i><span><strong>{{ $userName }}</strong><small>Switch Company <i class="bi bi-arrow-right"></i></small></span></button>
  <nav>
    <a class="active" href="#"><i class="bi bi-gift"></i>Dashboard</a>
    <a href="/orders"><i class="bi bi-cart3"></i>Orders</a>
    <a href="/corporate-addresses"><i class="bi bi-geo-alt"></i>Addresses</a>
    <a href="#branding"><i class="bi bi-image"></i>Branding Assets</a>
    <a href="/collections-all"><i class="bi bi-bookmark"></i>Saved Collections</a>
    <a href="#employees"><i class="bi bi-people-fill"></i>Employees</a>
    <a href="{{ route('corporate.reports') }}"><i class="bi bi-bar-chart-line"></i>Reports</a>
    <a href="/corporate-account-settings"><i class="bi bi-gear"></i>Account Settings</a>
    <a href="{{ route('corporate.logout') }}" style="color: #dc3545;"><i class="bi bi-box-arrow-right"></i>Logout</a>
  </nav>
  <div class="help"><i class="bi bi-headset"></i><div><strong>Need Help?</strong><p>Your dedicated account manager is here.</p></div><a class="outline full" href="mailto:info@morethangiftz.com?subject=Help%20with%20my%20account">Contact Us <i class="bi bi-arrow-right"></i></a><a class="help-call" href="tel:+971557863450"><i class="bi bi-telephone"></i> +971 55 786 3450</a></div>
</aside>
<main class="content">
  <section class="hero"><img src="{{ asset('web_frontend/assets/images/Thoughtful-Gifts.png') }}" alt=""><div><h1>Welcome back, {{ $userName }}!</h1><p>Manage your corporate gifting, all in one place.</p></div></section>
  <section class="stats">
    <article class="stat"><div class="stat-icon"><i class="bi bi-box-seam"></i></div><div><small>Total Orders</small><strong>{{ number_format($stats['orders']) }}</strong><b class="muted">{{ number_format($stats['ordersThisYear']) }} this year</b><small><a href="/orders">View all orders</a></small></div></article>
    <article class="stat"><div class="stat-icon"><i class="bi bi-gift"></i></div><div><small>Items Delivered</small><strong>{{ number_format($stats['itemsDelivered']) }}</strong><b class="muted">{{ $plural($stats['ordersDelivered'], 'order') }}</b><small>delivered to you</small></div></article>
    <article class="stat {{ $stats['waiting'] ? 'attention' : '' }}"><div class="stat-icon"><i class="bi bi-clock"></i></div><div><small>Pending Approvals</small><strong>{{ number_format($stats['waiting']) }}</strong>
      @if($stats['waiting'])<b class="down">Needs your decision</b><small><a href="{{ route('customer.orders.show', $stats['waitingOrder']) }}#respond">Review order #{{ $stats['waitingOrder'] }}</a></small>
      @else<b class="up">Nothing waiting</b><small>designs &amp; samples to approve</small>@endif
    </div></article>
    <article class="stat"><div class="stat-icon"><i class="bi bi-arrow-repeat"></i></div><div><small>Active Orders</small><strong>{{ number_format($stats['active']) }}</strong><b class="muted">{{ number_format($stats['completed']) }} completed</b><small>in progress with us</small></div></article>
    <article class="stat"><div class="stat-icon"><i class="bi bi-file-earmark-text"></i></div><div><small>Total Spend ({{ now()->year }})</small><strong>AED {{ number_format($stats['spend'], 2) }}</strong><b class="muted">{{ $plural($stats['confirmedOrders'], 'confirmed order') }}</b>
      <small>@if($stats['awaitingConfirmation'] > 0)AED {{ number_format($stats['awaitingConfirmation'], 2) }} awaiting confirmation @else this calendar year @endif</small></div></article>
  </section>
  <section class="quick"><h2>Quick Actions</h2><div class="quick-grid">
    <a href="{{ url('/') }}"><i class="bi bi-gift"></i><span>Place a New Order</span><i class="bi bi-arrow-right"></i></a>
    <a href="/orders"><i class="bi bi-truck"></i><span>Track My Orders</span>@if($stats['waiting'])<small class="quick-note wait">{{ $stats['waiting'] }} need{{ $stats['waiting'] == 1 ? 's' : '' }} your decision</small>@endif<i class="bi bi-arrow-right"></i></a>
    <a href="/corporate-addresses"><i class="bi bi-geo-alt"></i><span>Manage Addresses</span><i class="bi bi-arrow-right"></i></a>
    <a href="{{ route('cart.index') }}"><i class="bi bi-cart3"></i><span>View Quote Cart</span>@if($cartCount)<small class="quick-note">{{ $cartCount }} item{{ $cartCount == 1 ? '' : 's' }} in cart</small>@endif<i class="bi bi-arrow-right"></i></a>
    <a href="/collections-all"><i class="bi bi-bookmark"></i><span>View Saved Collections</span><i class="bi bi-arrow-right"></i></a>
    <a href="/corporate-account-settings"><i class="bi bi-gear"></i><span>Account Settings</span><i class="bi bi-arrow-right"></i></a>
  </div></section>
  <section class="middle">
    <article class="panel orders" id="orders"><div class="panel-head"><div><i class="bi bi-pencil-square"></i><span><strong>Recent Orders</strong></span></div><a class="outline" href="/orders">View All <i class="bi bi-arrow-right"></i></a></div>
      @if($recentOrders->count() > 0)
        @foreach($recentOrders as $order)
          @php
            $first = $order->items->first();
            $more = $order->items->count() - 1;
            $image = $first ? $first->productImageUrl() : null;
            $finished = $order->current_step == 10 && $order->step_state === 'done';
            $state = $finished ? 'done' : $order->step_state;
          @endphp
          <div class="order-row {{ $state === 'waiting_customer' ? 'attention' : '' }}">
            <div class="recent-thumb">@if($image)<img src="{{ $image }}" alt="" loading="lazy" onerror="this.remove()">@endif<i class="bi bi-gift"></i></div>
            <div class="order-info">
              <strong>#{{ $order->orderid }} · {{ optional($order->created_at)->format('d M Y') }}</strong>
              <small>{{ optional($first)->name ?: 'Order' }}@if($more > 0) + {{ $more }} more @endif</small>
              <small>{{ number_format($order->items->sum('quantity')) }} pcs · AED {{ number_format($order->total, 2) }}</small>
              <span class="recent-status {{ $state }}">{{ $finished ? 'Completed' : config('order_steps.' . $order->current_step . '.customer') . ' · ' . \App\Services\OrderWorkflow::CUSTOMER_LABELS[$state] }}</span>
            </div>
            <a class="outline track" href="{{ route('customer.orders.show', $order->orderid) }}{{ $state === 'waiting_customer' ? '#respond' : '' }}">{{ $state === 'waiting_customer' ? 'Respond' : 'Track' }} <i class="bi bi-arrow-right"></i></a>
          </div>
        @endforeach
      @else
        <div class="order-row" style="justify-content: center; color: #687181;">
          <small>No orders yet. Send a quote request and it will show up here.</small>
        </div>
      @endif
    </article>
    <div class="right-stack">
      <article class="quote-card"><h3>Need a Custom Solution?</h3><p>Talk to your dedicated account manager for bulk pricing, custom branding and special requirements.</p><a class="dark" href="{{ route('cart.index') }}">Request a Quote{{ $cartCount ? ' (' . $cartCount . ' item' . ($cartCount == 1 ? '' : 's') . ')' : '' }} <i class="bi bi-arrow-right"></i></a><div class="quote-product">Your<br>Logo</div></article>
      <article class="panel report" id="reports"><div class="panel-head"><div><i class="bi bi-file-earmark-text"></i><span><strong>Download Reports</strong><small>This year as CSV, or open your reports.</small></span></div><a class="outline" href="{{ route('corporate.reports') }}">Open <i class="bi bi-arrow-right"></i></a></div><div class="report-actions"><select aria-label="Report to download" onchange="document.getElementById('reportDownload').href = this.value"><option value="{{ route('corporate.reports.download', 'orders') }}">Order History Report</option><option value="{{ route('corporate.reports.download', 'spending') }}">Spending Summary</option><option value="{{ route('corporate.reports.download', 'approvals') }}">Approval Report</option></select><a class="dark" id="reportDownload" href="{{ route('corporate.reports.download', 'orders') }}" style="justify-content: center;" aria-label="Download report" title="Download"><i class="bi bi-download"></i></a></div></article>
    </div>
  </section>
  <section class="bottom">
    <article class="panel" id="collections"><div class="panel-head"><div><span><strong>Your Saved Collections</strong></span></div><button class="outline" onclick="window.location.href='/collections-all'">View All <i class="bi bi-arrow-right"></i></button></div><div class="collections">
      @if($collections->count() > 0)
        @foreach($collections as $collection)
          <button class="collection" onclick="viewCollection({{ $collection->id }})">
            <div class="collection-image"></div>
            <strong>{{ $collection->name }}</strong>
            <small>{{ $collection->items_count ?? 0 }} products</small>
          </button>
        @endforeach
      @endif
      <button class="create" id="createCollection"><i class="bi bi-plus-lg"></i><span>Create New<br>Collection</span></button>
    </div></article>
    <article class="panel" id="branding"><div class="panel-head"><div><span><strong>Branding Assets</strong></span></div><button class="outline" data-toast="Manage branding assets selected.">Manage <i class="bi bi-arrow-right"></i></button></div><div class="assets">
      <button data-toast="ABC Logo selected."><div class="asset-logo">A<span> ABC</span></div><strong>ABC Logo</strong><small>PNG</small></button>
      <button data-toast="Brand Guidelines selected."><div class="asset-doc">▤</div><strong>Brand Guidelines</strong><small>PDF</small></button>
      <button data-toast="Packaging Design selected."><div class="asset-box">ABC</div><strong>Packaging Design</strong><small>JPG</small></button>
      <button data-toast="Custom Card selected."><div class="asset-card">Thanks for<br>being awesome!</div><strong>Custom Card</strong><small>JPG</small></button>
    </div></article>
  </section>
</main>
<div class="toast" id="toast" role="status"></div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('web_frontend/assets/js/script.js') }}"></script>

<!-- Create Collection Modal -->
<div id="createCollectionModal" class="modal" style="display: none;">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Create New Collection</h2>
      <button class="close-btn" onclick="hideCreateCollectionModal()">&times;</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label for="collectionName">Collection Name *</label>
        <input type="text" id="collectionName" placeholder="Enter collection name" required>
      </div>
      <div class="form-group">
        <label for="collectionDescription">Description</label>
        <textarea id="collectionDescription" rows="3" placeholder="Enter description (optional)"></textarea>
      </div>
      <div class="form-actions">
        <button type="button" class="btn btn-outline" onclick="hideCreateCollectionModal()">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveCollectionBtn">Create Collection</button>
      </div>
    </div>
  </div>
</div>

<script>
// Create Collection Modal
function showCreateCollectionModal() {
  document.getElementById('createCollectionModal').style.display = 'flex';
}

function hideCreateCollectionModal() {
  document.getElementById('createCollectionModal').style.display = 'none';
  document.getElementById('collectionName').value = '';
  document.getElementById('collectionDescription').value = '';
}

document.getElementById('createCollection').addEventListener('click', function() {
  showCreateCollectionModal();
});

document.getElementById('saveCollectionBtn').addEventListener('click', function() {
  const name = document.getElementById('collectionName').value.trim();
  const description = document.getElementById('collectionDescription').value.trim();

  if (!name) {
    alert('Please enter a collection name');
    return;
  }

  fetch('/collections', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
      name: name,
      description: description
    })
  })
  .then(response => {
    console.log('Response status:', response.status);
    return response.json();
  })
  .then(data => {
    console.log('Response data:', data);
    if (data.success) {
      alert('Collection "' + name + '" created successfully!');
      hideCreateCollectionModal();
      location.reload();
    } else {
      alert('Error creating collection: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error creating collection: ' + error.message);
  });
});

function viewCollection(collectionId) {
  window.location.href = '/collections/' + collectionId;
}

// Close modal when clicking outside
window.onclick = function(event) {
  const modal = document.getElementById('createCollectionModal');
  if (event.target == modal) {
    hideCreateCollectionModal();
  }
}
</script>
@endsection
