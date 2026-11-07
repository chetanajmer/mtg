@extends('web_frontend.layouts.app')

@section('title', 'Reports | More Than Giftz')
@section('meta')
  <meta name="description" content="Your orders, spending and approvals, with downloads.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/reports.css') }}">
@endsection

@section('content')
@php
  $userName = session()->get('fullname') ?? session()->get('username') ?? 'User';
  $maxMonth = max(1, $months->max('value') ?: 1);
  $range = $period === 'custom'
    ? (($filters['from'] ?? null) ? date('d M Y', strtotime($filters['from'])) : 'the start') . ' to ' . (($filters['to'] ?? null) ? date('d M Y', strtotime($filters['to'])) : 'today')
    : $periods[$period];
  $downloadQuery = http_build_query(array_filter($query));
@endphp

<div class="dashboard-wrapper">

<aside class="sidebar">
  <button class="company"><i class="bi bi-buildings"></i><span><strong>{{ $userName }}</strong><small>Switch Company <i class="bi bi-arrow-right"></i></small></span></button>
  <nav>
    <a href="{{ route('corporate.portal') }}"><i class="bi bi-gift"></i>Dashboard</a>
    <a href="/orders"><i class="bi bi-cart3"></i>Orders</a>
    <a href="/corporate-addresses"><i class="bi bi-geo-alt"></i>Addresses</a>
    <a href="{{ route('corporate.portal') }}#branding"><i class="bi bi-image"></i>Branding Assets</a>
    <a href="/collections-all"><i class="bi bi-bookmark"></i>Saved Collections</a>
    <a href="{{ route('corporate.employees') }}"><i class="bi bi-people-fill"></i>Employees</a>
    <a class="active" href="{{ route('corporate.reports') }}"><i class="bi bi-bar-chart-line"></i>Reports</a>
    <a href="{{ route('planner.index') }}"><i class="bi bi-calculator"></i>Budget Planner</a>
    <a href="/corporate-account-settings"><i class="bi bi-gear"></i>Account Settings</a>
    <a href="{{ route('corporate.logout') }}" style="color: #dc3545;"><i class="bi bi-box-arrow-right"></i>Logout</a>
  </nav>
  <div class="help"><i class="bi bi-headset"></i><div><strong>Need Help?</strong><p>Your dedicated account manager is here.</p></div><a class="outline full help-link" href="mailto:info@morethangiftz.com?subject=Help%20with%20my%20account">Contact Us <i class="bi bi-arrow-right"></i></a><a class="help-call" href="tel:+971557863450"><i class="bi bi-telephone"></i> +971 55 786 3450</a></div>
</aside>

<main class="content">
  <section class="hero" style="min-height: 150px;">
    <div style="width: 100%;">
      <nav style="margin-bottom: 10px; font-size: 14px; color: #6b7280;">
        <a href="{{ route('corporate.portal') }}" style="color: #6b7280; text-decoration: none;">Corporate Portal</a>
        <span style="margin: 0 8px;">›</span>
        <span style="color: #374151;">Reports</span>
      </nav>
      <h1>Reports</h1>
      <p>Your orders, spending and approvals at a glance, with downloads for your records.</p>
    </div>
  </section>

  <section class="rp-filters">
    <div class="rp-periods" role="group" aria-label="Period">
      @foreach($periods as $key => $label)
        @if($key !== 'custom')
          <a href="{{ route('corporate.reports', ['period' => $key]) }}" class="{{ $period === $key ? 'active' : '' }}">{{ $label }}</a>
        @endif
      @endforeach
    </div>
    <form method="GET" action="{{ route('corporate.reports') }}" class="rp-custom">
      <input type="hidden" name="period" value="custom">
      <label>From <input type="date" name="from" value="{{ $period === 'custom' ? ($filters['from'] ?? '') : '' }}"></label>
      <label>To <input type="date" name="to" value="{{ $period === 'custom' ? ($filters['to'] ?? '') : '' }}"></label>
      <button type="submit" class="{{ $period === 'custom' ? 'active' : '' }}">Show</button>
    </form>
  </section>
  <p class="rp-range">Showing <strong>{{ $range }}</strong>, by order date.</p>

  <section class="rp-cards">
    <article><small>Orders</small><strong>{{ number_format($summary['orders']) }}</strong><span>{{ number_format($summary['completed']) }} completed</span></article>
    <article><small>Confirmed spend</small><strong>AED {{ number_format($summary['confirmedValue'], 2) }}</strong><span>{{ number_format($summary['confirmedOrders']) }} confirmed {{ $summary['confirmedOrders'] == 1 ? 'order' : 'orders' }}</span></article>
    <article><small>Pieces delivered</small><strong>{{ number_format($summary['deliveredPieces']) }}</strong><span>of {{ number_format($summary['pieces']) }} ordered</span></article>
    <article><small>Average order value</small><strong>AED {{ number_format($summary['average'], 2) }}</strong><span>incl. 5% VAT</span></article>
  </section>

  <section class="rp-grid">
    <article class="rp-panel">
      <h2>Spending by month</h2>
      @if($months->count())
        <ul class="rp-bars">
          @foreach($months as $m)
            <li>
              <span class="rp-bar-label">{{ $m['label'] }}</span>
              <span class="rp-bar"><span style="width: {{ max(2, round($m['value'] / $maxMonth * 100)) }}%"></span></span>
              <span class="rp-bar-value">AED {{ number_format($m['value'], 0) }}<small>{{ $m['orders'] }} {{ $m['orders'] == 1 ? 'order' : 'orders' }}</small></span>
            </li>
          @endforeach
        </ul>
      @else
        <p class="rp-empty-text">No orders in this period.</p>
      @endif
    </article>

    <article class="rp-panel">
      <h2>Most ordered products</h2>
      @if($products->count())
        <ol class="rp-products">
          @foreach($products as $p)
            <li><span><strong>{{ $p['name'] }}</strong><small>{{ $p['modelno'] ? 'Model ' . $p['modelno'] . ' · ' : '' }}{{ $p['orders'] }} {{ $p['orders'] == 1 ? 'order' : 'orders' }}</small></span><b>{{ number_format($p['pieces']) }} pcs</b></li>
          @endforeach
        </ol>
      @else
        <p class="rp-empty-text">No products ordered in this period.</p>
      @endif
    </article>
  </section>

  <section class="rp-panel rp-downloads" id="downloads">
    <h2>Download reports</h2>
    <p class="rp-hint">CSV files for {{ $range }}; they open in Excel or Google Sheets.</p>
    <div class="rp-download-grid">
      <a href="{{ route('corporate.reports.download', 'orders') }}{{ $downloadQuery ? '?' . $downloadQuery : '' }}"><i class="bi bi-receipt"></i><span><strong>Order history</strong><small>Every product in every order, with quantities and prices.</small></span><i class="bi bi-download"></i></a>
      <a href="{{ route('corporate.reports.download', 'spending') }}{{ $downloadQuery ? '?' . $downloadQuery : '' }}"><i class="bi bi-graph-up"></i><span><strong>Spending summary</strong><small>Orders and value per month, with a total.</small></span><i class="bi bi-download"></i></a>
      <a href="{{ route('corporate.reports.download', 'approvals') }}{{ $downloadQuery ? '?' . $downloadQuery : '' }}"><i class="bi bi-check2-square"></i><span><strong>Approval history</strong><small>Your decisions on designs and samples, with dates.</small></span><i class="bi bi-download"></i></a>
    </div>
  </section>

  <section class="rp-panel">
    <h2>Orders in this period</h2>
    @if($orders->count())
      <div class="rp-table-wrap">
        <table class="rp-table">
          <thead><tr><th>Order</th><th>Date</th><th>Products</th><th>Pieces</th><th>Value</th><th>Status</th></tr></thead>
          <tbody>
            @foreach($orders as $order)
              @php $first = $order->items->first(); $more = $order->items->count() - 1; @endphp
              <tr>
                <td data-label="Order"><a href="{{ route('customer.orders.show', $order->orderid) }}">#{{ $order->orderid }}</a></td>
                <td data-label="Date">{{ $order->created_at->format('d M Y') }}</td>
                <td data-label="Products">{{ optional($first)->name ?: 'Order' }}@if($more > 0) <small>+ {{ $more }} more</small>@endif</td>
                <td data-label="Pieces">{{ number_format($order->items->sum('quantity')) }}</td>
                <td data-label="Value">{{ $order->totalLabel() }}</td>
                <td data-label="Status"><span class="rp-status">{{ \App\Services\OrderReports::status($order, true) }}</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="rp-empty">
        <i class="bi bi-bar-chart"></i>
        <p>No orders in this period. @if($period !== 'all')<a href="{{ route('corporate.reports', ['period' => 'all']) }}">See all time</a>@endif</p>
      </div>
    @endif
  </section>
</main>
</div>
@endsection
