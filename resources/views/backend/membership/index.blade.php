@extends('layouts.app')
@section('content')
<div class="content-page">
  <div class="content">
    <div class="container">

      <div class="row">
        <div class="col-sm-12">
          <div class="btn-group pull-right m-t-15">
            <a href="{{ route('membership.page') }}" target="_blank" rel="noopener" class="btn btn-default waves-effect"><i class="ti-eye"></i> View page</a>
          </div>
          <h4 class="page-title">Annual Membership</h4>
          <p class="text-muted m-b-20">Content of the <a href="{{ route('membership.page') }}" target="_blank" rel="noopener">Annual Corporate Membership</a> page: the stats in the top image and the Membership Plans section.</p>
        </div>
      </div>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          <strong>Please check the form:</strong>
          <ul class="m-b-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
      @endif

      <form method="POST" action="{{ route('membership.content') }}">
        @csrf
        <div class="row">
          <div class="col-lg-6">
            <div class="card-box">
              <h4 class="header-title m-t-0 m-b-20">Stats on the top image</h4>
              <p class="text-muted">For example <b>500+</b> &middot; Corporate Clients. Up to 6; leave a row empty to remove it.</p>
              <table class="table m-b-10" id="statRows">
                <thead><tr><th style="width:38%">Value</th><th>Label</th><th style="width:44px"></th></tr></thead>
                <tbody>
                  @php $statRows = old('stats', $stats->map(function ($s) { return ['value' => $s->value, 'label' => $s->label]; })->all()); @endphp
                  @foreach($statRows as $i => $stat)
                    <tr>
                      <td><input type="text" name="stats[{{ $i }}][value]" value="{{ $stat['value'] ?? '' }}" class="form-control" maxlength="50" placeholder="500+"></td>
                      <td><input type="text" name="stats[{{ $i }}][label]" value="{{ $stat['label'] ?? '' }}" class="form-control" maxlength="100" placeholder="Corporate Clients"></td>
                      <td><button type="button" class="btn btn-sm btn-danger js-remove-row" title="Remove">&times;</button></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <button type="button" class="btn btn-sm btn-default" id="addStat"><i class="ti-plus"></i> Add stat</button>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="card-box">
              <h4 class="header-title m-t-0 m-b-20">Membership Plans section</h4>
              <div class="form-group">
                <label>Small heading</label>
                <input type="text" name="plans_eyebrow" value="{{ old('plans_eyebrow', $settings->plans_eyebrow) }}" class="form-control" maxlength="100" placeholder="MEMBERSHIP PLANS">
              </div>
              <div class="form-group">
                <label>Title <span class="text-danger">*</span></label>
                <input type="text" name="plans_title" value="{{ old('plans_title', $settings->plans_title) }}" class="form-control" maxlength="191" required>
              </div>
              <div class="form-group m-b-0">
                <label>Subtitle</label>
                <input type="text" name="plans_subtitle" value="{{ old('plans_subtitle', $settings->plans_subtitle) }}" class="form-control" maxlength="255">
              </div>
            </div>
          </div>
        </div>
        <div class="m-b-20">
          <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="ti-save"></i> Save stats and texts</button>
        </div>
      </form>

      <div class="row">
        <div class="col-lg-12">
          <div class="card-box">
            <a href="{{ route('membership.plans.create') }}" class="btn btn-primary btn-sm pull-right"><i class="ti-plus"></i> Add plan</a>
            <h4 class="header-title m-t-0 m-b-20">Membership plans</h4>
            <div class="table-responsive">
              <table class="table table-actions-bar">
                <thead>
                  <tr><th>Order</th><th>Plan</th><th>Medal</th><th>Price</th><th>Rows</th><th>Status</th><th style="width:150px">Actions</th></tr>
                </thead>
                <tbody>
                  @forelse($plans as $plan)
                    <tr>
                      <td>{{ $plan->sort_order }}</td>
                      <td>
                        <b>{{ $plan->name }}</b>
                        @if($plan->is_popular)<span class="label label-warning m-l-5">{{ $plan->badge_text ?: 'Most popular' }}</span>@endif
                        <br><small class="text-muted">{{ $plan->tagline }}</small>
                      </td>
                      <td>{{ \App\Models\MembershipPlan::MEDALS[$plan->medal] ?? $plan->medal }}</td>
                      <td>{{ $plan->price }}{{ $plan->price_period ? ' / ' . $plan->price_period : '' }}</td>
                      <td>{{ count($plan->features ?? []) }}</td>
                      <td>@if($plan->is_active)<span class="label label-success">Shown</span>@else<span class="label label-default">Hidden</span>@endif</td>
                      <td>
                        <a href="{{ route('membership.plans.edit', $plan->id) }}" class="btn btn-sm btn-default"><i class="ti-pencil"></i> Edit</a>
                        <form method="POST" action="{{ route('membership.plans.destroy', $plan->id) }}" style="display:inline" onsubmit="return confirm('Delete the {{ addslashes($plan->name) }} plan? This cannot be undone.');">
                          @csrf
                          <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="ti-trash"></i></button>
                        </form>
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="7" class="text-muted">No plans yet. The Membership Plans section is hidden on the page until you add one.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

@push('custom-scripts')
<script>
(function () {
  var body = document.querySelector('#statRows tbody');
  var max = 6;
  function renumber() {
    [].forEach.call(body.rows, function (row, i) {
      row.querySelectorAll('input').forEach(function (input) {
        input.name = input.name.replace(/stats\[\d+\]/, 'stats[' + i + ']');
      });
    });
    document.getElementById('addStat').disabled = body.rows.length >= max;
  }
  document.getElementById('addStat').addEventListener('click', function () {
    if (body.rows.length >= max) return;
    var row = document.createElement('tr');
    row.innerHTML = '<td><input type="text" name="stats[0][value]" class="form-control" maxlength="50" placeholder="500+"></td>'
      + '<td><input type="text" name="stats[0][label]" class="form-control" maxlength="100" placeholder="Corporate Clients"></td>'
      + '<td><button type="button" class="btn btn-sm btn-danger js-remove-row" title="Remove">&times;</button></td>';
    body.appendChild(row);
    renumber();
    row.querySelector('input').focus();
  });
  body.addEventListener('click', function (e) {
    if (e.target.closest('.js-remove-row')) {
      e.target.closest('tr').remove();
      renumber();
    }
  });
  renumber();
})();
</script>
@endpush
@endsection
