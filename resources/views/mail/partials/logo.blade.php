{{-- Text logo (works even when the mail app blocks images): @include('mail.partials.logo', ['light' => bool, 'small' => bool]) --}}
@php
  $light = $light ?? false;
  $small = $small ?? false;
  $ink = $light ? '#ffffff' : '#15171a';
@endphp
<a href="{{ rtrim(config('app.url'), '/') }}" style="text-decoration:none;">
  <table role="presentation" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td valign="middle" style="padding-right:8px;font-size:{{ $small ? 24 : 30 }}px;line-height:1;">&#127873;</td>
      <td valign="middle" style="font-family:'Nunito Sans',Arial,Helvetica,sans-serif;">
        <span style="display:block;font-size:{{ $small ? 15 : 19 }}px;line-height:1.1;font-weight:800;letter-spacing:.3px;color:{{ $ink }};">MORETHAN<span style="color:#f26b1d;">GIFTZ</span></span>
        <span style="display:block;font-size:{{ $small ? 9 : 11 }}px;line-height:1.4;color:{{ $light ? '#c9cfd8' : '#4b4f56' }};">Corporate Gifts. Made Special.</span>
      </td>
    </tr>
  </table>
</a>
