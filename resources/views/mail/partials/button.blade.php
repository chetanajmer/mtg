{{-- Orange pill button: @include('mail.partials.button', ['url' => ..., 'label' => ..., 'align' => 'center'|'left']) --}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:20px 0;">
  <tr>
    <td align="{{ $align ?? 'center' }}">
      <table role="presentation" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td style="border-radius:26px;background:#f26b1d;background-image:linear-gradient(90deg,#f7891f,#f26b1d);">
            <a href="{{ $url }}" style="display:inline-block;padding:13px 30px;font-family:'Nunito Sans',Arial,Helvetica,sans-serif;font-size:15px;font-weight:700;color:#ffffff;text-decoration:none;border-radius:26px;">{{ $label }} &rarr;</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
