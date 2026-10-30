  <!-- TOP HEADER -->
  <header class="site-header">
    <div class="container-fluid px-4 px-xl-5">
      <div class="header-main">
        <a href="#" class="brand" aria-label="More Than Giftz">
          <span>more</span>
          <span>than</span>
          <span>giftz</span>
        </a>

        <nav class="primary-nav">
          <a href="#">Best Sellers</a>
          <a href="#">Premium Gifts Set</a>
          <a href="#">Technology</a>
          <a href="#">Office &amp; Business</a>
          <a href="#">Sustainable</a>
          <a href="#">Bags</a>
          <a href="#">Drinkware</a>
          <a href="#">Apparel</a>
        </nav>

        <div class="header-actions">
          <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search corporate gifts..." aria-label="Search">
          </div>
          @if(session()->has('userid'))
            <a href="{{ route('corporate.portal') }}" class="icon-button" aria-label="Dashboard" title="Dashboard"><i class="bi bi-person-circle"></i></a>
            <a href="{{ route('corporate.logout') }}" class="icon-button" aria-label="Logout" title="Logout" style="color: #dc3545;"><i class="bi bi-box-arrow-right"></i></a>
          @else
            <a href="{{ route('corporate.login') }}" class="icon-button" aria-label="Login" title="Login"><i class="bi bi-person"></i></a>
          @endif
          <button class="icon-button cart-button" aria-label="Cart" onclick="window.location.href='/cart'">
            <i class="bi bi-cart3"></i>
            <span class="cart-count" id="cartCount">{{ $cartItemCount ?? 0 }}</span>
          </button>
          <a href="tel:+971557863450" class="phone-button">
            <i class="bi bi-telephone"></i>
            +971 55 786 3450
          </a>
        </div>
      </div>
    </div>

    <!-- SECONDARY NAV -->
    <div class="secondary-bar">
      <div class="container-fluid px-4 px-xl-5">
        <nav class="secondary-nav">
          <div class="secondary-left">
            <a href="#">About us</a>
            <a href="#">Branding</a>
            <a href="/annual-corporate-membership" class="{{ request()->is('annual-corporate-membership') ? 'active' : '' }}">Annual Contract</a>
            <a href="/track-order" class="{{ request()->is('track-order') ? 'active' : '' }}">Track your Order</a>
          </div>
          <div class="secondary-right">
            <a href="#">For Businesses</a>
            <span class="divider"></span>
            <a href="#">Support</a>
            <span class="divider"></span>
            <a href="#">Request a Quote</a>
          </div>
        </nav>
      </div>
    </div>
  </header>
