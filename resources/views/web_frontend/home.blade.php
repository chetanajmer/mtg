@extends('web_frontend.layouts.home')

@section('title', 'More Than Giftz | Corporate Gifts in the UAE')
@section('meta')
  <meta name="description" content="Discover, compare and find the perfect corporate gifts. Branded gifts, bulk orders and custom logos for companies across the UAE and GCC.">
@endsection

@section('content')
<main id="homePage">

  <section class="mh-hero">
    <div class="mh-hero-center">
      <span class="mh-eyebrow">CORPORATE GIFTING SOLUTIONS</span>
      <h1>Where should we <em>begin?</em></h1>
      <p>Discover, compare and find the perfect corporate gifts with AI</p>
      <form class="mh-search" action="{{ route('home.search') }}" method="get" role="search">
        <span class="mh-search-ai">Ai<i class="bi bi-stars"></i></span>
        <input type="search" id="homeSearch" name="q" placeholder="Ask anything related to Gifts..." aria-label="Ask anything related to gifts" maxlength="100">
        <button type="button" class="mh-search-tool" id="voiceSearch" aria-label="Search by voice"><i class="bi bi-mic"></i></button>
        <button type="button" class="mh-search-tool" data-soon="Searching with a picture arrives with our AI search." aria-label="Search with a picture"><i class="bi bi-image"></i></button>
        <button type="submit" class="mh-search-go" aria-label="Search"><i class="bi bi-search"></i></button>
      </form>
    </div>
    <div class="mh-hints">
      <button type="button" data-focus-search><i class="bi bi-globe"></i> Search in any Language</button>
      <button type="button" data-soon="Searching with a picture arrives with our AI search."><i class="bi bi-camera"></i> Search with Product Pics</button>
      <a href="{{ route('planner.index') }}"><i class="bi bi-cash-coin"></i> Search with your Budget</a>
      <a href="{{ $ordersUrl }}"><i class="bi bi-box-seam"></i> Track your Order with Invoice no.</a>
    </div>
  </section>

  <nav class="mh-shortcuts" aria-label="Shortcuts">
    <a href="{{ route('corporate.portal') }}"><i class="bi bi-person-gear"></i> Customer Portal</a>
    <a href="/annual-corporate-membership"><i class="bi bi-person-vcard"></i> Annual Membership Contract</a>
    <a href="{{ route('planner.index') }}"><i class="bi bi-coin"></i> Budget Planner</a>
    <button type="button" data-soon="Compare Products is coming soon."><i class="bi bi-sliders"></i> Compare Products</button>
  </nav>

  <section class="mh-section">
    <h2 class="mh-title">Search by Category</h2>
    <div class="mh-categories-grid">
      @foreach($categoryCards as $card)
      <article class="mh-cat">
        <a href="{{ $card['url'] }}" class="mh-cat-top">
          <img src="{{ asset('web_frontend/assets/images/home/' . $card['image']) }}" alt="" loading="lazy">
          <h3 class="mh-cat-title">{{ $card['title'] }}</h3>
        </a>
        <ul>
          @foreach($card['links'] as $link)
            <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
          @endforeach
        </ul>
        <a href="{{ $card['url'] }}" class="mh-cat-all">View All <i class="bi bi-chevron-right"></i></a>
      </article>
      @endforeach
    </div>
  </section>

  @if($newArrivals->count())
  <section class="mh-section mh-arrivals">
    <div class="mh-arrivals-head">
      <h2>New Arrivals</h2>
      <span class="mh-pill">Just In</span>
      <a href="{{ route('home.search', ['new' => 1]) }}" class="mh-view-all">View All New Arrivals <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="mh-carousel">
      <button type="button" class="mh-arrow mh-arrow-prev" id="arrivalsPrev" aria-label="Previous products"><i class="bi bi-chevron-left"></i></button>
      <div class="mh-track" id="arrivalsTrack" aria-label="New arrivals">
        @foreach($newArrivals as $product)
          @include('web_frontend.partials.home-product-card', ['product' => $product])
        @endforeach
      </div>
      <button type="button" class="mh-arrow mh-arrow-next" id="arrivalsNext" aria-label="Next products"><i class="bi bi-chevron-right"></i></button>
    </div>
    <div class="mh-dots" id="arrivalsDots"></div>
  </section>
  @endif

  @if($homeLogos->count())
  <section class="mh-logos {{ $homeLogos->count() === 1 ? 'mh-logos-single' : '' }}">
    @foreach(\App\Models\HomeLogo::TYPES as $type => $title)
      @if(isset($homeLogos[$type]))
      <div>
        <h2 class="mh-title">{{ $title }}</h2>
        <div class="mh-logo-row">
          @foreach($homeLogos[$type] as $logo)
            <img src="{{ $logo->logoUrl() }}" alt="{{ $logo->name }}" title="{{ $logo->name }}" loading="lazy">
          @endforeach
        </div>
      </div>
      @endif
    @endforeach
  </section>
  @endif

  <section class="mh-why">
    <h2 class="mh-title">Why Choose Us</h2>
    <p class="mh-why-sub">More Than Giftz — A Trusted Partner for Your Corporate Gifting Needs</p>
    <div class="mh-why-grid">
      <div class="mh-why-col">
        @foreach(config('home_page.why_left') as $item)
          <div class="mh-why-item"><span><i class="bi {{ $item['icon'] }}"></i></span><div><strong>{{ $item['title'] }}</strong><p>{{ $item['text'] }}</p></div></div>
        @endforeach
      </div>
      <img class="mh-why-gift" src="{{ asset('web_frontend/assets/images/home/why-gift.jpg') }}" alt="A black gift box tied with a gold ribbon" loading="lazy">
      <div class="mh-why-col">
        @foreach(config('home_page.why_right') as $item)
          <div class="mh-why-item"><span><i class="bi {{ $item['icon'] }}"></i></span><div><strong>{{ $item['title'] }}</strong><p>{{ $item['text'] }}</p></div></div>
        @endforeach
      </div>
    </div>
  </section>

  <div class="mh-toast" id="homeToast" role="status" hidden></div>
</main>
@endsection
