@php
  // Footer from the client's design (19 Nov). Text and links: config/home_page.php 'footer'; contacts: config/company.php
  $settings = rescue(function () { return \App\Models\General_setting::find(1); }, null, false);
  $footer = config('home_page.footer');
  $socials = [
    ['Facebook', 'bi-facebook', optional($settings)->facebook],
    ['Instagram', 'bi-instagram', optional($settings)->instagram],
    ['X', 'bi-twitter-x', optional($settings)->twitter],
    ['YouTube', 'bi-youtube', optional($settings)->youtube],
    ['TikTok', 'bi-tiktok', config('company.tiktok')],
  ];
  $email = config('company.enquiry_email');
  $landline = config('company.landline');
  $whatsapp = config('company.whatsapp');
@endphp
<footer class="mh-footer">
  <div class="mh-footer-about">
    <h3>{{ $footer['title'] }}</h3>
    <ul class="mh-footer-points">
      @foreach($footer['points'] as [$point, $detail])
        <li>{{ $point }} – {{ $detail }}</li>
      @endforeach
    </ul>
    <div class="mh-footer-row">
      <span class="mh-footer-label">Follow us on :</span>
      <div class="mh-socials">
        @foreach($socials as [$name, $icon, $link])
          @if($link)
            <a href="{{ $link }}" target="_blank" rel="noopener" aria-label="{{ $name }}"><i class="bi {{ $icon }}"></i></a>
          @else
            <span aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
          @endif
        @endforeach
      </div>
    </div>
    <div class="mh-footer-row">
      <span class="mh-footer-label">We Accept :</span>
      <div class="mh-pay" aria-label="Visa, Mastercard and bank transfer">
        <svg viewBox="0 0 44 26" aria-hidden="true"><rect x="1" y="1" width="42" height="24" rx="3" fill="none" stroke="currentColor" stroke-width="2"/><text x="22" y="18" text-anchor="middle" font-family="Arial, sans-serif" font-size="12" font-weight="900" font-style="italic" fill="currentColor">VISA</text></svg>
        <svg viewBox="0 0 44 26" aria-hidden="true"><rect x="1" y="1" width="42" height="24" rx="3" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="18" cy="13" r="6.5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="26" cy="13" r="6.5" fill="currentColor"/></svg>
        <span class="mh-pay-bank"><i class="bi bi-bank"></i><span>BANK<small>TRANSFER</small></span></span>
      </div>
    </div>
  </div>

  <div class="mh-footer-side">
    <div class="mh-footer-cols">
      @foreach($footer['columns'] as $heading => $links)
        <div class="mh-footer-col">
          <h4>{{ $heading }}</h4>
          @foreach($links as [$label, $url])
            <a href="{{ url($url) }}">{{ $label }}</a>
          @endforeach
        </div>
      @endforeach
    </div>
    <ul class="mh-footer-contact">
      <li><i class="bi bi-envelope"></i><a href="mailto:{{ $email }}">{{ $email }}</a></li>
      <li><i class="bi bi-telephone"></i><a href="tel:{{ preg_replace('/[^\d+]/', '', $landline) }}">{{ $landline }}</a></li>
      <li><i class="bi bi-phone"></i><a href="tel:{{ preg_replace('/[^\d+]/', '', $whatsapp) }}">{{ $whatsapp }}</a> <a href="https://wa.me/{{ preg_replace('/\D/', '', $whatsapp) }}" target="_blank" rel="noopener" class="mh-footer-wa" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a></li>
    </ul>
    <div class="mh-footer-news">
      <h4>Subscribe for the latest News and Offers !</h4>
      <form id="newsletterForm" data-url="{{ route('addsubscriber') }}" novalidate>
        <input type="email" name="email" placeholder="Your email..." aria-label="Your email address" maxlength="191" required>
        <button type="submit">Subscribe</button>
      </form>
      <p class="mh-news-msg" id="newsletterMsg" role="status" hidden></p>
    </div>
  </div>
</footer>
