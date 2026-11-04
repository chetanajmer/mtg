@extends('web_frontend.layouts.app')

@section('title', 'Corporate Portal Dashboard | More Than Giftz')
@section('meta')
  <meta name="description" content="Manage your corporate gifting, all in one place.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/dashboard-new.css') }}">
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/account-settings.css') }}">
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
  $steps = config('order_steps');
  $labels = \App\Services\OrderWorkflow::CUSTOMER_LABELS;
@endphp

<div class="dashboard-page">
  <div class="container-fluid px-4 px-xl-5">

    <nav class="account-breadcrumb" aria-label="Breadcrumb">
      <a href="/">Home</a>
      <i class="bi bi-chevron-right"></i>
      <a href="{{ route('corporate.portal') }}">My Account</a>
      <i class="bi bi-chevron-right"></i>
      <span>Dashboard</span>
    </nav>

    <div class="account-layout">

      @include('web_frontend.partials.corporate-sidebar', ['activePage' => 'dashboard'])

      <main class="account-main">

        <!-- Welcome Banner -->
        <div class="welcome-banner">
          <div class="welcome-banner-content">
            <h1>Welcome back, {{ $userName }}!</h1>
            <p>Manage your corporate gifting, all in one place.</p>
          </div>
          <div class="welcome-banner-image">
            Corporate<br>Gifting
          </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
          <div class="summary-card">
            <div class="summary-card-header">
              <div class="summary-card-icon"><i class="bi bi-box-seam"></i></div>
              <h3 class="summary-card-title">Total Orders</h3>
            </div>
            <div class="summary-card-value">{{ number_format($stats['orders']) }}</div>
            <p class="summary-card-subtitle">{{ number_format($stats['ordersThisYear']) }} this year</p>
            <div class="summary-card-growth positive"><i class="bi bi-arrow-up"></i> 12% vs last 3 months</div>
          </div>
          <div class="summary-card">
            <div class="summary-card-header">
              <div class="summary-card-icon"><i class="bi bi-gift"></i></div>
              <h3 class="summary-card-title">Items Delivered</h3>
            </div>
            <div class="summary-card-value">{{ number_format($stats['itemsDelivered']) }}</div>
            <p class="summary-card-subtitle">{{ $plural($stats['ordersDelivered'], 'order') }} delivered</p>
            <div class="summary-card-growth positive"><i class="bi bi-arrow-up"></i> 8% vs last 3 months</div>
          </div>
          <div class="summary-card {{ $stats['waiting'] ? 'attention' : '' }}">
            <div class="summary-card-header">
              <div class="summary-card-icon"><i class="bi bi-clock"></i></div>
              <h3 class="summary-card-title">Pending Approvals</h3>
            </div>
            <div class="summary-card-value">{{ number_format($stats['waiting']) }}</div>
            <p class="summary-card-subtitle">@if($stats['waiting']){{ $stats['waiting'] }} item{{ $stats['waiting'] == 1 ? '' : 's' }} need action @else No pending approvals @endif</p>
            @if($stats['waiting'])
            <div class="summary-card-growth"><a href="{{ route('customer.orders.show', $stats['waitingOrder']) }}#respond">Review now</a></div>
            @endif
          </div>
          <div class="summary-card">
            <div class="summary-card-header">
              <div class="summary-card-icon"><i class="bi bi-arrow-repeat"></i></div>
              <h3 class="summary-card-title">Active Orders</h3>
            </div>
            <div class="summary-card-value">{{ number_format($stats['active']) }}</div>
            <p class="summary-card-subtitle">{{ number_format($stats['completed']) }} completed</p>
            <div class="summary-card-growth positive"><i class="bi bi-arrow-up"></i> in progress with us</div>
          </div>
          <div class="summary-card">
            <div class="summary-card-header">
              <div class="summary-card-icon"><i class="bi bi-currency-dollar"></i></div>
              <h3 class="summary-card-title">Total Spend (YTD)</h3>
            </div>
            <div class="summary-card-value">AED {{ number_format($stats['spend'], 2) }}</div>
            <p class="summary-card-subtitle">{{ $plural($stats['confirmedOrders'], 'confirmed order') }}</p>
            <div class="summary-card-growth positive"><i class="bi bi-arrow-up"></i> 15% vs last year</div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-section">
          <h2>Quick Actions</h2>
          <div class="quick-actions-grid">
            <a href="{{ url('/') }}" class="quick-action-card">
              <div class="quick-action-icon"><i class="bi bi-gift"></i></div>
              <h3 class="quick-action-title">Place a New Order</h3>
            </a>
            <a href="/orders" class="quick-action-card">
              <div class="quick-action-icon"><i class="bi bi-truck"></i></div>
              <h3 class="quick-action-title">Track My Order</h3>
            </a>
            <a href="{{ route('corporate.addresses') }}" class="quick-action-card">
              <div class="quick-action-icon"><i class="bi bi-geo-alt"></i></div>
              <h3 class="quick-action-title">Manage Addresses</h3>
            </a>
            <a href="{{ route('cart.index') }}" class="quick-action-card">
              <div class="quick-action-icon"><i class="bi bi-cart3"></i></div>
              <h3 class="quick-action-title">View Quote Cart</h3>
            </a>
            <a href="/collections-all" class="quick-action-card">
              <div class="quick-action-icon"><i class="bi bi-bookmark"></i></div>
              <h3 class="quick-action-title">View Saved Collections</h3>
            </a>
            <a href="{{ route('corporate.account-settings') }}" class="quick-action-card">
              <div class="quick-action-icon"><i class="bi bi-gear"></i></div>
              <h3 class="quick-action-title">Account Settings</h3>
            </a>
          </div>
        </div>

        <!-- Recent Orders -->
        <div class="recent-orders-section">
          <div class="section-header">
            <h2>Recent Orders</h2>
            <a href="/orders" class="btn-view-all">View All <i class="bi bi-arrow-right"></i></a>
          </div>
          @if($recentOrders->count() > 0)
            <table class="order-table">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Date</th>
                  <th>Product</th>
                  <th>Quantity</th>
                  <th>Status</th>
                  <th>Amount</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($recentOrders->take(2) as $order)
                  @php
                    $first = $order->items->first();
                    $more = $order->items->count() - 1;
                    $image = $first ? $first->productImageUrl() : null;
                    $finished = $order->current_step == 10 && $order->step_state === 'done';
                    $state = $finished ? 'done' : $order->step_state;
                    $statusClass = $finished ? 'completed' : ($state === 'in_progress' ? 'in-progress' : ($state === 'waiting_customer' ? 'pending' : 'in-progress'));
                    $statusText = $finished ? 'Completed' : $labels[$state] ?? 'In Progress';
                  @endphp
                  <tr>
                    <td>#{{ $order->orderid }}</td>
                    <td>{{ optional($order->created_at)->format('d M Y') }}</td>
                    <td>
                      <div class="order-product">
                        @if($image)<img src="{{ $image }}" alt="" loading="lazy">@endif
                        <div>
                          <div class="order-product-name">{{ optional($first)->name ?: 'Order' }}</div>
                          @if($more > 0)<div class="order-product-additional">+ {{ $more }} more product{{ $more > 1 ? 's' : '' }}</div>@endif
                        </div>
                      </div>
                    </td>
                    <td>{{ number_format($order->items->sum('quantity')) }} pcs</td>
                    <td><span class="order-status-badge {{ $statusClass }}">{{ $statusText }}</span></td>
                    <td>AED {{ number_format($order->total, 2) }}</td>
                    <td><a href="{{ route('customer.orders.show', $order->orderid) }}" class="btn-view-order">View Order <i class="bi bi-arrow-right"></i></a></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @else
            <div style="text-align: center; padding: 40px; color: var(--muted);">
              <i class="bi bi-cart-x" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
              <p>No orders yet. Send a quote request and it will show up here.</p>
            </div>
          @endif
        </div>

        <!-- Custom Solution Card -->
        <div class="custom-solution-card">
          <div class="custom-solution-content">
            <h3>Need a Custom Solution?</h3>
            <p>Talk to your dedicated account manager for bulk pricing, custom branding and special requirements.</p>
            <a href="{{ route('cart.index') }}" class="btn-request-quote">Request a Quote <i class="bi bi-arrow-right"></i></a>
          </div>
          <div class="custom-solution-image">
            Your<br>Logo
          </div>
        </div>

        <!-- Download Reports -->
        <div class="download-reports-card">
          <div class="download-reports-header">
            <h3>Download Reports</h3>
          </div>
          <p class="download-reports-description">Get detailed reports for your records. Choose from available report types.</p>
          <div class="download-reports-actions">
            <select class="report-select" aria-label="Report to download" onchange="document.getElementById('reportDownload').href = this.value">
              <option value="{{ route('corporate.reports.download', 'orders') }}">Order History Report</option>
              <option value="{{ route('corporate.reports.download', 'spending') }}">Spending Summary</option>
              <option value="{{ route('corporate.reports.download', 'approvals') }}">Approval Report</option>
            </select>
            <a class="btn-download" id="reportDownload" href="{{ route('corporate.reports.download', 'orders') }}"><i class="bi bi-download"></i> Download</a>
          </div>
        </div>

        <!-- Bottom Sections -->
        <div class="bottom-sections">
          <!-- Saved Collections -->
          <div class="saved-collections-section">
            <div class="section-header">
              <h2>Your Saved Collections</h2>
              <a href="/collections-all" class="btn-view-all">View All <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="collections-grid">
              @if($collections->count() > 0)
                @foreach($collections->take(3) as $collection)
                  <a href="{{ route('collections.view', $collection->id) }}" class="collection-card">
                    <div class="collection-card-image"><i class="bi bi-gift"></i></div>
                    <h3 class="collection-card-name">{{ $collection->name }}</h3>
                    <p class="collection-card-count">{{ $collection->items_count ?? 0 }} products</p>
                  </a>
                @endforeach
              @endif
              <button class="btn-create-collection" onclick="window.location.href='/collections-all'">
                <i class="bi bi-plus-lg"></i>
                <span>Create New Collection</span>
              </button>
            </div>
          </div>

          <!-- Branding Assets -->
          <div class="branding-assets-section">
            <div class="section-header">
              <h2>Branding Assets</h2>
              <a href="{{ route('branding-assets.index') }}" class="btn-view-all">Manage <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="branding-assets-grid">
              <div class="branding-asset-card">
                <div class="branding-asset-thumbnail"><i class="bi bi-file-earmark-image"></i></div>
                <h3 class="branding-asset-name">Company Logo</h3>
                <p class="branding-asset-type">PNG</p>
              </div>
              <div class="branding-asset-card">
                <div class="branding-asset-thumbnail"><i class="bi bi-file-earmark-text"></i></div>
                <h3 class="branding-asset-name">Brand Guidelines</h3>
                <p class="branding-asset-type">PDF</p>
              </div>
              <div class="branding-asset-card">
                <div class="branding-asset-thumbnail"><i class="bi bi-box-seam"></i></div>
                <h3 class="branding-asset-name">Packaging Design</h3>
                <p class="branding-asset-type">JPG</p>
              </div>
              <div class="branding-asset-card">
                <div class="branding-asset-thumbnail"><i class="bi bi-card-image"></i></div>
                <h3 class="branding-asset-name">Custom Card</h3>
                <p class="branding-asset-type">JPG</p>
              </div>
            </div>
          </div>
        </div>

      </main>
    </div>
  </div>
</div>
@endsection
