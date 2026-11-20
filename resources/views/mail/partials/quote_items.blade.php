<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;margin:16px 0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#2b2d31;">
  <tr>
    <th align="left" style="padding:9px 8px;background:#15171a;color:#ffffff;font-weight:bold;">Product</th>
    <th align="left" style="padding:9px 8px;background:#15171a;color:#ffffff;font-weight:bold;">SKU</th>
    <th align="right" style="padding:9px 8px;background:#15171a;color:#ffffff;font-weight:bold;">Qty</th>
    <th align="right" style="padding:9px 8px;background:#15171a;color:#ffffff;font-weight:bold;">Unit (AED)</th>
    <th align="right" style="padding:9px 8px;background:#15171a;color:#ffffff;font-weight:bold;">Total (AED)</th>
  </tr>
  @foreach($items as $item)
  <tr>
    <td style="padding:9px 8px;border-bottom:1px solid #efe7da;">{{ $item->name }}</td>
    <td style="padding:9px 8px;border-bottom:1px solid #efe7da;color:#6a6e75;">{{ $item->modelno ?: '-' }}</td>
    <td align="right" style="padding:9px 8px;border-bottom:1px solid #efe7da;">{{ $item->quantity }}</td>
    <td align="right" style="padding:9px 8px;border-bottom:1px solid #efe7da;">{{ $item->price > 0 ? number_format($item->price, 2) : 'To be confirmed' }}</td>
    <td align="right" style="padding:9px 8px;border-bottom:1px solid #efe7da;">{{ $item->price > 0 ? number_format($item->price * $item->quantity, 2) : 'To be confirmed' }}</td>
  </tr>
  @endforeach
  @if($enquiry->total > 0)
  <tr><td colspan="4" align="right" style="padding:8px 8px 2px;color:#6a6e75;">Subtotal</td><td align="right" style="padding:8px 8px 2px;">{{ number_format($enquiry->subtotal, 2) }}</td></tr>
  <tr><td colspan="4" align="right" style="padding:2px 8px;color:#6a6e75;">VAT</td><td align="right" style="padding:2px 8px;">{{ number_format($enquiry->tax, 2) }}</td></tr>
  <tr><td colspan="4" align="right" style="padding:8px;font-weight:bold;border-top:2px solid #c9a24a;">Estimated total</td><td align="right" style="padding:8px;font-weight:bold;border-top:2px solid #c9a24a;">{{ number_format($enquiry->total, 2) }}</td></tr>
  @endif
</table>
