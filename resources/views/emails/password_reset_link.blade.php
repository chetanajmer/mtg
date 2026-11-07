@extends('mail.layouts.brand')

@section('title', 'Reset your password')
@section('preheader', 'Choose a new password for your corporate portal account.')
@section('reason', 'You received this because a password reset was requested for this email address.')

@section('content')
  <p style="margin:0 0 12px;">We received a request to reset the password for your {{ config('company.name') }} corporate portal account.</p>
  @include('mail.partials.button', ['url' => $resetUrl, 'label' => 'Choose a new password'])
  <p style="margin:0;font-size:13px;color:#6a6e75;">This link expires in {{ $minutes }} minutes. If you didn't ask to reset your password, you can ignore this email; your password has not been changed.</p>
@endsection
