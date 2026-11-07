@extends('mail.layouts.brand')

@section('title', 'Welcome to ' . config('company.name') . ($name ? ', ' . $name : ''))
@section('preheader', 'Your corporate gifting account is ready.')
@section('reason', 'You received this because an account was created with this email address.')

@section('content')
  <p style="margin:0 0 12px;">Your account is ready. From your corporate portal you can:</p>
  <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 6px;font-family:Arial,Helvetica,sans-serif;font-size:14px;">
    @foreach([
      'Build a quote cart and send it to us in one click',
      'Plan gifts by occasion, team size and budget',
      'Approve designs and samples, and follow every order step',
      'Save collections and download reports',
    ] as $line)
    <tr>
      <td valign="top" style="padding:4px 10px 4px 0;color:#c9a24a;font-weight:bold;">&#10003;</td>
      <td style="padding:4px 0;">{{ $line }}</td>
    </tr>
    @endforeach
  </table>
  @include('mail.partials.button', ['url' => url('/corporate-portal'), 'label' => 'Go to my account'])
  <p style="margin:0;font-size:13px;color:#6a6e75;">Need help choosing? Reply to this email or call us, and our gifting team will help.</p>
@endsection
