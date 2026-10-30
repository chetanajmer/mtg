@if(Session::has('role'))
@else
<script>
window.location.href = "{{url('/admin')}}";
</script>
@endif

@extends('layouts.app')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<style>
.list-container { padding: 20px; }
.list-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.list-header h1 { font-size: 24px; font-weight: 600; color: #1f2937; margin: 0; }
.search-box { display: flex; gap: 10px; }
.search-box input { padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; width: 300px; }
.search-box button { padding: 8px 16px; background: #3b82f6; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
.search-box button:hover { background: #2563eb; }

.table-container { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
.table { width: 100%; border-collapse: collapse; }
.table th { background: #f9fafb; padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; }
.table td { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; color: #4b5563; }
.table tr:hover { background: #f9fafb; }

.mockup-thumbnails { display: flex; gap: 8px; flex-wrap: wrap; }
.mockup-thumb { width: 50px; height: 50px; border-radius: 4px; object-fit: cover; border: 1px solid #e5e7eb; }
.mockup-link { color: #3b82f6; text-decoration: none; font-size: 13px; }
.mockup-link:hover { text-decoration: underline; }

.status-badge { display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 500; }
.status-pending { background: #f3f4f6; color: #4b5563; }
.status-approved { background: #d1fae5; color: #065f46; }
.status-revision { background: #fef3c7; color: #92400e; }

.btn-view { padding: 6px 12px; background: #3b82f6; color: #fff; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 13px; }
.btn-view:hover { background: #2563eb; }

.pagination { display: flex; justify-content: center; gap: 4px; margin-top: 20px; }
.pagination a { padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 4px; color: #4b5563; text-decoration: none; }
.pagination a:hover, .pagination .active { background: #3b82f6; color: #fff; border-color: #3b82f6; }

.no-records { text-align: center; padding: 40px; color: #6b7280; }
.no-records h3 { font-size: 18px; margin-bottom: 8px; }
</style>

<div class="content-page">
    <div class="content">
        <div class="container">
            <div class="list-container">
                <!-- Header -->
                <div class="list-header">
                    <h1>Order Tracking List</h1>
                    <form action="{{ route('ordertracking.list') }}" method="GET" class="search-box">
                        <input type="text" name="search" placeholder="Search by Order ID..." value="{{ request('search') }}">
                        <button type="submit">Search</button>
                        @if(request('search'))
                        <a href="{{ route('ordertracking.list') }}" style="padding: 8px 16px; background: #6b7280; color: #fff; border: none; border-radius: 6px; text-decoration: none;">Clear</a>
                        @endif
                    </form>
                </div>

                <!-- Table -->
                <div class="table-container">
                    @if(isset($workflows) && $workflows->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Order ID</th>
                                <th>Workflow Step</th>
                                <th>Mockup Images / Files</th>
                                <th>Customer Message</th>
                                <th>Approved Date</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workflows as $index => $workflow)
                            <tr>
                                <td>{{ ($workflows->currentPage() - 1) * $workflows->perPage() + $index + 1 }}</td>
                                <td>{{ $workflow->cartorder ? ($workflow->cartorder->orderid ?? $workflow->cartorder->id ?? 'N/A') : 'N/A' }}</td>
                                <td>
                                    @php
                                        $stepNames = [
                                            1 => 'Customer Enquiry / Order',
                                            2 => 'Sample Approval / Mockup Design & Approval',
                                            3 => 'Advance Payment – 50%',
                                            4 => 'Sample Production / Approval',
                                            5 => 'Mass Production',
                                            6 => 'Quality Check & Packing',
                                            7 => 'Balance Payment – 50%',
                                            8 => 'Delivery',
                                            9 => 'Project Completed',
                                            10 => 'Project Review / Feedback'
                                        ];
                                        $stepName = $stepNames[$workflow->current_step] ?? 'N/A';
                                    @endphp
                                    {{ $stepName }}
                                </td>
                                <td>
                                    @if($workflow->mockup_files && is_array($workflow->mockup_files) && count($workflow->mockup_files) > 0)
                                        <div class="mockup-thumbnails">
                                            @foreach($workflow->mockup_files as $file)
                                                @if(in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'webp']))
                                                    <img src="{{ asset('storage/' . $file) }}" alt="Mockup" class="mockup-thumb">
                                                @else
                                                    <a href="{{ asset('storage/' . $file) }}" target="_blank" class="mockup-link">PDF</a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span style="color: #9ca3af;">No files</span>
                                    @endif
                                </td>
                                <td>{{ $workflow->customer_message ? Str::limit($workflow->customer_message, 50) : '-' }}</td>
                                <td>{{ $workflow->approved_date ? $workflow->approved_date->format('d-M-Y') : '-' }}</td>
                                <td>
                                    @if($workflow->mockup_status)
                                        @if($workflow->mockup_status == 'pending')
                                            <span class="status-badge status-pending">Pending</span>
                                        @elseif($workflow->mockup_status == 'approved')
                                            <span class="status-badge status-approved">Approved</span>
                                        @elseif($workflow->mockup_status == 'revision_required')
                                            <span class="status-badge status-revision">Revision Required</span>
                                        @endif
                                    @else
                                        <span style="color: #9ca3af;">-</span>
                                    @endif
                                </td>
                                <td>{{ $workflow->created_at ? $workflow->created_at->format('d-M-Y') : '-' }}</td>
                                <td>
                                    @if($workflow->cartorder)
                                        <a href="{{ route('ordertracking.show', $workflow->cartorder->id) }}" class="btn-view">View</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination">
                        {{ $workflows->links() }}
                    </div>
                    @else
                    <div class="no-records">
                        <h3>No Records Found</h3>
                        <p>There are no order tracking records to display.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
