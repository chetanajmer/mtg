@php

$site_settings=\App\Models\Homepage_setting::findorFail(1);
$headersettings= \App\Models\Header_setting::where('id','1')->first();

@endphp

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tbody><tr>

<td align="center" valign="top">

						<div id="m_-1300593100174662049m_1663186110346867073template_header_image">

							<p style="margin-top:0"><div class="a6S" dir="ltr" style="opacity: 1; left: -376px; top: -175px;"><div id=":q6" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" role="button" tabindex="0" aria-label="Download attachment " data-tooltip-class="a1V" data-tooltip="Download"><div class="akn"><div class="aSK J-J5-Ji aYr"></div></div></div></div></p>						</div>

						<table border="0" cellpadding="0" cellspacing="0" width="600" id="m_-1300593100174662049m_1663186110346867073template_container" style="background-color:#ffffff;border:1px solid #dedede;border-radius:3px">

<tbody><tr>

<td align="center" valign="top">

									

									<table border="0" cellpadding="0" cellspacing="0" width="600" id="m_-1300593100174662049m_1663186110346867073template_header" style="background-color:{{$site_settings->themecolor}};color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0"><tbody><tr>

<td id="m_-1300593100174662049m_1663186110346867073header_wrapper" style="padding:36px 48px;display:block">

												<h1 style="font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;color:#ffffff">Welcome to {{$site_settings->sitename}}</h1>

											</td>

										</tr></tbody></table>



</td>

							</tr>

<tr>

<td align="center" valign="top">

									

									<table border="0" cellpadding="0" cellspacing="0" width="600" id="m_-1300593100174662049m_1663186110346867073template_body"><tbody><tr>

<td valign="top" id="m_-1300593100174662049m_1663186110346867073body_content" style="background-color:#ffffff">

												

												<table border="0" cellpadding="20" cellspacing="0" width="100%"><tbody><tr>

<td valign="top" style="padding:48px 48px 0">

															<div id="m_-1300593100174662049m_1663186110346867073body_content_inner" style="color:#636363;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left">



<p style="margin:0 0 16px">Hi <a href="mailto:{{$details['email']}}" target="_blank">{{$details['email']}}</a>,</p>

<p style="margin:0 0 16px">Thanks for creating an account on {{$site_settings->sitename}}. Your username is <strong><a href="mailto:{{$details['email']}}" target="_blank">{{$details['email']}}</a></strong>.</p>

		<p style="margin:0 0 16px">Your password is: <strong>{{$details['password']}}</strong></p>



<p style="margin:0 0 16px">We look forward to seeing you soon.</p>



															</div>

														</td>

													</tr></tbody></table>



</td>

										</tr></tbody></table>



</td>

							</tr>

<tr>

<td align="center" valign="top">

									

									<table border="0" cellpadding="10" cellspacing="0" width="600" id="m_-1300593100174662049m_1663186110346867073template_footer"><tbody><tr>

<td valign="top" style="padding:0;border-radius:6px">

												<table border="0" cellpadding="10" cellspacing="0" width="100%"><tbody><tr>



													</tr></tbody></table>

</td>

										</tr></tbody></table>



</td>

							</tr>

</tbody></table>

</td>

				</tr></tbody></table>