<!DOCTYPE html>
<html>
<body style="margin:0;padding:24px;background:#f9fafb;font-family:Arial,Helvetica,sans-serif;color:#111827;">
  <div style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;padding:24px;">
    <h2 style="margin:0 0 4px;">New quote request #{{ $enquiry->orderid }}</h2>
    <p style="margin:0 0 16px;color:#6b7280;">Received {{ $enquiry->created_at->format('d M Y, H:i') }}</p>

    <p style="margin:0;"><strong>{{ $enquiry->name }}</strong>@if($enquiry->company) &middot; {{ $enquiry->company }}@endif</p>
    <p style="margin:4px 0 0;">{{ $enquiry->email }}@if($enquiry->phone) &middot; {{ $enquiry->phone }}@endif</p>

    @include('mail.partials.quote_items')

    @if($enquiry->message)
    <p style="margin:0 0 4px;"><strong>Customer message</strong></p>
    <p style="margin:0 0 16px;white-space:pre-line;">{{ $enquiry->message }}</p>
    @endif

    <p style="margin:16px 0 0;">
      <a href="{{ route('orders.index') }}" style="display:inline-block;background:#111827;color:#ffffff;padding:10px 18px;border-radius:6px;text-decoration:none;">Open Store Enquiries</a>
    </p>
  </div>
</body>
</html>
