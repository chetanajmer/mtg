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
                        <h4 class="page-title">Order Processing - #{{ $cartorder->id }}</h4>
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

                <!-- Order Details -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>Order Details</b></h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>Order ID:</strong> #{{ $cartorder->id }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Customer Name:</strong> {{ $cartorder->customer_name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Order Date:</strong> {{ $cartorder->orderdate ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Total Amount:</strong> {{ $cartorder->ordertotal ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>Email:</strong> {{ $cartorder->customer_email ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Phone:</strong> {{ $cartorder->customer_phone ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Product:</strong> {{ $cartorder->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Quantity:</strong> {{ $cartorder->quantity ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>Progress: {{ $workflow->progress_percentage }}% Complete</b></h4>
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar {{ $workflow->progress_percentage == 100 ? 'progress-bar-success' : 'progress-bar-info' }}"
                                     role="progressbar"
                                     style="width: {{ $workflow->progress_percentage }}%"
                                     aria-valuenow="{{ $workflow->progress_percentage }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                    {{ number_format($workflow->progress_percentage) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5-Step Workflow -->
                <div class="row">
                    @foreach($steps as $stepNum => $step)
                    <div class="col-md-{{ $stepNum == 5 ? '12' : '2-4' }}">
                        <div class="card-box"
                             style="border: 2px solid {{
                                 $step['completed'] ? '#81C868' :
                                 (!$step['locked'] && !$step['completed']) ? '#4a76fd' :
                                 '#e2e8f0'
                             }}; background-color: {{
                                 $step['completed'] ? '#f0fdf4' :
                                 (!$step['locked'] && !$step['completed']) ? '#eff6ff' :
                                 '#f8fafc'
                             }};">
                            <div class="text-center">
                                <div class="step-icon" style="font-size: 48px; color: {{
                                    $step['completed'] ? '#81C868' :
                                    (!$step['locked'] && !$step['completed']) ? '#4a76fd' :
                                    '#94a3b8'
                                }};">
                                    @if($step['locked'])
                                        <i class="ti-lock"></i>
                                    @else
                                        <i class="{{ $step['icon'] }}"></i>
                                    @endif
                                </div>
                                <h4 class="m-t-20" style="color: {{
                                    $step['completed'] ? '#81C868' :
                                    (!$step['locked'] && !$step['completed']) ? '#4a76fd' :
                                    '#94a3b8'
                                }};">
                                    Step {{ $stepNum }}
                                </h4>
                                <h5 class="m-b-10">{{ $step['title'] }}</h5>
                                <p class="text-muted">{{ $step['description'] }}</p>

                                @if($step['completed'])
                                    <span class="badge badge-success m-t-10">
                                        <i class="ti-check"></i> Completed
                                        @if($step['completed_at'])
                                            <br><small>{{ $step['completed_at']->format('M d, Y H:i') }}</small>
                                        @endif
                                    </span>
                                @elseif($step['locked'])
                                    <span class="badge badge-default m-t-10">
                                        <i class="ti-lock"></i> Locked
                                    </span>
                                @else
                                    <span class="badge badge-info m-t-10">
                                        <i class="ti-arrow-right"></i> In Progress
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Current Step Action -->
                <div class="row m-t-30">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>Current Action</b></h4>

                            @if($workflow->current_step == 1)
                                <form action="{{route('orderprocessing.completeStep', [$workflow->id, 1])}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Notes (Optional)</label>
                                        <textarea class="form-control" name="notes" rows="3">{{ $workflow->notes }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success waves-effect waves-light">
                                        <i class="ti-check"></i> Mark Order as Received
                                    </button>
                                </form>

                            @elseif($workflow->current_step == 2)
                                <form action="{{route('orderprocessing.completeStep', [$workflow->id, 2])}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Notes (Optional)</label>
                                        <textarea class="form-control" name="notes" rows="3">{{ $workflow->notes }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success waves-effect waves-light">
                                        <i class="ti-check"></i> Mark Order as Verified
                                    </button>
                                </form>

                            @elseif($workflow->current_step == 3)
                                <form action="{{route('orderprocessing.completeStep', [$workflow->id, 3])}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Notes (Optional)</label>
                                        <textarea class="form-control" name="notes" rows="3">{{ $workflow->notes }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success waves-effect waves-light">
                                        <i class="ti-check"></i> Mark Order as Packed
                                    </button>
                                </form>

                            @elseif($workflow->current_step == 4)
                                <form action="{{route('orderprocessing.completeStep', [$workflow->id, 4])}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Courier Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="courier_name" required placeholder="Enter courier name">
                                    </div>
                                    <div class="form-group">
                                        <label>Tracking Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="tracking_number" required placeholder="Enter tracking number">
                                    </div>
                                    <div class="form-group">
                                        <label>Dispatch Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="dispatch_date" required value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Notes (Optional)</label>
                                        <textarea class="form-control" name="notes" rows="3">{{ $workflow->notes }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success waves-effect waves-light">
                                        <i class="ti-check"></i> Mark Order as Shipped
                                    </button>
                                </form>

                            @elseif($workflow->current_step == 5)
                                <form action="{{route('orderprocessing.completeStep', [$workflow->id, 5])}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Delivery Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="delivery_date" required value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Notes (Optional)</label>
                                        <textarea class="form-control" name="notes" rows="3">{{ $workflow->notes }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success waves-effect waves-light">
                                        <i class="ti-check"></i> Mark Order as Delivered
                                    </button>
                                </form>

                            @elseif($workflow->current_step == 6)
                                <div class="alert alert-success">
                                    <h4><i class="ti-check"></i> Order Processing Complete!</h4>
                                    <p>This order has successfully completed all 5 steps of the processing workflow.</p>
                                    <p><strong>Completed At:</strong> {{ $workflow->step5_completed_at ? $workflow->step5_completed_at->format('M d, Y H:i') : 'N/A' }}</p>
                                </div>

                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reset Steps (Admin Only) -->
                @if(session()->get('role') == 'superadmin')
                <div class="row m-t-20">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b>Reset Steps (Admin Only)</b></h4>
                            <p class="text-muted">Warning: This will reset the selected step and all subsequent steps.</p>
                            <div class="btn-group">
                                @foreach($steps as $stepNum => $step)
                                    @if($step['completed'])
                                        <form action="{{route('orderprocessing.resetStep', [$workflow->id, $stepNum])}}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning waves-effect waves-light" onclick="return confirm('Are you sure you want to reset Step {{ $stepNum }}?')">
                                                Reset Step {{ $stepNum }}
                                            </button>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
@endsection
