@extends('web_frontend.layouts.app')

@section('title', 'Order Tracking | More Than Giftz')
@section('meta')
  <meta name="description" content="Track your bulk order status in real-time with delivery updates and SLA monitoring.">
@endsection

@section('content')
<!-- BREADCRUMB -->
<div class="container-fluid px-4 px-xl-5">
  <div class="breadcrumb-row">
    <span>Home</span><i class="bi bi-chevron-right"></i>
    <span>My Orders</span><i class="bi bi-chevron-right"></i>
    <span>Order #MTG-2025-00125</span>
  </div>
</div>

<!-- ORDER TRACKING SECTION -->
<section class="container-fluid px-4 px-xl-5 order-tracking-section">
  <div class="tracking-header">
    <h1>Order Tracking</h1>
    <p class="tracking-subtitle">Stay updated with real-time status, estimated delivery and important alerts for your bulk order.</p>
    <div class="tracking-actions">
      <button class="btn-back-orders">
        <i class="bi bi-arrow-left"></i> Back to Orders
      </button>
      <button class="btn-download-report">
        <i class="bi bi-download"></i> Download Delivery Report
      </button>
    </div>
  </div>

  <!-- MAIN GRID LAYOUT -->
  <div class="tracking-grid">
    <!-- LEFT COLUMN - ORDER TRACKING CARD -->
    <div class="tracking-left">
      <div class="order-tracking-card">
        <!-- ORDER HEADER WITH IMAGE -->
        <div class="order-card-header">
          <div class="order-image">
            <img src="{{ asset('web_frontend/assets/images/Thoughtful-Gifts.png') }}" alt="Welcome Kit">
          </div>
          <div class="order-status-badge status-in-transit">
            <i class="bi bi-truck"></i>
            <span>In Transit</span>
          </div>
        </div>

        <!-- ORDER DETAILS -->
        <div class="order-details">
          <h2>Order #MTG-2025-00125</h2>
          <p class="order-description">Employee Welcome Kit – Dubai Office</p>

          <div class="order-stats">
            <div class="stat-row">
              <span class="stat-label">Client:</span>
              <span class="stat-value">ABC Technologies</span>
            </div>
            <div class="stat-row">
              <span class="stat-label">PO Number:</span>
              <span class="stat-value">PO-77821</span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Order Date:</span>
              <span class="stat-value">12 Sep 2025</span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Total Items:</span>
              <span class="stat-value">500 pcs</span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Order Value:</span>
              <span class="stat-value value-highlight">AED 27,500</span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Estimated Delivery:</span>
              <span class="stat-value delivery-date">25 Sep 2025</span>
            </div>
          </div>

          <a href="#" class="view-order-details">View Order Details <i class="bi bi-arrow-right"></i></a>
        </div>

        <!-- HORIZONTAL PROGRESS TRACKER -->
        <div class="progress-tracker-inline">
          <div class="timeline-inline">
            <div class="timeline-step completed">
              <div class="step-icon">
                <i class="bi bi-check-circle-fill"></i>
              </div>
              <div class="step-content">
                <h4>Order Confirmed</h4>
                <p class="step-date">12 Sep 2025, 10:15 AM</p>
                <span class="step-status completed">Completed</span>
              </div>
            </div>

            <div class="timeline-step completed">
              <div class="step-icon">
                <i class="bi bi-check-circle-fill"></i>
              </div>
              <div class="step-content">
                <h4>Design & Branding</h4>
                <p class="step-date">13 Sep 2025</p>
                <span class="step-status completed">Completed</span>
              </div>
            </div>

            <div class="timeline-step completed">
              <div class="step-icon">
                <i class="bi bi-check-circle-fill"></i>
              </div>
              <div class="step-content">
                <h4>Production</h4>
                <p class="step-date">16 Sep 2025</p>
                <span class="step-status completed">Completed</span>
              </div>
            </div>

            <div class="timeline-step delayed">
              <div class="step-icon">
                <i class="bi bi-exclamation-circle-fill"></i>
              </div>
              <div class="step-content">
                <h4>Quality Check</h4>
                <p class="step-date">18 Sep 2025</p>
                <span class="step-status delayed">Delayed by 1 day</span>
              </div>
            </div>

            <div class="timeline-step pending">
              <div class="step-icon">
                <i class="bi bi-circle"></i>
              </div>
              <div class="step-content">
                <h4>Dispatched</h4>
                <p class="step-date">Expected: 22 Sep 2025</p>
                <span class="step-status pending">Pending</span>
              </div>
            </div>

            <div class="timeline-step pending">
              <div class="step-icon">
                <i class="bi bi-circle"></i>
              </div>
              <div class="step-content">
                <h4>Delivered</h4>
                <p class="step-date">Expected: 25 Sep 2025</p>
                <span class="step-status pending">Pending</span>
              </div>
            </div>
          </div>
        </div>

        <!-- SLA ALERT -->
        <div class="sla-alert-inline">
          <div class="alert-icon-wrapper">
            <i class="bi bi-exclamation-triangle-fill"></i>
          </div>
          <div class="alert-content-wrapper">
            <h4>SLA Alert: Quality Check is delayed by 1 day</h4>
            <p>Our team is working on it. You will be notified once this stage is completed.</p>
            <div class="alert-footer">
              <span class="expected-date">Expected to complete by: 19 Sep 2025</span>
              <button class="btn-notify-me">
                <i class="bi bi-bell"></i> Notify Me on Update
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN - SLA MONITORING & NEED HELP -->
    <div class="tracking-right">
      <!-- SLA MONITORING CARD -->
      <div class="sla-monitoring-card">
        <div class="card-header-with-icon">
          <div class="header-icon">
            <i class="bi bi-clock-history"></i>
          </div>
          <div class="header-text">
            <h3>SLA Monitoring</h3>
            <a href="#" class="view-policy-link">View SLA Policy <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
        <p class="card-subtitle">We keep you informed if any stage is delayed.</p>

        <div class="sla-stages-list">
          <div class="sla-stage-item on-time">
            <div class="stage-icon">
              <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stage-info">
              <h4>Order Confirmation</h4>
              <p class="stage-duration">Within 24 hours</p>
            </div>
            <span class="stage-badge on-time">On Time</span>
          </div>

          <div class="sla-stage-item on-time">
            <div class="stage-icon">
              <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stage-info">
              <h4>Design & Approvals</h4>
              <p class="stage-duration">1–2 business days</p>
            </div>
            <span class="stage-badge on-time">On Time</span>
          </div>

          <div class="sla-stage-item on-time">
            <div class="stage-icon">
              <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stage-info">
              <h4>Production</h4>
              <p class="stage-duration">5–7 business days</p>
            </div>
            <span class="stage-badge on-time">On Time</span>
          </div>

          <div class="sla-stage-item delayed">
            <div class="stage-icon">
              <i class="bi bi-exclamation-circle-fill"></i>
            </div>
            <div class="stage-info">
              <h4>Quality Check</h4>
              <p class="stage-duration">1–2 business days</p>
            </div>
            <span class="stage-badge delayed">Delayed</span>
          </div>

          <div class="sla-stage-item pending">
            <div class="stage-icon">
              <i class="bi bi-circle"></i>
            </div>
            <div class="stage-info">
              <h4>Shipping & Delivery</h4>
              <p class="stage-duration">As per location</p>
            </div>
            <span class="stage-badge pending">Pending</span>
          </div>
        </div>
      </div>

      <!-- NEED HELP CARD -->
      <div class="need-help-card">
        <div class="help-icon">
          <i class="bi bi-headset"></i>
        </div>
        <h3>Need Help?</h3>
        <p>Our corporate support team is here for you.</p>
        <div class="help-buttons">
          <button class="btn-chat-us">
            <i class="bi bi-chat-dots"></i> Chat With Us
          </button>
          <a href="tel:+971557863450" class="btn-call-us">
            <i class="bi bi-telephone"></i> Call +971 55 786 3450
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- BOTTOM ROW - ITEMS TABLE & DELIVERY REPORT -->
  <div class="bottom-grid">
    <!-- ITEMS IN THIS ORDER -->
    <div class="items-order-card">
      <div class="card-header-with-icon">
        <div class="header-icon">
          <i class="bi bi-box-seam"></i>
        </div>
        <h3>Items in this Order</h3>
      </div>

      <div class="items-table-responsive">
        <table class="items-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>SKU</th>
              <th>Quantity</th>
              <th>Branding</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <div class="table-product-info">
                  <img src="https://via.placeholder.com/50" alt="Bottle">
                  <span>Insulated Bottle (Matte Black, 500ml)</span>
                </div>
              </td>
              <td>MTG-BTL-001</td>
              <td>200 pcs</td>
              <td>Your Logo (Laser)</td>
              <td><span class="status-badge-table completed">Production Completed</span></td>
            </tr>
            <tr>
              <td>
                <div class="table-product-info">
                  <img src="https://via.placeholder.com/50" alt="Notebook">
                  <span>Premium Notebook (A5, Hard Cover)</span>
                </div>
              </td>
              <td>MTG-NBK-002</td>
              <td>200 pcs</td>
              <td>Your Logo (Deboss)</td>
              <td><span class="status-badge-table completed">Production Completed</span></td>
            </tr>
            <tr>
              <td>
                <div class="table-product-info">
                  <img src="https://via.placeholder.com/50" alt="Box">
                  <span>Welcome Kit Box (Custom Packaging)</span>
                </div>
              </td>
              <td>MTG-BOX-003</td>
              <td>100 pcs</td>
              <td>Full Branding</td>
              <td><span class="status-badge-table in-progress">In Quality Check</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- DELIVERY REPORT CARD -->
    <div class="delivery-report-card">
      <div class="card-header-with-icon">
        <div class="header-icon">
          <i class="bi bi-file-earmark-bar-graph"></i>
        </div>
        <div class="header-text">
          <h3>Delivery Report</h3>
          <span class="last-updated">Last Updated 18 Sep 2025, 04:30 PM</span>
        </div>
      </div>
      <p class="report-desc">Download detailed delivery report for HR/Admin reconciliation.</p>

      <button class="btn-download-pdf">
        <i class="bi bi-file-earmark-pdf"></i> Download Full Delivery Report (PDF)
      </button>

      <div class="report-features">
        <h4>Report Includes:</h4>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> Item-wise delivery status</li>
          <li><i class="bi bi-check-circle-fill"></i> Recipient list (if available)</li>
          <li><i class="bi bi-check-circle-fill"></i> Delivery dates & location</li>
          <li><i class="bi bi-check-circle-fill"></i> Tracking reference numbers</li>
          <li><i class="bi bi-check-circle-fill"></i> Exception / failed deliveries (if any)</li>
        </ul>
      </div>

      <div class="report-preview-box">
        <div class="preview-content">
          <div class="preview-logo">
            <span>more</span>
            <span>than</span>
            <span>giftz</span>
          </div>
          <div class="preview-badge">
            <i class="bi bi-file-earmark-pdf"></i>
            <span>PDF</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Back to Orders button
  const backBtn = document.querySelector('.btn-back-orders');
  if (backBtn) {
    backBtn.addEventListener('click', function() {
      window.history.back();
    });
  }

  // Download Report button (header)
  const downloadReportBtn = document.querySelector('.btn-download-report');
  if (downloadReportBtn) {
    downloadReportBtn.addEventListener('click', function() {
      alert('Downloading delivery report...');
    });
  }

  // Download PDF button
  const downloadPdfBtn = document.querySelector('.btn-download-pdf');
  if (downloadPdfBtn) {
    downloadPdfBtn.addEventListener('click', function() {
      alert('Downloading full delivery report as PDF...');
    });
  }

  // Notify Me button
  const notifyBtn = document.querySelector('.btn-notify-me');
  if (notifyBtn) {
    notifyBtn.addEventListener('click', function() {
      this.innerHTML = '<i class="bi bi-check-circle-fill"></i> Notifications Enabled';
      this.style.background = '#22c55e';
      this.style.borderColor = '#22c55e';
      this.style.color = 'white';
    });
  }

  // Chat With Us button
  const chatBtn = document.querySelector('.btn-chat-us');
  if (chatBtn) {
    chatBtn.addEventListener('click', function() {
      alert('Opening chat support...');
    });
  }
});
</script>
@endsection
