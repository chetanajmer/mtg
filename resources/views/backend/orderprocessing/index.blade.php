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
                            <a href="{{route('orderprocessing.available')}}">
                                <button type="button" class="btn btn-info waves-effect waves-light">Add New Order</button>
                            </a>
                        </div>
                        <h4 class="page-title">Order Processing Dashboard</h4>
                        <ol class="breadcrumb"></ol>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

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
                                            <th>Current Step</th>
                                            <th>Progress</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($workflows as $workflow)
                                        <tr>
                                            <td>#{{ $workflow->cartorder_id }}</td>
                                            <td>{{ $workflow->cartorder->customer_name ?? 'N/A' }}</td>
                                            <td>{{ $workflow->cartorder->orderdate ?? 'N/A' }}</td>
                                            <td>
                                                @if($workflow->current_step == 1) Step 1 - Received
                                                @elseif($workflow->current_step == 2) Step 2 - Verification
                                                @elseif($workflow->current_step == 3) Step 3 - Packing
                                                @elseif($workflow->current_step == 4) Step 4 - Shipped
                                                @elseif($workflow->current_step == 5) Step 5 - Delivered
                                                @else Completed
                                                @endif
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar {{ $workflow->progress_percentage == 100 ? 'progress-bar-success' : 'progress-bar-info' }}"
                                                         role="progressbar"
                                                         style="width: {{ $workflow->progress_percentage }}%"
                                                         aria-valuenow="{{ $workflow->progress_percentage }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100">
                                                        {{ number_format($workflow->progress_percentage) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $workflow->status_text == 'Delivered' ? 'badge-success' : 'badge-info' }}">
                                                    {{ $workflow->status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{route('orderprocessing.show', $workflow->id)}}" class="table-action-btn">
                                                    <i class="md md-view-list"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $workflows->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
