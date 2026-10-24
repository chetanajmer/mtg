@if(Session::has('role'))
@else
<script>
window.location.href = "{{url('/admin')}}";
</script>
@endif

@extends('layouts.app')

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
.mockup-remove { position: absolute; top: 8px; right: 8px; width: 24px; height: 24px; border-radius: 50%; background: rgba(255,255,255,0.9); color: #1f2937; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px; }
.upload-area { aspect-ratio: 1; border: 2px dashed #d1d5db; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: #6b7280; cursor: pointer; transition: border-color 0.2s; }
.upload-area:hover { border-color: #3b82f6; }
.upload-area span { font-size: 32px; color: #9ca3af; }

.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }
.form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px; color: #1f2937; resize: vertical; font-family: inherit; }
.form-group textarea:focus { outline: none; border-color: #3b82f6; }

.revision-list { list-style: none; margin: 0; padding: 0; }
.revision-item { display: flex; align-items: center; gap: 12px; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; margin-bottom: 8px; }
.revision-item.current { border-color: #3b82f6; background: #eff6ff; }
.revision-info { flex: 1; }
.revision-info strong { display: block; font-size: 13px; font-weight: 600; color: #1f2937; margin-bottom: 2px; }
.revision-info small { font-size: 12px; color: #6b7280; }
.revision-actions { display: flex; gap: 8px; }
.revision-actions button { padding: 6px 12px; border: 1px solid #e5e7eb; border-radius: 4px; background: #fff; color: #4b5563; font-size: 12px; cursor: pointer; }
.revision-actions button:hover { background: #f9fafb; }
.revision-badge { font-size: 11px; padding: 4px 8px; border-radius: 4px; }
.revision-badge.sent { background: #d1fae5; color: #065f46; }
.revision-badge.requested { background: #fee2e2; color: #991b1b; }
.revision-badge.replaced { background: #f3f4f6; color: #6b7280; }

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

@media (max-width: 1024px) {
  .workflow-grid { grid-template-columns: 1fr; }
  .product-details-row { flex-direction: column; }
}
</style>
    <div class="content-page">
        <div class="content">
            <div class="container">
                <!-- Header -->
                <div class="order-workflow-header">
                    <h1>Order Workflow — #8618</h1>
                    <a href="{{url('orders')}}" class="btn-back">← Back to Orders</a>
                </div>

                <!-- Product Details -->
                <div class="product-details">
                    <div class="product-details-row">
                        <div class="product-thumb">📦</div>
                        <div class="product-info">
                            <h2>MILOT - Waste-to-Wins® Recycled Award - Black Swirl</h2>
                            <div class="product-meta">
                                <span>Model No: <strong>SG4779</strong></span>
                                <span>Customer: <strong>jojo jacob</strong></span>
                                <span>Order Date: <strong>24-Sep-2026 09:00:36</strong></span>
                                <span>Quantity: <strong>500 pcs</strong></span>
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
                            <li class="completed">
                                <span class="step-badge">1</span>
                                <div class="step-content">
                                    <strong>Customer Enquiry / Order</strong>
                                    <small>Product selection, quantity, logo and requirements.</small>
                                </div>
                                <span class="step-status completed">Completed</span>
                            </li>
                            <li class="active">
                                <span class="step-badge">2</span>
                                <div class="step-content">
                                    <strong>Sample Approval / Mockup Design & Approval</strong>
                                    <small>Sample shown, design submitted → revisions → final approval.</small>
                                </div>
                                <span class="step-status in-review">In Review</span>
                            </li>
                            <li>
                                <span class="step-badge">3</span>
                                <div class="step-content">
                                    <strong>Advance Payment – 50%</strong>
                                    <small>Accounts confirms receipt of advance.</small>
                                </div>
                                <span class="step-status pending">Pending</span>
                            </li>
                            <li>
                                <span class="step-badge">4</span>
                                <div class="step-content">
                                    <strong>Sample Production / Approval</strong>
                                    <small>Physical or pre-production sample prepared and approved.</small>
                                </div>
                                <span class="step-status pending">Pending</span>
                            </li>
                            <li>
                                <span class="step-badge">5</span>
                                <div class="step-content">
                                    <strong>Mass Production</strong>
                                    <small>Production starts after payment and artwork approval.</small>
                                </div>
                                <span class="step-status not-started">Not Started</span>
                            </li>
                            <li>
                                <span class="step-badge">6</span>
                                <div class="step-content">
                                    <strong>Quality Check & Packing</strong>
                                    <small>Final product photos and packing confirmation.</small>
                                </div>
                                <span class="step-status not-started">Not Started</span>
                            </li>
                            <li>
                                <span class="step-badge">7</span>
                                <div class="step-content">
                                    <strong>Balance Payment – 50%</strong>
                                    <small>Customer pays remaining amount before dispatch.</small>
                                </div>
                                <span class="step-status pending">Pending</span>
                            </li>
                            <li>
                                <span class="step-badge">8</span>
                                <div class="step-content">
                                    <strong>Delivery</strong>
                                    <small>Unipatch, delivery tracking and proof of delivery.</small>
                                </div>
                                <span class="step-status not-started">Not Started</span>
                            </li>
                            <li>
                                <span class="step-badge">9</span>
                                <div class="step-content">
                                    <strong>Project Completed</strong>
                                    <small>Customer acknowledgement and reorder option.</small>
                                </div>
                                <span class="step-status not-started">Not Started</span>
                            </li>
                            <li>
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
                                <h3>Step 2: Sample Approval / Mockup Design & Approval</h3>
                                <p>Upload mockup designs, send to customer, manage revisions and get final approval.</p>
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

                        <!-- Mockup Images -->
                        <div class="detail-section">
                            <h4>Mockup Images</h4>
                            <div class="mockup-grid">
                                <div class="mockup-item">
                                    <button class="mockup-remove">×</button>
                                    <span style="color: #fff; font-size: 24px;">📦</span>
                                </div>
                                <div class="mockup-item">
                                    <button class="mockup-remove">×</button>
                                    <span style="color: #fff; font-size: 24px;">📦</span>
                                </div>
                                <div class="upload-area">
                                    <span>+</span>
                                    <small>Upload Images</small>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Message -->
                        <div class="detail-section">
                            <h4>Customer Message</h4>
                            <div class="form-group">
                                <textarea rows="3">Please find attached the mockup design for your review.
Let us know if any changes are required.</textarea>
                            </div>
                        </div>

                        <!-- Internal Remarks -->
                        <div class="detail-section">
                            <h4>Internal Remarks</h4>
                            <div class="form-group">
                                <textarea rows="3" placeholder="Add internal remarks..."></textarea>
                            </div>
                        </div>

                        <!-- Design Revisions -->
                        <div class="detail-section">
                            <h4>Design Revisions</h4>
                            <ul class="revision-list">
                                <li class="revision-item current">
                                    <div class="revision-info">
                                        <strong>Revision 3 (Current)</strong>
                                        <small>Uploaded by Rohan Sharma • 25-Sep-2026 11:30 AM • Mockup_v3.jpg</small>
                                    </div>
                                    <div class="revision-actions">
                                        <button>👁 View</button>
                                        <button>⭳ Download</button>
                                    </div>
                                    <span class="revision-badge sent">Sent to Customer</span>
                                </li>
                                <li class="revision-item">
                                    <div class="revision-info">
                                        <strong>Revision 2</strong>
                                        <small>Uploaded by Neha Verma • 24-Sep-2026 04:15 PM • Mockup_v2.jpg</small>
                                    </div>
                                    <div class="revision-actions">
                                        <button>👁 View</button>
                                        <button>⭳ Download</button>
                                    </div>
                                    <span class="revision-badge requested">Revision Requested</span>
                                </li>
                                <li class="revision-item">
                                    <div class="revision-info">
                                        <strong>Revision 1</strong>
                                        <small>Uploaded by Rohan Sharma • 24-Sep-2026 10:20 AM • Mockup_v1.jpg</small>
                                    </div>
                                    <div class="revision-actions">
                                        <button>👁 View</button>
                                        <button>⭳ Download</button>
                                    </div>
                                    <span class="revision-badge replaced">Replaced</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Customer Approval -->
                        <div class="detail-section">
                            <h4>Customer Approval</h4>
                            <div class="approval-options">
                                <label>
                                    <input type="radio" name="approval" checked> Pending
                                </label>
                                <label>
                                    <input type="radio" name="approval"> Approved
                                </label>
                                <label>
                                    <input type="radio" name="approval"> Revision Required
                                </label>
                            </div>
                        </div>

                        <!-- Approved Date -->
                        <div class="detail-section">
                            <h4>Approved Date</h4>
                            <div class="form-group">
                                <input type="date" class="form-control" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px;">
                            </div>
                        </div>

                        <!-- Send to Customer -->
                        <div class="detail-section">
                            <h4>Send to Customer</h4>
                            <div class="checkbox-group">
                                <input type="checkbox" checked>
                                <label>Send notification to customer</label>
                            </div>
                            <div class="form-group">
                                <textarea rows="2" placeholder="Add message to customer (optional)..."></textarea>
                            </div>
                            <button class="btn-primary">➤ Send to Customer</button>
                        </div>

                        <!-- Actions -->
                        <div class="detail-actions">
                            <button class="btn-secondary">Cancel</button>
                            <button class="btn-primary">Save & Update</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
