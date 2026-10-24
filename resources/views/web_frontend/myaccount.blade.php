@extends('web_frontend.layouts.app')

@section('title', 'Corporate Portal Dashboard | More Than Giftz')
@section('meta')
  <meta name="description" content="Manage your corporate gifting, all in one place.">
@endsection

@section('content')
@php
  $userdetails = $userdetails ?? null;
  $items_array = $items_array ?? [];
  $footerlevel1 = $footerlevel1 ?? null;
  $contact = $contact ?? null;
  $totalOrders = $totalOrders ?? 0;
  $totalSpend = $totalSpend ?? 0;
  $recentOrders = $recentOrders ?? collect();
  $collections = $collections ?? collect();
@endphp

<div class="dashboard-wrapper">

<aside class="sidebar">
  <button class="company"><i class="bi bi-buildings"></i><span><strong>ABC Technologies</strong><small>Switch Company <i class="bi bi-arrow-right"></i></small></span></button>
  <nav>
    <a class="active" href="#"><i class="bi bi-gift"></i>Dashboard</a>
    <a href="#orders"><i class="bi bi-cart3"></i>Orders</a>
    <a href="#recipients"><i class="bi bi-people"></i>Recipients</a>
    <a href="#addresses"><i class="bi bi-geo-alt"></i>Addresses</a>
    <a href="#branding"><i class="bi bi-image"></i>Branding Assets</a>
    <a href="#collections"><i class="bi bi-bookmark"></i>Saved Collections</a>
    <a href="#employees"><i class="bi bi-people-fill"></i>Employees</a>
    <a href="#reports"><i class="bi bi-bar-chart-line"></i>Reports</a>
    <a href="#settings"><i class="bi bi-gear"></i>Account Settings</a>
  </nav>
  <div class="help"><i class="bi bi-headset"></i><div><strong>Need Help?</strong><p>Your dedicated account manager is here.</p></div><button class="outline full" data-toast="Contact request started.">Contact Us <i class="bi bi-arrow-right"></i></button></div>
</aside>
<main class="content">
  <section class="hero"><img src="{{ asset('web_frontend/assets/images/Thoughtful-Gifts.png') }}" alt=""><div><h1>Welcome back, Rohan!</h1><p>Manage your corporate gifting, all in one place.</p></div></section>
  <section class="stats">
    <article class="stat"><div class="stat-icon"><i class="bi bi-box-seam"></i></div><div><small>Total Orders</small><strong>{{ $totalOrders }}</strong><b class="up">↑ 20%</b><small>vs last 3 months</small></div></article>
    <article class="stat"><div class="stat-icon"><i class="bi bi-gift"></i></div><div><small>Items Delivered</small><strong>2,850</strong><b class="up">↑ 35%</b><small>vs last 3 months</small></div></article>
    <article class="stat"><div class="stat-icon"><i class="bi bi-clock"></i></div><div><small>Pending Approvals</small><strong>3</strong><b class="down">↓ 2</b><small>requires your action</small></div></article>
    <article class="stat"><div class="stat-icon"><i class="bi bi-people"></i></div><div><small>Total Recipients</small><strong>1,240</strong><b class="up">↑ 18%</b><small>active employees</small></div></article>
    <article class="stat"><div class="stat-icon"><i class="bi bi-file-earmark-text"></i></div><div><small>Total Spend (YTD)</small><strong>AED {{ number_format($totalSpend, 2) }}</strong><b class="up">↑ 12%</b><small>vs last year</small></div></article>
  </section>
  <section class="quick"><h2>Quick Actions</h2><div class="quick-grid">
    <button data-toast="Place a New Order selected."><i class="bi bi-gift"></i><span>Place a New Order</span><i class="bi bi-arrow-right"></i></button>
    <button data-toast="Manage Recipients selected."><i class="bi bi-people"></i><span>Manage Recipients</span><i class="bi bi-arrow-right"></i></button>
    <button data-toast="Manage Addresses selected."><i class="bi bi-geo-alt"></i><span>Manage Addresses</span><i class="bi bi-arrow-right"></i></button>
    <button data-toast="Upload Branding Assets selected."><i class="bi bi-image"></i><span>Upload Branding Assets</span><i class="bi bi-arrow-right"></i></button>
    <button data-toast="View Saved Collections selected."><i class="bi bi-bookmark"></i><span>View Saved Collections</span><i class="bi bi-arrow-right"></i></button>
    <button data-toast="Invite Employees selected."><i class="bi bi-person-plus"></i><span>Invite Employees</span><i class="bi bi-arrow-right"></i></button>
  </div></section>
  <section class="middle">
    <article class="panel orders" id="orders"><div class="panel-head"><div><i class="bi bi-pencil-square"></i><span><strong>Recent Orders</strong></span></div><button class="outline" data-toast="Showing all orders.">View All <i class="bi bi-arrow-right"></i></button></div>
      @if($recentOrders->count() > 0)
        @foreach($recentOrders as $order)
          <div class="order-row">
            <div class="product-thumb thumb-one"></div>
            <div class="order-info">
              <strong>#MTG-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong>
              <small>{{ $order->name ?? 'Order Item' }}</small>
              <small>{{ $order->quantity ?? 0 }} items • {{ $order->created_at ? $order->created_at->format('d M Y') : 'N/A' }}</small>
            </div>
            <span class="pill green-bg">{{ $order->orderstatus ?? 'Pending' }}</span>
            <button class="outline track" data-toast="Tracking #MTG-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}">Track →</button>
          </div>
        @endforeach
      @else
        <div class="order-row" style="justify-content: center; color: #687181;">
          <small>No recent orders found</small>
        </div>
      @endif
    </article>
    <div class="right-stack">
      <article class="quote-card"><h3>Need a Custom Solution?</h3><p>Talk to your dedicated account manager for bulk pricing, custom branding and special requirements.</p><button class="dark" data-toast="Quote request started.">Request a Quote <i class="bi bi-arrow-right"></i></button><div class="quote-product">Your<br>Logo</div></article>
      <article class="panel report" id="reports"><div class="panel-head"><div><i class="bi bi-file-earmark-text"></i><span><strong>Download Reports</strong><small>Get detailed reports for your records.</small></span></div></div><div class="report-actions"><select id="reportType"><option>Order History Report</option><option>Spending Summary</option><option>Recipient Report</option><option>Approval Report</option></select><button class="dark" id="download"><i class="bi bi-download"></i></button></div></article>
    </div>
  </section>
  <section class="bottom">
    <article class="panel" id="collections"><div class="panel-head"><div><span><strong>Your Saved Collections</strong></span></div><button class="outline" data-toast="Showing saved collections.">View All <i class="bi bi-arrow-right"></i></button></div><div class="collections">
      @if($collections->count() > 0)
        @foreach($collections as $collection)
          <button class="collection" onclick="viewCollection({{ $collection->id }})">
            <div class="collection-image {{ $loop->index % 4 == 0 ? 'image-a' : ($loop->index % 4 == 1 ? 'image-b' : ($loop->index % 4 == 2 ? 'image-c' : 'image-d')) }}"></div>
            <strong>{{ $collection->name }}</strong>
            <small>{{ $collection->products_count ?? 0 }} products</small>
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
<script>
document.getElementById('createCollection').addEventListener('click', function() {
  const name = prompt('Enter a name for your new collection:');
  if (name && name.trim()) {
    fetch('/collections', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        name: name.trim()
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Collection "' + name.trim() + '" created successfully!');
        location.reload();
      } else {
        alert('Error creating collection: ' + (data.message || 'Unknown error'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error creating collection');
    });
  }
});

function viewCollection(collectionId) {
  alert('Viewing collection ID: ' + collectionId);
  // You can redirect to a collection detail page here
  // window.location.href = '/collections/' + collectionId;
}
</script>
@endsection
