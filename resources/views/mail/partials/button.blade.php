{{-- Email button: @include('mail.partials.button', ['url' => ..., 'label' => ...]) --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:22px 0;">
  <tr>
    <td style="border-radius:6px;background:#15171a;">
      <a href="{{ $url }}" style="display:inline-block;padding:13px 26px;font-family:Arial,Helvetica,sans-serif;font-size:15px;font-weight:bold;color:#ffffff;text-decoration:none;border-radius:6px;">{{ $label }}</a>
    </td>
  </tr>
</table>
