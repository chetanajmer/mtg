{{-- Peach "Order ID / Order Date" pill: @include('mail.partials.id_badge', ['label' => 'Request ID', 'id' => '#10165', 'dateLabel' => 'Date', 'date' => '21 Nov 2026, 12:30']) --}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 16px;">
  <tr>
    <td style="padding:14px 20px;background:#fde6d6;border-radius:12px;font-family:'Nunito Sans',Arial,Helvetica,sans-serif;">
      <span style="display:block;font-size:17px;font-weight:800;color:#15171a;">{{ $label }} :&nbsp; {{ $id }}</span>
      @if(!empty($date))
        <span style="display:block;margin-top:2px;font-size:13px;color:#4b4f56;">{{ $dateLabel ?? 'Date' }} : {{ $date }}</span>
      @endif
    </td>
  </tr>
</table>
