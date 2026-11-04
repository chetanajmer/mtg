@extends('web_frontend.layouts.app')

@section('title', 'Account Settings | More Than Giftz')
@section('meta')
  <meta name="description" content="Update your account details.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/account-settings.css') }}">
@endsection

@section('content')
@php
  $user = $user ?? null;
@endphp

<div class="account-settings-page">
  <div class="container-fluid px-4 px-xl-5">

    <nav class="account-breadcrumb" aria-label="Breadcrumb">
      <a href="/">Home</a>
      <i class="bi bi-chevron-right"></i>
      <a href="{{ route('corporate.portal') }}">My Account</a>
      <i class="bi bi-chevron-right"></i>
      <span>Account Settings</span>
    </nav>

    <div class="account-layout">

      @include('web_frontend.partials.corporate-sidebar', ['activePage' => 'settings'])

      <main class="account-main">

        <div class="settings-head">
          <div>
            <h1>Account Settings</h1>
            <p>Update your profile and contact details.</p>
          </div>
        </div>

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

        <div class="profile-card">
          <div class="profile-card-header">
            <i class="bi bi-person-gear"></i>
            <h2>Profile Details</h2>
          </div>

          <form action="{{ route('corporate.account-settings.update') }}" method="POST">
            @csrf
            <div class="form-grid">
              <div class="form-field @error('fname') has-error @enderror">
                <label for="fname">First Name <span>*</span></label>
                <input type="text" id="fname" name="fname" value="{{ old('fname', $user->fname ?? '') }}" required>
                <small class="field-error">{{ $errors->first('fname') }}</small>
              </div>
              <div class="form-field @error('lname') has-error @enderror">
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" value="{{ old('lname', $user->lname ?? '') }}">
                <small class="field-error">{{ $errors->first('lname') }}</small>
              </div>
              <div class="form-field">
                <label for="email">Email Address</label>
                <input type="email" id="email" value="{{ $user->email ?? '' }}" disabled>
                <span class="field-helper">Email cannot be changed</span>
              </div>
              <div class="form-field @error('mobile') has-error @enderror">
                <label for="mobile">Mobile</label>
                <input type="text" id="mobile" name="mobile" value="{{ old('mobile', $user->mobile ?? '') }}">
                <small class="field-error">{{ $errors->first('mobile') }}</small>
              </div>
              <div class="form-field @error('company') has-error @enderror">
                <label for="company">Company</label>
                <input type="text" id="company" name="company" value="{{ old('company', $user->company ?? '') }}">
                <small class="field-error">{{ $errors->first('company') }}</small>
              </div>
              <div class="form-field @error('location') has-error @enderror">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" value="{{ old('location', $user->location ?? '') }}">
                <small class="field-error">{{ $errors->first('location') }}</small>
              </div>
              <div class="form-field @error('address') has-error @enderror">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address', $user->address ?? '') }}">
                <small class="field-error">{{ $errors->first('address') }}</small>
              </div>
              <div class="form-field @error('flatno') has-error @enderror">
                <label for="flatno">Flat / Unit No.</label>
                <input type="text" id="flatno" name="flatno" value="{{ old('flatno', $user->flatno ?? '') }}">
                <small class="field-error">{{ $errors->first('flatno') }}</small>
              </div>
              <div class="form-field @error('city') has-error @enderror">
                <label for="city">City</label>
                <input type="text" id="city" name="city" value="{{ old('city', $user->city ?? '') }}">
                <small class="field-error">{{ $errors->first('city') }}</small>
              </div>
              <div class="form-field @error('region') has-error @enderror">
                <label for="region">Region / State</label>
                <input type="text" id="region" name="region" value="{{ old('region', $user->region ?? '') }}">
                <small class="field-error">{{ $errors->first('region') }}</small>
              </div>
              <div class="form-field @error('pincode') has-error @enderror">
                <label for="pincode">Pincode</label>
                <input type="text" id="pincode" name="pincode" value="{{ old('pincode', $user->pincode ?? '') }}">
                <small class="field-error">{{ $errors->first('pincode') }}</small>
              </div>
              <div class="form-field @error('country') has-error @enderror">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" value="{{ old('country', $user->country ?? '') }}">
                <small class="field-error">{{ $errors->first('country') }}</small>
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-gold">Save Changes <i class="bi bi-arrow-right"></i></button>
            </div>
          </form>
        </div>

      </main>
    </div>
  </div>
</div>
@endsection

