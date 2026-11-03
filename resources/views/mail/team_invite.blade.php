<!DOCTYPE html>
<html>
<body style="margin:0;padding:24px;background:#f9fafb;font-family:Arial,Helvetica,sans-serif;color:#111827;">
  <div style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;padding:24px;">
    <h2 style="margin:0 0 12px;">You're invited to {{ $company }}</h2>
    <p style="margin:0 0 12px;">Hi {{ $member->name }},</p>
    <p style="margin:0 0 12px;">{{ $inviter ?: 'A colleague' }} has invited you to use the {{ $company }} account on More Than Giftz as <strong>{{ $role }}</strong>. You'll see the company's orders, quotes and saved collections.</p>
    <p style="margin:20px 0;"><a href="{{ $link }}" style="background:#15171a;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:6px;font-weight:bold;display:inline-block;">Set up my login</a></p>
    <p style="margin:0 0 8px;color:#6b7280;font-size:13px;">The link works for {{ $days }} days. If you weren't expecting this, you can ignore this email.</p>
    <p style="margin:16px 0 0;">Kind regards,<br>More Than Giftz</p>
  </div>
</body>
</html>
