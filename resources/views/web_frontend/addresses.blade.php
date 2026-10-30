@extends('web_frontend.layouts.app')

@section('title', 'Addresses | More Than Giftz')
@section('meta')
  <meta name="description" content="Manage your addresses for corporate gifting.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/addresses.css') }}">
@endsection

@section('content')
@php
  $user = $user ?? null;
  $userName = session()->get('fullname') ?? session()->get('username') ?? 'User';
  $userLocation = $user->location ?? 'Not specified';
@endphp

<div class="dashboard-wrapper">

<aside class="sidebar">
  <button class="company"><i class="bi bi-buildings"></i><span><strong>{{ $userName }}</strong><small>Switch Company <i class="bi bi-arrow-right"></i></small></span></button>
  <nav>
    <a href="{{ route('corporate.portal') }}"><i class="bi bi-gift"></i>Dashboard</a>
    <a href="/orders"><i class="bi bi-cart3"></i>Orders</a>
    <a class="active" href="/corporate-addresses"><i class="bi bi-geo-alt"></i>Addresses</a>
    <a href="#branding"><i class="bi bi-image"></i>Branding Assets</a>
    <a href="/collections-all"><i class="bi bi-bookmark"></i>Saved Collections</a>
    <a href="#employees"><i class="bi bi-people-fill"></i>Employees</a>
    <a href="#reports"><i class="bi bi-bar-chart-line"></i>Reports</a>
    <a href="/corporate-account-settings"><i class="bi bi-gear"></i>Account Settings</a>
    <a href="{{ route('corporate.logout') }}" style="color: #dc3545;"><i class="bi bi-box-arrow-right"></i>Logout</a>
  </nav>
  <div class="help"><i class="bi bi-headset"></i><div><strong>Need Help?</strong><p>Your dedicated account manager is here.</p></div><button class="outline full" data-toast="Contact request started.">Contact Us <i class="bi bi-arrow-right"></i></button></div>
</aside>

<main class="content">
  <section class="hero" style="min-height: 200px;">
    <div style="width: 100%; text-align: center;">
      <h1>Addresses</h1>
      <p>Manage your delivery addresses for corporate gifting.</p>
    </div>
  </section>

  <section class="addresses-section">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="address-card">
      <div class="address-header">
        <h3><i class="bi bi-geo-alt-fill"></i> Primary Address</h3>
        <span class="badge primary">Default</span>
      </div>
      <div class="address-body">
        <div class="address-item">
          <label>Full Name:</label>
          <span>{{ $user->fname ?? 'N/A' }} {{ $user->lname ?? '' }}</span>
        </div>
        <div class="address-item">
          <label>Email:</label>
          <span>{{ $user->email ?? 'N/A' }}</span>
        </div>
        <div class="address-item">
          <label>Mobile:</label>
          <span>{{ $user->mobile ?? 'Not specified' }}</span>
        </div>
        <div class="address-item">
          <label>Location:</label>
          <span>{{ $userLocation }}</span>
        </div>
        @if($user->address)
        <div class="address-item">
          <label>Address:</label>
          <span>{{ $user->address }}</span>
        </div>
        @endif
        @if($user->city)
        <div class="address-item">
          <label>City:</label>
          <span>{{ $user->city }}</span>
        </div>
        @endif
        @if($user->country)
        <div class="address-item">
          <label>Country:</label>
          <span>{{ $user->country }}</span>
        </div>
        @endif
      </div>
      <div class="address-actions">
        <button class="btn btn-primary" onclick="showAddModal()">
          <i class="bi bi-plus-lg"></i> Add New Address
        </button>
        <button class="btn btn-outline" onclick="showEditModal()">
          <i class="bi bi-pencil"></i> Edit Address
        </button>
      </div>
    </div>

    <h3 class="saved-heading"><i class="bi bi-geo-alt"></i> Saved Addresses</h3>
    <div class="addresses-grid">
      @forelse($addresses ?? collect() as $addr)
        <div class="address-card saved-card {{ $addr->is_default ? 'selected' : '' }}">
          @if($addr->is_default)
          <div class="address-header" style="justify-content: flex-end;">
            <span class="badge primary">Selected</span>
          </div>
          @endif
          <div class="address-body">
            @if($addr->name)
            <div class="address-item">
              <label>Contact:</label>
              <span>{{ $addr->name }}</span>
            </div>
            @endif
            @if($addr->mobile)
            <div class="address-item">
              <label>Mobile:</label>
              <span>{{ $addr->mobile }}</span>
            </div>
            @endif
            <div class="address-item">
              <label>Location:</label>
              <span>{{ $addr->location }}</span>
            </div>
            @if($addr->address)
            <div class="address-item">
              <label>Address:</label>
              <span>{{ $addr->address }}</span>
            </div>
            @endif
            @if($addr->city || $addr->country)
            <div class="address-item">
              <label>City/Country:</label>
              <span>{{ $addr->city }}{{ $addr->city && $addr->country ? ', ' : '' }}{{ $addr->country }}</span>
            </div>
            @endif
          </div>
          <div class="address-actions select-actions">
            <form action="{{ route('corporate.addresses.select', $addr->id) }}" method="POST">
              @csrf
              <label class="select-label">
                <input type="radio" name="selected_address_{{ $addr->id }}"
                  {{ $addr->is_default ? 'checked disabled' : 'onchange=this.form.submit()' }}>
                {{ $addr->is_default ? 'Default address' : 'Select this address' }}
              </label>
            </form>
            <form action="{{ route('corporate.addresses.delete', $addr->id) }}" method="POST"
              onsubmit="return confirm('Delete this address?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-icon-danger" title="Delete address"><i class="bi bi-trash"></i></button>
            </form>
          </div>
        </div>
      @empty
        <p class="no-addresses">No saved addresses yet. Add your first address below.</p>
      @endforelse

      <button type="button" class="add-address-card" onclick="showAddModal()">
        <i class="bi bi-plus-lg"></i><span>Add New<br>Address</span>
      </button>
    </div>
  </section>
</main>
</div>

<!-- Edit Address Modal -->
<div id="editAddressModal" class="modal" style="display: none;">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Edit Address</h2>
      <button class="close-btn" onclick="hideEditModal()">&times;</button>
    </div>
    <div class="modal-body">
      <form action="{{ route('corporate.addresses.update') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="location">Location *</label>
          <input type="text" id="location" name="location" value="{{ $user->location ?? '' }}" required>
        </div>
        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" id="address" name="address" value="{{ $user->address ?? '' }}">
        </div>
        <div class="form-group">
          <label for="city">City</label>
          <input type="text" id="city" name="city" value="{{ $user->city ?? '' }}">
        </div>
        <div class="form-group">
          <label for="country">Country</label>
          <input type="text" id="country" name="country" value="{{ $user->country ?? '' }}">
        </div>
        <div class="form-actions">
          <button type="button" class="btn btn-outline" onclick="hideEditModal()">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Address Modal -->
<div id="addAddressModal" class="modal" style="display: none;">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Add New Address</h2>
      <button class="close-btn" onclick="hideAddModal()">&times;</button>
    </div>
    <div class="modal-body">
      <form action="{{ route('corporate.addresses.store') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="add_label">Label *</label>
          <input type="text" id="add_label" name="label" placeholder="e.g. Office, Warehouse, Home" required>
        </div>
        <div class="form-group">
          <label for="add_name">Contact Person</label>
          <input type="text" id="add_name" name="name" placeholder="Receiver name">
        </div>
        <div class="form-group">
          <label for="add_mobile">Mobile</label>
          <input type="text" id="add_mobile" name="mobile" placeholder="Contact number">
        </div>
        <div class="form-group">
          <label for="add_location">Location *</label>
          <input type="text" id="add_location" name="location" required>
        </div>
        <div class="form-group">
          <label for="add_address">Address</label>
          <input type="text" id="add_address" name="address">
        </div>
        <div class="form-group">
          <label for="add_city">City</label>
          <input type="text" id="add_city" name="city">
        </div>
        <div class="form-group">
          <label for="add_country">Country</label>
          <input type="text" id="add_country" name="country">
        </div>
        <div class="form-group">
          <label class="checkbox-label" for="add_is_default">
            <input type="checkbox" id="add_is_default" name="is_default" value="1">
            Set as default address
          </label>
        </div>
        <div class="form-actions">
          <button type="button" class="btn btn-outline" onclick="hideAddModal()">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Address</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')

<script>
function showEditModal() {
  document.getElementById('editAddressModal').style.display = 'flex';
}

function hideEditModal() {
  document.getElementById('editAddressModal').style.display = 'none';
}

function showAddModal() {
  document.getElementById('addAddressModal').style.display = 'flex';
}

function hideAddModal() {
  document.getElementById('addAddressModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
  if (event.target == document.getElementById('editAddressModal')) {
    hideEditModal();
  }
  if (event.target == document.getElementById('addAddressModal')) {
    hideAddModal();
  }
}
</script>
@endsection
