@extends('mail.layouts.brand', ['hero' => [
  'variant' => 'blue',
  'title' => 'Reset Your',
  'accent' => 'Password',
  'text' => 'We received a request to reset your password. Click the button below to create a new password.',
  'icon' => '&#128274;',
]])

@section('title', 'Reset your password')
@section('preheader', 'Choose a new password for your corporate portal account.')
@section('reason', 'You received this because a password reset was requested for this email address.')

@section('content')
  @include('mail.partials.button', ['url' => $resetUrl, 'label' => 'Reset Password'])
  <p style="margin:0;font-size:13px;color:#6a6e75;text-align:center;">This link will expire in <strong>{{ $minutes }} minutes</strong> for security reasons. If you didn't ask to reset your password, you can ignore this email; your password has not been changed.</p>
@endsection
