@extends('web_frontend.layouts.app')

@section('title', 'Reports | More Than Giftz')
@section('meta')
  <meta name="description" content="Your orders, spending and approvals, with downloads.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/reports.css') }}">
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/orders-new.css') }}">
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

<div class="orders-page">
  <div class="container-fluid px-4 px-xl-5">

    <nav class="account-breadcrumb" aria-label="Breadcrumb">
      <a href="/">Home</a>
      <i class="bi bi-chevron-right"></i>
      <a href="{{ route('corporate.portal') }}">My Account</a>
      <i class="bi bi-chevron-right"></i>
      <span>Reports</span>
    </nav>

    <div class="account-layout">

      @include('web_frontend.partials.corporate-sidebar', ['activePage' => 'reports'])

      <main class="account-main">

        <div class="orders-head">
          <h1>Reports</h1>
          <p>Your orders, spending and approvals at a glance, with downloads for your records.</p>
        </div>

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
  </div>
</div>
@endsection
