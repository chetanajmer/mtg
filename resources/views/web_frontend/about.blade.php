@extends('web_frontend.layouts.app')

@section('title', 'About Us | More Than Giftz')
@section('meta')
  <meta name="description" content="More Than Giftz helps UAE companies choose, brand and deliver corporate gifts for their teams, clients and events, with design approval, volume pricing and online order tracking.">
@endsection

@section('styles')
<style>
  .ab-hero { background: var(--light); border-bottom: 1px solid var(--border); padding: 64px 0 56px; }
  .ab-eyebrow { display: inline-block; font-size: 13px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #8c5109; margin-bottom: 12px; }
  .ab-hero h1 { font-family: Georgia, "Times New Roman", serif; font-size: clamp(30px, 4.2vw, 48px); line-height: 1.15; color: var(--black); margin: 0 0 16px; max-width: 760px; }
  .ab-hero p { color: var(--muted); font-size: 17px; line-height: 1.6; max-width: 680px; margin: 0 0 26px; }
  .ab-btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; border-radius: 6px; font-weight: 600; text-decoration: none; margin: 0 8px 8px 0; }
  .ab-btn-dark { background: var(--black); color: #fff; }
  .ab-btn-dark:hover { color: #fff; opacity: .9; }
  .ab-btn-light { background: #fff; color: var(--black); border: 1px solid var(--border); }
  .ab-btn-light:hover { color: var(--black); border-color: var(--black); }
  .ab-section { padding: 56px 0; }
  .ab-section + .ab-section { padding-top: 0; }
  .ab-section h2 { font-family: Georgia, "Times New Roman", serif; font-size: clamp(24px, 3vw, 30px); color: var(--black); margin: 0 0 8px; }
  .ab-lead { color: var(--muted); font-size: 16px; max-width: 680px; margin: 0 0 28px; }
  .ab-card { height: 100%; border: 1px solid var(--border); border-radius: 10px; padding: 22px; background: #fff; }
  .ab-card i { display: inline-flex; width: 46px; height: 46px; align-items: center; justify-content: center; border-radius: 10px; background: #faf7f1; color: #8c5109; font-size: 22px; margin-bottom: 14px; }
  .ab-card h3 { font-size: 17px; color: var(--black); margin: 0 0 8px; }
  .ab-card p { color: var(--muted); font-size: 15px; line-height: 1.55; margin: 0; }
  .ab-steps { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; counter-reset: step; }
  @media (max-width: 991px) { .ab-steps { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
  @media (max-width: 575px) { .ab-steps { grid-template-columns: 1fr; } }
  .ab-steps li { position: relative; border: 1px solid var(--border); border-radius: 10px; padding: 18px 18px 16px 62px; background: #fff; }
  .ab-steps li::before { counter-increment: step; content: counter(step); position: absolute; left: 18px; top: 18px; width: 30px; height: 30px; border-radius: 50%; background: var(--black); color: #fff; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center; }
  .ab-steps strong { display: block; color: var(--black); font-size: 15px; margin-bottom: 4px; }
  .ab-steps span { color: var(--muted); font-size: 14px; line-height: 1.5; }
  .ab-tile { display: block; text-decoration: none; color: var(--text); border: 1px solid var(--border); border-radius: 8px; overflow: hidden; background: #fff; height: 100%; transition: box-shadow .2s; }
  .ab-tile:hover { box-shadow: 0 6px 18px rgba(0,0,0,.08); color: var(--text); }
  .ab-tile-img { aspect-ratio: 4 / 3; background: var(--light); display: flex; align-items: center; justify-content: center; }
  .ab-tile-img img { width: 100%; height: 100%; object-fit: contain; }
  .ab-tile-img .bi { font-size: 34px; color: var(--muted); }
  .ab-tile strong { display: block; padding: 10px 12px; font-size: 14px; }
  .ab-list { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 14px 28px; }
  .ab-list li { display: flex; gap: 12px; align-items: flex-start; color: var(--text); line-height: 1.55; }
  .ab-list li i { color: #17752c; font-size: 20px; flex: 0 0 auto; margin-top: 1px; }
  .ab-list li strong { color: var(--black); }
  .ab-cta { background: var(--black); color: #fff; border-radius: 12px; padding: 40px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 24px; }
  .ab-cta h2 { color: #fff; margin: 0 0 8px; }
  .ab-cta p { color: #c9ccd1; margin: 0; }
  .ab-cta-contact { display: flex; flex-wrap: wrap; gap: 6px 18px; margin-top: 14px; }
  .ab-cta-contact a { color: #fff; text-decoration: none; }
  .ab-cta-contact a:hover { text-decoration: underline; }
  .ab-cta .ab-btn-light { border-color: #fff; }
  @media (max-width: 575px) {
    .ab-hero { padding: 40px 0 36px; }
    .ab-section { padding: 40px 0; }
    .ab-cta { padding: 26px 20px; }
  }
  .ab-hero-grid { display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr); gap: 40px; align-items: center; }
  .ab-hero-img { border-radius: 12px; overflow: hidden; background: #fff; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
  .ab-hero-img img { display: block; width: 100%; height: auto; max-height: 420px; object-fit: cover; }
  @media (max-width: 991px) { .ab-hero-grid { grid-template-columns: 1fr; gap: 24px; } }
</style>
@endsection

@section('content')
@php
  $company = config('company');
  $c = $content;
  $categoryCount = $categories->count();
  $heroImage = !empty($c['hero_image']) && is_file(public_path('upload/pages/' . $c['hero_image'])) ? asset('upload/pages/' . rawurlencode($c['hero_image'])) : null;
  $cardIcons = ['bi-gift', 'bi-brush', 'bi-tags', 'bi-people'];
@endphp

<section class="ab-hero">
  <div class="container">
    <div class="{{ $heroImage ? 'ab-hero-grid' : '' }}">
      <div>
        @if($c['hero_eyebrow'])<span class="ab-eyebrow">{{ $c['hero_eyebrow'] }}</span>@endif
        <h1>{{ $c['hero_title'] }}</h1>
        <p>{{ $c['hero_text'] }}</p>
        <a href="{{ url('/') }}#categories" class="ab-btn ab-btn-dark">Browse products <i class="bi bi-arrow-right"></i></a>
        <a href="{{ route('custom-order.create') }}" class="ab-btn ab-btn-light">Request a quote</a>
      </div>
      @if($heroImage)
        <div class="ab-hero-img"><img src="{{ $heroImage }}" alt=""></div>
      @endif
    </div>
  </div>
</section>

<section class="ab-section">
  <div class="container">
    <h2>{{ $c['what_title'] }}</h2>
    @if($c['what_intro'])<p class="ab-lead">{{ $c['what_intro'] }}</p>@endif
    <div class="row g-3">
      @foreach($c['cards'] as $i => $card)
        <div class="col-sm-6 col-lg-3">
          <div class="ab-card"><i class="bi {{ $cardIcons[$i] ?? 'bi-star' }}"></i><h3>{{ $card['title'] }}</h3>
            <p>{{ str_replace(':categories', $categoryCount, $card['text']) }}</p></div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="ab-section" id="how-it-works">
  <div class="container">
    <h2>{{ $c['steps_title'] }}</h2>
    @if($c['steps_intro'])<p class="ab-lead">{{ $c['steps_intro'] }}</p>@endif
    <ol class="ab-steps">
      @foreach($steps as $n => $step)
        @if(isset($c['steps'][$n]))
          <li><strong>{{ $step['customer'] }}</strong><span>{{ $c['steps'][$n] }}</span></li>
        @endif
      @endforeach
    </ol>
  </div>
</section>

@if($categories->isNotEmpty())
<section class="ab-section">
  <div class="container">
    <h2>{{ $c['categories_title'] }}</h2>
    @if($c['categories_intro'])<p class="ab-lead">{{ $c['categories_intro'] }}</p>@endif
    <div class="row g-3">
      @foreach($categories as $category)
        <div class="col-6 col-md-4 col-lg-3">
          <a href="{{ url('category/' . $category->slug) }}" class="ab-tile">
            <div class="ab-tile-img">
              @if($category->image1 && is_file(public_path('upload/category/' . $category->image1)))
                <img src="{{ asset('upload/category/' . $category->image1) }}" alt="" loading="lazy">
              @else
                <i class="bi bi-gift"></i>
              @endif
            </div>
            <strong>{{ $category->catname }}</strong>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<section class="ab-section">
  <div class="container">
    <h2>{{ $c['why_title'] }}</h2>
    @if($c['why_intro'])<p class="ab-lead">{{ $c['why_intro'] }}</p>@endif
    <ul class="ab-list">
      @foreach($c['points'] as $point)
        <li><i class="bi bi-check-circle-fill"></i><span><strong>{{ $point['title'] }}</strong> {{ $point['text'] }}</span></li>
      @endforeach
    </ul>
  </div>
</section>

<section class="ab-section">
  <div class="container">
    <div class="ab-cta">
      <div>
        <h2>{{ $c['cta_title'] }}</h2>
        @if($c['cta_text'])<p>{{ $c['cta_text'] }}</p>@endif
        <div class="ab-cta-contact">
          @if(!empty($company['phone']))<a href="tel:{{ preg_replace('/[^0-9+]/', '', $company['phone']) }}"><i class="bi bi-telephone"></i> {{ $company['phone'] }}</a>@endif
          @if(!empty($company['email']))<a href="mailto:{{ $company['email'] }}"><i class="bi bi-envelope"></i> {{ $company['email'] }}</a>@endif
          @if(!empty($company['address']))<span><i class="bi bi-geo-alt"></i> {{ $company['address'] }}</span>@endif
        </div>
      </div>
      <div>
        <a href="{{ route('custom-order.create') }}" class="ab-btn ab-btn-light">Request a quote</a>
        <a href="{{ url('contactus') }}" class="ab-btn ab-btn-light">Contact us</a>
      </div>
    </div>
  </div>
</section>
@endsection
