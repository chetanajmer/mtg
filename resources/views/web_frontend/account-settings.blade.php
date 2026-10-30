@extends('web_frontend.layouts.app')

@section('title', 'Account Settings | More Than Giftz')
@section('meta')
  <meta name="description" content="Update your account details.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/addresses.css') }}">
@endsection

@section('content')
@php
  $user = $user ?? null;
  $userName = session()->get('fullname') ?? session()->get('username') ?? 'User';
@endphp

<div class="dashboard-wrapper">

<aside class="sidebar">
  <button class="company"><i class="bi bi-buildings"></i><span><strong>{{ $userName }}</strong><small>Switch Company <i class="bi bi-arrow-right"></i></small></span></button>
  <nav>
    <a href="{{ route('corporate.portal') }}"><i class="bi bi-gift"></i>Dashboard</a>
    <a href="/orders"><i class="bi bi-cart3"></i>Orders</a>
    <a href="/corporate-addresses"><i class="bi bi-geo-alt"></i>Addresses</a>
    <a href="#branding"><i class="bi bi-image"></i>Branding Assets</a>
    <a href="/collections-all"><i class="bi bi-bookmark"></i>Saved Collections</a>
    <a href="#employees"><i class="bi bi-people-fill"></i>Employees</a>
    <a href="#reports"><i class="bi bi-bar-chart-line"></i>Reports</a>
    <a class="active" href="/corporate-account-settings"><i class="bi bi-gear"></i>Account Settings</a>
    <a href="{{ route('corporate.logout') }}" style="color: #dc3545;"><i class="bi bi-box-arrow-right"></i>Logout</a>
  </nav>
  <div class="help"><i class="bi bi-headset"></i><div><strong>Need Help?</strong><p>Your dedicated account manager is here.</p></div><button class="outline full" data-toast="Contact request started.">Contact Us <i class="bi bi-arrow-right"></i></button></div>
</aside>

<main class="content">
  <section class="hero" style="min-height: 200px;">
    <div style="width: 100%; text-align: center;">
      <h1>Account Settings</h1>
      <p>Update your profile and contact details.</p>
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
        <h3><i class="bi bi-person-gear"></i> Profile Details</h3>
      </div>
      <form action="{{ route('corporate.account-settings.update') }}" method="POST" style="padding: 20px;">
        @csrf
        <div class="settings-grid">
          <div class="form-group">
            <label for="fname">First Name *</label>
            <input type="text" id="fname" name="fname" value="{{ old('fname', $user->fname ?? '') }}" required>
          </div>
          <div class="form-group">
            <label for="lname">Last Name</label>
            <input type="text" id="lname" name="lname" value="{{ old('lname', $user->lname ?? '') }}">
          </div>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" value="{{ $user->email ?? '' }}" disabled style="background: #f3f4f6; color: #6b7280; cursor: not-allowed;">
            <small style="color: #9ca3af; font-size: 12px; margin-top: 5px; display: block;">Email cannot be changed</small>
          </div>
          <div class="form-group">
            <label for="mobile">Mobile</label>
            <input type="text" id="mobile" name="mobile" value="{{ old('mobile', $user->mobile ?? '') }}">
          </div>
          <div class="form-group">
            <label for="company">Company</label>
            <input type="text" id="company" name="company" value="{{ old('company', $user->company ?? '') }}">
          </div>
          <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" value="{{ old('location', $user->location ?? '') }}">
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" value="{{ old('address', $user->address ?? '') }}">
          </div>
          <div class="form-group">
            <label for="flatno">Flat / Unit No.</label>
            <input type="text" id="flatno" name="flatno" value="{{ old('flatno', $user->flatno ?? '') }}">
          </div>
          <div class="form-group">
            <label for="city">City</label>
            <input type="text" id="city" name="city" value="{{ old('city', $user->city ?? '') }}">
          </div>
          <div class="form-group">
            <label for="region">Region / State</label>
            <input type="text" id="region" name="region" value="{{ old('region', $user->region ?? '') }}">
          </div>
          <div class="form-group">
            <label for="pincode">Pincode</label>
            <input type="text" id="pincode" name="pincode" value="{{ old('pincode', $user->pincode ?? '') }}">
          </div>
          <div class="form-group">
            <label for="country">Country</label>
            <input type="text" id="country" name="country" value="{{ old('country', $user->country ?? '') }}">
          </div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </section>
</main>
</div>
@endsection

@section('scripts')
<style>
.settings-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 0 24px;
}
</style>
@endsection
