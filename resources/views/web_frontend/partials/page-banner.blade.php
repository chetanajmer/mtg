{{-- Banner strip under the header on every page except the home page; a different one on every visit
     (admin: Banners > Page Banners). Nothing is shown when the page has no banners. --}}
@php
  $pageBanner = request()->is('/') ? null : rescue(function () { return \App\Models\PageBanner::pick(request()); }, null, false);
@endphp
@if($pageBanner)
  <div class="mh-page-banner">
    @if($pageBanner->link)<a href="{{ $pageBanner->link }}">@endif
      <img src="{{ $pageBanner->imageUrl() }}" alt="{{ $pageBanner->alt ?: '' }}" width="1920" height="250" fetchpriority="high">
    @if($pageBanner->link)</a>@endif
  </div>
@endif
