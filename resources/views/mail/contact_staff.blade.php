@extends('mail.layouts.brand')

@section('title', 'New message from the contact form')
@section('preheader', $name . ': ' . \Illuminate\Support\Str::limit($msg, 90))
@section('reason', 'Sent from the Contact Us page of ' . config('company.name') . '.')
@section('signoff')@endsection

@section('content')
  <p style="margin:0 0 14px;font-size:13px;color:#6a6e75;">Reply to this email to answer the customer directly.</p>
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;font-family:Arial,Helvetica,sans-serif;font-size:14px;">
    <tr><td style="padding:9px 8px;border-bottom:1px solid #efe7da;width:110px;color:#6a6e75;">Name</td><td style="padding:9px 8px;border-bottom:1px solid #efe7da;">{{ $name }}</td></tr>
    <tr><td style="padding:9px 8px;border-bottom:1px solid #efe7da;color:#6a6e75;">Email</td><td style="padding:9px 8px;border-bottom:1px solid #efe7da;">{{ $email }}</td></tr>
    <tr><td style="padding:9px 8px;border-bottom:1px solid #efe7da;color:#6a6e75;">Phone</td><td style="padding:9px 8px;border-bottom:1px solid #efe7da;">{{ $phone ?: '-' }}</td></tr>
    <tr><td style="padding:9px 8px;color:#6a6e75;vertical-align:top;">Message</td><td style="padding:9px 8px;white-space:pre-wrap;">{{ $msg }}</td></tr>
  </table>
@endsection
