@extends('web_frontend.layouts.home')

@section('title', ($query !== '' ? $query . ' | ' : '') . 'Search | More Than Giftz')
@section('meta')
  <meta name="robots" content="noindex">
@endsection

@section('content')
<main id="homePage">
  <section class="mh-section mh-results">
    <form class="mh-search mh-results-search" action="{{ route('home.search') }}" method="get" role="search">
      <i class="bi bi-search"></i>
      <input type="search" name="q" value="{{ $query }}" placeholder="Search corporate gifts..." aria-label="Search corporate gifts" maxlength="100">
      <button type="submit" class="mh-search-go" aria-label="Search"><i class="bi bi-arrow-right"></i></button>
    </form>
    <h1>{{ $heading }}</h1>
    <p class="mh-results-count">{{ $products->total() }} {{ $products->total() === 1 ? 'product' : 'products' }}</p>
    @if($products->count())
      <div class="mh-results-grid">
        @foreach($products as $product)
          @include('web_frontend.partials.home-product-card', ['product' => $product])
        @endforeach
      </div>
      <div class="mh-pagination">
        @if($products->previousPageUrl())<a href="{{ $products->previousPageUrl() }}"><i class="bi bi-arrow-left"></i> Previous</a>@endif
        @if($products->nextPageUrl())<a href="{{ $products->nextPageUrl() }}">Next <i class="bi bi-arrow-right"></i></a>@endif
      </div>
    @else
      <div class="mh-no-results">
        <i class="bi bi-search"></i>
        <h2>Nothing matched that search</h2>
        <p>Try a simpler word like "bottle" or "notebook", or let us help you choose.</p>
        <a href="{{ route('planner.index') }}">Open the Budget Planner</a>
        <a href="{{ url('contactus') }}">Talk to our team</a>
      </div>
    @endif
  </section>
  <div class="mh-toast" id="homeToast" role="status" hidden></div>
</main>
@endsection
