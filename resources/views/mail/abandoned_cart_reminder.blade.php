<!DOCTYPE html>
<html>
<body style="margin:0;padding:24px;background:#f9fafb;font-family:Arial,Helvetica,sans-serif;color:#111827;">
  <div style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;padding:24px;">
    <h2 style="margin:0 0 12px;">You left some items in your quote cart</h2>
    <p style="margin:0 0 16px;">Hi {{ $customer->fname }}, your cart is saved. Send it to us whenever you're ready and we'll prepare a quote.</p>

    <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;font-size:14px;margin:0 0 16px;">
      @foreach($items as $item)
      <tr>
        <td style="border-bottom:1px solid #f3f4f6;">{{ $item->name }}</td>
        <td style="border-bottom:1px solid #f3f4f6;text-align:right;">{{ $item->quantity }} pcs</td>
      </tr>
      @endforeach
    </table>

    <p style="margin:0 0 16px;">
      <a href="{{ url('/cart') }}" style="display:inline-block;background:#111827;color:#ffffff;padding:10px 18px;border-radius:6px;text-decoration:none;">Open my cart</a>
    </p>
    <p style="margin:0;">Kind regards,<br>More Than Giftz</p>
  </div>
</body>
</html>
