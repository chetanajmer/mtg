@php
  $settings = rescue(function () { return \App\Models\General_setting::find(1); }, null, false);
  $socials = ['instagram' => 'bi-instagram', 'linkedin' => 'bi-linkedin', 'youtube' => 'bi-youtube', 'facebook' => 'bi-facebook', 'twitter' => 'bi-twitter-x'];
@endphp
<footer class="mh-footer">
  <div class="mh-footer-brand">
    <img src="{{ asset('web_frontend/assets/images/home/logo.png') }}" alt="More Than Giftz">
  </div>
  <div class="mh-footer-about">
    <h3>The #1 Corporate Gifting Partner for Businesses &amp; Teams</h3>
    <p>More Than Giftz offers a curated range of premium, customizable and sustainable corporate gifts for companies across the UAE &amp; GCC. From branded merchandise to bulk gifting solutions, we help you create lasting brand impressions.</p>
    <div class="mh-socials">
      @foreach($socials as $key => $icon)
        @if($settings && !empty($settings->$key))
          <a href="{{ $settings->$key }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($key) }}"><i class="bi {{ $icon }}"></i></a>
        @else
          <span aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
        @endif
      @endforeach
    </div>
  </div>
  <div class="mh-footer-col">
    <h4>Quick Links</h4>
    <a href="{{ url('aboutus') }}">About Us</a>
    <a href="{{ url('/') }}">Corporate Gifting</a>
    <a href="/annual-corporate-membership">Bulk Orders</a>
    <a href="{{ route('custom-order.create') }}">Request a Quote</a>
    <a href="{{ url('contactus') }}">Contact Us</a>
  </div>
  <div class="mh-footer-col">
    <h4>Categories</h4>
    @foreach(config('home_page.category_cards') as $card)
      <a href="{{ isset($card['category']) ? url('category/' . $card['category']) : url('search?q=' . urlencode($card['search'])) }}">{{ $card['title'] }}</a>
    @endforeach
  </div>
  <div class="mh-footer-col">
    <h4>Help &amp; Support</h4>
    <a href="{{ url('contactus') }}">FAQs</a>
    <a href="{{ $ordersUrl }}">Track Enquiry</a>
    <a href="{{ url('return-policy') }}">Shipping &amp; Delivery</a>
    <a href="{{ url('return-policy') }}">Returns &amp; Refunds</a>
    <a href="{{ url('privacy-policy') }}">Privacy Policy</a>
    <a href="{{ url('privacy-policy') }}">Terms &amp; Conditions</a>
  </div>
  <div class="mh-footer-news">
    <h4>Subscribe to Our Newsletter</h4>
    <p>Get the latest products, offers and corporate gifting ideas straight to your inbox.</p>
    <form id="newsletterForm" data-url="{{ route('addsubscriber') }}" novalidate>
      <input type="email" name="email" placeholder="Enter your email address" aria-label="Your email address" maxlength="191" required>
      <button type="submit" aria-label="Subscribe"><i class="bi bi-arrow-right"></i></button>
    </form>
    <p class="mh-news-msg" id="newsletterMsg" role="status" hidden></p>
  </div>
</footer>
