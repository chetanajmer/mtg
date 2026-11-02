<!DOCTYPE html>
<html>
<body style="margin:0;padding:24px;background:#f9fafb;font-family:Arial,Helvetica,sans-serif;color:#111827;">
  <div style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;padding:24px;">
    <h2 style="margin:0 0 12px;">New message from the website contact form</h2>
    <p style="margin:0 0 16px;color:#6b7280;">Reply to this email to answer the customer directly.</p>
    <table style="width:100%;border-collapse:collapse;font-size:14px;">
      <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;width:120px;color:#6b7280;">Name</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">{{ $name }}</td></tr>
      <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;color:#6b7280;">Email</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">{{ $email }}</td></tr>
      <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;color:#6b7280;">Phone</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;">{{ $phone ?: '-' }}</td></tr>
      <tr><td style="padding:8px;color:#6b7280;vertical-align:top;">Message</td><td style="padding:8px;white-space:pre-wrap;">{{ $msg }}</td></tr>
    </table>
    <p style="margin:16px 0 0;color:#6b7280;font-size:12px;">Sent from the Contact Us page of More Than Giftz.</p>
  </div>
</body>
</html>
