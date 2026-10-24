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
                        <div class="btn-group pull-right m-t-15">
                            <a href="{{route('orderprocessing.index')}}">
                                <button type="button" class="btn btn-default waves-effect waves-light">Back to Dashboard</button>
                            </a>
                        </div>
                        <h4 class="page-title">Available Orders for Processing</h4>
                        <ol class="breadcrumb"></ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <div class="table-responsive">
                                <table class="table table-actions-bar">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer Name</th>
                                            <th>Order Date</th>
                                            <th>Total Amount</th>
                                            <th>Order Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->customer_name ?? 'N/A' }}</td>
                                            <td>{{ $order->orderdate ?? 'N/A' }}</td>
                                            <td>{{ $order->ordertotal ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $order->orderstatus }}</span>
                                            </td>
                                            <td>
                                                <a href="{{route('orderprocessing.create', $order->id)}}" class="btn btn-sm btn-info waves-effect waves-light">
                                                    Start Processing
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
