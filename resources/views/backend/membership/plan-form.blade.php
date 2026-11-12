@extends('layouts.app')
@section('content')
@php
  $editing = $plan->exists;
  $features = old('features', $plan->features ?: []);
  if (!count($features)) { $features = [['label' => '', 'value' => '']]; }
@endphp
<div class="content-page">
  <div class="content">
    <div class="container">

      <div class="row">
        <div class="col-sm-12">
          <div class="btn-group pull-right m-t-15">
            <a href="{{ route('membership.index') }}" class="btn btn-default waves-effect"><i class="ti-arrow-left"></i> Back to Annual Membership</a>
          </div>
          <h4 class="page-title">{{ $editing ? 'Edit plan: ' . $plan->name : 'Add plan' }}</h4>
          <ol class="breadcrumb">
            <li><a href="{{ route('membership.index') }}">Annual Membership</a></li>
            <li class="active">{{ $editing ? 'Edit plan' : 'Add plan' }}</li>
          </ol>
        </div>
      </div>

      @if($errors->any())
        <div class="alert alert-danger">
          <strong>Please check the form:</strong>
          <ul class="m-b-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
      @endif

      <form method="POST" action="{{ $editing ? route('membership.plans.update', $plan->id) : route('membership.plans.store') }}">
        @csrf
        <div class="row">
          <div class="col-lg-5">
            <div class="card-box">
              <h4 class="header-title m-t-0 m-b-20">Plan card</h4>

              <div class="form-group">
                <label>Plan name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $plan->name) }}" class="form-control" maxlength="100" required placeholder="Gold">
              </div>
              <div class="form-group">
                <label>Tagline</label>
                <input type="text" name="tagline" value="{{ old('tagline', $plan->tagline) }}" class="form-control" maxlength="150" placeholder="For Established Businesses">
              </div>
              <div class="form-group">
                <label>Short description</label>
                <input type="text" name="description" value="{{ old('description', $plan->description) }}" class="form-control" maxlength="255" placeholder="Maximum value with premium benefits.">
              </div>
              <div class="form-group">
                <label>Medal style <span class="text-danger">*</span></label>
                <select name="medal" class="form-control">
                  @foreach(\App\Models\MembershipPlan::MEDALS as $value => $label)
                    <option value="{{ $value }}" {{ old('medal', $plan->medal) === $value ? 'selected' : '' }}>{{ $label }}</option>
                  @endforeach
                </select>
              </div>
              <div class="row">
                <div class="col-sm-7 form-group">
                  <label>Price <span class="text-danger">*</span></label>
                  <input type="text" name="price" value="{{ old('price', $plan->price) }}" class="form-control" maxlength="50" required placeholder="₹5,00,000">
                  <small class="text-muted">Shown exactly as typed, with the currency sign.</small>
                </div>
                <div class="col-sm-5 form-group">
                  <label>Per</label>
                  <input type="text" name="price_period" value="{{ old('price_period', $plan->price_period) }}" class="form-control" maxlength="30" placeholder="year">
                </div>
              </div>
              <div class="row">
                <div class="col-sm-7 form-group">
                  <label>Button text</label>
                  <input type="text" name="button_text" value="{{ old('button_text', $plan->button_text) }}" class="form-control" maxlength="50" placeholder="Select Plan">
                </div>
                <div class="col-sm-5 form-group">
                  <label>Order</label>
                  <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order) }}" class="form-control" min="0" max="999">
                  <small class="text-muted">Lower shows first.</small>
                </div>
              </div>

              <div class="checkbox checkbox-primary">
                <input type="checkbox" id="is_popular" name="is_popular" value="1" {{ old('is_popular', $plan->is_popular) ? 'checked' : '' }}>
                <label for="is_popular">Highlight as the popular plan <small class="text-muted">(only one plan can be highlighted)</small></label>
              </div>
              <div class="form-group" id="badgeGroup">
                <label>Badge text</label>
                <input type="text" name="badge_text" value="{{ old('badge_text', $plan->badge_text ?: 'MOST POPULAR') }}" class="form-control" maxlength="50">
              </div>
              <div class="checkbox checkbox-primary m-b-0">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
                <label for="is_active">Show this plan on the page</label>
              </div>
            </div>
          </div>

          <div class="col-lg-7">
            <div class="card-box">
              <h4 class="header-title m-t-0 m-b-10">Plan details (rows)</h4>
              <p class="text-muted">Each row shows a name on the left and a value on the right, e.g. <b>Estimated Savings</b> &middot; ₹75,000 (15%). Use <b>✓</b> for "included"; an empty value shows <b>–</b>. Up to 12 rows.</p>
              <table class="table m-b-10" id="featureRows">
                <thead><tr><th style="width:34px"></th><th style="width:45%">Name</th><th>Value</th><th style="width:44px"></th></tr></thead>
                <tbody>
                  @foreach($features as $i => $feature)
                    <tr>
                      <td class="text-muted" style="vertical-align:middle">
                        <button type="button" class="btn btn-xs btn-link js-up" title="Move up" style="padding:0 2px">&#9650;</button><br>
                        <button type="button" class="btn btn-xs btn-link js-down" title="Move down" style="padding:0 2px">&#9660;</button>
                      </td>
                      <td><input type="text" name="features[{{ $i }}][label]" value="{{ $feature['label'] ?? '' }}" class="form-control" maxlength="100" placeholder="Annual Spend"></td>
                      <td><input type="text" name="features[{{ $i }}][value]" value="{{ $feature['value'] ?? '' }}" class="form-control" maxlength="100" placeholder="₹5,00,000 / year"></td>
                      <td style="vertical-align:middle"><button type="button" class="btn btn-sm btn-danger js-remove-row" title="Remove">&times;</button></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <button type="button" class="btn btn-sm btn-default" id="addFeature"><i class="ti-plus"></i> Add row</button>
              <button type="button" class="btn btn-sm btn-default" id="tickValue" title="Puts ✓ in the value box you last clicked">Insert ✓</button>
            </div>
          </div>
        </div>

        <div class="m-b-30">
          <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="ti-save"></i> {{ $editing ? 'Save plan' : 'Add plan' }}</button>
          <a href="{{ route('membership.index') }}" class="btn btn-default m-l-5">Cancel</a>
        </div>
      </form>

    </div>
  </div>
</div>

@push('custom-scripts')
<script>
(function () {
  var body = document.querySelector('#featureRows tbody');
  var max = 12, lastValue = null;

  function renumber() {
    [].forEach.call(body.rows, function (row, i) {
      row.querySelectorAll('input').forEach(function (input) {
        input.name = input.name.replace(/features\[\d+\]/, 'features[' + i + ']');
      });
    });
    document.getElementById('addFeature').disabled = body.rows.length >= max;
  }

  document.getElementById('addFeature').addEventListener('click', function () {
    if (body.rows.length >= max) return;
    var row = body.rows[0].cloneNode(true);
    row.querySelectorAll('input').forEach(function (input) { input.value = ''; });
    body.appendChild(row);
    renumber();
    row.querySelector('input').focus();
  });

  body.addEventListener('click', function (e) {
    var row = e.target.closest('tr');
    if (e.target.closest('.js-remove-row')) {
      if (body.rows.length > 1) { row.remove(); } else { row.querySelectorAll('input').forEach(function (i) { i.value = ''; }); }
    } else if (e.target.closest('.js-up') && row.previousElementSibling) {
      body.insertBefore(row, row.previousElementSibling);
    } else if (e.target.closest('.js-down') && row.nextElementSibling) {
      body.insertBefore(row.nextElementSibling, row);
    }
    renumber();
  });

  body.addEventListener('focusin', function (e) {
    if (/\[value\]$/.test(e.target.name || '')) lastValue = e.target;
  });
  document.getElementById('tickValue').addEventListener('click', function () {
    if (lastValue) { lastValue.value = '✓'; lastValue.focus(); }
  });

  // Badge text only matters for the highlighted plan
  var popular = document.getElementById('is_popular'), badge = document.getElementById('badgeGroup');
  function toggleBadge() { badge.style.display = popular.checked ? '' : 'none'; }
  popular.addEventListener('change', toggleBadge);
  toggleBadge();
  renumber();
})();
</script>
@endpush
@endsection
