@extends('mail.layouts.brand', ['hero' => [
  'variant' => 'welcome',
  'title' => 'Welcome to',
  'accent' => config('company.name') . '!',
  'text' => ($name ? 'Hi ' . $name . ', your' : 'Your') . ' account has been successfully created. Start exploring our premium corporate gifts.',
  'icon' => '&#127873;',
]])

@section('title', 'Welcome to ' . config('company.name') . ($name ? ', ' . $name : ''))
@section('preheader', 'Your corporate gifting account is ready.')
@section('reason', 'You received this because an account was created with this email address.')
@section('closing', 'Thank you for joining us!')

@section('content')
  <p style="margin:0 0 12px;">From your corporate portal you can:</p>
  <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 6px;font-family:'Nunito Sans',Arial,Helvetica,sans-serif;font-size:14px;">
    @foreach([
      'Build a quote cart and send it to us in one click',
      'Plan gifts by occasion, team size and budget',
      'Approve designs and samples, and follow every order step',
      'Save collections and download reports',
    ] as $line)
    <tr>
      <td valign="top" style="padding:4px 10px 4px 0;color:#f26b1d;font-weight:bold;">&#10003;</td>
      <td style="padding:4px 0;">{{ $line }}</td>
    </tr>
    @endforeach
  </table>
  @include('mail.partials.button', ['url' => url('/'), 'label' => 'Start Shopping'])
  <p style="margin:0;font-size:13px;color:#6a6e75;text-align:center;">Need help choosing? Reply to this email or call us, and our gifting team will help.</p>
@endsection
