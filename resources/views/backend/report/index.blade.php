@extends('layouts.app')

@section('content')
@php
  $maxMonth = max(1, $months->max('value') ?: 1);
  $from = $filters['from'] ?? '';
  $to = $filters['to'] ?? '';
  $year = now()->year;
  $quick = [
    'This year' => ['from' => "$year-01-01", 'to' => "$year-12-31"],
    'Last year' => ['from' => ($year - 1) . '-01-01', 'to' => ($year - 1) . '-12-31'],
    'Last 30 days' => ['from' => now()->subDays(29)->toDateString(), 'to' => now()->toDateString()],
    'All time' => ['from' => '', 'to' => ''],
  ];
  $keep = array_filter(['customer' => $filters['customer'] ?? null, 'product' => $filters['product'] ?? null, 'step' => $filters['step'] ?? null]);
@endphp
<style>
  .rp-admin .rp-cards { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-bottom: 16px; }
  .rp-admin .rp-cards .card-box { margin: 0; padding: 16px; }
  .rp-admin .rp-cards small, .rp-admin .rp-cards span { display: block; color: #6b7280; font-size: 12px; }
  .rp-admin .rp-cards strong { display: block; font-size: 22px; color: #15171a; margin: 4px 0; }
  .rp-admin .rp-quick a { margin: 0 6px 6px 0; background: #fff; color: #15171a; border: 1px solid #cfd5dc; }
  .rp-admin .rp-quick a.active { background: #15171a; color: #fff; border-color: #15171a; }
  .rp-admin .rp-bar { height: 10px; background: #eef0f3; border-radius: 5px; overflow: hidden; min-width: 80px; }
  .rp-admin .rp-bar span { display: block; height: 100%; background: #c9a46a; }
  .rp-admin h5 { font-weight: 600; margin: 0 0 12px; }
  .rp-admin .table td, .rp-admin .table th { vertical-align: middle; }
  .rp-admin .rp-filters label { font-size: 12px; color: #6b7280; margin-bottom: 4px; }
  .rp-admin .rp-note { color: #6b7280; font-size: 12px; }
  @media (max-width: 991px) { .rp-admin .rp-cards { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
</style>

<div class="content-page">
  <div class="content">
    <div class="container rp-admin">
      <div class="row">
        <div class="col-sm-12">
          <h4 class="page-title">Reports</h4>
          <p class="rp-note">One row per order, by order date. Values include 5% VAT; "confirmed" means staff confirmed the order (step 1).</p>
        </div>
      </div>

      <div class="card-box rp-filters">
        <div class="rp-quick">
          @foreach($quick as $label => $range)
            <a href="{{ route('reports.index', array_merge($range, $keep)) }}" class="btn btn-sm {{ $from === $range['from'] && $to === $range['to'] ? 'active' : '' }}" @if($from === $range['from'] && $to === $range['to']) aria-current="true" @endif>{{ $label }}</a>
          @endforeach
        </div>
        <form method="GET" action="{{ route('reports.index') }}" class="row" style="margin-top: 8px;">
          <div class="col-md-2 col-sm-6"><label for="rp-from">From</label><input type="date" id="rp-from" name="from" value="{{ $from }}" class="form-control"></div>
          <div class="col-md-2 col-sm-6"><label for="rp-to">To</label><input type="date" id="rp-to" name="to" value="{{ $to }}" class="form-control"></div>
          <div class="col-md-3 col-sm-6">
            <label for="rp-customer">Customer</label>
            <select id="rp-customer" name="customer" class="form-control">
              <option value="">All customers</option>
              @foreach($customerOptions as $c)
                <option value="{{ $c->customer_email }}" {{ ($filters['customer'] ?? '') === $c->customer_email ? 'selected' : '' }}>{{ $c->name ?: $c->customer_email }}{{ $c->company ? ' (' . $c->company . ')' : '' }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3 col-sm-6">
            <label for="rp-product">Product</label>
            <select id="rp-product" name="product" class="form-control">
              <option value="">All products</option>
              @foreach($productOptions as $p)
                <option value="{{ $p->productid }}" {{ (int) ($filters['product'] ?? 0) === (int) $p->productid ? 'selected' : '' }}>{{ $p->name }}{{ $p->modelno ? ' · ' . $p->modelno : '' }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 col-sm-6">
            <label for="rp-step">Stage</label>
            <select id="rp-step" name="step" class="form-control">
              <option value="">Any stage</option>
              <option value="waiting" {{ ($filters['step'] ?? '') === 'waiting' ? 'selected' : '' }}>Waiting for customer</option>
              @foreach(\App\Services\OrderReports::openSteps() as $n => $s)
                <option value="{{ $n }}" {{ (string) ($filters['step'] ?? '') === (string) $n ? 'selected' : '' }}>{{ $n }}. {{ $s['title'] }}</option>
              @endforeach
              <option value="completed" {{ ($filters['step'] ?? '') === 'completed' ? 'selected' : '' }}>Completed (incl. awaiting rating)</option>
            </select>
          </div>
          <div class="col-sm-12" style="margin-top: 10px;">
            <button type="submit" class="btn btn-primary">Show report</button>
            <a href="{{ route('reports.index') }}" class="btn btn-default">Reset</a>
            <span class="pull-right">
              <a href="{{ route('reports.export', $exportQuery) }}" class="btn btn-default"><i class="md md-file-download"></i> Export orders (CSV)</a>
              <a href="{{ route('reports.export', array_merge($exportQuery, ['rows' => 'lines'])) }}" class="btn btn-default"><i class="md md-file-download"></i> Export product lines (CSV)</a>
            </span>
          </div>
        </form>
      </div>

      <div class="rp-cards">
        <div class="card-box"><small>Orders</small><strong>{{ number_format($summary['orders']) }}</strong><span>{{ $summary['completed'] }} completed · {{ $summary['waiting'] }} waiting for customer</span></div>
        <div class="card-box"><small>Order value</small><strong>AED {{ number_format($summary['value'], 2) }}</strong><span>average AED {{ number_format($summary['average'], 2) }}</span></div>
        <div class="card-box"><small>Confirmed value</small><strong>AED {{ number_format($summary['confirmedValue'], 2) }}</strong><span>{{ $summary['confirmedOrders'] }} confirmed orders</span></div>
        <div class="card-box"><small>Pieces</small><strong>{{ number_format($summary['pieces']) }}</strong><span>{{ number_format($summary['deliveredPieces']) }} delivered</span></div>
      </div>

      <div class="row">
        <div class="col-lg-6">
          <div class="card-box" id="by-month">
            <h5>By month</h5>
            @if($months->count())
            <table class="table table-condensed">
              <thead><tr><th>Month</th><th>Orders</th><th></th><th class="text-right">Value</th><th class="text-right">Confirmed</th></tr></thead>
              <tbody>
                @foreach($months as $m)
                <tr><td>{{ $m['label'] }}</td><td>{{ $m['orders'] }}</td><td style="width: 30%;"><div class="rp-bar"><span style="width: {{ max(2, round($m['value'] / $maxMonth * 100)) }}%"></span></div></td>
                  <td class="text-right">AED {{ number_format($m['value'], 2) }}</td><td class="text-right">AED {{ number_format($m['confirmedValue'], 2) }}</td></tr>
                @endforeach
              </tbody>
            </table>
            @else <p class="rp-note">No orders for these filters.</p> @endif
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card-box" id="by-step">
            <h5>Where open orders are</h5>
            <table class="table table-condensed">
              <thead><tr><th>Step</th><th>Open orders</th><th>Waiting for customer</th></tr></thead>
              <tbody>
                @foreach($steps['steps'] as $s)
                <tr>
                  <td>{{ $s['step'] }}. {{ $s['title'] }}</td>
                  <td>@if($s['orders'])<a href="{{ route('reports.index', array_merge(['from' => $from, 'to' => $to], $keep, ['step' => $s['step']])) }}">{{ $s['orders'] }}</a>@else 0 @endif</td>
                  <td>{{ $s['waiting'] ?: '' }}</td>
                </tr>
                @endforeach
                <tr><td><strong>Completed</strong></td><td><strong>{{ $steps['completed'] }}</strong></td><td></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6">
          <div class="card-box" id="by-customer">
            <h5>Top customers</h5>
            @if($customers->count())
            <table class="table table-condensed">
              <thead><tr><th>Customer</th><th>Orders</th><th class="text-right">Value</th><th class="text-right">Confirmed</th></tr></thead>
              <tbody>
                @foreach($customers as $c)
                <tr>
                  <td>@if($c['email'])<a href="{{ route('reports.index', array_merge(['from' => $from, 'to' => $to], $keep, ['customer' => $c['email']])) }}">{{ $c['name'] ?: $c['email'] }}</a>@else {{ $c['name'] ?: 'Unknown' }} @endif
                    @if($c['company'])<br><small class="rp-note">{{ $c['company'] }}</small>@endif</td>
                  <td>{{ $c['orders'] }}</td>
                  <td class="text-right">AED {{ number_format($c['value'], 2) }}</td>
                  <td class="text-right">AED {{ number_format($c['confirmedValue'], 2) }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @else <p class="rp-note">No orders for these filters.</p> @endif
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card-box" id="by-product">
            <h5>Top products</h5>
            @if($products->count())
            <table class="table table-condensed">
              <thead><tr><th>Product</th><th>Pieces</th><th>Orders</th><th class="text-right">Value (excl. VAT)</th></tr></thead>
              <tbody>
                @foreach($products as $p)
                <tr>
                  <td>@if($p['product'])<a href="{{ route('reports.index', array_merge(['from' => $from, 'to' => $to], $keep, ['product' => $p['product']])) }}">{{ $p['name'] }}</a>@else {{ $p['name'] }} @endif
                    @if($p['modelno'])<br><small class="rp-note">{{ $p['modelno'] }}</small>@endif</td>
                  <td>{{ number_format($p['pieces']) }}</td>
                  <td>{{ $p['orders'] }}</td>
                  <td class="text-right">AED {{ number_format($p['value'], 2) }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @else <p class="rp-note">No orders for these filters.</p> @endif
          </div>
        </div>
      </div>

      <div class="card-box" id="orders">
        <h5>Orders ({{ $list->total() }})</h5>
        <div class="table-responsive">
          <table class="table table-actions-bar">
            <thead><tr><th>Order #</th><th>Date</th><th>Customer</th><th>Products</th><th>Pieces</th><th class="text-right">Value</th><th>Status</th></tr></thead>
            <tbody>
              @forelse($list as $order)
                @php $first = $order->items->first(); $more = $order->items->count() - 1; @endphp
                <tr>
                  <td><a href="{{ route('orders.show', $order->id) }}">#{{ $order->orderid }}</a></td>
                  <td>{{ $order->created_at->format('d M Y') }}</td>
                  <td>{{ $order->customer_name ?: '-' }}@if($order->customer_company)<br><small class="rp-note">{{ $order->customer_company }}</small>@endif</td>
                  <td>{{ optional($first)->name ?: '-' }}@if($more > 0) <small class="rp-note">+ {{ $more }} more</small>@endif</td>
                  <td>{{ number_format($order->items->sum('quantity')) }}</td>
                  <td class="text-right">AED {{ number_format($order->total, 2) }}</td>
                  <td>{{ \App\Services\OrderReports::status($order) }}</td>
                </tr>
              @empty
                <tr><td colspan="7" class="rp-note">No orders for these filters.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        {{ $list->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>
@endsection
