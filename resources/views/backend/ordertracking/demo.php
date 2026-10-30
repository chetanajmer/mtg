@if(Session::has('role'))
@else
<script>
window.location.href = "{{url('/admin')}}";
</script>
@endif

@extends('layouts.app')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
@endsection

@section('content')
<style>
/* Order Tracking Page Styles */
.order-workflow-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.order-workflow-header h1 { font-size: 24px; font-weight: 600; color: #1f2937; margin: 0; }
.btn-back { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 6px; color: #4b5563; font-size: 14px; cursor: pointer; text-decoration: none; }
.btn-back:hover { background: #f9fafb; }

.product-details { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.product-details-row { display: flex; gap: 20px; align-items: flex-start; }
.product-thumb { width: 100px; height: 100px; border-radius: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; }
.product-info h2 { font-size: 18px; font-weight: 600; color: #1f2937; margin: 0 0 12px 0; }
.product-meta { display: flex; gap: 20px; flex-wrap: wrap; color: #6b7280; font-size: 14px; }
.product-meta span { display: flex; align-items: center; gap: 4px; }
.product-meta strong { color: #1f2937; font-weight: 500; }

.workflow-progress { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.workflow-progress ol { display: flex; gap: 8px; margin: 0; padding: 0; list-style: none; overflow-x: auto; }
.workflow-progress li { flex: 1; min-width: 120px; text-align: center; font-size: 11px; color: #6b7280; position: relative; }
.workflow-progress li::before { content: ""; position: absolute; top: 14px; left: 0; right: 0; height: 2px; background: #e5e7eb; z-index: 0; }
.workflow-progress li:last-child::before { display: none; }
.workflow-progress .step-number { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 50%; background: #e5e7eb; color: #6b7280; font-weight: 600; font-size: 12px; position: relative; z-index: 1; margin-bottom: 8px; }
.workflow-progress li.completed .step-number { background: #10b981; color: #fff; }
.workflow-progress li.completed::before { background: #10b981; }
.workflow-progress li.current .step-number { background: #3b82f6; color: #fff; }
.workflow-progress li.current::before { background: #3b82f6; }
.workflow-progress li.completed + li::before { background: #10b981; }
.workflow-progress li.current + li::before { background: #3b82f6; }
.workflow-progress li span { display: block; line-height: 1.4; }

.workflow-grid { display: grid; grid-template-columns: 320px 1fr; gap: 20px; }
.workflow-steps { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.workflow-steps h3 { font-size: 16px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0; }
.workflow-steps ul { margin: 0; padding: 0; list-style: none; }
.workflow-steps li { display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 6px; cursor: pointer; transition: background 0.2s; }
.workflow-steps li:hover { background: #f9fafb; }
.workflow-steps li.active { background: #eff6ff; border-left: 3px solid #3b82f6; }
.workflow-steps .step-badge { display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: #e5e7eb; color: #6b7280; font-weight: 600; font-size: 12px; flex-shrink: 0; }
.workflow-steps li.completed .step-badge { background: #10b981; color: #fff; }
.workflow-steps li.active .step-badge { background: #3b82f6; color: #fff; }
.workflow-steps .step-content { flex: 1; min-width: 0; }
.workflow-steps .step-content strong { display: block; font-size: 13px; font-weight: 600; color: #1f2937; margin-bottom: 2px; }
.workflow-steps .step-content small { display: block; font-size: 11px; color: #6b7280; line-height: 1.3; }
.workflow-steps .step-status { font-size: 11px; padding: 4px 8px; border-radius: 4px; white-space: nowrap; }
.workflow-steps .step-status.completed { background: #d1fae5; color: #065f46; }
.workflow-steps .step-status.in-review { background: #fef3c7; color: #92400e; }
.workflow-steps .step-status.pending { background: #f3f4f6; color: #4b5563; }
.workflow-steps .step-status.not-started { background: #f9fafb; color: #9ca3af; }

.step-detail { background: #fff; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.step-detail-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb; }
.step-detail-header h3 { font-size: 18px; font-weight: 600; color: #1f2937; margin: 0 0 8px 0; }
.step-detail-header p { font-size: 14px; color: #6b7280; margin: 0; }
.status-dropdown { display: flex; align-items: center; gap: 8px; }
.status-dropdown label { font-size: 13px; color: #6b7280; }
.status-dropdown select { padding: 6px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px; color: #1f2937; background: #fff; }

.detail-section { margin-bottom: 24px; }
.detail-section h4 { font-size: 14px; font-weight: 600; color: #1f2937; margin: 0 0 12px 0; }

.mockup-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; }
.mockup-item { position: relative; aspect-ratio: 1; border-radius: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; overflow: hidden; }
.mockup-item img { width: 100%; height: 100%; object-fit: cover; }
.mockup-remove { position: absolute; top: 8px; right: 8px; width: 24px; height: 24px; border-radius: 50%; background: rgba(255,255,255,0.9); color: #1f2937; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px; z-index: 10; }
.mockup-remove:hover { background: #fff; color: #dc2626; }
.upload-area { aspect-ratio: 1; border: 2px dashed #d1d5db; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: #6b7280; cursor: pointer; transition: border-color 0.2s; }
.upload-area:hover { border-color: #3b82f6; }
.upload-area span { font-size: 32px; color: #9ca3af; }

.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }
.form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px; color: #1f2937; resize: vertical; font-family: inherit; }
.form-group textarea:focus { outline: none; border-color: #3b82f6; }

.approval-options { display: flex; gap: 12px; }
.approval-options label { display: flex; align-items: center; gap: 6px; padding: 10px 16px; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer; font-size: 13px; color: #4b5563; }
.approval-options input[type="radio"] { accent-color: #3b82f6; }
.approval-options label:has(input:checked) { border-color: #3b82f6; background: #eff6ff; color: #3b82f6; }

.btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 20px; background: #3b82f6; color: #fff; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; }
.btn-primary:hover { background: #2563eb; }
.btn-secondary { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 20px; background: #fff; color: #4b5563; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; }
.btn-secondary:hover { background: #f9fafb; }

.checkbox-group { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
.checkbox-group input[type="checkbox"] { accent-color: #3b82f6; width: 16px; height: 16px; }
.checkbox-group label { font-size: 13px; color: #374151; }

.detail-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #e5e7eb; }

/* Step 8 Delivery Type Options */
.delivery-type-options { display: flex; gap: 24px; margin-top: 12px; }
.delivery-type-option { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.delivery-type-option input[type="radio"] { width: 18px; height: 18px; accent-color: #3b82f6; cursor: pointer; }
.delivery-type-option .radio-label { font-size: 14px; color: #374151; font-weight: 500; }

/* Delivery Details Grid */
.delivery-details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 16px; }
.delivery-column { border-left: 1px solid #e5e7eb; padding-left: 24px; }
.delivery-column:first-child { border-left: none; padding-left: 0; }

/* Message & Remarks Grid */
.message-remarks-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

/* Tracking Type Options */
.tracking-type-options { display: flex; flex-wrap: wrap; gap: 12px; }
.tracking-type-options label { display: flex; align-items: center; gap: 6px; padding: 8px 14px; border: 1px solid #e5e7eb; border-radius: 6px; cursor: pointer; font-size: 13px; color: #4b5563; transition: all 0.2s; }
.tracking-type-options label:hover { border-color: #d1d5db; background: #f9fafb; }
.tracking-type-options input[type="radio"] { width: 16px; height: 16px; accent-color: #3b82f6; cursor: pointer; }
.tracking-type-options label:has(input:checked) { border-color: #3b82f6; background: #eff6ff; color: #3b82f6; }

/* File Upload Wrapper */
.file-upload-wrapper { display: flex; align-items: center; gap: 12px; }
.file-input { display: none; }
.file-upload-btn { display: inline-block; padding: 8px 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 6px; color: #374151; font-size: 13px; cursor: pointer; transition: all 0.2s; }
.file-upload-btn:hover { border-color: #3b82f6; color: #3b82f6; }
.file-name { font-size: 12px; color: #6b7280; font-style: italic; }

@media (max-width: 1024px) {
  .workflow-grid { grid-template-columns: 1fr; }
  .product-details-row { flex-direction: column; }
  .delivery-type-options { flex-direction: column; gap: 12px; }
  .delivery-details-grid { grid-template-columns: 1fr; }
  .delivery-column { border-left: none; padding-left: 0; border-top: 1px solid #e5e7eb; padding-top: 20px; }
  .delivery-column:first-child { border-top: none; padding-top: 0; }
  .message-remarks-grid { grid-template-columns: 1fr; }
}
</style>
    <div class="content-page">
        <div class="content">
            <div class="container">
                <!-- Header -->
                <div class="order-workflow-header">
                    <h1>Order Workflow — #{{ $order->id ?? 'N/A' }}</h1>
                    <a href="{{ route('ordertracking.index') }}" class="btn-back">← Back to Order List</a>
                </div>

                <!-- Product Details -->
                <div class="product-details">
                    <div class="product-details-row">
                        <div class="product-thumb">📦</div>
                        <div class="product-info">
                            <h2>{{ $order->name ?? 'N/A' }}</h2>
                            <div class="product-meta">
                                <span>Order ID: <strong>{{ $order->orderid ?? $order->id ?? 'N/A' }}</strong></span>
                                <span>Customer: <strong>{{ $order->username ?? 'N/A' }}</strong></span>
                                <span>Order Date: <strong>{{ $order->created_at ? $order->created_at->format('d-M-Y H:i:s') : 'N/A' }}</strong></span>
                                <span>Quantity: <strong>{{ $order->quantity ?? 'N/A' }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Workflow Progress -->
                <div class="workflow-progress">
                    <ol>
                        <li class="completed">
                            <span class="step-number">✓</span>
                            <span>Customer<br>Enquiry / Order</span>
                        </li>
                        <li class="current">
                            <span class="step-number">2</span>
                            <span>Mockup Design<br>& Approval</span>
                        </li>
                        <li>
                            <span class="step-number">3</span>
                            <span>Advance<br>Payment 50%</span>
                        </li>
                        <li>
                            <span class="step-number">4</span>
                            <span>Sample<br>Production</span>
                        </li>
                        <li>
                            <span class="step-number">5</span>
                            <span>Mass<br>Production</span>
                        </li>
                        <li>
                            <span class="step-number">6</span>
                            <span>Quality Check<br>& Packing</span>
                        </li>
                        <li>
                            <span class="step-number">7</span>
                            <span>Balance<br>Payment 50%</span>
                        </li>
                        <li>
                            <span class="step-number">8</span>
                            <span>Delivery</span>
                        </li>
                        <li>
                            <span class="step-number">9</span>
                            <span>Completed</span>
                        </li>
                        <li>
                            <span class="step-number">10</span>
                            <span>Feedback</span>
                        </li>
                    </ol>
                </div>

                <!-- Workflow Grid -->
                <div class="workflow-grid">
                    <!-- Left: Workflow Steps -->
                    <div class="workflow-steps">
                        <h3>Workflow Steps</h3>
                        <ul>
                            <li class="completed" onclick="selectStep(1)">
                                <span class="step-badge">1</span>
                                <div class="step-content">
                                    <strong>Customer Enquiry / Order</strong>
                                    <small>Product selection, quantity, logo and requirements.</small>
                                </div>
                                <span class="step-status completed">Completed</span>
                            </li>
                            <li class="active" onclick="selectStep(2)">
                                <span class="step-badge">2</span>
                                <div class="step-content">
                                    <strong>Sample Approval / Mockup Design & Approval</strong>
                                    <small>Sample shown, design submitted → revisions → final approval.</small>
                                </div>
                                <span class="step-status in-review">In Review</span>
                            </li>
                            <li onclick="selectStep(3)">
                                <span class="step-badge">3</span>
                                <div class="step-content">
                                    <strong>Advance Payment – 50%</strong>
                                    <small>Accounts confirms receipt of advance.</small>
                                </div>
                                <span class="step-status pending">Pending</span>
                            </li>
                            <li onclick="selectStep(4)">
                                <span class="step-badge">4</span>
                                <div class="step-content">
                                    <strong>Sample Production / Approval</strong>
                                    <small>Physical or pre-production sample prepared and approved.</small>
                                </div>
                                <span class="step-status pending">Pending</span>
                            </li>
                            <li onclick="selectStep(5)">
                                <span class="step-badge">5</span>
                                <div class="step-content">
                                    <strong>Mass Production</strong>
                                    <small>Production starts after payment and artwork approval.</small>
                                </div>
                                <span class="step-status not-started">Not Started</span>
                            </li>
                            <li onclick="selectStep(6)">
                                <span class="step-badge">6</span>
                                <div class="step-content">
                                    <strong>Quality Check & Packing</strong>
                                    <small>Final product photos and packing confirmation.</small>
                                </div>
                                <span class="step-status not-started">Not Started</span>
                            </li>
                            <li onclick="selectStep(7)">
                                <span class="step-badge">7</span>
                                <div class="step-content">
                                    <strong>Balance Payment – 50%</strong>
                                    <small>Customer pays remaining amount before dispatch.</small>
                                </div>
                                <span class="step-status pending">Pending</span>
                            </li>
                            <li onclick="selectStep(8)">
                                <span class="step-badge">8</span>
                                <div class="step-content">
                                    <strong>Delivery</strong>
                                    <small>Dispatch, delivery tracking and proof of delivery.</small>
                                </div>
                                <span class="step-status not-started">Not Started</span>
                            </li>
                            <li onclick="selectStep(9)">
                                <span class="step-badge">9</span>
                                <div class="step-content">
                                    <strong>Project Completed</strong>
                                    <small>Customer acknowledgement and reorder option.</small>
                                </div>
                                <span class="step-status not-started">Not Started</span>
                            </li>
                            <li onclick="selectStep(10)">
                                <span class="step-badge">10</span>
                                <div class="step-content">
                                    <strong>Project Review / Feedback</strong>
                                    <small>Customer satisfaction, ratings, comments and improvement suggestions.</small>
                                </div>
                                <span class="step-status not-started">Not Started</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Right: Step Detail -->
                    <div class="step-detail">
                        <div class="step-detail-header">
                            <div>
                                <h3 id="step-title">Step 2: Sample Approval / Mockup Design & Approval</h3>
                                <p id="step-description">Upload mockup designs, send to customer, manage revisions and get final approval.</p>
                            </div>
                            <div class="status-dropdown">
                                <label>Current Status:</label>
                                <select>
                                    <option>In Review</option>
                                    <option>Approved</option>
                                    <option>Revision Required</option>
                                </select>
                            </div>
                        </div>

                        <!-- Step 1 Content -->
                        <div id="step-1-content" class="step-content-wrapper" style="display: none;">
                            <div class="detail-section">
                                <h4>Order Information</h4>
                                <div class="form-group">
                                    <label>Product Details</label>
                                    <textarea rows="3" readonly>{{ $order->name ?? 'N/A' }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Quantity Required</label>
                                    <textarea rows="2" readonly>{{ $order->quantity ?? 'N/A' }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Customer Requirements</label>
                                    <textarea rows="3" placeholder="Customer specific requirements..." readonly></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 Content -->
                        <div id="step-2-content" class="step-content-wrapper">
                            <form id="step-2-form" class="step-form" data-step="2">
                                @csrf
                                <input type="hidden" name="cartorder_id" value="{{ $order->id ?? '' }}">

                                <div class="detail-section">
                                    <h4>Mockup Images</h4>
                                    <div class="form-group">
                                        <input type="file" name="mockup_files[]" multiple accept="image/*,.pdf" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                                        <p class="order-workflow-hint" style="margin-top: 8px; font-size: 12px; color: #6b7280;">Supported formats: JPG, PNG, PDF (Max 10 MB each)</p>
                                    </div>
                                    <div id="step-2-saved-files" class="saved-files" style="margin-top: 12px;"></div>
                                </div>

                                <div class="detail-section">
                                    <h4>Customer Message</h4>
                                    <div class="form-group">
                                        <textarea name="customer_message" rows="3" placeholder="Enter customer message..." style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; resize: vertical;"></textarea>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h4>Approved Date</h4>
                                    <div class="form-group">
                                        <input type="date" name="approved_date" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h4>Send notification to customer</h4>
                                    <div class="checkbox-group">
                                        <input type="checkbox" name="send_notification" id="step-2-notification">
                                        <label for="step-2-notification">Send notification to customer</label>
                                    </div>
                                </div>

                                <div class="detail-actions">
                                    <button type="submit" class="btn-primary">Save Step 2</button>
                                </div>

                                <div id="step-2-saved-data" class="saved-data" style="margin-top: 20px; padding: 16px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; display: none;"></div>
                            </form>
                        </div>

                        <!-- Step 3 Content -->
                        <div id="step-3-content" class="step-content-wrapper" style="display: none;">
                            <form id="step-3-form" class="step-form" data-step="3">
                                @csrf
                                <input type="hidden" name="cartorder_id" value="{{ $order->id ?? '' }}">

                                <div class="detail-section">
                                    <h4>Payment Proof Image</h4>
                                    <div class="form-group">
                                        <input type="file" name="mockup_files[]" multiple accept="image/*,.pdf" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                                        <p class="order-workflow-hint" style="margin-top: 8px; font-size: 12px; color: #6b7280;">Supported formats: JPG, PNG, PDF (Max 10 MB each)</p>
                                    </div>
                                    <div id="step-3-saved-files" class="saved-files" style="margin-top: 12px;"></div>
                                </div>

                                <div class="detail-section">
                                    <h4>Customer Message</h4>
                                    <div class="form-group">
                                        <textarea name="customer_message" rows="3" placeholder="Enter customer message..." style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; resize: vertical;"></textarea>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h4>Approval Status</h4>
                                    <div class="approval-options">
                                        <label>
                                            <input type="radio" name="approval_status" value="pending"> Pending
                                        </label>
                                        <label>
                                            <input type="radio" name="approval_status" value="approved"> Approved
                                        </label>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h4>Send notification to customer</h4>
                                    <div class="checkbox-group">
                                        <input type="checkbox" name="send_notification" id="step-3-notification">
                                        <label for="step-3-notification">Send notification to customer</label>
                                    </div>
                                </div>

                                <div class="detail-actions">
                                    <button type="submit" class="btn-primary">Save Step 3</button>
                                </div>

                                <div id="step-3-saved-data" class="saved-data" style="margin-top: 20px; padding: 16px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; display: none;"></div>
                            </form>
                        </div>

                        <!-- Step 4 Content -->
                        <div id="step-4-content" class="step-content-wrapper" style="display: none;">
                            <form id="step-4-form" class="step-form" data-step="4">
                                @csrf
                                <input type="hidden" name="cartorder_id" value="{{ $order->id ?? '' }}">

                                <div class="detail-section">
                                    <h4>Sample Approval Image</h4>
                                    <div class="form-group">
                                        <input type="file" name="mockup_files[]" multiple accept="image/*,.pdf" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                                        <p class="order-workflow-hint" style="margin-top: 8px; font-size: 12px; color: #6b7280;">Supported formats: JPG, PNG, PDF (Max 10 MB each)</p>
                                    </div>
                                    <div id="step-4-saved-files" class="saved-files" style="margin-top: 12px;"></div>
                                </div>

                                <div class="detail-section">
                                    <h4>Customer Message</h4>
                                    <div class="form-group">
                                        <textarea name="customer_message" rows="3" placeholder="Enter customer message..." style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; resize: vertical;"></textarea>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h4>Approval Status</h4>
                                    <div class="approval-options">
                                        <label>
                                            <input type="radio" name="approval_status" value="pending"> Pending
                                        </label>
                                        <label>
                                            <input type="radio" name="approval_status" value="approved"> Approved
                                        </label>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h4>Approved Date</h4>
                                    <div class="form-group">
                                        <input type="date" name="approved_date" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h4>Send notification to customer</h4>
                                    <div class="checkbox-group">
                                        <input type="checkbox" name="send_notification" id="step-4-notification">
                                        <label for="step-4-notification">Send notification to customer</label>
                                    </div>
                                </div>

                                <div class="detail-actions">
                                    <button type="submit" class="btn-primary">Save Step 4</button>
                                </div>

                                <div id="step-4-saved-data" class="saved-data" style="margin-top: 20px; padding: 16px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; display: none;"></div>
                            </form>
                        </div>

                        <!-- Step 5 Content -->
                        <div id="step-5-content" class="step-content-wrapper" style="display: none;">
                            <form id="step-5-form" class="step-form" data-step="5">
                                @csrf
                                <input type="hidden" name="cartorder_id" value="{{ $order->id ?? '' }}">

                                <div class="detail-section">
                                    <h4>Production Status</h4>
                                    <div class="approval-options">
                                        <label>
                                            <input type="radio" name="approval_status" value="started"> Started
                                        </label>
                                        <label>
                                            <input type="radio" name="approval_status" value="not_started"> Not Started
                                        </label>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h4>Send notification to customer</h4>
                                    <div class="checkbox-group">
                                        <input type="checkbox" name="send_notification" id="step-5-notification">
                                        <label for="step-5-notification">Send notification to customer</label>
                                    </div>
                                </div>

                                <div class="detail-actions">
                                    <button type="submit" class="btn-primary">Save Step 5</button>
                                </div>

                                <div id="step-5-saved-data" class="saved-data" style="margin-top: 20px; padding: 16px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; display: none;"></div>
                            </form>
                        </div>

                        <!-- Step 6 Content -->
                        <div id="step-6-content" class="step-content-wrapper" style="display: none;">
                            <form id="step-6-form" class="step-form" data-step="6">
                                @csrf
                                <input type="hidden" name="cartorder_id" value="{{ $order->id ?? '' }}">

                                <div class="detail-section">
                                    <h4>Quality Check Status</h4>
                                    <div class="approval-options">
                                        <label>
                                            <input type="radio" name="approval_status" value="passed"> Passed
                                        </label>
                                        <label>
                                            <input type="radio" name="approval_status" value="not_passed"> Not Passed
                                        </label>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h4>Send notification to customer</h4>
                                    <div class="checkbox-group">
                                        <input type="checkbox" name="send_notification" id="step-6-notification">
                                        <label for="step-6-notification">Send notification to customer</label>
                                    </div>
                                </div>

                                <div class="detail-actions">
                                    <button type="submit" class="btn-primary">Save Step 6</button>
                                </div>

                                <div id="step-6-saved-data" class="saved-data" style="margin-top: 20px; padding: 16px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; display: none;"></div>
                            </form>
                        </div>

                        <!-- Step 7 Content -->
                        <div id="step-7-content" class="step-content-wrapper" style="display: none;">
                            <form id="step-7-form" class="step-form" data-step="7">
                                @csrf
                                <input type="hidden" name="cartorder_id" value="{{ $order->id ?? '' }}">

                                <div class="detail-section">
                                    <h4>Invoice Image</h4>
                                    <div class="form-group">
                                        <input type="file" name="mockup_files[]" multiple accept="image/*,.pdf" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                                        <p class="order-workflow-hint" style="margin-top: 8px; font-size: 12px; color: #6b7280;">Supported formats: JPG, PNG, PDF (Max 10 MB each)</p>
                                    </div>
                                    <div id="step-7-saved-files" class="saved-files" style="margin-top: 12px;"></div>
                                </div>

                                <div class="detail-section">
                                    <h4>Customer Message</h4>
                                    <div class="form-group">
                                        <textarea name="customer_message" rows="3" placeholder="Enter customer message..." style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; resize: vertical;"></textarea>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h4>Approval Status</h4>
                                    <div class="approval-options">
                                        <label>
                                            <input type="radio" name="approval_status" value="pending"> Pending
                                        </label>
                                        <label>
                                            <input type="radio" name="approval_status" value="approved"> Approved
                                        </label>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h4>Send notification to customer</h4>
                                    <div class="checkbox-group">
                                        <input type="checkbox" name="send_notification" id="step-7-notification">
                                        <label for="step-7-notification">Send notification to customer</label>
                                    </div>
                                </div>

                                <div class="detail-actions">
                                    <button type="submit" class="btn-primary">Save Step 7</button>
                                </div>

                                <div id="step-7-saved-data" class="saved-data" style="margin-top: 20px; padding: 16px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; display: none;"></div>
                            </form>
                        </div>

                        <!-- Step 8 Content -->
                        <div id="step-8-content" class="step-content-wrapper" style="display: none;">
                            <form id="step-8-form" class="step-form" data-step="8">
                                @csrf
                                <input type="hidden" name="cartorder_id" value="{{ $order->id ?? '' }}">

                                <!-- Step Header -->
                                
                             

                                <!-- Delivery Type Section -->
                                <div class="detail-section">
                                    <h4 style="margin-bottom: 12px;">Delivery Type</h4>
                                    <div class="delivery-type-options">
                                        <label class="delivery-type-option">
                                            <input type="radio" name="delivery_type" value="local" id="delivery-local">
                                            <span class="radio-label">Local Delivery</span>
                                        </label>
                                        <label class="delivery-type-option">
                                            <input type="radio" name="delivery_type" value="outside" id="delivery-outside">
                                            <span class="radio-label">Outside Delivery</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Delivery Details Section -->
                                <div class="detail-section">
                                    <h4 style="margin-bottom: 12px;">Delivery Details</h4>
                                    <div class="delivery-details-grid">
                                        <!-- Local Delivery Details -->
                                        <div id="local-delivery-details" class="delivery-column">
                                            <h5 style="margin: 0 0 16px 0; font-size: 14px; font-weight: 600; color: #374151;">Local Delivery Details</h5>
                                            <div class="form-group" style="margin-bottom: 16px;">
                                                <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Delivery Date *</label>
                                                <input type="date" name="delivery_date" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                                            </div>
                                            <div class="form-group" style="margin-bottom: 16px;">
                                                <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Delivered To *</label>
                                                <input type="text" name="delivered_to" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;" placeholder="Enter recipient name">
                                            </div>
                                            <div class="form-group" style="margin-bottom: 16px;">
                                                <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Address / Location</label>
                                                <textarea name="delivery_address" rows="3" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; resize: vertical;" placeholder="Enter delivery address"></textarea>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 16px;">
                                                <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Proof of Delivery / Receipt *</label>
                                                <div class="file-upload-wrapper">
                                                    <input type="file" name="delivery_proof[]" accept="image/*,.pdf" id="delivery-proof-input" class="file-input" onchange="updateFileName('delivery-proof-input', 'delivery-proof-name')">
                                                    <label for="delivery-proof-input" class="file-upload-btn">Click to upload receipt</label>
                                                    <span id="delivery-proof-name" class="file-name"></span>
                                                </div>
                                                <p style="margin-top: 8px; font-size: 12px; color: #6b7280;">Supported formats: JPG, PNG, PDF (Max 10 MB)</p>
                                            </div>
                                        </div>

                                        <!-- Outside Delivery Details -->
                                        <div id="outside-delivery-details" class="delivery-column">
                                            <h5 style="margin: 0 0 16px 0; font-size: 14px; font-weight: 600; color: #374151;">Outside Delivery Details</h5>
                                            <p style="margin-bottom: 12px; font-size: 12px; color: #6b7280;">Please enter proper number, this will be used for the tracking.</p>
                                            <div class="form-group" style="margin-bottom: 16px;">
                                                <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Tracking Document Type</label>
                                                <div class="tracking-type-options">
                                                    <label>
                                                        <input type="radio" name="tracking_type" value="awb" onchange="updateTrackingLabel('awb')"> AWB Number
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="tracking_type" value="challan" onchange="updateTrackingLabel('challan')"> Delivery Challan
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="tracking_type" value="builty" onchange="updateTrackingLabel('builty')"> Builty Number
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="tracking_type" value="courier" onchange="updateTrackingLabel('courier')"> Courier Number
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 16px;">
                                                <label id="tracking-number-label" style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">AWB Number *</label>
                                                <input type="text" name="awb_number" id="tracking-number-input" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;" placeholder="Enter AWB number">
                                            </div>
                                            <div class="form-group" style="margin-bottom: 16px;">
                                                <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Courier Partner</label>
                                                <select name="courier_partner" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                                                    <option value="">Select Courier Partner</option>
                                                    <option value="dhl">DHL</option>
                                                    <option value="fedex">FedEx</option>
                                                    <option value="ups">UPS</option>
                                                    <option value="bluedart">Blue Dart</option>
                                                    <option value="delhivery">Delhivery</option>
                                                    <option value="ekart">Ekart</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                            <div class="form-group" style="margin-bottom: 16px;">
                                                <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Dispatch Date *</label>
                                                <input type="date" name="dispatch_date" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                                            </div>
                                            <div class="form-group" style="margin-bottom: 16px;">
                                                <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Proof of Dispatch / Receipt</label>
                                                <div class="file-upload-wrapper">
                                                    <input type="file" name="dispatch_proof[]" accept="image/*,.pdf" id="dispatch-proof-input" class="file-input" onchange="updateFileName('dispatch-proof-input', 'dispatch-proof-name')">
                                                    <label for="dispatch-proof-input" class="file-upload-btn">Click to upload receipt</label>
                                                    <span id="dispatch-proof-name" class="file-name"></span>
                                                </div>
                                                <p style="margin-top: 8px; font-size: 12px; color: #6b7280;">Supported formats: JPG, PNG, PDF (Max 10 MB)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bottom Section: Customer Message & Internal Remarks -->
                                <div class="detail-section">
                                    <div class="message-remarks-grid">
                                        <div class="form-group">
                                            <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Customer Message</label>
                                            <textarea name="customer_message" rows="3" placeholder="Enter message to customer (optional)..." style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; resize: vertical;"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label style="display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px;">Internal Remarks</label>
                                            <textarea name="internal_remarks" rows="3" placeholder="Add internal remarks..." style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; resize: vertical;"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="detail-actions" style="display: flex; justify-content: flex-end;">
                                    <button type="submit" class="btn-primary">Save & Update</button>
                                </div>

                                <div id="step-8-saved-data" class="saved-data" style="margin-top: 20px; padding: 16px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; display: none;"></div>
                            </form>
                        </div>

                        <!-- Step 9 Content -->
                        <div id="step-9-content" class="step-content-wrapper" style="display: none;">
                            <div class="detail-section">
                                <h4>Project Completion</h4>
                                <div class="form-group">
                                    <label>Completion Status</label>
                                    <div class="approval-options">
                                        <label>
                                            <input type="radio" name="completion" disabled> Pending
                                        </label>
                                        <label>
                                            <input type="radio" name="completion" checked disabled> Completed
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Completion Date</label>
                                    <input type="date" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Final Delivery Proof</label>
                                    <div class="mockup-grid">
                                        <div class="mockup-item">
                                            <span class="order-workflow-box">POD</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 10 Content -->
                        <div id="step-10-content" class="step-content-wrapper" style="display: none;">
                            <div class="detail-section">
                                <h4>Customer Feedback</h4>
                                <div class="form-group">
                                    <label>Rating</label>
                                    <div class="approval-options">
                                        <label>
                                            <input type="radio" name="rating" disabled> ⭐
                                        </label>
                                        <label>
                                            <input type="radio" name="rating" disabled> ⭐⭐
                                        </label>
                                        <label>
                                            <input type="radio" name="rating" disabled> ⭐⭐⭐
                                        </label>
                                        <label>
                                            <input type="radio" name="rating" checked disabled> ⭐⭐⭐⭐
                                        </label>
                                        <label>
                                            <input type="radio" name="rating" disabled> ⭐⭐⭐⭐⭐
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Customer Comments</label>
                                    <textarea rows="4" placeholder="Customer feedback..." readonly></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Improvement Suggestions</label>
                                    <textarea rows="3" placeholder="Suggestions for improvement..." readonly></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<script>
const stepInfo = {
    1: {
        title: 'Step 1: Customer Enquiry / Order',
        description: 'Product selection, quantity, logo and requirements.'
    },
    2: {
        title: 'Step 2: Sample Approval / Mockup Design & Approval',
        description: 'Upload mockup designs, send to customer, manage revisions and get final approval.'
    },
    3: {
        title: 'Step 3: Advance Payment – 50%',
        description: 'Accounts confirms receipt of advance.'
    },
    4: {
        title: 'Step 4: Sample Production / Approval',
        description: 'Physical or pre-production sample prepared and approved.'
    },
    5: {
        title: 'Step 5: Mass Production',
        description: 'Production starts after payment and artwork approval.'
    },
    6: {
        title: 'Step 6: Quality Check & Packing',
        description: 'Final product photos and packing confirmation.'
    },
    7: {
        title: 'Step 7: Balance Payment – 50%',
        description: 'Customer pays remaining amount before dispatch.'
    },
    8: {
        title: 'Step 8: Delivery',
        description: 'Dispatch, delivery tracking and proof of delivery.'
    },
    9: {
        title: 'Step 9: Project Completed',
        description: 'Customer acknowledgement and reorder option.'
    },
    10: {
        title: 'Step 10: Project Review / Feedback',
        description: 'Customer satisfaction, ratings, comments and improvement suggestions.'
    }
};

// Load saved data when page loads
document.addEventListener('DOMContentLoaded', function() {
    const cartorderId = document.querySelector('input[name="cartorder_id"]')?.value;
    if (cartorderId) {
        loadSavedData(2);
        loadSavedData(3);
        loadSavedData(4);
        loadSavedData(5);
        loadSavedData(6);
        loadSavedData(7);
        loadSavedData(8);
    }

    // Add form submit handlers
    document.querySelectorAll('.step-form').forEach(form => {
        form.addEventListener('submit', handleFormSubmit);
    });
});

function loadSavedData(stepNumber) {
    const cartorderId = document.querySelector('input[name="cartorder_id"]')?.value;
    if (!cartorderId) return;

    fetch(`/order-tracking/step/${stepNumber}/save?cartorder_id=${cartorderId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            displaySavedData(stepNumber, data.data);
        }
    })
    .catch(error => console.error('Error loading saved data:', error));
}

function handleFormSubmit(event) {
    event.preventDefault();

    const form = event.target;
    const stepNumber = form.dataset.step;
    const formData = new FormData(form);

    // Step 8 specific validation - removed since both columns are now always visible

    // Remove empty mockup_files array from FormData
    const fileInput = form.querySelector('input[name="mockup_files[]"]');
    if (fileInput && fileInput.files.length === 0) {
        formData.delete('mockup_files');
    }

    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Saving...';
    submitButton.disabled = true;

    fetch(`/order-tracking/step/${stepNumber}/save`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displaySavedData(stepNumber, data.data);
            // Use SweetAlert toast instead of alert
            if (typeof swal !== 'undefined') {
                swal({
                    title: 'Success!',
                    text: 'Step ' + stepNumber + ' saved successfully!',
                    type: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        } else {
            console.error('Validation errors:', data.errors);
            if (typeof swal !== 'undefined') {
                swal({
                    title: 'Error!',
                    text: 'Error saving step: ' + (data.message || 'Unknown error'),
                    type: 'error',
                    confirmButtonColor: '#3b82f6'
                });
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof swal !== 'undefined') {
            swal({
                title: 'Error!',
                text: 'Error saving step. Please try again.',
                type: 'error',
                confirmButtonColor: '#3b82f6'
            });
        }
    })
    .finally(() => {
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    });
}

function displaySavedData(stepNumber, data) {
    const savedDataDiv = document.getElementById(`step-${stepNumber}-saved-data`);
    const savedFilesDiv = document.getElementById(`step-${stepNumber}-saved-files`);

    if (!savedDataDiv) return;

    // Helper function to format date to DD Month YYYY
    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        const day = date.getDate();
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const month = months[date.getMonth()];
        const year = date.getFullYear();
        return `${day} ${month} ${year}`;
    }

    // Display saved files first
    if (data.mockup_files && data.mockup_files.length > 0) {
        let filesHtml = '<div style="margin-top: 12px;"><strong>Saved Files:</strong><div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px;">';
        data.mockup_files.forEach(file => {
            const isImage = file.match(/\.(jpg|jpeg|png|gif|webp)$/i);
            if (isImage) {
                filesHtml += `<a href="/storage/${file}" target="_blank" style="display: inline-block;"><img src="/storage/${file}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #e5e7eb;"></a>`;
            } else {
                filesHtml += `<a href="/storage/${file}" target="_blank" style="display: inline-block; padding: 8px 12px; background: #f3f4f6; border-radius: 4px; text-decoration: none; color: #374151; font-size: 12px;">📄 ${file.split('/').pop()}</a>`;
            }
        });
        filesHtml += '</div></div>';
        savedFilesDiv.innerHTML = filesHtml;
    }

    // Display Step 8 specific files
    if (data.step_number == 8) {
        let step8FilesHtml = '';
        if (data.delivery_proof && data.delivery_proof.length > 0) {
            step8FilesHtml += '<div style="margin-top: 12px;"><strong>Delivery Proof:</strong><div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px;">';
            data.delivery_proof.forEach(file => {
                const isImage = file.match(/\.(jpg|jpeg|png|gif|webp)$/i);
                if (isImage) {
                    step8FilesHtml += `<a href="/storage/${file}" target="_blank" style="display: inline-block;"><img src="/storage/${file}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #e5e7eb;"></a>`;
                } else {
                    step8FilesHtml += `<a href="/storage/${file}" target="_blank" style="display: inline-block; padding: 8px 12px; background: #f3f4f6; border-radius: 4px; text-decoration: none; color: #374151; font-size: 12px;">📄 ${file.split('/').pop()}</a>`;
                }
            });
            step8FilesHtml += '</div></div>';
        }
        if (data.dispatch_proof && data.dispatch_proof.length > 0) {
            step8FilesHtml += '<div style="margin-top: 12px;"><strong>Dispatch Proof:</strong><div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px;">';
            data.dispatch_proof.forEach(file => {
                const isImage = file.match(/\.(jpg|jpeg|png|gif|webp)$/i);
                if (isImage) {
                    step8FilesHtml += `<a href="/storage/${file}" target="_blank" style="display: inline-block;"><img src="/storage/${file}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #e5e7eb;"></a>`;
                } else {
                    step8FilesHtml += `<a href="/storage/${file}" target="_blank" style="display: inline-block; padding: 8px 12px; background: #f3f4f6; border-radius: 4px; text-decoration: none; color: #374151; font-size: 12px;">📄 ${file.split('/').pop()}</a>`;
                }
            });
            step8FilesHtml += '</div></div>';
        }
        if (step8FilesHtml && savedFilesDiv) {
            savedFilesDiv.innerHTML += step8FilesHtml;
        }
    }

    // Display saved data in table format
    let html = '<h5 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #166534;">✓ Saved Data</h5>';
    html += '<table style="width: 100%; border-collapse: collapse; margin-top: 12px; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">';
    html += '<thead style="background: #f9fafb;">';
    html += '<tr>';
    html += '<th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; font-size: 13px;">Field</th>';
    html += '<th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; font-size: 13px;">Value</th>';
    html += '</tr>';
    html += '</thead>';
    html += '<tbody>';

    if (data.approved_date) {
        html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
        html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Approved Date</td>`;
        html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${formatDate(data.approved_date)}</td>`;
        html += '</tr>';
    }

    if (data.customer_message) {
        html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
        html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Customer Message</td>`;
        html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${data.customer_message}</td>`;
        html += '</tr>';
    }

    if (data.approval_status) {
        html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
        html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Approval Status</td>`;
        html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${data.approval_status}</td>`;
        html += '</tr>';
    }

    if (data.send_notification !== undefined) {
        html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
        html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Send Notification</td>`;
        html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${data.send_notification ? 'Yes' : 'No'}</td>`;
        html += '</tr>';
    }

    if (data.created_at) {
        html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
        html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Created Date</td>`;
        html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${formatDate(data.created_at)}</td>`;
        html += '</tr>';
    }

    // Step 8 specific fields
    if (data.step_number == 8) {
        if (data.delivery_type) {
            html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
            html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Delivery Type</td>`;
            html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${data.delivery_type === 'local' ? 'Local Delivery' : 'Outside Delivery'}</td>`;
            html += '</tr>';
        }

        if (data.delivery_date) {
            html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
            html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Delivery Date</td>`;
            html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${formatDate(data.delivery_date)}</td>`;
            html += '</tr>';
        }

        if (data.delivered_to) {
            html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
            html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Delivered To</td>`;
            html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${data.delivered_to}</td>`;
            html += '</tr>';
        }

        if (data.delivery_address) {
            html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
            html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Delivery Address</td>`;
            html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${data.delivery_address}</td>`;
            html += '</tr>';
        }

        if (data.tracking_type) {
            html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
            html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Tracking Document Type</td>`;
            const trackingLabels = {
                'awb': 'AWB Number',
                'challan': 'Delivery Challan',
                'builty': 'Builty Number',
                'courier': 'Courier Number'
            };
            html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${trackingLabels[data.tracking_type] || data.tracking_type}</td>`;
            html += '</tr>';
        }

        if (data.awb_number) {
            html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
            html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Tracking Number</td>`;
            html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${data.awb_number}</td>`;
            html += '</tr>';
        }

        if (data.courier_partner) {
            html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
            html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Courier Partner</td>`;
            html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${data.courier_partner}</td>`;
            html += '</tr>';
        }

        if (data.dispatch_date) {
            html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
            html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Dispatch Date</td>`;
            html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${formatDate(data.dispatch_date)}</td>`;
            html += '</tr>';
        }

        if (data.internal_remarks) {
            html += '<tr style="border-bottom: 1px solid #e5e7eb;">';
            html += `<td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">Internal Remarks</td>`;
            html += `<td style="padding: 12px 16px; color: #4b5563; font-size: 13px;">${data.internal_remarks}</td>`;
            html += '</tr>';
        }
    }

    html += '</tbody>';
    html += '</table>';

    savedDataDiv.innerHTML = html;
    savedDataDiv.style.display = 'block';

    // For Step 8, load saved delivery type
    if (data.step_number == 8 && data.delivery_type) {
        // Check the radio button
        const radio = document.getElementById(`delivery-${data.delivery_type}`);
        if (radio) {
            radio.checked = true;
        }
    }

    // For Step 8, load saved tracking type to update label
    if (data.step_number == 8 && data.tracking_type) {
        updateTrackingLabel(data.tracking_type);
        // Check the tracking type radio button
        const trackingRadio = document.querySelector(`input[name="tracking_type"][value="${data.tracking_type}"]`);
        if (trackingRadio) {
            trackingRadio.checked = true;
        }
    }
}

function selectStep(stepNumber) {
    // Update active state in left sidebar
    document.querySelectorAll('.workflow-steps li').forEach(li => {
        li.classList.remove('active');
    });
    event.currentTarget.classList.add('active');

    // Update header
    const stepDetail = stepInfo[stepNumber];
    if (stepDetail) {
        document.getElementById('step-title').textContent = stepDetail.title;
        document.getElementById('step-description').textContent = stepDetail.description;
    }

    // Hide all step content
    document.querySelectorAll('.step-content-wrapper').forEach(wrapper => {
        wrapper.style.display = 'none';
    });

    // Show selected step content
    const selectedContent = document.getElementById('step-' + stepNumber + '-content');
    if (selectedContent) {
        selectedContent.style.display = 'block';
    }
}

function updateTrackingLabel(type) {
    const label = document.getElementById('tracking-number-label');
    const input = document.getElementById('tracking-number-input');

    if (!label || !input) return;

    const labels = {
        'awb': 'AWB Number *',
        'challan': 'Delivery Challan Number *',
        'builty': 'Builty Number *',
        'courier': 'Courier Number *'
    };

    label.textContent = labels[type] || 'Tracking Number *';
    input.placeholder = labels[type].replace(' *', '') || 'Enter tracking number';
}

function updateFileName(inputId, nameSpanId) {
    const input = document.getElementById(inputId);
    const nameSpan = document.getElementById(nameSpanId);

    if (input && input.files.length > 0 && nameSpan) {
        nameSpan.textContent = input.files[0].name;
    } else if (nameSpan) {
        nameSpan.textContent = '';
    }
}
</script>
@endsection
