@extends('web_frontend.layouts.app')

@section('title', 'Request a Quote | More Than Giftz')
@section('meta')
  <meta name="description" content="Tell us what you need and our team will prepare a custom corporate gifting quote for you.">
@endsection
@section('styles')
  <link rel="stylesheet" href="{{ asset('web_frontend/assets/css/custom-order.css') }}">
@endsection

@section('content')
<main class="co-page">
  <div class="co-head">
    <h1>Request a Quote</h1>
    <p>Looking for something custom? Tell us what you need and our team will reply with a price. You can follow the request from your Orders page.</p>
  </div>

  <form method="post" action="{{ route('custom-order.store') }}" class="co-card">
    @csrf
    @if($errors->any())
      <div class="co-alert" role="alert">{{ $errors->first() }}</div>
    @endif

    <div class="co-field">
      <label for="coTitle">What would you like? <span>*</span></label>
      <input type="text" id="coTitle" name="title" maxlength="150" required value="{{ old('title', $product) }}" placeholder="e.g. Leather notebook with our logo">
    </div>

    <div class="co-field">
      <label for="coDetails">Details <span>*</span></label>
      <textarea id="coDetails" name="details" rows="5" maxlength="3000" required placeholder="Colours, sizes, materials, packaging, the occasion, or anything else we should know.">{{ old('details') }}</textarea>
    </div>

    <div class="co-grid">
      <div class="co-field">
        <label for="coQuantity">Quantity <span>*</span></label>
        <input type="number" id="coQuantity" name="quantity" min="1" max="1000000" required value="{{ old('quantity') }}" placeholder="e.g. 250">
      </div>
      <div class="co-field">
        <label for="coBudget">Budget per piece (AED)</label>
        <input type="number" id="coBudget" name="budget" min="0" step="0.01" value="{{ old('budget') }}" placeholder="Optional">
      </div>
      <div class="co-field">
        <label for="coDate">Needed by</label>
        <input type="date" id="coDate" name="needed_by" min="{{ date('Y-m-d') }}" value="{{ old('needed_by') }}">
      </div>
      <div class="co-field">
        <label for="coBranding">Custom branding <span>*</span></label>
        <select id="coBranding" name="branding" required>
          @foreach($branding as $key => $label)
            <option value="{{ $key }}" {{ old('branding', 'yes') === $key ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="co-contact">
      <div>
        <strong>Sending as</strong>
        <p>{{ trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')) }}@if(!empty($user->company)) · {{ $user->company }}@endif<br>{{ $user->email ?? '' }}</p>
      </div>
      <div class="co-field">
        <label for="coPhone">Phone</label>
        <input type="tel" id="coPhone" name="phone" maxlength="30" value="{{ old('phone', $user->mobile ?? '') }}" placeholder="So we can call you back">
      </div>
    </div>

    <button type="submit" class="co-submit">Send Request <i class="bi bi-arrow-right"></i></button>
    <p class="co-note">There's no payment now. We'll confirm the price before anything is made.</p>
  </form>
</main>
@endsection
