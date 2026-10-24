@extends('web_frontend.layouts.app')

@section('title', 'Annual Corporate Membership | More Than Giftz')
@section('meta')
  <meta name="description" content="Join our annual corporate membership program for exclusive pricing, premium benefits and seamless corporate gifting experience.">
@endsection

@section('content')
<!-- HERO SECTION -->
<section class="membership-hero">
  <div class="container-fluid px-4 px-xl-5">
    <div class="hero-content">
      <div class="hero-left">
        <span class="hero-eyebrow">MORE VALUE. STRONGER RELATIONSHIPS.</span>
        <h1>Annual Corporate Membership</h1>
        <h2 class="hero-subheading">A smarter way to gift. Greater savings. Bigger impact.</h2>
        <p class="hero-description">Join our annual membership program and unlock exclusive pricing, premium benefits and a seamless corporate gifting experience all year round.</p>
        
        <div class="hero-ctas">
          <button class="btn-primary btn-choose-plan">Choose Your Plan <i class="bi bi-arrow-right"></i></button>
          <button class="btn-secondary btn-talk-team">Talk to Our Team</button>
        </div>
        
        <div class="hero-stats">
          <div class="stat-item">
            <span class="stat-number">500+</span>
            <span class="stat-label">Corporate Clients</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number">50,000+</span>
            <span class="stat-label">Gifts Delivered</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number">4.9/5</span>
            <span class="stat-label">Client Satisfaction</span>
          </div>
        </div>
      </div>
      
      <div class="hero-right">
        <div class="hero-image">
          <img src="{{ asset('web_frontend/assets/images/Thoughtful-Gifts.png') }}" alt="Corporate Gifting">
        </div>
        <div class="hero-vertical-text">
          <span>GIFTS</span>
          <span>THAT</span>
          <span>BUILD</span>
          <span>BUSINESS</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- MEMBERSHIP PLANS -->
<section class="membership-plans">
  <div class="container-fluid px-4 px-xl-5">
    <div class="plans-header">
      <span class="plans-eyebrow">MEMBERSHIP PLANS</span>
      <h2>Choose the Right Plan for Your Business</h2>
      <p class="plans-subtitle">More gifting. More savings. More possibilities.</p>
    </div>
    
    <div class="plans-grid">
      <!-- Silver Plan -->
      <div class="plan-card silver">
        <div class="plan-icon">
          <i class="bi bi-star"></i>
        </div>
        <h3>Silver</h3>
        <span class="plan-audience">For Growing Teams</span>
        <p class="plan-description">Essential benefits to get you started.</p>
        
        <div class="plan-details">
          <div class="detail-row">
            <span class="detail-label">Annual Spend</span>
            <span class="detail-value">₹2,00,000 / year</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Estimated Savings</span>
            <span class="detail-value savings">₹20,000 (10%)</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Free Customization</span>
            <span class="detail-value">Up to 5 products</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Priority Dispatch</span>
            <span class="detail-value">Standard</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Dedicated Account Manager</span>
            <span class="detail-value">—</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Unused Budget Rollover</span>
            <span class="detail-value">—</span>
          </div>
        </div>
        
        <div class="plan-price">
          <span class="price">₹2,00,000</span>
          <span class="period">/ year</span>
        </div>
        
        <button class="btn-select-plan" data-plan="silver">Select Plan</button>
      </div>
      
      <!-- Gold Plan (Featured) -->
      <div class="plan-card gold featured">
        <div class="featured-badge">MOST POPULAR</div>
        <div class="plan-icon">
          <i class="bi bi-star-fill"></i>
        </div>
        <h3>Gold</h3>
        <span class="plan-audience">For Established Businesses</span>
        <p class="plan-description">Maximum value with premium benefits.</p>
        
        <div class="plan-details">
          <div class="detail-row">
            <span class="detail-label">Annual Spend</span>
            <span class="detail-value">₹5,00,000 / year</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Estimated Savings</span>
            <span class="detail-value savings">₹75,000 (15%)</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Free Customization</span>
            <span class="detail-value">Up to 10 products</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Priority Dispatch</span>
            <span class="detail-value">Priority (3–5 days)</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Dedicated Account Manager</span>
            <span class="detail-value"><i class="bi bi-check-circle-fill"></i></span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Unused Budget Rollover</span>
            <span class="detail-value">Up to 25%</span>
          </div>
        </div>
        
        <div class="plan-price">
          <span class="price">₹5,00,000</span>
          <span class="period">/ year</span>
        </div>
        
        <button class="btn-select-plan btn-gold" data-plan="gold">Select Plan</button>
      </div>
      
      <!-- Platinum Plan -->
      <div class="plan-card platinum">
        <div class="plan-icon">
          <i class="bi bi-star-half"></i>
        </div>
        <h3>Platinum</h3>
        <span class="plan-audience">For Large Enterprises</span>
        <p class="plan-description">The complete corporate gifting solution.</p>
        
        <div class="plan-details">
          <div class="detail-row">
            <span class="detail-label">Annual Spend</span>
            <span class="detail-value">₹10,00,000 / year</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Estimated Savings</span>
            <span class="detail-value savings">₹2,00,000 (20%)</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Free Customization</span>
            <span class="detail-value">Unlimited</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Priority Dispatch</span>
            <span class="detail-value">Express (1–3 days)</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Dedicated Account Manager</span>
            <span class="detail-value"><i class="bi bi-check-circle-fill"></i></span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Unused Budget Rollover</span>
            <span class="detail-value">Up to 50%</span>
          </div>
        </div>
        
        <div class="plan-price">
          <span class="price">₹10,00,000</span>
          <span class="period">/ year</span>
        </div>
        
        <button class="btn-select-plan" data-plan="platinum">Select Plan</button>
      </div>
    </div>
  </div>
</section>

<!-- BENEFITS STRIP -->
<section class="benefits-strip">
  <div class="container-fluid px-4 px-xl-5">
    <div class="benefits-grid">
      <div class="benefit-item">
        <i class="bi bi-gift"></i>
        <span>Exclusive Member Pricing</span>
      </div>
      <div class="benefit-divider"></div>
      <div class="benefit-item">
        <i class="bi bi-gear"></i>
        <span>Free Customization</span>
      </div>
      <div class="benefit-divider"></div>
      <div class="benefit-item">
        <i class="bi bi-truck"></i>
        <span>Priority Dispatch</span>
      </div>
      <div class="benefit-divider"></div>
      <div class="benefit-item">
        <i class="bi bi-bar-chart"></i>
        <span>Dedicated Account Manager</span>
      </div>
      <div class="benefit-divider"></div>
      <div class="benefit-item">
        <i class="bi bi-currency-rupee"></i>
        <span>Credit Rollover</span>
      </div>
      <div class="benefit-divider"></div>
      <div class="benefit-item">
        <i class="bi bi-star"></i>
        <span>Early Access to New Products</span>
      </div>
    </div>
  </div>
</section>

<!-- INFO CARDS -->
<section class="info-cards">
  <div class="container-fluid px-4 px-xl-5">
    <div class="info-cards-grid">
      <!-- Auto-Renewal Card -->
      <div class="info-card">
        <div class="card-icon-wrapper">
          <div class="card-icon">
            <i class="bi bi-arrow-repeat"></i>
          </div>
        </div>
        <div class="card-content">
          <h3>Auto-Renewal</h3>
          <p>Keep enjoying uninterrupted benefits. Your membership auto-renews annually, so you never miss out on savings.</p>
        </div>
        <div class="card-toggle">
          <label class="toggle-switch">
            <input type="checkbox" id="autoRenewal" checked>
            <span class="toggle-slider"></span>
          </label>
        </div>
      </div>
      
      <!-- Credit Rollover Card -->
      <div class="info-card">
        <div class="card-icon-wrapper">
          <div class="card-icon">
            <i class="bi bi-currency-rupee"></i>
          </div>
        </div>
        <div class="card-content">
          <h3>Credit Rollover</h3>
          <p>Unused annual budget rolls over to the next year (As per your plan benefits). No value goes to waste.</p>
        </div>
        <div class="card-calendar-icon">
          <i class="bi bi-calendar-check"></i>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- BOTTOM CTA -->
<section class="bottom-cta">
  <div class="container-fluid px-4 px-xl-5">
    <button class="btn-get-started">Get Started Today <i class="bi bi-arrow-right"></i></button>
    <p class="cta-subtext">Have questions? Contact our corporate gifting experts.</p>
  </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Choose Your Plan button - scroll to plans
  document.querySelector('.btn-choose-plan').addEventListener('click', function() {
    document.querySelector('.membership-plans').scrollIntoView({ behavior: 'smooth' });
  });

  // Talk to Our Team button
  document.querySelector('.btn-talk-team').addEventListener('click', function() {
    // Navigate to contact or enquiry page
    window.location.href = '/corporate-portal';
  });

  // Select Plan buttons
  document.querySelectorAll('.btn-select-plan').forEach(btn => {
    btn.addEventListener('click', function() {
      const plan = this.dataset.plan;
      alert(`You selected the ${plan.charAt(0).toUpperCase() + plan.slice(1)} plan. Proceeding to registration...`);
      // Here you would navigate to the registration/checkout flow
    });
  });

  // Auto-Renewal toggle
  const autoRenewalToggle = document.getElementById('autoRenewal');
  autoRenewalToggle.addEventListener('change', function() {
    const isEnabled = this.checked;
    alert(`Auto-renewal is now ${isEnabled ? 'enabled' : 'disabled'}. This setting will be saved to your account.`);
    // Here you would make an API call to save the setting
  });

  // Get Started Today button
  document.querySelector('.btn-get-started').addEventListener('click', function() {
    // Navigate to registration or enquiry
    window.location.href = '/corporate-portal';
  });
});
</script>
@endsection
