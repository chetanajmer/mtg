@if(Session::has('role'))
@else
<script>
window.location.href = "{{url('/admin')}}";
</script>
@endif

@extends('layouts.app')

@section('content')
    <style>
        /* ========== Variables & reset ========== */
        :root {
          --blue: #2563eb;
          --blue-soft: #e8f0fe;
          --green: #16a34a;
          --orange: #f08a24;
          --orange-bg: #fdf0e1;
          --red: #e5533d;
          --red-bg: #fde9e4;
          --text: #1f2937;
          --muted: #6b7280;
          --border: #e5e7eb;
          --bg: #f3f6fb;
          --white: #fff;
          --radius: 8px;
        }
        .order-workflow-wrapper * { box-sizing: border-box; margin: 0; padding: 0; }
        .order-workflow-wrapper ul, .order-workflow-wrapper ol { list-style: none; }
        .order-workflow-wrapper a { text-decoration: none; color: inherit; }
        .order-workflow-wrapper button, .order-workflow-wrapper input, .order-workflow-wrapper select, .order-workflow-wrapper textarea { font: inherit; color: inherit; }
        .order-workflow-wrapper button { cursor: pointer; border: 0; background: none; }
        .order-workflow-wrapper h1, .order-workflow-wrapper h2, .order-workflow-wrapper h3, .order-workflow-wrapper h4 { font-weight: 600; }

        /* ========== Layout ========== */
        .order-workflow-wrapper { background: var(--bg); min-height: 100vh; }
        .order-workflow-content { padding: 12px 20px 16px; }
        .order-workflow-page-head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px; }
        .order-workflow-breadcrumb { color: var(--muted); margin-bottom: 4px; }
        .order-workflow-breadcrumb span { margin: 0 6px; }
        .order-workflow-wrapper h1 { font-size: 22px; }
        .order-workflow-card { background: var(--white); border-radius: var(--radius); box-shadow: 0 1px 3px rgba(16, 24, 40, .08); }
        .order-workflow-grid { display: grid; grid-template-columns: 365px 1fr; gap: 14px; margin-top: 14px; align-items: start; }

        /* ========== Buttons / pills ========== */
        .order-workflow-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 9px 16px; border-radius: 6px; font-weight: 500; }
        .order-workflow-btn-primary { background: var(--blue); color: var(--white); }
        .order-workflow-btn-outline { border: 1px solid var(--border); background: var(--white); padding: 7px 14px; font-size: 12px; }
        .order-workflow-pill { min-width: 128px; text-align: center; padding: 8px 10px; border-radius: 6px; font-size: 12px; }
        .order-workflow-pill-orange { background: var(--orange-bg); color: var(--orange); }
        .order-workflow-pill-red { background: var(--red-bg); color: var(--red); }
        .order-workflow-pill-grey { background: #f1f3f5; color: var(--muted); }

        /* ========== Order summary ========== */
        .order-workflow-order-card { padding: 8px 32px 12px; }
        .order-workflow-order-info { display: flex; gap: 18px; align-items: center; padding: 6px 0 12px; margin-left: -20px; }
        .order-workflow-thumb { width: 84px; height: 76px; border-radius: 6px; background: linear-gradient(#eef0f3, #cfd4da); display: grid; place-items: center; }
        .order-workflow-bottle { display: block; width: 24px; height: 52px; border-radius: 6px 6px 4px 4px; background: linear-gradient(90deg, #111, #3a3a3a 50%, #111); position: relative; }
        .order-workflow-bottle::before { content: ""; position: absolute; top: -7px; left: 7px; width: 10px; height: 8px; background: #111; border-radius: 2px; }
        .order-workflow-order-info h2 { font-size: 16px; margin-bottom: 8px; }
        .order-workflow-meta { color: var(--muted); display: flex; gap: 14px; }
        .order-workflow-meta span + span::before { content: "|"; margin-right: 14px; color: #c3c8d0; }
        .order-workflow-meta b { font-weight: 500; color: var(--text); }
        .order-workflow-meta span:first-child b { color: var(--muted); }

        /* ========== Stepper ========== */
        .order-workflow-stepper { display: flex; margin: 0 -10px; }
        .order-workflow-stepper li { flex: 1; text-align: center; font-size: 12px; line-height: 1.45; color: var(--muted); position: relative; }
        .order-workflow-stepper .dot { display: grid; place-items: center; width: 26px; height: 26px; margin: 0 auto 8px; border-radius: 50%; background: #e5e7eb; color: #6b7280; font-weight: 600; font-size: 12px; position: relative; z-index: 1; }
        .order-workflow-stepper li:not(:last-child)::after { content: ""; position: absolute; top: 12px; left: calc(50% + 20px); width: calc(100% - 40px); height: 2px; background: #e5e7eb; }
        .order-workflow-stepper li.done .dot { background: var(--green); color: var(--white); }
        .order-workflow-stepper li.done::after { background: var(--blue) !important; }
        .order-workflow-stepper li.active { color: var(--text); font-weight: 500; }
        .order-workflow-stepper li.active .dot { background: var(--blue); color: var(--white); box-shadow: 0 0 0 4px var(--blue-soft); }

        /* ========== Workflow steps list ========== */
        .order-workflow-steps-card h3 { padding: 14px 16px 10px; font-size: 16px; }
        .order-workflow-steps li { display: flex; align-items: center; gap: 12px; padding: 12px 14px 12px 12px; border-top: 1px solid var(--border); }
        .order-workflow-steps li.current { background: var(--blue-soft); border-left: 3px solid var(--blue); }
        .order-workflow-steps li > div { flex: 1; min-width: 0; }
        .order-workflow-steps strong { display: block; font-size: 12.5px; font-weight: 600; }
        .order-workflow-steps small { display: block; color: var(--muted); font-size: 11px; margin-top: 2px; line-height: 1.35; }
        .order-workflow-num { flex-shrink: 0; width: 30px; height: 30px; border-radius: 50%; background: #7b8794; color: var(--white); display: grid; place-items: center; font-size: 13px; }
        .order-workflow-num.green { background: var(--green); }
        .order-workflow-num.blue { background: var(--blue); }
        .order-workflow-tag { font-style: normal; font-size: 11px; padding: 4px 10px; border-radius: 4px; white-space: nowrap; }
        .order-workflow-tag-green { background: #e3f6e8; color: var(--green); }
        .order-workflow-tag-orange { background: var(--orange-bg); color: var(--orange); }
        .order-workflow-tag-grey { background: #f1f3f5; color: var(--muted); }
        .order-workflow-chev { font-size: 18px; color: #4b5563; }

        /* ========== Detail panel ========== */
        .order-workflow-detail { padding: 14px 18px 18px; }
        .order-workflow-detail-head { display: flex; justify-content: space-between; align-items: flex-start; }
        .order-workflow-detail h3 { font-size: 16px; }
        .order-workflow-detail-head p { color: var(--muted); margin-top: 6px; }
        .order-workflow-status { display: flex; align-items: center; gap: 12px; font-size: 12px; }
        .order-workflow-status select { width: 150px; padding: 8px 10px; border: 1px solid var(--border); border-radius: 6px; color: var(--orange); background: var(--white); }
        .order-workflow-detail h4 { font-size: 12.5px; margin-bottom: 8px; }
        .order-workflow-two-col { display: grid; grid-template-columns: 1.05fr 1fr; gap: 20px; margin-top: 16px; }
        .order-workflow-col-right { border-left: 1px solid var(--border); padding-left: 20px; display: flex; flex-direction: column; }
        .order-workflow-col-right h4:not(:first-child) { margin-top: 14px; }
        .order-workflow-wrapper textarea { width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: 6px; resize: vertical; font-size: 12px; color: var(--muted); }

        /* Mockups */
        .order-workflow-mockups { display: flex; gap: 10px; }
        .order-workflow-mock { position: relative; width: 122px; height: 108px; border-radius: 6px; display: grid; place-items: center; background: linear-gradient(#2b2b2b, #0e0e0e); color: #d9b46a; }
        .order-workflow-mock-2 { width: 130px; background: linear-gradient(#3a3a3a, #111); }
        .order-workflow-mock small { position: absolute; bottom: 20px; font-size: 7px; }
        .order-workflow-box { display: block; border: 1px solid #d9b46a55; padding: 12px 16px; font-size: 12px; text-align: center; font-weight: 600; }
        .order-workflow-remove { position: absolute; top: 6px; right: 6px; width: 17px; height: 17px; border-radius: 50%; background: var(--white); color: var(--text); line-height: 15px; font-size: 13px; }
        .order-workflow-upload { width: 125px; height: 108px; border: 1px dashed #b8bfca; border-radius: 6px; color: var(--muted); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; font-size: 12px; }
        .order-workflow-upload span { font-size: 26px; font-weight: 300; }
        .order-workflow-hint { color: var(--muted); font-size: 11px; margin-top: 8px; }

        /* Revisions */
        .order-workflow-section-title { margin-top: 20px; font-size: 14px !important; }
        .order-workflow-revisions { border: 1px solid var(--border); border-radius: 6px; padding: 4px 18px 4px 30px; position: relative; }
        .order-workflow-revisions li { display: flex; align-items: center; gap: 10px; padding: 10px 0; position: relative; }
        .order-workflow-revisions li::before { content: ""; position: absolute; left: -17px; top: 50%; width: 12px; height: 12px; margin-top: -6px; border-radius: 50%; background: #9aa3b0; }
        .order-workflow-revisions li.rev-current::before { background: var(--blue); box-shadow: 0 0 0 3px var(--blue-soft); }
        .order-workflow-rev-info { flex: 1; }
        .order-workflow-rev-info strong { display: block; font-size: 12.5px; }
        .order-workflow-rev-info small { color: var(--muted); font-size: 11px; }

        /* Approval + send */
        .order-workflow-bottom { margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--border); }
        .order-workflow-bottom h4:not(:first-child) { margin-top: 18px; }
        .order-workflow-radios { display: flex; gap: 8px; }
        .order-workflow-radio { display: flex; align-items: center; gap: 8px; padding: 10px 14px; border: 1px solid var(--border); border-radius: 6px; color: var(--muted); }
        .order-workflow-radio.checked { border-color: var(--blue); color: var(--text); }
        .order-workflow-radio input { accent-color: var(--blue); }
        .order-workflow-date { position: relative; width: 245px; }
        .order-workflow-date input { width: 100%; padding: 10px 36px 10px 12px; border: 1px solid var(--border); border-radius: 6px; }
        .order-workflow-date span { position: absolute; right: 10px; top: 9px; font-size: 14px; }
        .order-workflow-check { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
        .order-workflow-check input { accent-color: var(--blue); width: 15px; height: 15px; }
        .order-workflow-col-right .order-workflow-btn { align-self: flex-start; margin-top: 10px; }
        .order-workflow-footer-actions { display: flex; justify-content: flex-end; margin-top: 18px; }

        .order-workflow-footer { margin-top: 12px; color: var(--muted); font-size: 11px; }

        /* ========== Responsive ========== */
        @media (max-width: 1100px) {
          .order-workflow-grid, .order-workflow-two-col { grid-template-columns: 1fr; }
          .order-workflow-col-right { border-left: 0; padding-left: 0; }
          .order-workflow-stepper { overflow-x: auto; }
          .order-workflow-stepper li { min-width: 110px; }
        }
        @media (max-width: 760px) {
          .order-workflow-meta, .order-workflow-page-head, .order-workflow-detail-head { flex-direction: column; align-items: flex-start; gap: 8px; }
          .order-workflow-revisions li { flex-wrap: wrap; }
        }
    </style>

    <div class="order-workflow-wrapper">
        <div class="order-workflow-content">
            <!-- ===== Page heading ===== -->
            <div class="order-workflow-page-head">
                <div>
                    <p class="order-workflow-breadcrumb">Store Enquiries <span>/</span> Order #{{ $order->id ?? 'N/A' }}</p>
                    <h1>Order Workflow — #{{ $order->id ?? 'N/A' }}</h1>
                </div>
                <a href="{{url('orders')}}" class="order-workflow-btn order-workflow-btn-primary">← Back to Orders</a>
            </div>

            <!-- ===== Order summary + stepper ===== -->
            <section class="order-workflow-card order-workflow-order-card">
                <div class="order-workflow-order-info">
                    <div class="order-workflow-thumb"><span class="order-workflow-bottle"></span></div>
                    <div>
                        <h2>{{ $order->name ?? 'N/A' }}</h2>
                        <p class="order-workflow-meta">
                            <span>Order ID: <b>{{ $order->orderid ?? 'N/A' }}</b></span>
                            <span>Order Date: <b>{{ $order->created_at ?? 'N/A' }}</b></span>
                            <span>Total: <b>{{ $order->ordertotal ?? 'N/A' }}</b></span>
                        </p>
                    </div>
                </div>

                <ol class="order-workflow-stepper">
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
            </section>

            <div class="order-workflow-grid">
                <!-- ===== Workflow steps list ===== -->
                <aside class="order-workflow-card order-workflow-steps-card">
                    <h3>Workflow Steps</h3>
                    <ul class="order-workflow-steps">
                        <li><b class="order-workflow-num green">1</b><div><strong>Customer Enquiry / Order</strong><small>Product selection, quantity, logo and requirements.</small></div><em class="order-workflow-tag order-workflow-tag-green">Completed</em><span class="order-workflow-chev">›</span></li>
                        <li class="current"><b class="order-workflow-num blue">2</b><div><strong>Sample Approval / Mockup Design &amp; Approval</strong><small>Sample shown, design submitted → revisions → final approval.</small></div><em class="order-workflow-tag order-workflow-tag-orange">In Review</em><span class="order-workflow-chev">›</span></li>
                        <li><b class="order-workflow-num">3</b><div><strong>Advance Payment – 50%</strong><small>Accounts confirms receipt of advance.</small></div><em class="order-workflow-tag order-workflow-tag-orange">Pending</em><span class="order-workflow-chev">›</span></li>
                        <li><b class="order-workflow-num">4</b><div><strong>Sample Production / Approval</strong><small>Physical or pre-production sample prepared and approved.</small></div><em class="order-workflow-tag order-workflow-tag-orange">Pending</em><span class="order-workflow-chev">›</span></li>
                        <li><b class="order-workflow-num">5</b><div><strong>Mass Production</strong><small>Production starts after payment and artwork approval.</small></div><em class="order-workflow-tag order-workflow-tag-grey">Not Started</em><span class="order-workflow-chev">›</span></li>
                        <li><b class="order-workflow-num">6</b><div><strong>Quality Check &amp; Packing</strong><small>Final product photos and packing confirmation.</small></div><em class="order-workflow-tag order-workflow-tag-grey">Not Started</em><span class="order-workflow-chev">›</span></li>
                        <li><b class="order-workflow-num">7</b><div><strong>Balance Payment – 50%</strong><small>Customer pays remaining amount before dispatch.</small></div><em class="order-workflow-tag order-workflow-tag-orange">Pending</em><span class="order-workflow-chev">›</span></li>
                        <li><b class="order-workflow-num">8</b><div><strong>Delivery</strong><small>Unipatch, delivery tracking and proof of delivery.</small></div><em class="order-workflow-tag order-workflow-tag-grey">Not Started</em><span class="order-workflow-chev">›</span></li>
                        <li><b class="order-workflow-num">9</b><div><strong>Project Completed</strong><small>Customer acknowledgement and reorder option.</small></div><em class="order-workflow-tag order-workflow-tag-grey">Not Started</em><span class="order-workflow-chev">›</span></li>
                        <li><b class="order-workflow-num">10</b><div><strong>Project Review / Feedback</strong><small>Customer satisfaction, ratings, comments and improvement suggestions.</small></div><em class="order-workflow-tag order-workflow-tag-grey">Not Started</em><span class="order-workflow-chev">›</span></li>
                    </ul>
                </aside>

                <!-- ===== Step detail panel ===== -->
                <section class="order-workflow-card order-workflow-detail">
                    <div class="order-workflow-detail-head">
                        <div>
                            <h3>Step 2: &nbsp;Sample Approval / Mockup Design &amp; Approval</h3>
                            <p>Upload mockup designs, send to customer, manage revisions and get final approval.</p>
                        </div>
                        <label class="order-workflow-status">Current Status
                            <select><option>In Review</option><option>Approved</option><option>Revision Required</option></select>
                        </label>
                    </div>

                    <div class="order-workflow-two-col">
                        <div class="col-left">
                            <h4>Mockup Images</h4>
                            <div class="order-workflow-mockups">
                                <div class="order-workflow-mock order-workflow-mock-1"><button class="order-workflow-remove" aria-label="Remove">×</button><span class="order-workflow-bottle"></span><small>ABC</small></div>
                                <div class="order-workflow-mock order-workflow-mock-2"><button class="order-workflow-remove" aria-label="Remove">×</button><span class="order-workflow-box">ABC<br><small>COMPANY</small></span></div>
                                <button class="order-workflow-upload"><span>+</span>Upload Images</button>
                            </div>
                            <p class="order-workflow-hint">Supported formats: JPG, PNG, PDF (Max 10 MB each)</p>
                        </div>

                        <div class="order-workflow-col-right">
                            <h4>Customer Message</h4>
                            <textarea rows="3">Please find attached the mockup design for your review.
Let us know if any changes are required.</textarea>
                            <h4>Internal Remarks</h4>
                            <textarea rows="3" placeholder="Add internal remarks..."></textarea>
                        </div>
                    </div>

                    <h4 class="order-workflow-section-title">Design Revisions</h4>
                    <ul class="order-workflow-revisions">
                        <li class="rev-current">
                            <div class="order-workflow-rev-info"><strong>Revision 3 (Current)</strong><small>Uploaded by Rohan Sharma &nbsp;•&nbsp; 25-Sep-2026 11:30 AM &nbsp;•&nbsp; Mockup_v3.jpg</small></div>
                            <button class="order-workflow-btn order-workflow-btn-outline">👁 View</button><button class="order-workflow-btn order-workflow-btn-outline">⭳ Download</button><span class="order-workflow-pill order-workflow-pill-orange">Sent to Customer</span>
                        </li>
                        <li>
                            <div class="order-workflow-rev-info"><strong>Revision 2</strong><small>Uploaded by Neha Verma &nbsp;•&nbsp; 24-Sep-2026 04:15 PM &nbsp;•&nbsp; Mockup_v2.jpg</small></div>
                            <button class="order-workflow-btn order-workflow-btn-outline">👁 View</button><button class="order-workflow-btn order-workflow-btn-outline">⭳ Download</button><span class="order-workflow-pill order-workflow-pill-red">Revision Requested</span>
                        </li>
                        <li>
                            <div class="order-workflow-rev-info"><strong>Revision 1</strong><small>Uploaded by Rohan Sharma &nbsp;•&nbsp; 24-Sep-2026 10:20 AM &nbsp;•&nbsp; Mockup_v1.jpg</small></div>
                            <button class="order-workflow-btn order-workflow-btn-outline">👁 View</button><button class="order-workflow-btn order-workflow-btn-outline">⭳ Download</button><span class="order-workflow-pill order-workflow-pill-grey">Replaced</span>
                        </li>
                    </ul>

                    <div class="order-workflow-two-col order-workflow-bottom">
                        <div class="col-left">
                            <h4>Customer Approval</h4>
                            <div class="order-workflow-radios">
                                <label class="order-workflow-radio checked"><input type="radio" name="approval" checked> Pending</label>
                                <label class="order-workflow-radio"><input type="radio" name="approval"> Approved</label>
                                <label class="order-workflow-radio"><input type="radio" name="approval"> Revision Required</label>
                            </div>
                            <h4>Approved Date</h4>
                            <div class="order-workflow-date"><input type="text" placeholder="Select date"><span>📅</span></div>
                        </div>

                        <div class="order-workflow-col-right">
                            <h4>Send to Customer</h4>
                            <label class="order-workflow-check"><input type="checkbox" checked> Send notification to customer</label>
                            <textarea rows="2" placeholder="Add message to customer (optional)..."></textarea>
                            <button class="order-workflow-btn order-workflow-btn-primary">➤ Send to Customer</button>
                        </div>
                    </div>

                    <div class="order-workflow-footer-actions"><button class="order-workflow-btn order-workflow-btn-primary">Save &amp; Update</button></div>
                </section>
            </div>

            <footer class="order-workflow-footer">© 2026 MoreThanGiftz. All rights reserved. Developed By MDD Infotech, India &nbsp;v5.0</footer>
        </div>
    </div>
@endsection
