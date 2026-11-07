@extends('web_frontend.layouts.app')

@section('title', 'Corporate Gifting Budget Planner | More Than Giftz')
@section('meta')
  <meta name="description" content="Plan your corporate gifting budget with our smart planner. Get tailored recommendations based on occasion, team size and budget.">
@endsection
@section('styles')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('web_frontend/assets/css/budget-planner.css') }}?v={{ filemtime(public_path('web_frontend/assets/css/budget-planner.css')) }}">
@endsection

@section('content')
<div class="bp" id="budgetPlanner" data-url="{{ route('planner.recommend') }}" data-image-url="{{ route('planner.image') }}" data-cart-url="{{ route('cart.add-bundle') }}" data-logged-in="{{ session()->has('userid') ? '1' : '0' }}" data-login-url="{{ route('corporate.login', ['redirect' => route('planner.index')]) }}" data-default-team="{{ $planner['defaults']['team'] }}" data-default-budget="{{ $planner['defaults']['budget'] }}" data-default-team-label="{{ $planner['team_sizes'][$planner['defaults']['team']]['label'] }}">

  <section class="bp-hero">
    <div class="bp-hero-text">
      <span class="bp-eyebrow">PLAN SMART. GIFT BETTER.</span>
      <h1>Corporate Gifting<br>Budget Planner</h1>
      <p>Tell us the occasion, team size and budget — we'll suggest the best gifting options tailored to your needs.</p>
      <div class="bp-hero-benefits">
        <div class="bp-hero-benefit"><i class="bi bi-gift"></i><span>Curated for<br>your occasion</span></div>
        <div class="bp-hero-benefit"><i class="bi bi-gear"></i><span>Best value<br>suggestions</span></div>
        <div class="bp-hero-benefit"><i class="bi bi-truck"></i><span>Save time<br>&amp; effort</span></div>
      </div>
    </div>
    <div class="bp-hero-image">
      <img src="{{ asset('web_frontend/assets/images/planner/budget-planner-banner.jpg') }}" alt="Branded mug, gift box, pen, notebook and thank-you card">
    </div>
  </section>

  <section class="bp-occasions bp-wrap" id="plannerStart">
    <h2 class="bp-step-title">1. Select the Occasion</h2>
    <div class="bp-occasion-list">
      @foreach($planner['occasions'] as $key => $occasion)
      <button type="button" class="bp-occasion {{ $loop->first ? 'active' : '' }}" data-occasion="{{ $key }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
        @if(!empty($occasion['image']))
          <img src="{{ asset('web_frontend/assets/images/planner/' . $occasion['image']) }}" alt="">
        @else
          <span class="bp-occasion-dots"><i class="bi {{ $occasion['icon'] ?? 'bi-three-dots' }}"></i></span>
        @endif
        <span class="bp-occasion-label">{{ $occasion['label'] }}</span>
        @if($key === 'other')<i class="bi bi-pencil bp-occasion-edit" id="otherEdit" aria-hidden="true" hidden></i>@endif
      </button>
      @endforeach
    </div>
  </section>

  <section class="bp-config bp-wrap">
    <div class="bp-config-col bp-config-team">
      <h2 class="bp-step-title"><label for="teamSize">2. Team Size</label></h2>
      <div class="bp-select-wrap">
        <i class="bi bi-people"></i>
        <select class="bp-select" id="teamSize">
          <option value="">Select team size</option>
          @foreach($planner['team_sizes'] as $key => $team)
          <option value="{{ $key }}">{{ $team['label'] }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="bp-config-col bp-config-budget">
      <h2 class="bp-step-title"><label for="budgetRange">3. Budget per Person (AED)</label></h2>
      <div class="bp-select-wrap">
        <i class="bi bi-database"></i>
        <select class="bp-select" id="budgetRange">
          <option value="">Select budget range</option>
          @foreach($planner['budgets'] as $key => $budget)
          <option value="{{ $key }}">{{ $budget['label'] }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="bp-config-prefs">
      <h2 class="bp-step-title">4. Add Preferences <small>(Optional)</small></h2>
      <div class="bp-chips">
        @foreach($planner['preferences'] as $key => $preference)
        <button type="button" class="bp-chip" data-pref="{{ $key }}" aria-pressed="false">
          @if($key === 'eco')
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 4c-9 0-15 3-15 10 0 3 2 6 6 6 7 0 9-7 9-16z"/><path d="M4 21c2-5 6-9 11-11"/></svg>
          @else
            <i class="bi {{ $preference['icon'] }}"></i>
          @endif
          <span>{{ $preference['label'] }}</span>
        </button>
        @endforeach
      </div>
    </div>
    <div class="bp-config-action">
      <button type="button" class="bp-btn bp-show" id="showRecommendations">Show Recommendations <i class="bi bi-arrow-right"></i></button>
      <p class="bp-error" id="plannerError" role="alert" hidden></p>
    </div>
  </section>

  <section class="bp-results bp-wrap" id="recommendations">
    <div class="bp-welcome" id="plannerWelcome">
      <div class="bp-welcome-head">
        <span class="bp-eyebrow">A TASTE OF WHAT WE CREATE</span>
        <h2>Your perfect gifts are just a few choices away</h2>
        <p>Pick the occasion, your team size and budget above, then tap <strong>Show Recommendations</strong>. We'll hand-pick bundles like these, pictured with your own logo.</p>
      </div>
      <div class="bp-examples">
        @foreach($planner['examples'] as $example)
        <figure class="bp-example">
          <div class="bp-example-image">
            <img src="{{ asset('web_frontend/assets/images/planner/examples/' . $example['image']) }}" alt="Example {{ $planner['occasions'][$example['occasion']]['label'] }} gift bundle: {{ $example['items'] }}" loading="lazy" width="800" height="800">
            <span class="bp-example-tag">{{ $planner['occasions'][$example['occasion']]['label'] }}</span>
          </div>
          <figcaption>
            <strong>{{ $example['title'] }}</strong>
            <span>{{ $example['items'] }}</span>
            <button type="button" class="bp-example-try" data-occasion="{{ $example['occasion'] }}">Plan for {{ $planner['occasions'][$example['occasion']]['label'] }} <i class="bi bi-arrow-right"></i></button>
          </figcaption>
        </figure>
        @endforeach
      </div>
      <p class="bp-examples-note">Example bundles for illustration. Your recommendations use real products from our catalogue and your budget.</p>
    </div>
    <div class="bp-results-head" id="resultsHead" hidden>
      <div>
        <h2>Recommended Bundles for <span id="selectedOccasion">{{ reset($planner['occasions'])['label'] }}</span></h2>
        <p class="bp-results-sub" id="plannerSubtitle">Curated options based on your budget, team size and occasion.</p>
      </div>
      <button type="button" class="bp-btn bp-more" id="viewMore">View More Options <i class="bi bi-arrow-right"></i></button>
    </div>
    <div class="bp-grid" id="bundlesGrid" aria-live="polite"></div>
    <div class="bp-empty" id="plannerEmpty" hidden>
      <i class="bi bi-search"></i>
      <h3>No ready-made bundle fits these choices yet</h3>
      <p>Try a higher budget or a different team size, or tell us what you need and our team will put a bundle together for you.</p>
      <a href="{{ url('contactus') }}" class="bp-btn">Talk to our team</a>
    </div>
  </section>

  <section class="bp-benefits bp-wrap">
    <div class="bp-benefit"><i class="bi bi-gift"></i><div><strong>Multiple Options</strong><span>Get 2–3 curated bundles, not just one.</span></div></div>
    <div class="bp-benefit"><i class="bi bi-sliders"></i><div><strong>Tailored Recommendations</strong><span>Based on your occasion, budget and team size.</span></div></div>
    <div class="bp-benefit"><i class="bi bi-clock"></i><div><strong>Save Time</strong><span>Ready-to-use templates for common occasions.</span></div></div>
    <div class="bp-benefit"><i class="bi bi-heart"></i><div><strong>Better Relationships</strong><span>Thoughtful gifts create stronger connections.</span></div></div>
  </section>

  <section class="bp-cta bp-wrap">
    <a href="#plannerStart" class="bp-btn">Start Planning Your Gifts <i class="bi bi-arrow-right"></i></a>
    <p><a href="{{ url('contactus') }}">Need help? Talk to our corporate gifting experts.</a></p>
  </section>

  <div class="bp-modal" id="otherModal" role="dialog" aria-modal="true" aria-labelledby="otherTitle" hidden>
    <form class="bp-modal-box" id="otherForm" novalidate>
      <button type="button" class="bp-modal-close" id="otherClose" aria-label="Close"><i class="bi bi-x-lg"></i></button>
      <h2 id="otherTitle">What's the occasion?</h2>
      <p class="bp-modal-sub">Tell us what you're gifting for and we'll suggest the best bundles.</p>
      <label class="bp-modal-label" for="otherInput">Your occasion</label>
      <input type="text" id="otherInput" class="bp-modal-input" maxlength="40" autocomplete="off" placeholder="e.g. Team Offsite, Retirement">
      <div class="bp-modal-meta">
        <span class="bp-error" id="otherError" role="alert" hidden></span>
        <span class="bp-modal-count"><span id="otherCount">0</span>/40</span>
      </div>
      <p class="bp-modal-label">Popular choices</p>
      <div class="bp-modal-picks">
        @foreach($planner['other_suggestions'] as $suggestion)
        <button type="button" class="bp-modal-pick" data-pick="{{ $suggestion }}">{{ $suggestion }}</button>
        @endforeach
      </div>
      <div class="bp-modal-actions">
        <button type="button" class="bp-btn bp-modal-cancel" id="otherCancel">Cancel</button>
        <button type="submit" class="bp-btn" id="otherContinue" disabled>Continue</button>
      </div>
    </form>
  </div>

  <div class="bp-modal" id="brandModal" role="dialog" aria-modal="true" aria-labelledby="brandTitle" hidden data-store-url="{{ route('planner.brand.store') }}" data-remove-url="{{ route('planner.brand.remove') }}" data-max-assets="{{ \App\Services\PlannerBrandFiles::MAX_ASSETS }}" data-brand='@json($brand)'>
    <div class="bp-modal-box">
      <button type="button" class="bp-modal-close" id="brandClose" aria-label="Close"><i class="bi bi-x-lg"></i></button>
      <h2 id="brandTitle">Make the bundles yours</h2>
      <p class="bp-modal-sub">Add your company logo and we'll show it on the gifts in the bundle pictures. Both are optional.</p>

      <p class="bp-modal-label">Company logo</p>
      <div class="bp-brand-row" id="brandLogo"></div>
      <label class="bp-brand-add" id="brandLogoAdd">
        <input type="file" id="brandLogoInput" accept="image/png,image/jpeg,image/webp" hidden>
        <i class="bi bi-upload"></i> <span>Upload logo</span>
      </label>

      <p class="bp-modal-label">Brand assets <small>(up to {{ \App\Services\PlannerBrandFiles::MAX_ASSETS }}: packaging, brand pattern, colours)</small></p>
      <div class="bp-brand-row" id="brandAssets"></div>
      <label class="bp-brand-add" id="brandAssetAdd">
        <input type="file" id="brandAssetInput" accept="image/png,image/jpeg,image/webp" multiple hidden>
        <i class="bi bi-plus-lg"></i> <span>Add brand asset</span>
      </label>

      <p class="bp-brand-note">JPG, PNG or WEBP, up to 5 MB each. Used only to create your bundle pictures, and kept for your next visit until you remove them.</p>
      <p class="bp-error" id="brandError" role="alert" hidden></p>
      <div class="bp-modal-actions">
        <button type="button" class="bp-btn bp-modal-cancel" id="brandSkip">Skip</button>
        <button type="button" class="bp-btn" id="brandContinue">Show Recommendations <i class="bi bi-arrow-right"></i></button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('web_frontend/assets/js/corporate-gifting-planner.js') }}?v={{ filemtime(public_path('web_frontend/assets/js/corporate-gifting-planner.js')) }}"></script>
@endsection
