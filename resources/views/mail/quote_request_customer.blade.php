<!DOCTYPE html>
<html>
<body style="margin:0;padding:24px;background:#f9fafb;font-family:Arial,Helvetica,sans-serif;color:#111827;">
  <div style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;padding:24px;">
    <h2 style="margin:0 0 12px;">Thank you, {{ $enquiry->name }}</h2>
    <p style="margin:0 0 8px;">We have received your quote request <strong>#{{ $enquiry->orderid }}</strong>. Our team will review it and get back to you with a confirmed quote.</p>
    <p style="margin:0;color:#6b7280;">Prices below are estimates; the final quote may change with branding options and delivery.</p>

    @include('mail.partials.quote_items')

    <p style="margin:16px 0 0;">Kind regards,<br>More Than Giftz</p>
  </div>
</body>
</html>
