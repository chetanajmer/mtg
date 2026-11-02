@extends('web_frontend.layouts.app')

@section('title', 'More Than Giftz | Corporate Gifts in the UAE')
@section('meta')
  <meta name="description" content="Branded corporate gifts for UAE businesses: drinkware, technology, bags, apparel, awards and more, with logo branding and quantity pricing.">
@endsection

@section('styles')
<style>
  .home-hero { background: var(--light); border-bottom: 1px solid var(--border); padding: 56px 0; }
  .home-hero h1 { font-family: Georgia, "Times New Roman", serif; font-size: clamp(30px, 4vw, 46px); color: var(--black); margin: 0 0 12px; }
  .home-hero p { color: var(--muted); font-size: 17px; max-width: 620px; margin: 0 0 24px; }
  .home-btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; border-radius: 6px; font-weight: 600; text-decoration: none; }
  .home-btn-dark { background: var(--black); color: #fff; }
  .home-btn-dark:hover { color: #fff; opacity: .9; }
  .home-btn-light { background: #fff; color: var(--black); border: 1px solid var(--border); }
  .home-section { padding: 48px 0; }
  .home-section h2 { font-family: Georgia, "Times New Roman", serif; font-size: 28px; color: var(--black); margin: 0 0 24px; }
  .home-tile { display: block; text-decoration: none; color: var(--text); border: 1px solid var(--border); border-radius: 8px; overflow: hidden; background: #fff; height: 100%; transition: box-shadow .15s; }
  .home-tile:hover { box-shadow: 0 6px 18px rgba(0,0,0,.08); color: var(--text); }
  .home-tile-img { aspect-ratio: 1 / 1; background: var(--light); display: flex; align-items: center; justify-content: center; }
  .home-tile-img img { width: 100%; height: 100%; object-fit: contain; }
  .home-tile-img .bi { font-size: 40px; color: var(--muted); }
  .home-tile-body { padding: 12px 14px; }
  .home-tile-body strong { display: block; font-size: 15px; }
  .home-tile-body span { color: var(--muted); font-size: 14px; }
  .home-cta { background: var(--black); color: #fff; border-radius: 10px; padding: 36px; }
  .home-cta h2 { color: #fff; margin-bottom: 8px; }
  .home-cta p { color: #c9ccd1; margin: 0 0 20px; }
</style>
@endsection

@section('content')
<section class="home-hero">
  <div class="container">
    <h1>Corporate gifts your team and clients will keep</h1>
    <p>Branded gifts for UAE businesses, with your logo, quantity pricing and delivery across the UAE &amp; GCC.</p>
    <a href="#categories" class="home-btn home-btn-dark">Browse categories <i class="bi bi-arrow-down"></i></a>
    <a href="{{ route('cart.index') }}" class="home-btn home-btn-light">Request a quote</a>
  </div>
</section>

<section class="home-section" id="categories">
  <div class="container">
    <h2>Shop by category</h2>
    <div class="row g-3">
      @foreach($categories as $category)
        <div class="col-6 col-md-4 col-lg-3">
          <a href="{{ url('category/' . $category->slug) }}" class="home-tile">
            <div class="home-tile-img">
              @if($category->image1 && is_file(public_path('upload/category/' . $category->image1)))
                <img src="{{ asset('upload/category/' . $category->image1) }}" alt="{{ $category->catname }}" loading="lazy">
              @else
                <i class="bi bi-gift"></i>
              @endif
            </div>
            <div class="home-tile-body"><strong>{{ $category->catname }}</strong></div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>

@if($products->isNotEmpty())
<section class="home-section" style="padding-top: 0;">
  <div class="container">
    <h2>Latest products</h2>
    <div class="row g-3">
      @foreach($products as $product)
        @php $image = $product->imageUrl(); @endphp
        <div class="col-6 col-md-4 col-lg-3">
          <a href="{{ route('web.product', $product->slug) }}" class="home-tile">
            <div class="home-tile-img">
              @if($image)
                <img src="{{ $image }}" alt="{{ $product->name }}" loading="lazy">
              @else
                <i class="bi bi-image"></i>
              @endif
            </div>
            <div class="home-tile-body">
              <strong>{{ $product->name }}</strong>
              <span>From AED {{ number_format($product->displayPrice(), 2) }} &middot; MOQ {{ $product->minOrderQuantity() }}</span>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<section class="home-section" style="padding-top: 0;">
  <div class="container">
    <div class="home-cta">
      <h2>Need gifts for a team or an event?</h2>
      <p>Add products to your quote cart and send it to us. We'll reply with a confirmed price, branding options and delivery date.</p>
      <a href="{{ route('cart.index') }}" class="home-btn home-btn-light">Open quote cart <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>
@endsection
