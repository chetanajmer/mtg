{{--
  Shared look for every More Than Giftz email (client's email design, 21 Nov): logo bar, a hero with a two-tone
  heading, the email's content, a closing line and the navy footer. Table-based with inline styles for mail apps.

  A view extends it with an optional hero:
    @extends('mail.layouts.brand', ['hero' => [
      'variant' => 'light' | 'dark' | 'success' | 'blue' | 'welcome',
      'title'   => 'New Quote',            // first line of the heading
      'accent'  => 'Request Received!',    // second line, in orange
      'text'    => 'A customer has ...',   // short line under the heading
      'icon'    => '🎁',                    // shown in a circle on the right
    ]])
  Sections: title (subject-like name, used when there is no hero), preheader (inbox preview), content,
  closing (centred line under the content), signoff, reason (small print under the email).
--}}
@php
  $company = config('company');
  $site = rtrim(config('app.url'), '/');
  $hero = $hero ?? null;
  $variant = $hero['variant'] ?? 'light';

  $themes = [
    'light'   => ['bg' => '#fff4ea', 'title' => '#15171a', 'text' => '#4b4f56', 'iconBg' => '#ffe3cc', 'iconFg' => '#f26b1d'],
    'dark'    => ['bg' => '#0f1c2e', 'title' => '#ffffff', 'text' => '#d5dae2', 'iconBg' => '#1d2f48', 'iconFg' => '#f7a23b'],
    'success' => ['bg' => '#fff7f0', 'title' => '#15171a', 'text' => '#4b4f56', 'iconBg' => '#22b14c', 'iconFg' => '#ffffff'],
    'blue'    => ['bg' => '#eef4fd', 'title' => '#15171a', 'text' => '#3f4a5a', 'iconBg' => '#2f7de1', 'iconFg' => '#ffffff'],
    'welcome' => ['bg' => '#fff6ec', 'title' => '#15171a', 'text' => '#4b4f56', 'iconBg' => '#ffe0b8', 'iconFg' => '#f26b1d'],
  ];
  $t = $themes[$variant] ?? $themes['light'];
  $dark = $variant === 'dark';

  $settings = rescue(function () { return \App\Models\General_setting::find(1); }, null, false);
  $socials = array_filter([
    ['f', optional($settings)->facebook, 'Facebook'],
    ['ig', optional($settings)->instagram, 'Instagram'],
    ['X', optional($settings)->twitter, 'X'],
    ['&#9654;', optional($settings)->youtube, 'YouTube'],
    ['tt', $company['tiktok'] ?? null, 'TikTok'],
  ], function ($s) { return !empty($s[1]); });

  $font = "font-family:'Nunito Sans',Arial,Helvetica,sans-serif;";
  $orange = '#f26b1d';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="x-apple-disable-message-reformatting">
  <title>@yield('title') - {{ $company['name'] }}</title>
  <style>
    @media only screen and (max-width:620px){
      .mtg-pad{padding-left:20px !important;padding-right:20px !important}
      .mtg-hero-title{font-size:28px !important}
      .mtg-hero-icon{display:none !important}
      .mtg-col{display:block !important;width:100% !important;text-align:left !important;padding:6px 0 !important}
      .mtg-tagnav{display:none !important}
    }
  </style>
</head>
<body style="margin:0;padding:0;background:#f3efeb;-webkit-text-size-adjust:100%;">
  <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:#f3efeb;">@yield('preheader')</div>
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f3efeb;">
    <tr>
      <td align="center" style="padding:24px 12px;">
        <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 4px 18px rgba(21,23,26,.08);">

          {{-- Logo bar --}}
          <tr>
            <td class="mtg-pad" style="background:{{ $dark ? '#0f1c2e' : $t['bg'] }};padding:22px 32px 6px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td valign="middle">
                    @include('mail.partials.logo', ['light' => $dark])
                  </td>
                  <td class="mtg-tagnav" align="right" valign="middle" style="{{ $font }}font-size:12px;font-weight:600;color:{{ $dark ? '#ffffff' : '#15171a' }};white-space:nowrap;">
                    Gift <span style="color:{{ $orange }};">&bull;</span> Brand <span style="color:{{ $orange }};">&bull;</span> Inspire
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          {{-- Hero --}}
          <tr>
            <td class="mtg-pad" style="background:{{ $t['bg'] }};padding:22px 32px 30px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td valign="middle" style="{{ $font }}">
                    @if($hero)
                      <h1 class="mtg-hero-title" style="margin:0;{{ $font }}font-size:32px;line-height:1.15;font-weight:800;color:{{ $t['title'] }};">
                        {{ $hero['title'] ?? '' }}@if(!empty($hero['accent']))<br><span style="color:{{ $orange }};">{{ $hero['accent'] }}</span>@endif
                      </h1>
                      @if(!empty($hero['text']))
                        <p style="margin:12px 0 0;{{ $font }}font-size:15px;line-height:1.5;color:{{ $t['text'] }};">{{ $hero['text'] }}</p>
                      @endif
                    @else
                      <h1 class="mtg-hero-title" style="margin:0;{{ $font }}font-size:28px;line-height:1.2;font-weight:800;color:{{ $t['title'] }};">@yield('title')</h1>
                    @endif
                  </td>
                  @if(!empty($hero['icon']))
                    <td class="mtg-hero-icon" width="96" align="right" valign="middle">
                      <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                          <td align="center" valign="middle" width="84" height="84" style="width:84px;height:84px;border-radius:42px;background:{{ $t['iconBg'] }};{{ $font }}font-size:40px;line-height:84px;color:{{ $t['iconFg'] }};font-weight:800;">{!! $hero['icon'] !!}</td>
                        </tr>
                      </table>
                    </td>
                  @endif
                </tr>
              </table>
            </td>
          </tr>

          {{-- Content --}}
          <tr>
            <td class="mtg-pad" style="padding:26px 32px 6px;{{ $font }}font-size:15px;line-height:1.6;color:#2b2d31;">
              @yield('content')
            </td>
          </tr>

          {{-- Closing line --}}
          <tr>
            <td class="mtg-pad" align="center" style="padding:4px 32px 26px;{{ $font }}font-size:15px;line-height:1.6;color:#2b2d31;">
              @hasSection('closing')
                <p style="margin:0;font-weight:600;color:#15171a;">@yield('closing')</p>
              @endif
              {{-- An empty @section('signoff') (staff emails) means no sign-off; @hasSection treats empty as missing --}}
              @if($__env->hasSection('signoff'))
                @yield('signoff')
              @else
                <p style="margin:12px 0 0;">Warm regards,<br><strong>The {{ $company['name'] }} team</strong></p>
              @endif
            </td>
          </tr>

          {{-- Orange wave into the footer --}}
          <tr><td style="height:6px;line-height:6px;font-size:0;background:{{ $orange }};background-image:linear-gradient(90deg,#f7a23b,{{ $orange }} 45%,#e2531a);">&nbsp;</td></tr>

          {{-- Footer --}}
          <tr>
            <td class="mtg-pad" style="background:#0f1c2e;padding:20px 32px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td class="mtg-col" valign="middle" width="38%">
                    @include('mail.partials.logo', ['light' => true, 'small' => true])
                  </td>
                  <td class="mtg-col" valign="middle" width="34%" style="{{ $font }}font-size:12px;line-height:1.8;color:#ffffff;border-left:1px solid #2a3b55;padding-left:14px;">
                    <span style="color:{{ $orange }};">&#9993;</span>&nbsp; <a href="mailto:{{ $company['email'] }}" style="color:#ffffff;text-decoration:none;">{{ $company['email'] }}</a><br>
                    <span style="color:{{ $orange }};">&#127760;</span>&nbsp; <a href="{{ $site }}" style="color:#ffffff;text-decoration:none;">{{ $company['website'] }}</a>
                    @if(!empty($company['phone']))<br><span style="color:{{ $orange }};">&#9742;</span>&nbsp; <a href="tel:{{ preg_replace('/[^0-9+]/', '', $company['phone']) }}" style="color:#ffffff;text-decoration:none;">{{ $company['phone'] }}</a>@endif
                  </td>
                  @if($socials)
                    <td class="mtg-col" valign="middle" align="right" style="{{ $font }}font-size:12px;color:#ffffff;border-left:1px solid #2a3b55;padding-left:14px;">
                      <span style="display:block;margin-bottom:6px;">Follow Us:</span>
                      @foreach($socials as [$glyph, $url, $label])
                        <a href="{{ $url }}" title="{{ $label }}" style="display:inline-block;width:24px;height:24px;line-height:24px;margin:0 0 0 4px;border-radius:12px;background:{{ $orange }};color:#ffffff;text-align:center;text-decoration:none;font-size:11px;font-weight:800;">{!! $glyph !!}</a>
                      @endforeach
                    </td>
                  @endif
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <p style="margin:14px 0 0;{{ $font }}font-size:11px;color:#8a8d93;">@yield('reason', 'You are receiving this email about your ' . $company['name'] . ' account or order.')</p>
      </td>
    </tr>
  </table>
</body>
</html>
