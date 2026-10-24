@if(Session::has('role'))
@else
<script>
window.location.href = "{{url('/admin')}}";
</script>
@endif

@extends('layouts.app')

@section('header-scripts')
<link href="{{asset('assets/css/order-tracking.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="page-title">Order Workflow — #8618</h4>
                        <ol class="breadcrumb"></ol>
                    </div>
                </div>

                <!-- ===== Order summary + stepper ===== -->
                <div class="card-box order-card">
                    <div class="order-info">
                        <div class="thumb"><span class="bottle"></span></div>
                        <div>
                            <h2>MILOT - Waste-to-Wins® Recycled Award - Black Swirl</h2>
                            <p class="meta">
                                <span>Model No: <b>SG4779</b></span>
                                <span>Customer: <b>jojo jacob</b></span>
                                <span>Order Date: <b>24-Sep-2026 09:00:36</b></span>
                                <span>Quantity: <b>500 pcs</b></span>
                            </p>
                        </div>
                    </div>

                    <ol class="stepper">
                        <li class="done"><span class="dot">✓</span>Customer<br>Enquiry / Order</li>
                        <li class="active"><span class="dot">2</span>Mockup Design<br>&amp; Approval</li>
                        <li><span class="dot">3</span>Advance<br>Payment 50%</li>
                        <li><span class="dot">4</span>Sample<br>Production</li>
                        <li><span class="dot">5</span>Mass<br>Production</li>
                        <li><span class="dot">6</span>Quality Check<br>&amp; Packing</li>
                        <li><span class="dot">7</span>Balance<br>Payment 50%</li>
                        <li><span class="dot">8</span>Delivery</li>
                        <li><span class="dot">9</span>Completed</li>
                        <li><span class="dot">10</span>Feedback</li>
                    </ol>
                </div>

                <div class="row">
                    <!-- ===== Workflow steps list ===== -->
                    <div class="col-md-4">
                        <div class="card-box steps-card">
                            <h3>Workflow Steps</h3>
                            <ul class="steps">
                                <li><b class="num green">1</b><div><strong>Customer Enquiry / Order</strong><small>Product selection, quantity, logo and requirements.</small></div><em class="tag tag-green">Completed</em><span class="chev">›</span></li>
                                <li class="current"><b class="num blue">2</b><div><strong>Sample Approval / Mockup Design &amp; Approval</strong><small>Sample shown, design submitted → revisions → final approval.</small></div><em class="tag tag-orange">In Review</em><span class="chev">›</span></li>
                                <li><b class="num">3</b><div><strong>Advance Payment – 50%</strong><small>Accounts confirms receipt of advance.</small></div><em class="tag tag-orange">Pending</em><span class="chev">›</span></li>
                                <li><b class="num">4</b><div><strong>Sample Production / Approval</strong><small>Physical or pre-production sample prepared and approved.</small></div><em class="tag tag-orange">Pending</em><span class="chev">›</span></li>
                                <li><b class="num">5</b><div><strong>Mass Production</strong><small>Production starts after payment and artwork approval.</small></div><em class="tag tag-grey">Not Started</em><span class="chev">›</span></li>
                                <li><b class="num">6</b><div><strong>Quality Check &amp; Packing</strong><small>Final product photos and packing confirmation.</small></div><em class="tag tag-grey">Not Started</em><span class="chev">›</span></li>
                                <li><b class="num">7</b><div><strong>Balance Payment – 50%</strong><small>Customer pays remaining amount before dispatch.</small></div><em class="tag tag-orange">Pending</em><span class="chev">›</span></li>
                                <li><b class="num">8</b><div><strong>Delivery</strong><small>Unipatch, delivery tracking and proof of delivery.</small></div><em class="tag tag-grey">Not Started</em><span class="chev">›</span></li>
                                <li><b class="num">9</b><div><strong>Project Completed</strong><small>Customer acknowledgement and reorder option.</small></div><em class="tag tag-grey">Not Started</em><span class="chev">›</span></li>
                                <li><b class="num">10</b><div><strong>Project Review / Feedback</strong><small>Customer satisfaction, ratings, comments and improvement suggestions.</small></div><em class="tag tag-grey">Not Started</em><span class="chev">›</span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- ===== Step detail panel ===== -->
                    <div class="col-md-8">
                        <div class="card-box detail">
                            <div class="detail-head">
                                <div>
                                    <h3>Step 2: &nbsp;Sample Approval / Mockup Design &amp; Approval</h3>
                                    <p>Upload mockup designs, send to customer, manage revisions and get final approval.</p>
                                </div>
                                <label class="status">Current Status
                                    <select><option>In Review</option><option>Approved</option><option>Revision Required</option></select>
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Mockup Images</h4>
                                    <div class="mockups">
                                        <div class="mock mock-1"><button class="remove" aria-label="Remove">×</button><span class="bottle"></span><small>ABC</small></div>
                                        <div class="mock mock-2"><button class="remove" aria-label="Remove">×</button><span class="box">ABC<br><small>COMPANY</small></span></div>
                                        <button class="upload"><span>+</span>Upload Images</button>
                                    </div>
                                    <p class="hint">Supported formats: JPG, PNG, PDF (Max 10 MB each)</p>
                                </div>

                                <div class="col-md-6">
                                    <h4>Customer Message</h4>
                                    <textarea rows="3" class="form-control">Please find attached the mockup design for your review.
Let us know if any changes are required.</textarea>
                                    <h4>Internal Remarks</h4>
                                    <textarea rows="3" class="form-control" placeholder="Add internal remarks..."></textarea>
                                </div>
                            </div>

                            <h4 class="section-title">Design Revisions</h4>
                            <ul class="revisions">
                                <li class="rev-current">
                                    <div class="rev-info"><strong>Revision 3 (Current)</strong><small>Uploaded by Rohan Sharma &nbsp;•&nbsp; 25-Sep-2026 11:30 AM &nbsp;•&nbsp; Mockup_v3.jpg</small></div>
                                    <button class="btn btn-outline">👁 View</button><button class="btn btn-outline">⭳ Download</button><span class="pill pill-orange">Sent to Customer</span>
                                </li>
                                <li>
                                    <div class="rev-info"><strong>Revision 2</strong><small>Uploaded by Neha Verma &nbsp;•&nbsp; 24-Sep-2026 04:15 PM &nbsp;•&nbsp; Mockup_v2.jpg</small></div>
                                    <button class="btn btn-outline">👁 View</button><button class="btn btn-outline">⭳ Download</button><span class="pill pill-red">Revision Requested</span>
                                </li>
                                <li>
                                    <div class="rev-info"><strong>Revision 1</strong><small>Uploaded by Rohan Sharma &nbsp;•&nbsp; 24-Sep-2026 10:20 AM &nbsp;•&nbsp; Mockup_v1.jpg</small></div>
                                    <button class="btn btn-outline">👁 View</button><button class="btn btn-outline">⭳ Download</button><span class="pill pill-grey">Replaced</span>
                                </li>
                            </ul>

                            <div class="row bottom">
                                <div class="col-md-6">
                                    <h4>Customer Approval</h4>
                                    <div class="radios">
                                        <label class="radio checked"><input type="radio" name="approval" checked> Pending</label>
                                        <label class="radio"><input type="radio" name="approval"> Approved</label>
                                        <label class="radio"><input type="radio" name="approval"> Revision Required</label>
                                    </div>
                                    <h4>Approved Date</h4>
                                    <div class="date"><input type="text" class="form-control" placeholder="Select date"><span>📅</span></div>
                                </div>

                                <div class="col-md-6">
                                    <h4>Send to Customer</h4>
                                    <label class="check"><input type="checkbox" checked> Send notification to customer</label>
                                    <textarea rows="2" class="form-control" placeholder="Add message to customer (optional)..."></textarea>
                                    <button class="btn btn-primary">➤ Send to Customer</button>
                                </div>
                            </div>

                            <div class="footer-actions"><button class="btn btn-primary">Save &amp; Update</button></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
