@extends('web_frontend.layouts.app')

@section('title', 'Annual Corporate Membership | More Than Giftz')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/annual-corporate-membership.css') }}">

<div class="membership-wrapper">
  <section class="hero">
    <div class="hero-in">
      <div class="eyebrow">MORE VALUE. STRONGER RELATIONSHIPS.</div>
      <h1>Annual Corporate<br>Membership</h1>
      <p class="lead">A smarter way to gift. Greater savings. Bigger impact.</p>
      <p class="copy">Join our annual membership program and unlock exclusive pricing, premium benefits and a seamless corporate gifting experience all year round.</p>
      <div class="btns">
        <a href="#plans" class="btn dark">Choose Your Plan <span>&rarr;</span></a>
        <a href="#" class="btn light">Talk to Our Team</a>
      </div>
      <div class="stats">
        <div><b>500+</b><span>Corporate Clients</span></div>
        <div><b>50,000+</b><span>Gifts Delivered</span></div>
        <div><b>4.9/5</b><span>Client Satisfaction</span></div>
      </div>
    </div>
    <div class="tag">GIFTS THAT BUILD BUSINESS</div>
  </section>

  <main class="wrap plans" id="plans">
    <span class="eyebrow">MEMBERSHIP PLANS</span>
    <h2>Choose the Right Plan for Your Business</h2>
    <p class="sub2">More gifting. More savings. More possibilities.</p>

    <div class="grid">
      <article class="plan">
        <div class="head">
          <div class="medal silver"><i></i><u></u></div>
          <div><h3>Silver</h3><p>For Growing Teams</p><small>Essential benefits to get you started.</small></div>
        </div>
        <div class="rows">
          <div>Annual Spend<b>₹2,00,000 / year</b></div>
          <div>Estimated Savings<b>₹20,000 (10%)</b></div>
          <div>Free Customization<b>Up to 5 products</b></div>
          <div>Priority Dispatch<b>Standard</b></div>
          <div>Dedicated Account Manager<b>–</b></div>
          <div>Unused Budget Rollover<b>–</b></div>
        </div>
        <div class="foot"><span class="price">₹2,00,000 / <small>year</small></span><button class="sel">Select Plan</button></div>
      </article>

      <article class="plan gold">
        <span class="badge">MOST POPULAR</span>
        <div class="head">
          <div class="medal goldm"><i></i><u></u></div>
          <div><h3>Gold</h3><p style="text-decoration:underline;text-decoration-color:#c9ad7a;text-underline-offset:3px">For Established Businesses</p><small>Maximum value with premium benefits.</small></div>
        </div>
        <div class="rows">
          <div>Annual Spend<b>₹5,00,000 / year</b></div>
          <div>Estimated Savings<b>₹75,000 (15%)</b></div>
          <div>Free Customization<b>Up to 10 products</b></div>
          <div>Priority Dispatch<b>Priority (3–5 days)</b></div>
          <div>Dedicated Account Manager<b>✓</b></div>
          <div>Unused Budget Rollover<b>Up to 25%</b></div>
        </div>
        <div class="foot"><span class="price">₹5,00,000 / <small>year</small></span><button class="sel">Select Plan</button></div>
      </article>

      <article class="plan">
        <div class="head">
          <div class="medal plat"><i></i><u></u></div>
          <div><h3>Platinum</h3><p>For Large Enterprises</p><small>The complete corporate gifting solution.</small></div>
        </div>
        <div class="rows">
          <div>Annual Spend<b>₹10,00,000 / year</b></div>
          <div>Estimated Savings<b>₹2,00,000 (20%)</b></div>
          <div>Free Customization<b>Unlimited</b></div>
          <div>Priority Dispatch<b>Express (1–3 days)</b></div>
          <div>Dedicated Account Manager<b>✓</b></div>
          <div>Unused Budget Rollover<b>Up to 50%</b></div>
        </div>
        <div class="foot"><span class="price">₹10,00,000 / <small>year</small></span><button class="sel">Select Plan</button></div>
      </article>
    </div>

    <div class="strip">
      <div><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="1.6"><rect x="3" y="9" width="18" height="12"/><path d="M12 9v12M3 9h18V6H3zM12 6c-3-4-6-1-4 0M12 6c3-4 6-1 4 0"/></svg>Exclusive<br>Member Pricing</div>
      <div><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="1.6"><circle cx="12" cy="12" r="3.5"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/></svg>Free<br>Customization</div>
      <div><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="1.6"><path d="M2 6h12v10H2zM14 9h4l4 3v4h-8"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg>Priority<br>Dispatch</div>
      <div><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="1.6"><path d="M4 21V12h4v9M10 21V4h4v17M16 21v-6h4v6M2 21h20"/></svg>Dedicated<br>Account Manager</div>
      <div><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="1.6"><ellipse cx="9" cy="6" rx="6" ry="3"/><path d="M3 6v10c0 2 3 3 6 3M3 11c0 2 3 3 6 3"/><circle cx="17" cy="16" r="5"/><path d="M17 13.5v5"/></svg>Credit<br>Rollover</div>
      <div><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="1.6"><path d="m12 3 2.7 5.8 6.3.7-4.7 4.3 1.3 6.2L12 17l-5.6 3 1.3-6.2L3 9.5l6.3-.7z"/></svg>Early Access<br>to New Products</div>
    </div>

    <div class="two">
      <div class="info">
        <div class="circ"><svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#a47624" stroke-width="1.8"><path d="M20 8a8 8 0 0 0-14-2M4 4v3h3M4 16a8 8 0 0 0 14 2m2 2v-3h-3"/></svg></div>
        <div><h4>Auto-Renewal</h4><p>Keep enjoying uninterrupted benefits. Your membership auto-renews annually, so you never miss out on savings.</p></div>
        <div class="toggle" role="switch" aria-checked="true">ON</div>
      </div>
      <div class="info">
        <div class="circ" style="background:#f3f1ee"><svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="1.6"><ellipse cx="12" cy="6" rx="8" ry="3"/><path d="M4 6v4c0 2 3.5 3 8 3s8-1 8-3V6M4 10v4c0 2 3.5 3 8 3s8-1 8-3v-4M4 14v4c0 2 3.5 3 8 3s8-1 8-3v-4"/></svg></div>
        <div><h4>Credit Rollover</h4><p>Unused annual budget rolls over to the next year (As per your plan benefits). No value goes to waste.</p></div>
        <div class="cal"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#a47624" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18M12 13v5M9.5 15.5h5"/></svg></div>
      </div>
    </div>

    <div class="cta">
      <a href="#" class="btn">Get Started Today &nbsp;&rarr;</a>
      <p>Have questions? Contact our corporate gifting experts.</p>
    </div>
  </main>
</div>
@endsection