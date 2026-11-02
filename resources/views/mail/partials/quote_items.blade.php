<table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;font-size:14px;margin:16px 0;">
  <tr style="background:#f3f4f6;text-align:left;">
    <th style="border-bottom:1px solid #e5e7eb;">Product</th>
    <th style="border-bottom:1px solid #e5e7eb;">SKU</th>
    <th style="border-bottom:1px solid #e5e7eb;text-align:right;">Qty</th>
    <th style="border-bottom:1px solid #e5e7eb;text-align:right;">Unit (AED)</th>
    <th style="border-bottom:1px solid #e5e7eb;text-align:right;">Total (AED)</th>
  </tr>
  @foreach($items as $item)
  <tr>
    <td style="border-bottom:1px solid #f3f4f6;">{{ $item->name }}</td>
    <td style="border-bottom:1px solid #f3f4f6;">{{ $item->modelno ?: '-' }}</td>
    <td style="border-bottom:1px solid #f3f4f6;text-align:right;">{{ $item->quantity }}</td>
    <td style="border-bottom:1px solid #f3f4f6;text-align:right;">{{ number_format($item->price, 2) }}</td>
    <td style="border-bottom:1px solid #f3f4f6;text-align:right;">{{ number_format($item->price * $item->quantity, 2) }}</td>
  </tr>
  @endforeach
  <tr><td colspan="4" style="text-align:right;">Subtotal</td><td style="text-align:right;">{{ number_format($enquiry->subtotal, 2) }}</td></tr>
  <tr><td colspan="4" style="text-align:right;">VAT</td><td style="text-align:right;">{{ number_format($enquiry->tax, 2) }}</td></tr>
  <tr><td colspan="4" style="text-align:right;font-weight:bold;">Estimated total</td><td style="text-align:right;font-weight:bold;">{{ number_format($enquiry->total, 2) }}</td></tr>
</table>
