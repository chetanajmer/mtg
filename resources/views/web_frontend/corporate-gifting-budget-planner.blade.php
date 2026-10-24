@extends('web_frontend.layouts.app')

@section('title', 'Corporate Gifting Budget Planner | More Than Giftz')
@section('meta')
  <meta name="description" content="Plan your corporate gifting budget with our smart planner. Get tailored recommendations based on occasion, team size and budget.">
@endsection

@section('content')
<!-- HERO SECTION -->
<section class="budget-planner-hero">
  <div class="container-fluid px-4 px-xl-5">
    <div class="hero-content">
      <div class="hero-left">
        <span class="hero-eyebrow">PLAN SMART. GIFT BETTER.</span>
        <h1>Corporate Gifting Budget Planner</h1>
        <p class="hero-description">Tell us the occasion, team size and budget — we'll suggest the best gifting options tailored to your needs.</p>
        
        <div class="hero-benefits">
          <div class="benefit-item">
            <i class="bi bi-gift"></i>
            <span>Curated for your occasion</span>
          </div>
          <div class="benefit-item">
            <i class="bi bi-gear"></i>
            <span>Best value suggestions</span>
          </div>
          <div class="benefit-item">
            <i class="bi bi-truck"></i>
            <span>Save time & effort</span>
          </div>
        </div>
      </div>
      
      <div class="hero-right">
        <div class="hero-image">
          <img src="{{ asset('web_frontend/assets/images/Thoughtful-Gifts.png') }}" alt="Corporate Gifting">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- OCCASION SELECTOR -->
<section class="occasion-section">
  <div class="container-fluid px-4 px-xl-5">
    <div class="section-header">
      <span class="section-number">1</span>
      <h2>Select the Occasion</h2>
    </div>
    
    <div class="occasion-cards">
      <div class="occasion-card active" data-occasion="eid">
        <div class="occasion-icon">
          <i class="bi bi-stars"></i>
        </div>
        <span>Eid Gifts</span>
      </div>
      <div class="occasion-card" data-occasion="joining">
        <div class="occasion-icon">
          <i class="bi bi-person-plus"></i>
        </div>
        <span>Joining Kit</span>
      </div>
      <div class="occasion-card" data-occasion="anniversary">
        <div class="occasion-icon">
          <i class="bi bi-calendar-check"></i>
        </div>
        <span>Work Anniversary</span>
      </div>
      <div class="occasion-card" data-occasion="festive">
        <div class="occasion-icon">
          <i class="bi bi-gift"></i>
        </div>
        <span>Festive Gifts</span>
      </div>
      <div class="occasion-card" data-occasion="client">
        <div class="occasion-icon">
          <i class="bi bi-handshake"></i>
        </div>
        <span>Client Appreciation</span>
      </div>
      <div class="occasion-card" data-occasion="conference">
        <div class="occasion-icon">
          <i class="bi bi-people"></i>
        </div>
        <span>Conference / Event</span>
      </div>
      <div class="occasion-card" data-occasion="diwali">
        <div class="occasion-icon">
          <i class="bi bi-lightbulb"></i>
        </div>
        <span>Diwali Gifts</span>
      </div>
      <div class="occasion-card" data-occasion="other">
        <div class="occasion-icon">
          <i class="bi bi-three-dots"></i>
        </div>
        <span>Other</span>
      </div>
    </div>
  </div>
</section>

<!-- CONFIGURATION SECTION -->
<section class="config-section">
  <div class="container-fluid px-4 px-xl-5">
    <div class="config-grid">
      <!-- Team Size -->
      <div class="config-item">
        <span class="config-number">2</span>
        <h3>Team Size</h3>
        <div class="config-select-wrapper">
          <i class="bi bi-people"></i>
          <select class="config-select" id="teamSize">
            <option value="">Select team size</option>
            <option value="1-25">1–25 people</option>
            <option value="26-50">26–50 people</option>
            <option value="51-100">51–100 people</option>
            <option value="101-250">101–250 people</option>
            <option value="251-500">251–500 people</option>
            <option value="500+">500+ people</option>
          </select>
        </div>
      </div>
      
      <!-- Budget per Person -->
      <div class="config-item">
        <span class="config-number">3</span>
        <h3>Budget per Person (AED)</h3>
        <div class="config-select-wrapper">
          <i class="bi bi-currency-dollar"></i>
          <select class="config-select" id="budgetRange">
            <option value="">Select budget range</option>
            <option value="under-50">Under AED 50</option>
            <option value="50-100">AED 50–100</option>
            <option value="101-200">AED 101–200</option>
            <option value="201-300">AED 201–300</option>
            <option value="301-500">AED 301–500</option>
            <option value="500+">AED 500+</option>
          </select>
        </div>
      </div>
      
      <!-- Preferences -->
      <div class="config-item preferences">
        <span class="config-number">4</span>
        <h3>Add Preferences (Optional)</h3>
        <div class="preference-chips">
          <div class="preference-chip" data-pref="eco">
            <i class="bi bi-tree"></i>
            <span>Eco-friendly</span>
          </div>
          <div class="preference-chip" data-pref="premium">
            <i class="bi bi-star"></i>
            <span>Premium look</span>
          </div>
          <div class="preference-chip" data-pref="tech">
            <i class="bi bi-laptop"></i>
            <span>Tech items</span>
          </div>
          <div class="preference-chip" data-pref="branding">
            <i class="bi bi-palette"></i>
            <span>Custom branding</span>
          </div>
        </div>
      </div>
      
      <!-- Show Recommendations Button -->
      <div class="config-item button-wrapper">
        <button class="btn-show-recommendations">Show Recommendations <i class="bi bi-arrow-right"></i></button>
      </div>
    </div>
  </div>
</section>

<!-- RECOMMENDED BUNDLES -->
<section class="recommendations-section" id="recommendations">
  <div class="container-fluid px-4 px-xl-5">
    <div class="recommendations-header">
      <div>
        <h2>Recommended Bundles for <span id="selectedOccasion">Eid Gifts</span></h2>
        <p class="recommendations-subtitle">Curated options based on your budget, team size and occasion.</p>
      </div>
      <button class="btn-view-more">View More Options</button>
    </div>
    
    <div class="bundles-grid">
      <!-- Bundle 1: Eid Essentials Pack -->
      <div class="bundle-card">
        <div class="bundle-left">
          <span class="bundle-badge budget">Budget Friendly</span>
          <h3>Eid Essentials Pack</h3>
          <p class="bundle-description">A thoughtful way to show you care.</p>
          <div class="bundle-price">
            <span class="price">AED 75</span>
            <span class="per-person">/ person</span>
          </div>
          <div class="bundle-includes">
            <h4>Include these items:</h4>
            <ul>
              <li><i class="bi bi-check-circle-fill"></i> Premium Mug</li>
              <li><i class="bi bi-check-circle-fill"></i> Dates Box (250g)</li>
              <li><i class="bi bi-check-circle-fill"></i> Greeting Card (Customizable)</li>
              <li><i class="bi bi-check-circle-fill"></i> Branded Gift Box</li>
            </ul>
          </div>
          <button class="btn-select-bundle" data-bundle="essentials">Select This Bundle</button>
        </div>
        <div class="bundle-right">
          <div class="bundle-image">
            <img src="{{ asset('web_frontend/assets/images/Thoughtful-Gifts.png') }}" alt="Eid Essentials Pack">
          </div>
        </div>
      </div>
      
      <!-- Bundle 2: Eid Premium Pack (Featured) -->
      <div class="bundle-card featured">
        <div class="bundle-left">
          <span class="bundle-badge popular">Most Popular</span>
          <h3>Eid Premium Pack</h3>
          <p class="bundle-description">A perfect balance of elegance and utility.</p>
          <div class="bundle-price">
            <span class="price">AED 150</span>
            <span class="per-person">/ person</span>
          </div>
          <div class="bundle-includes">
            <h4>Include these items:</h4>
            <ul>
              <li><i class="bi bi-check-circle-fill"></i> Insulated Tumbler</li>
              <li><i class="bi bi-check-circle-fill"></i> Luxury Dates Box (500g)</li>
              <li><i class="bi bi-check-circle-fill"></i> Leather Notebook</li>
              <li><i class="bi bi-check-circle-fill"></i> Premium Pen</li>
              <li><i class="bi bi-check-circle-fill"></i> Custom Greeting Card</li>
              <li><i class="bi bi-check-circle-fill"></i> Branded Gift Box</li>
            </ul>
          </div>
          <button class="btn-select-bundle btn-gold" data-bundle="premium">Select This Bundle</button>
        </div>
        <div class="bundle-right">
          <div class="bundle-image">
            <img src="{{ asset('web_frontend/assets/images/Thoughtful-Gifts.png') }}" alt="Eid Premium Pack">
          </div>
        </div>
      </div>
      
      <!-- Bundle 3: Eid Luxury Hamper -->
      <div class="bundle-card">
        <div class="bundle-left">
          <span class="bundle-badge premium">Premium</span>
          <h3>Eid Luxury Hamper</h3>
          <p class="bundle-description">Make a lasting impression.</p>
          <div class="bundle-price">
            <span class="price">AED 300</span>
            <span class="per-person">/ person</span>
          </div>
          <div class="bundle-includes">
            <h4>Include these items:</h4>
            <ul>
              <li><i class="bi bi-check-circle-fill"></i> Wireless Speaker</li>
              <li><i class="bi bi-check-circle-fill"></i> Premium Dates Box (1kg)</li>
              <li><i class="bi bi-check-circle-fill"></i> Scented Candle</li>
              <li><i class="bi bi-check-circle-fill"></i> Leather Notebook</li>
              <li><i class="bi bi-check-circle-fill"></i> Executive Pen</li>
              <li><i class="bi bi-check-circle-fill"></i> Custom Card & Sleeve</li>
              <li><i class="bi bi-check-circle-fill"></i> Luxury Gift Box</li>
            </ul>
          </div>
          <button class="btn-select-bundle" data-bundle="luxury">Select This Bundle</button>
        </div>
        <div class="bundle-right">
          <div class="bundle-image">
            <img src="{{ asset('web_frontend/assets/images/Thoughtful-Gifts.png') }}" alt="Eid Luxury Hamper">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- BENEFITS STRIP -->
<section class="planner-benefits-strip">
  <div class="container-fluid px-4 px-xl-5">
    <div class="benefits-row">
      <div class="planner-benefit">
        <i class="bi bi-gift"></i>
        <div class="benefit-content">
          <strong>Multiple Options</strong>
          <span>Get 2–3 curated bundles, not just one.</span>
        </div>
      </div>
      <div class="benefit-divider"></div>
      <div class="planner-benefit">
        <i class="bi bi-sliders"></i>
        <div class="benefit-content">
          <strong>Tailored Recommendations</strong>
          <span>Based on your occasion, budget and team size.</span>
        </div>
      </div>
      <div class="benefit-divider"></div>
      <div class="planner-benefit">
        <i class="bi bi-clock"></i>
        <div class="benefit-content">
          <strong>Save Time</strong>
          <span>Ready-to-use templates for common occasions.</span>
        </div>
      </div>
      <div class="benefit-divider"></div>
      <div class="planner-benefit">
        <i class="bi bi-heart"></i>
        <div class="benefit-content">
          <strong>Better Relationships</strong>
          <span>Thoughtful gifts create stronger connections.</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- BOTTOM CTA -->
<section class="planner-bottom-cta">
  <div class="container-fluid px-4 px-xl-5">
    <button class="btn-start-planning">Start Planning Your Gifts <i class="bi bi-arrow-right"></i></button>
    <p class="cta-text">Need help? Talk to our corporate gifting experts.</p>
  </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('web_frontend/assets/js/budget-planner.js') }}"></script>
@endsection
