<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $items->orderid }} - More Than Giftz</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style>
        body { padding: 30px; color: #111827; }
        .invoice { max-width: 820px; margin: 0 auto; }
        @media print { .no-print { display: none; } body { padding: 0; } }
    </style>
</head>
<body>
@php $orders = $orders ?? collect([$items]); @endphp
<div class="invoice">
    <div class="row" style="margin-bottom: 30px;">
        <div class="col-xs-6">
            <h2 style="margin-top: 0;">More Than Giftz</h2>
        </div>
        <div class="col-xs-6 text-right">
            <h3 style="margin-top: 0;">Invoice #{{ $items->orderid }}</h3>
            <p>Date: {{ $items->created_at ? $items->created_at->format('d M Y') : '' }}</p>
        </div>
    </div>

    <p><strong>Bill to</strong><br>
        {{ $items->customer_name }}<br>
        @if($items->customer_company){{ $items->customer_company }}<br>@endif
        @if($items->customer_email){{ $items->customer_email }}<br>@endif
        @if($items->customer_phone){{ $items->customer_phone }}<br>@endif
        @if($items->customer_address){{ $items->customer_address }} {{ $items->customer_city }} {{ $items->customer_country }}@endif
    </p>

    @include('backend.orders.partials.order_items')

    <p class="no-print"><button onclick="window.print()" class="btn btn-primary">Print</button></p>
</div>
</body>
</html>
