@php
  $userName = $userName ?? session()->get('fullname') ?? session()->get('username') ?? 'User';
  $activePage = $activePage ?? '';
@endphp

<aside class="account-sidebar">
  <div class="account-menu">
    <h2>My Account</h2>
    <nav>
      <a href="{{ route('corporate.portal') }}" class="{{ $activePage === 'dashboard' ? 'active' : '' }}"><i class="bi bi-box-seam"></i>Dashboard</a>
      <a href="/orders" class="{{ $activePage === 'orders' ? 'active' : '' }}"><i class="bi bi-cart3"></i>Orders</a>
      <a href="{{ route('corporate.addresses') }}" class="{{ $activePage === 'addresses' ? 'active' : '' }}"><i class="bi bi-geo-alt-fill"></i>Addresses</a>
      <a href="{{ route('branding-assets.index') }}" class="{{ $activePage === 'branding' ? 'active' : '' }}"><i class="bi bi-image"></i>Branding Assets</a>
      <a href="/collections-all" class="{{ $activePage === 'collections' ? 'active' : '' }}"><i class="bi bi-bookmark"></i>Saved Collections</a>
      <a href="#" class="{{ $activePage === 'employees' ? 'active' : '' }}"><i class="bi bi-people-fill"></i>Employees</a>
      <a href="{{ route('corporate.reports') }}" class="{{ $activePage === 'reports' ? 'active' : '' }}"><i class="bi bi-bar-chart-line"></i>Reports</a>
      <a href="{{ route('corporate.account-settings') }}" class="{{ $activePage === 'settings' ? 'active' : '' }}"><i class="bi bi-gear"></i>Account Settings</a>
      <a href="{{ route('corporate.logout') }}"><i class="bi bi-box-arrow-right"></i>Logout</a>
    </nav>
  </div>

  <div class="account-help">
    <div class="account-help-top">
      <div>
        <h3>Need Help?</h3>
        <p>Our team is here to help you with your orders.</p>
      </div>
      <i class="bi bi-headset"></i>
    </div>
    <a href="/contactus" class="btn-help">Contact Us</a>
  </div>
</aside>
