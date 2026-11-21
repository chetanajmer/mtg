@if(Session::has('role'))
@else
<script>
window.location.href = "{{url('/admin')}}";</script>
@endif
@extends('layouts.app')
@section('content')
@php
  $priceFilter = $filters->firstWhere('filter_key', 'price');
  $savedBands = old('bands', $priceFilter ? ($priceFilter->options ?: []) : []);
  $sustainability = $filters->firstWhere('filter_key', 'sustainability');
  $savedTags = old('sustainability_tags', $sustainability ? ($sustainability->options ?: []) : []);
@endphp
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title">Category Page Filters</h4>
                                <ol class="breadcrumb">
                                    <li>The filters in the left panel of every category page.</li>
                                </ol>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <form method="post" action="{{ route('filter-settings.update') }}">
                                    @csrf
                                    <div class="card-box">
                                        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                                        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

                                        <h4 class="header-title m-t-0 m-b-20">Filters</h4>
                                        <div class="table-responsive">
                                            <table class="table table-actions-bar">
                                                <thead>
                                                    <tr>
                                                        <th style="min-width:220px;">Name on the website</th>
                                                        <th style="width:110px;">Position</th>
                                                        <th style="width:110px;">On website</th>
                                                        <th style="min-width:280px;">Hide on these categories</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($filters as $filter)
                                                        @php
                                                          $key = $filter->filter_key;
                                                          $hidden = array_map('intval', old("filters.$key.hidden_categories", $filter->hidden_categories ?: []));
                                                          $isOn = old('filters') ? !empty(old("filters.$key.is_active")) : $filter->is_active;
                                                        @endphp
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="filters[{{ $key }}][label]" value="{{ old("filters.$key.label", $filter->label) }}" maxlength="60" required aria-label="Name of the {{ $key }} filter"></td>
                                                            <td><input type="number" class="form-control" name="filters[{{ $key }}][ranking]" value="{{ old("filters.$key.ranking", $filter->ranking) }}" min="0" max="9999" required aria-label="Position of the {{ $key }} filter"></td>
                                                            <td><input type="checkbox" name="filters[{{ $key }}][is_active]" value="1" {{ $isOn ? 'checked' : '' }} aria-label="Show the {{ $key }} filter on the website"></td>
                                                            <td>
                                                                <details>
                                                                    <summary style="cursor:pointer;">{{ count($hidden) ? count($hidden) . ' hidden' : 'Shown on all categories' }}</summary>
                                                                    <div style="margin-top:8px;">
                                                                        @foreach($categories as $category)
                                                                            <label style="display:block;font-weight:400;margin-bottom:4px;"><input type="checkbox" name="filters[{{ $key }}][hidden_categories][]" value="{{ $category->id }}" {{ in_array((int) $category->id, $hidden, true) ? 'checked' : '' }}> {{ $category->catname }}</label>
                                                                        @endforeach
                                                                    </div>
                                                                </details>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="card-box">
                                        <h4 class="header-title m-t-0">Price bands</h4>
                                        <p class="text-muted">Prices are in AED. A product counts from the From price up to, but not including, the To price. Leave To empty on the top band for "and above".</p>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead><tr><th>Name on the website</th><th style="width:150px;">From</th><th style="width:150px;">To</th><th style="width:100px;"></th></tr></thead>
                                                <tbody id="priceBands">
                                                    @foreach($savedBands as $i => $band)
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="bands[{{ $i }}][label]" value="{{ $band['label'] ?? '' }}" maxlength="40" aria-label="Band name"></td>
                                                            <td><input type="number" class="form-control" name="bands[{{ $i }}][from]" value="{{ $band['from'] ?? '' }}" min="0" step="0.01" aria-label="From price"></td>
                                                            <td><input type="number" class="form-control" name="bands[{{ $i }}][to]" value="{{ $band['to'] ?? '' }}" min="0" step="0.01" aria-label="To price"></td>
                                                            <td><button type="button" class="btn btn-danger btn-sm" data-remove-band>Remove</button></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-default" id="addPriceBand">Add a band</button>
                                    </div>

                                    <div class="card-box">
                                        <h4 class="header-title m-t-0">Sustainability options</h4>
                                        <p class="text-muted">Which product Preferences appear under this filter. Manage the list itself on the <a href="{{ url('preferences') }}">Preferences</a> page.</p>
                                        @forelse($tags as $tag)
                                            <label style="display:block;font-weight:400;margin-bottom:4px;"><input type="checkbox" name="sustainability_tags[]" value="{{ $tag }}" {{ in_array($tag, $savedTags, true) ? 'checked' : '' }}> {{ $tag }}</label>
                                        @empty
                                            <p class="text-muted">No preferences yet.</p>
                                        @endforelse
                                    </div>

                                    <button type="submit" class="btn btn-info waves-effect waves-light m-b-20">Save Filters</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<script>
  (function () {
    var body = document.getElementById('priceBands');
    var next = body.children.length;
    document.getElementById('addPriceBand').addEventListener('click', function () {
      var row = document.createElement('tr');
      row.innerHTML = '<td><input type="text" class="form-control" name="bands[' + next + '][label]" maxlength="40" aria-label="Band name"></td>' +
        '<td><input type="number" class="form-control" name="bands[' + next + '][from]" min="0" step="0.01" aria-label="From price"></td>' +
        '<td><input type="number" class="form-control" name="bands[' + next + '][to]" min="0" step="0.01" aria-label="To price"></td>' +
        '<td><button type="button" class="btn btn-danger btn-sm" data-remove-band>Remove</button></td>';
      body.appendChild(row);
      next++;
    });
    body.addEventListener('click', function (event) {
      if (event.target.hasAttribute('data-remove-band')) {
        event.target.closest('tr').remove();
      }
    });
  })();
</script>
@endsection
