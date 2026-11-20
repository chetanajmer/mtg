@php
  $d = $invoice->data;
  $c = $d['company'] ?? [];
  $cu = $d['customer'] ?? [];
  $p = $d['payment'] ?? [];
  $money = function ($v) { return 'AED ' . number_format((float) $v, 2); };
  $date = function ($v) { return $v ? \Carbon\Carbon::parse($v)->format('d M Y') : '-'; };
  $vatPercent = rtrim(rtrim(number_format(($d['vat_rate'] ?? 0.05) * 100, 2), '0'), '.');
  $isBalance = $invoice->type === 'balance';
  $a = $d['advance'] ?? [];
  $outstanding = $isBalance ? ($d['outstanding'] ?? 0) : ($d['balance'] ?? 0);
  $fullyPaid = $isBalance && $outstanding <= 0;
  $orderNo = $d['order']['orderid'] ?? '';
@endphp
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>{{ $invoice->number }} | {{ $invoice->title() }} | {{ $c['name'] ?? 'More Than Giftz' }}</title>
<style>
  * { box-sizing: border-box; }
  body { margin: 0; background: #f3f4f6; color: #15171a; font: 14px/1.5 Arial, Helvetica, sans-serif; }
  .bar { max-width: 820px; margin: 16px auto 0; padding: 0 16px; display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
  .bar a, .bar button { display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 6px; font: 600 14px Arial, sans-serif; cursor: pointer; text-decoration: none; }
  .bar a { color: #15171a; border: 1px solid #d1d5db; background: #fff; }
  .bar button { color: #fff; background: #15171a; border: 0; }
  .sheet { max-width: 820px; margin: 12px auto 32px; background: #fff; padding: 40px 44px; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,.12); position: relative; }
  .head { display: flex; justify-content: space-between; gap: 24px; flex-wrap: wrap; border-bottom: 3px solid #c9a46a; padding-bottom: 18px; }
  .head > div:first-child { flex: 1 1 240px; min-width: 0; }
  .head .doc { flex: 0 0 auto; }
  .brand { font-size: 24px; font-weight: 700; letter-spacing: .3px; }
  .muted { color: #6b7280; }
  .small { font-size: 12px; }
  .doc { text-align: right; }
  .doc h1 { margin: 0; font-size: 22px; text-transform: uppercase; letter-spacing: 1px; }
  .doc .no { font-weight: 700; margin-top: 4px; }
  .paid { display: inline-block; margin-top: 10px; transform: rotate(-4deg); border: 3px solid #17752c; color: #17752c; border-radius: 8px; padding: 6px 14px; font-weight: 700; letter-spacing: 1px; text-align: center; line-height: 1.2; }
  .paid small { display: block; font-size: 11px; letter-spacing: 0; font-weight: 600; }
  .parties { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin: 22px 0; }
  .label { font-size: 11px; text-transform: uppercase; letter-spacing: .8px; color: #8c5109; font-weight: 700; margin-bottom: 4px; }
  table { width: 100%; border-collapse: collapse; }
  .lines th { text-align: left; font-size: 12px; color: #6b7280; border-bottom: 1px solid #e5e7eb; padding: 8px 6px; }
  .lines td { padding: 9px 6px; border-bottom: 1px solid #f0f2f4; vertical-align: top; }
  .num { text-align: right; white-space: nowrap; }
  .totals { width: 340px; margin: 16px 0 0 auto; }
  .totals td { padding: 6px; }
  .totals .grand td { border-top: 1px solid #e5e7eb; font-weight: 700; }
  .totals .received td { color: #17752c; font-weight: 700; }
  .totals .balance td { border-top: 2px solid #15171a; font-weight: 700; font-size: 15px; }
  .payment { margin-top: 24px; background: #f7f3ed; border-radius: 8px; padding: 14px 16px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
  .payment b { display: block; }
  .note { margin-top: 20px; font-size: 13px; }
  .foot { margin-top: 28px; padding-top: 14px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280; display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
  @media (max-width: 640px) {
    .sheet { padding: 22px 16px; margin: 8px; }
    .doc { text-align: left; }
    .parties { grid-template-columns: 1fr; }
    .payment { grid-template-columns: 1fr 1fr; }
    .totals { width: 100%; }
    .lines .model { display: none; }
  }
  @media print {
    body { background: #fff; }
    .bar { display: none; }
    .sheet { box-shadow: none; margin: 0; max-width: none; padding: 0; }
    @page { margin: 16mm; }
  }
</style>
</head>
<body>
  <div class="bar">
    <a href="{{ $back }}">&larr; Back to the order</a>
    <button type="button" onclick="window.print()">Print / Save as PDF</button>
  </div>

  <div class="sheet">
    <div class="head">
      <div>
        <div class="brand">{{ $c['name'] ?? 'More Than Giftz' }}</div>
        @if(!empty($c['address']))<div class="muted">{{ $c['address'] }}</div>@endif
        <div class="muted small">{{ implode(' · ', array_filter([$c['email'] ?? null, $c['phone'] ?? null, $c['website'] ?? null])) }}</div>
        @if(!empty($c['trn']))<div class="small">TRN: {{ $c['trn'] }}</div>@endif
      </div>
      <div class="doc">
        <h1>{{ $invoice->title() }}</h1>
        <div class="no">{{ $invoice->number }}</div>
        <div class="muted small">Issued {{ optional($invoice->issued_at)->format('d M Y') }}</div>
        <div class="muted small">Order #{{ $d['order']['orderid'] ?? '' }} · placed {{ $date($d['order']['date'] ?? null) }}</div>
        <div class="paid">{{ $fullyPaid ? 'PAID IN FULL' : 'PAID' }}<small>{{ $money($p['amount'] ?? 0) }} {{ $isBalance ? 'balance' : 'advance' }} received</small></div>
      </div>
    </div>

    <div class="parties">
      <div>
        <div class="label">Billed to</div>
        <div><strong>{{ $cu['company'] ?: $cu['name'] }}</strong></div>
        @if(!empty($cu['company']) && !empty($cu['name']))<div>{{ $cu['name'] }}</div>@endif
        @if(!empty($cu['address']))<div class="muted">{{ $cu['address'] }}</div>@endif
        <div class="muted small">{{ implode(' · ', array_filter([$cu['email'] ?? null, $cu['phone'] ?? null])) }}</div>
      </div>
      <div>
        <div class="label">About this invoice</div>
        @if($isBalance)
          <div>This invoice confirms that we have received the balance payment for order #{{ $orderNo }}.@if($fullyPaid) Together with the advance of {{ $money($a['amount'] ?? 0) }}, the order is now paid in full.@else An amount of {{ $money($outstanding) }} is still outstanding.@endif</div>
        @else
          <div>This invoice confirms that we have received the 50% advance payment for order #{{ $orderNo }}. The remaining balance is due before delivery.</div>
        @endif
      </div>
    </div>

    <table class="lines">
      <thead><tr><th>Product</th><th class="model">Model no.</th><th class="num">Qty</th><th class="num">Unit price</th><th class="num">Amount</th></tr></thead>
      <tbody>
        @foreach($d['lines'] ?? [] as $line)
          <tr>
            <td>{{ $line['name'] }}</td>
            <td class="model muted">{{ $line['modelno'] ?: '-' }}</td>
            <td class="num">{{ number_format($line['quantity']) }}</td>
            <td class="num">{{ $money($line['unit']) }}</td>
            <td class="num">{{ $money($line['total']) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <table class="totals">
      <tr><td>Subtotal (excl. VAT)</td><td class="num">{{ $money($d['subtotal'] ?? 0) }}</td></tr>
      <tr><td>VAT {{ $vatPercent }}%</td><td class="num">{{ $money($d['vat'] ?? 0) }}</td></tr>
      <tr class="grand"><td>Order total</td><td class="num">{{ $money($d['total'] ?? 0) }}</td></tr>
      @if($isBalance)
        <tr class="received"><td>Advance received {{ !empty($a['paid_on']) ? '(' . $date($a['paid_on']) . ')' : '' }}</td><td class="num">&minus; {{ $money($a['amount'] ?? 0) }}</td></tr>
        <tr class="received"><td>Balance received ({{ $date($p['paid_on'] ?? null) }})</td><td class="num">&minus; {{ $money($p['amount'] ?? 0) }}</td></tr>
        <tr class="balance"><td>{{ $fullyPaid ? 'Amount outstanding' : 'Still outstanding' }}</td><td class="num">{{ $money($outstanding) }}</td></tr>
      @else
        <tr class="received"><td>Advance received (50%)</td><td class="num">&minus; {{ $money($p['amount'] ?? 0) }}</td></tr>
        <tr class="balance"><td>Balance due</td><td class="num">{{ $money($outstanding) }}</td></tr>
      @endif
    </table>

    <div class="payment">
      <div><span class="label">Received on</span><b>{{ $date($p['paid_on'] ?? null) }}</b></div>
      <div><span class="label">Method</span><b>{{ $p['method'] ?: '-' }}</b></div>
      <div><span class="label">Reference</span><b>{{ $p['reference'] ?: '-' }}</b></div>
      <div><span class="label">Amount</span><b>{{ $money($p['amount'] ?? 0) }}</b></div>
    </div>

    @if($isBalance && !empty($a['reference']))
      <p class="note muted">Advance payment: {{ $money($a['amount'] ?? 0) }} received {{ $date($a['paid_on'] ?? null) }} ({{ $a['method'] ?: '-' }}, ref {{ $a['reference'] }}).</p>
    @endif
    @if(abs(($p['amount'] ?? 0) - ($d['due'] ?? 0)) >= 0.01 && ($d['due'] ?? 0) > 0)
      <p class="note muted">The 50% {{ $isBalance ? 'balance' : 'advance' }} due was {{ $money($d['due']) }}; {{ $money($p['amount'] ?? 0) }} was received. The figures above use the amounts actually received.</p>
    @endif

    <div class="foot">
      <span>Thank you for your business.</span>
      <span>{{ $c['name'] ?? 'More Than Giftz' }} · {{ $invoice->number }}</span>
    </div>
  </div>
</body>
</html>
