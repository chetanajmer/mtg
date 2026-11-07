@extends('web_frontend.layouts.home')

@php
  $pageTitle = $currentSubcategory ? $currentSubcategory->catname : $category->catname;
  $lines = $design['lines'] ?? [$category->catname];
  $from = $products->firstItem() ?: 0;
  $to = $products->lastItem() ?: 0;
  $anyFilter = $budget !== '' || array_filter($chosen);
  $plainUrl = url('category/' . $category->slug) . ($currentSubcategory ? '?sub=' . urlencode($currentSubcategory->slug) : '');
  $viewUrl = function ($mode) {
      return request()->fullUrlWithQuery(['view' => $mode === 'grid' ? null : $mode, 'page' => null]);
  };
@endphp

@section('title', $pageTitle . ' | Corporate Gifts | More Than Giftz')
@section('meta')
  <meta name="description" content="{{ $design['intro'] }}">
  <link rel="canonical" href="{{ $plainUrl }}">
  <link rel="stylesheet" href="{{ asset('web_frontend/assets/css/category.css') }}?v={{ filemtime(public_path('web_frontend/assets/css/category.css')) }}">
@endsection

@section('content')
<main id="homePage" class="cp-page">
  <section class="cp-hero">
    @if($banner)
      <img class="cp-hero-banner" src="{{ $banner }}" alt="">
    @endif
    <div class="cp-hero-text">
      <nav class="cp-crumbs" aria-label="Breadcrumb">
        <a href="{{ url('/') }}"><i class="bi bi-house-door" aria-hidden="true"></i> Home</a>
        <i class="bi bi-chevron-right" aria-hidden="true"></i>
        <span>Categories</span>
        <i class="bi bi-chevron-right" aria-hidden="true"></i>
        @if($currentSubcategory)
          <a href="{{ url('category/' . $category->slug) }}">{{ $category->catname }}</a>
          <i class="bi bi-chevron-right" aria-hidden="true"></i>
        @endif
        <strong aria-current="page">{{ $pageTitle }}</strong>
      </nav>
      <h1>
        @foreach($lines as $line)
          <span class="cp-line">{!! preg_replace('/\{(.+?)\}/', '<span class="cp-gold">$1</span>', e($line)) !!}</span>
        @endforeach
      </h1>
      <p>{{ $design['intro'] }}</p>
    </div>
    <ul class="cp-highlights">
      @foreach($design['highlights'] as $highlight)
        <li><i class="bi {{ $highlight[0] }}" aria-hidden="true"></i> {{ $highlight[1] }}</li>
      @endforeach
    </ul>
  </section>

  <div class="cp-body {{ $groups->isEmpty() ? 'cp-body-full' : '' }}">
    <form method="get" action="{{ url('category/' . $category->slug) }}" id="categoryFilters">
      @if($currentSubcategory)<input type="hidden" name="sub" value="{{ $currentSubcategory->slug }}">@endif
      @if($view === 'list')<input type="hidden" name="view" value="list">@endif
    </form>

    @if($groups->isNotEmpty())
    <aside class="cp-filters" aria-label="Filters">
      <h2>Filters</h2>
      @foreach($groups as $key => $group)
        @if($key === 'price')
          <details class="cp-filter" @if($budget !== '') open @endif>
            <summary>{{ $group->label }} <i class="bi bi-chevron-down" aria-hidden="true"></i></summary>
            <div class="cp-filter-options">
              <label><input type="radio" name="budget" value="" form="categoryFilters" @if($budget === '') checked @endif> Any price</label>
              @foreach($budgets as $value => $band)
                <label><input type="radio" name="budget" value="{{ $value }}" form="categoryFilters" @if($budget === (string) $value) checked @endif> {{ $band['label'] }}</label>
              @endforeach
            </div>
          </details>
        @else
          <details class="cp-filter" @if($chosen[$key]) open @endif>
            <summary>{{ $group->label }} <i class="bi bi-chevron-down" aria-hidden="true"></i></summary>
            <div class="cp-filter-options">
              @forelse($filters[$key] as $value => $name)
                <label>
                  <input type="checkbox" name="{{ $key }}[]" value="{{ $value }}" form="categoryFilters" @if(in_array((string) $value, $chosen[$key], true)) checked @endif>
                  @if($key === 'color')<span class="cp-swatch" style="background: {{ $value }}"></span>@endif
                  {{ $name }}
                </label>
              @empty
                <p class="cp-filter-none">No options yet</p>
              @endforelse
            </div>
          </details>
        @endif
      @endforeach
      @if($anyFilter)
        <a class="cp-filters-clear" href="{{ $plainUrl }}">Clear filters</a>
      @endif
      <noscript><button type="submit" form="categoryFilters" class="cp-filters-apply">Apply</button></noscript>
    </aside>
    @endif

    <section class="cp-results" aria-label="Products">
      <div class="cp-toolbar">
        <p class="cp-count">Showing {{ $from }}–{{ $to }} of {{ $products->total() }} {{ $products->total() === 1 ? 'product' : 'products' }}</p>
        <label class="cp-sort">Sort by:
          <select name="sort" form="categoryFilters">
            @foreach($sorts as $value => $name)
              <option value="{{ $value }}" @if($sort === $value) selected @endif>{{ $name }}</option>
            @endforeach
          </select>
        </label>
        <div class="cp-views">
          <a href="{{ $viewUrl('grid') }}" class="{{ $view === 'grid' ? 'active' : '' }}" aria-label="Grid view" @if($view === 'grid') aria-current="true" @endif><i class="bi bi-grid-fill" aria-hidden="true"></i></a>
          <a href="{{ $viewUrl('list') }}" class="{{ $view === 'list' ? 'active' : '' }}" aria-label="List view" @if($view === 'list') aria-current="true" @endif><i class="bi bi-list-ul" aria-hidden="true"></i></a>
        </div>
      </div>

      @if($products->count())
        <div class="cp-grid {{ $view === 'list' ? 'cp-grid-list' : '' }}">
          @foreach($products as $product)
            @php
              $image = $product->imageUrl();
              $moq = $product->minOrderQuantity();
            @endphp
            <article class="cp-card">
              <button type="button" class="cp-heart" data-save-collection="{{ $product->id }}" data-saved-icon="bi bi-heart-fill" data-quantity="{{ $moq }}" data-name="{{ $product->name }}" aria-label="Save {{ $product->name }} to a collection" title="Save to a collection"><i class="bi bi-heart" aria-hidden="true"></i></button>
              <a class="cp-card-image" href="{{ route('web.product', $product->slug) }}" aria-label="{{ $product->name }}">
                @if($image)
                  <img src="{{ $image }}" alt="{{ $product->name }}" loading="lazy">
                @else
                  <i class="bi bi-gift" aria-hidden="true"></i>
                @endif
              </a>
              <a class="cp-card-info" href="{{ route('web.product', $product->slug) }}">
                <span class="cp-card-name">{{ $product->name }}</span>
                <span class="cp-card-moq">{{ $product->modelno }}{{ $product->modelno ? ' · ' : '' }}MOQ {{ $moq }}</span>
                <strong>AED {{ number_format($product->priceForQuantity($moq), 2) }}</strong>
              </a>
            </article>
          @endforeach
        </div>
      @else
        <div class="cp-empty">
          @if($anyFilter)
            <h2>No products match these filters</h2>
            <p>Try removing a filter, or tell us what you have in mind and we'll find it for you.</p>
            <a class="cp-btn" href="{{ $plainUrl }}">Clear filters</a>
          @else
            <h2>This range is coming soon</h2>
            <p>We're still adding products here. Tell us what you need and we'll source it for you.</p>
          @endif
          <a class="cp-btn cp-btn-dark" href="{{ route('custom-order.create') }}">Request a Quote</a>
        </div>
      @endif

      @if($products->lastPage() > 1)
        <nav class="cp-pages" aria-label="Pages">
          <a class="{{ $products->onFirstPage() ? 'disabled' : '' }}" href="{{ $products->previousPageUrl() ?: '#' }}" aria-label="Previous page"><i class="bi bi-chevron-left" aria-hidden="true"></i></a>
          @foreach($pageLinks as $link)
            @if($link === '...')
              <span>…</span>
            @else
              <a class="{{ $link === $products->currentPage() ? 'active' : '' }}" href="{{ $products->url($link) }}" @if($link === $products->currentPage()) aria-current="page" @endif>{{ $link }}</a>
            @endif
          @endforeach
          <a class="{{ $products->hasMorePages() ? '' : 'disabled' }}" href="{{ $products->nextPageUrl() ?: '#' }}" aria-label="Next page"><i class="bi bi-chevron-right" aria-hidden="true"></i></a>
        </nav>
      @endif
    </section>
  </div>

  <div class="mh-toast" id="homeToast" role="status" hidden></div>
</main>
@endsection

@section('scripts')
<script>
  (function () {
    var form = document.getElementById('categoryFilters');
    document.querySelectorAll('[form="categoryFilters"]').forEach(function (field) {
      field.addEventListener('change', function () {
        // Leaves the defaults out of the address
        document.querySelectorAll('input[name="budget"][form="categoryFilters"]').forEach(function (radio) {
          radio.disabled = radio.value === '';
        });
        var sort = document.querySelector('select[name="sort"][form="categoryFilters"]');
        sort.disabled = sort.value === 'popular';
        form.submit();
      });
    });
  })();
</script>
@endsection
