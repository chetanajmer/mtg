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
                                            <th>Customer</th>
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
                                                <td>{{$order->name ?? 'N/A'}}</td>
                                                <td>{{$order->created_at ?? 'N/A'}}</td>
                                                <td>{{$order->ordertotal ?? 'N/A'}}</td>
                                                <td>{{$order->orderstatus ?? 'Pending'}}</td>
                                                <td>
                                                    <a href="{{route('orders.workflow', $order->id)}}" class="btn btn-sm btn-info waves-effect waves-light">
                                                        View Workflow
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" style="text-align: center;">No orders found.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
