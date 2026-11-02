@extends('layouts.app')

@section('content')
@php $order = $orders->first(); @endphp
    <div class="content-page">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="page-title">Order #{{ $order->orderid }}</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <div class="row" style="margin-bottom: 20px;">
                                <div class="col-md-6">
                                    <h5 class="text-muted text-uppercase">Customer</h5>
                                    <p class="m-b-0"><strong>{{ $order->customer_name ?: 'N/A' }}</strong></p>
                                    @if($order->customer_company)<p class="m-b-0">{{ $order->customer_company }}</p>@endif
                                    @if($order->customer_email)<p class="m-b-0">{{ $order->customer_email }}</p>@endif
                                    @if($order->customer_phone)<p class="m-b-0">{{ $order->customer_phone }}</p>@endif
                                    @if($order->customer_address)<p class="m-b-0">{{ $order->customer_address }} {{ $order->customer_city }} {{ $order->customer_country }}</p>@endif
                                </div>
                                <div class="col-md-6 text-right">
                                    <h5 class="text-muted text-uppercase">Order</h5>
                                    <p class="m-b-0">Date: {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : 'N/A' }}</p>
                                    <p class="m-b-0">Status: <strong>{{ $order->orderstatus ?: 'Pending' }}</strong></p>
                                </div>
                            </div>

                            @include('backend.orders.partials.order_items')

                            @if($order->additional_info)
                                <h5 class="text-muted text-uppercase">Customer message</h5>
                                <p style="white-space: pre-line;">{{ $order->additional_info }}</p>
                            @endif

                            <a href="{{ $order->order_id ? route('orders.show', $order->order_id) : route('ordertracking.show', $order->id) }}" class="btn btn-info waves-effect waves-light">Order Tracking</a>
                            <a href="{{ url('printinvoice/' . $order->orderid) }}" target="_blank" class="btn btn-default waves-effect">Print Invoice</a>
                            <a href="{{ route('orders.index') }}" class="btn btn-default waves-effect">Back to Store Enquiries</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
