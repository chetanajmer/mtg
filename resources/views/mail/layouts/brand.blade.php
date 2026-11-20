{{-- Shared look for every More Than Giftz email. Views set: title (h1), preheader (inbox preview), and the content section. --}}
@php
  $company = config('company');
  $site = rtrim(config('app.url'), '/');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="x-apple-disable-message-reformatting">
  <title>@yield('title') - {{ $company['name'] }}</title>
</head>
<body style="margin:0;padding:0;background:#f6f1ed;-webkit-text-size-adjust:100%;">
  <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:#f6f1ed;">@yield('preheader')</div>
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f6f1ed;">
    <tr>
      <td align="center" style="padding:24px 12px;">
        <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:600px;background:#ffffff;border-radius:10px;overflow:hidden;">
          <tr>
            <td align="center" style="background:#15171a;padding:18px 24px;">
              <a href="{{ $site }}" style="text-decoration:none;">
                <img src="{{ asset('web_frontend/assets/images/home/logo.png') }}" width="96" alt="{{ $company['name'] }}" style="display:block;width:96px;max-width:96px;height:auto;border:0;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:20px;font-weight:bold;">
              </a>
            </td>
          </tr>
          <tr><td style="height:4px;line-height:4px;font-size:0;background:#c9a24a;">&nbsp;</td></tr>
          <tr>
            <td style="padding:30px 32px 8px;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.6;color:#2b2d31;">
              <h1 style="margin:0 0 16px;font-family:Arial,Helvetica,sans-serif;font-size:22px;line-height:1.3;font-weight:bold;color:#15171a;">@yield('title')</h1>
              @yield('content')
            </td>
          </tr>
          <tr>
            <td style="padding:8px 32px 28px;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.6;color:#2b2d31;">
              @hasSection('signoff')
                @yield('signoff')
              @else
                <p style="margin:16px 0 0;">Warm regards,<br><strong>The {{ $company['name'] }} team</strong></p>
              @endif
            </td>
          </tr>
          <tr>
            <td style="padding:20px 32px 24px;background:#faf7f2;border-top:1px solid #efe7da;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:1.6;color:#6a6e75;text-align:center;">
              <p style="margin:0 0 6px;font-weight:bold;color:#15171a;">{{ $company['name'] }} &middot; Corporate gifting across the UAE &amp; GCC</p>
              <p style="margin:0 0 6px;">
                @if($company['phone'])<a href="tel:{{ preg_replace('/[^0-9+]/', '', $company['phone']) }}" style="color:#6a6e75;text-decoration:none;">{{ $company['phone'] }}</a> &middot; @endif
                <a href="mailto:{{ $company['email'] }}" style="color:#6a6e75;text-decoration:none;">{{ $company['email'] }}</a>
                @if($company['website']) &middot; <a href="{{ $site }}" style="color:#6a6e75;text-decoration:none;">{{ $company['website'] }}</a>@endif
              </p>
              @if($company['address'])<p style="margin:0 0 6px;">{{ $company['address'] }}</p>@endif
              <p style="margin:8px 0 0;">
                <a href="{{ url('/track-order') }}" style="color:#9a6b16;text-decoration:none;">Track an order</a> &nbsp;&middot;&nbsp;
                <a href="{{ url('/corporate-portal') }}" style="color:#9a6b16;text-decoration:none;">My account</a> &nbsp;&middot;&nbsp;
                <a href="{{ url('/contactus') }}" style="color:#9a6b16;text-decoration:none;">Contact us</a>
              </p>
            </td>
          </tr>
        </table>
        <p style="margin:14px 0 0;font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#8a8d93;">@yield('reason', 'You are receiving this email about your ' . $company['name'] . ' account or order.')</p>
      </td>
    </tr>
  </table>
</body>
</html>
