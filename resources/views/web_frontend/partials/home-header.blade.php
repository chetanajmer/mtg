@php
  // Header from the client's design (19 Nov): black top bar, white bar with links and WhatsApp, logo box, black product bar
  $whatsapp = config('company.whatsapp');
  $whatsappDigits = preg_replace('/\D/', '', $whatsapp);
@endphp
<header class="mh-header">
  <div class="mh-topbar">
    {{-- Language, currency and offers: admin Setup & Config > Header Top Bar --}}
    <div class="mh-topbar-left">
      @foreach($topbarItems ?? [] as $item)
        @if($item->type === 'offer')
          @if($item->link)<a href="{{ $item->link }}" class="mh-offer">{{ $item->text }}</a>@else<span class="mh-offer">{{ $item->text }}</span>@endif
        @else
          <details class="mh-menu">
            <summary>{{ $item->text }} <i class="bi bi-chevron-down"></i></summary>
            <div class="mh-menu-list"><span class="active">{{ $item->text }}</span></div>
          </details>
        @endif
      @endforeach
    </div>
    <div class="mh-topbar-right">
      <a href="{{ $ordersUrl }}"><i class="bi bi-geo-alt-fill"></i> Track Order</a>
      @if(count($supplyCountries ?? []))
      <div class="mh-supplies">
        <span>Supplies to:</span>
        <ul>
          @foreach($supplyCountries->take(10) as $country)
            <li><img src="{{ $country->flagUrl() }}" alt="{{ $country->name }}" title="{{ $country->name }}" width="18" height="18"></li>
          @endforeach
          @if($supplyCountries->count() > 10)
            <li class="mh-supplies-more" title="{{ $supplyCountries->slice(10)->pluck('name')->implode(', ') }}">+{{ $supplyCountries->count() - 10 }}</li>
          @endif
        </ul>
      </div>
      @endif
    </div>
  </div>

  <div class="mh-main">
    <nav class="mh-links" aria-label="Company">
      <a href="{{ url('aboutus') }}" class="{{ request()->is('aboutus') ? 'active' : '' }}">About us</a>
      <a href="{{ route('branding-assets.index') }}" class="{{ request()->is('branding-assets*') ? 'active' : '' }}">Branding</a>
      <a href="/annual-corporate-membership" class="{{ request()->is('annual-corporate-membership') ? 'active' : '' }}">Annual Contract</a>
      <a href="{{ $ordersUrl }}" class="{{ request()->is('orders') || request()->is('track-order*') ? 'active' : '' }}">Track your Order</a>
    </nav>
    <div class="mh-account">
      <a href="{{ session()->has('userid') ? route('corporate.portal') : route('corporate.login') }}" class="mh-account-link"><i class="bi bi-person"></i> Account</a>
      <a href="{{ route('cart.index') }}" class="mh-account-link mh-cart"><i class="bi bi-cart3"></i> Cart <span class="mh-cart-count" id="cartCount">{{ $cartItemCount ?? 0 }}</span></a>
      <a href="https://wa.me/{{ $whatsappDigits }}" target="_blank" rel="noopener" class="mh-whatsapp"><i class="bi bi-whatsapp"></i> {{ $whatsapp }}</a>
    </div>
  </div>

  <a href="{{ url('/') }}" class="mh-logo" aria-label="More Than Giftz home">
    <img src="{{ asset('web_frontend/assets/images/home/logo.png') }}" alt="More Than Giftz. It's an experience.">
  </a>

  <nav class="mh-nav" aria-label="Products">
    <div class="mh-nav-side">
      <details class="mh-categories">
        <summary><i class="bi bi-list"></i> CATEGORIES</summary>
        <div class="mh-categories-list">
          @foreach($navCategories ?? [] as $category)
            <a href="{{ url('category/' . $category->slug) }}">{{ $category->catname }}</a>
          @endforeach
        </div>
      </details>
      @foreach(config('home_page.nav_left') as $link)
        <a href="{{ url($link['url']) }}" class="{{ request()->fullUrlIs(url($link['url'])) ? 'active' : '' }}">{{ $link['label'] }}</a>
      @endforeach
    </div>
    <div class="mh-nav-side mh-nav-right">
      @foreach(config('home_page.nav_right') as $link)
        <a href="{{ url($link['url']) }}" class="{{ request()->fullUrlIs(url($link['url'])) ? 'active' : '' }}">{{ $link['label'] }}</a>
      @endforeach
    </div>
  </nav>
</header>
@include('web_frontend.partials.page-banner')
