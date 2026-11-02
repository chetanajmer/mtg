<!DOCTYPE html>
<html>
<body style="margin:0;padding:24px;background:#f9fafb;font-family:Arial,Helvetica,sans-serif;color:#111827;">
  <div style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;padding:24px;">
    <h2 style="margin:0 0 12px;">Update on order #{{ $order->orderid ?: $order->id }}</h2>
    <p style="margin:0 0 12px;">Hi {{ $order->customer_name ?: 'there' }}, there is a new update: <strong>{{ $stepTitle }}</strong>.</p>
    @if($note)
      <p style="margin:0 0 12px;white-space:pre-line;">{{ $note }}</p>
    @endif
    @if(!empty($button))
      <p style="margin:20px 0;">
        <a href="{{ $button['url'] }}" style="display:inline-block;padding:12px 22px;background:#15171a;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:bold;">{{ $button['label'] }}</a>
      </p>
      <p style="margin:0 0 12px;font-size:12px;color:#6b7280;">This button works without logging in and stays valid for {{ \App\Services\OrderWorkflow::LINK_DAYS }} days. Please don't forward this email.</p>
    @endif
    @if(!empty($orderUrl))
      <p style="margin:0 0 12px;font-size:13px;"><a href="{{ $orderUrl }}" style="color:#2563c9;">See your order's progress</a> (log in) or track it at <a href="{{ url('/track-order') }}" style="color:#2563c9;">{{ url('/track-order') }}</a> with your order number and email.</p>
    @endif
    <p style="margin:16px 0 0;">Kind regards,<br>More Than Giftz</p>
  </div>
</body>
</html>
