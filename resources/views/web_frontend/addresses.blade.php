@extends('web_frontend.layouts.app')

@section('title', 'My Addresses | More Than Giftz')
@section('meta')
  <meta name="description" content="Manage your shipping addresses for a faster checkout experience.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/addresses.css') }}">
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/account-settings.css') }}">
@endsection

@section('content')
@php
  $addresses = $addresses ?? collect();
@endphp

<div class="mtg-account-page">
  <div class="container-fluid px-4 px-xl-5">

    <nav class="account-breadcrumb" aria-label="Breadcrumb">
      <a href="/">Home</a>
      <i class="bi bi-chevron-right"></i>
      <a href="{{ route('corporate.portal') }}">My Account</a>
      <i class="bi bi-chevron-right"></i>
      <span>My Addresses</span>
    </nav>

    <div class="account-layout">

      @include('web_frontend.partials.corporate-sidebar', ['activePage' => 'addresses'])

      <main class="account-main">

        <div class="addresses-head">
          <div>
            <h1>My Addresses</h1>
            <p>Manage your shipping addresses for a faster checkout experience.</p>
          </div>
          <button type="button" class="btn-gold" id="btnAddNew"><i class="bi bi-plus-lg"></i> Add New Address</button>
        </div>

        <div id="addressList" class="address-list">
          @forelse($addresses as $addr)
            @include('web_frontend.partials.address-card', ['addr' => $addr])
          @empty
            <div class="address-empty" id="addressEmpty">
              <div class="address-empty-icon"><i class="bi bi-geo-alt"></i></div>
              <h3>No saved addresses yet</h3>
              <p>Add your first delivery address using the form below to speed up checkout.</p>
            </div>
          @endforelse
        </div>

        <section class="address-form-card" id="addressFormCard">
          <div class="address-form-head">
            <span class="form-head-icon"><i class="bi bi-plus-lg"></i></span>
            <div>
              <h2 id="formTitle">Add New Address</h2>
              <p id="formSubtitle">Enter your address details to add a new delivery address.</p>
            </div>
          </div>

          <form id="addressForm" action="{{ route('corporate.addresses.store') }}" method="POST" novalidate>
            @csrf
            <input type="hidden" name="address_id" id="address_id" value="">

            <div class="form-grid">
              <div class="form-field">
                <label for="f_name">Full Name <span>*</span></label>
                <input type="text" id="f_name" name="name" placeholder="Enter your name" maxlength="255" required>
                <small class="field-error" data-error-for="name"></small>
              </div>
              <div class="form-field">
                <label for="f_mobile">Mobile Number <span>*</span></label>
                <input type="tel" id="f_mobile" name="mobile" placeholder="Enter mobile number" maxlength="20" required>
                <small class="field-error" data-error-for="mobile"></small>
              </div>
              <div class="form-field">
                <label for="f_label">Address Type <span>*</span></label>
                <div class="select-wrap">
                  <select id="f_label" name="label" required>
                    <option value="Home">Home</option>
                    <option value="Office">Office</option>
                    <option value="Other">Other</option>
                  </select>
                  <i class="bi bi-chevron-down"></i>
                </div>
                <small class="field-error" data-error-for="label"></small>
              </div>

              <div class="form-field">
                <label for="f_address">Address <span>*</span></label>
                <input type="text" id="f_address" name="address" placeholder="House No., Street, Area" maxlength="500" required>
                <small class="field-error" data-error-for="address"></small>
              </div>
              <div class="form-field">
                <label for="f_city">City <span>*</span></label>
                <input type="text" id="f_city" name="city" placeholder="Enter city" maxlength="100" required>
                <small class="field-error" data-error-for="city"></small>
              </div>
              <div class="form-field">
                <label for="f_state">State <span>*</span></label>
                <input type="text" id="f_state" name="state" placeholder="Enter state" maxlength="100" required>
                <small class="field-error" data-error-for="state"></small>
              </div>

              <div class="form-field">
                <label for="f_pincode">Pincode <span>*</span></label>
                <input type="text" id="f_pincode" name="pincode" placeholder="Enter pincode" maxlength="20" required>
                <small class="field-error" data-error-for="pincode"></small>
              </div>
            </div>

            <label class="form-check-row" for="f_is_default">
              <input type="checkbox" id="f_is_default" name="is_default" value="1" {{ $addresses->isEmpty() ? 'checked' : '' }}>
              <span>Make this my primary address</span>
            </label>

            <div class="form-actions">
              <button type="button" class="btn-outline-gold" id="btnCancel">Cancel</button>
              <button type="submit" class="btn-gold" id="btnSave"><span id="btnSaveText">Save Address</span> <i class="bi bi-arrow-right"></i></button>
            </div>
          </form>
        </section>

      </main>
    </div>
  </div>
</div>

<!-- Delete confirmation -->
<div class="mtg-modal" id="deleteModal" aria-hidden="true">
  <div class="mtg-modal-box" role="dialog" aria-labelledby="deleteTitle">
    <div class="mtg-modal-icon"><i class="bi bi-trash"></i></div>
    <h3 id="deleteTitle">Delete this address?</h3>
    <p>This action cannot be undone. The address will be permanently removed from your account.</p>
    <div class="mtg-modal-actions">
      <button type="button" class="btn-outline-gold" id="deleteCancel">Cancel</button>
      <button type="button" class="btn-danger-solid" id="deleteConfirm">Delete</button>
    </div>
  </div>
</div>

<div class="mtg-toast-wrap" id="toastWrap"></div>
@endsection

@section('scripts')
<script>
  window.MTG_ADDRESS_ROUTES = {
    store:  "{{ route('corporate.addresses.store') }}",
    update: "{{ route('corporate.addresses.update-single', ['id' => '__ID__']) }}",
    select: "{{ route('corporate.addresses.select', ['id' => '__ID__']) }}",
    delete: "{{ route('corporate.addresses.delete', ['id' => '__ID__']) }}"
  };
  window.MTG_FLASH = @json(session('success'));
</script>
<script src="{{ asset('web_frontend/assets/js/addresses.js') }}"></script>
@endsection
