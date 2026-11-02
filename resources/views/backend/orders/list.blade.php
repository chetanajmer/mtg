@if(Session::has('role'))
@else
<script>
window.location.href = "{{url('/admin')}}";
</script>
@endif

@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="page-title">Store Enquiries</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <div class="table-responsive">
                                <table class="table table-actions-bar">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Product</th>
                                            <th>Order Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($items_array))
                                            @foreach($items_array as $order)
                                            <tr>
                                                <td>{{$order->id}}</td>
                                                <td>{{$order->orderid ?? '-'}}</td>
                                                <td>{{$order->customer_name ?: 'N/A'}}@if($order->customer_company)<br><small>{{$order->customer_company}}</small>@endif</td>
                                                <td>{{$order->name ?? 'N/A'}}@if(($itemCounts[$order->orderid] ?? 1) > 1)<br><small>+ {{ $itemCounts[$order->orderid] - 1 }} more</small>@endif</td>
                                                <td>{{$order->created_at ?? 'N/A'}}</td>
                                                <td>{{$order->ordertotal ?? 'N/A'}}</td>
                                                <td>{{$order->orderstatus ?? 'Pending'}}</td>
                                                <td>
                                                    <a href="{{ $order->order_id ? route('orders.show', $order->order_id) : route('ordertracking.show', $order->id) }}" class="btn btn-sm btn-info waves-effect waves-light">
                                                        Order Tracking
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8" style="text-align: center;">No orders found.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                @if(method_exists($items_array, 'links'))
                                    {{ $items_array->links('pagination::bootstrap-4') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
