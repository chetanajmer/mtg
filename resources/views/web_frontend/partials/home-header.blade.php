@php
  $phone = config('company.phone');
  $phoneDigits = preg_replace('/\D/', '', $phone);
@endphp
<header class="mh-header">
  <div class="mh-topbar">
    <a href="https://wa.me/{{ $phoneDigits }}" target="_blank" rel="noopener" class="mh-topbar-call"><i class="bi bi-whatsapp"></i> Call /Whatsapp {{ $phone }}</a>
    <div class="mh-topbar-right">
      <a href="{{ $ordersUrl }}"><i class="bi bi-geo-alt-fill"></i> Track Order</a>
      <span class="mh-topbar-divider"></span>
      <details class="mh-menu">
        <summary>AED <i class="bi bi-chevron-down"></i></summary>
        <div class="mh-menu-list"><span class="active">AED</span></div>
      </details>
    </div>
  </div>

  <div class="mh-main">
    <nav class="mh-links" aria-label="Company">
      <a href="{{ url('aboutus') }}">About us</a>
      <a href="{{ url('contactus') }}">Branding</a>
      <a href="/annual-corporate-membership">Annual Contract</a>
      <a href="{{ $ordersUrl }}" class="{{ request()->is('orders') ? 'active' : '' }}">Track your Order</a>
      <a href="{{ route('planner.index') }}" class="{{ request()->routeIs('planner.index') ? 'active' : '' }}">Budget Planner</a>
    </nav>
    <div class="mh-account">
      <a href="{{ session()->has('userid') ? route('corporate.portal') : route('corporate.login') }}" class="mh-account-link"><i class="bi bi-person"></i> Account</a>
      <a href="{{ route('cart.index') }}" class="mh-account-link mh-cart"><i class="bi bi-cart3"></i> Cart <span class="mh-cart-count" id="cartCount">{{ $cartItemCount ?? 0 }}</span></a>
      @if(count($supplyCountries ?? []))
      <div class="mh-supplies">
        <span>Supplies to:</span>
        <ul>
          @foreach($supplyCountries->take(8) as $country)
            <li><img src="{{ $country->flagUrl() }}" alt="{{ $country->name }}" title="{{ $country->name }}" width="17" height="17"></li>
          @endforeach
          @if($supplyCountries->count() > 8)
            <li class="mh-supplies-more" title="{{ $supplyCountries->slice(8)->pluck('name')->implode(', ') }}">+{{ $supplyCountries->count() - 8 }}</li>
          @endif
        </ul>
      </div>
      @endif
    </div>
  </div>

  <a href="{{ url('/') }}" class="mh-logo" aria-label="More Than Giftz home">
    <img src="{{ asset('web_frontend/assets/images/home/logo.png') }}" alt="More Than Giftz. It's an experience.">
  </a>

  <nav class="mh-nav" aria-label="Categories">
    <div class="mh-nav-side">
      <details class="mh-categories">
        <summary><i class="bi bi-list"></i> CATEGORIES</summary>
        <div class="mh-categories-list">
          @foreach($navCategories ?? [] as $category)
            <a href="{{ url('category/' . $category->slug) }}">{{ $category->catname }}</a>
          @endforeach
        </div>
      </details>
      <span class="mh-nav-divider"></span>
      @foreach(config('home_page.nav_left') as $link)
        <a href="{{ url($link['url']) }}">{{ $link['label'] }}</a>
      @endforeach
    </div>
    <div class="mh-nav-side mh-nav-right">
      @foreach(config('home_page.nav_right') as $link)
        <a href="{{ url($link['url']) }}">{{ $link['label'] }}</a>
      @endforeach
    </div>
  </nav>
</header>
