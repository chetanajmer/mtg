@extends('mail.layouts.brand')

@section('title', "You're invited to " . $company)
@section('preheader', ($inviter ?: 'A colleague') . ' invited you to the ' . $company . ' account on ' . config('company.name') . '.')
@section('reason', 'You received this because someone at ' . $company . ' invited this email address.')

@section('content')
  <p style="margin:0 0 12px;">Hi {{ $member->name }},</p>
  <p style="margin:0 0 12px;">{{ $inviter ?: 'A colleague' }} has invited you to use the {{ $company }} account on {{ config('company.name') }} as <strong>{{ $role }}</strong>. You'll see the company's orders, quotes and saved collections.</p>
  @include('mail.partials.button', ['url' => $link, 'label' => 'Set up my login'])
  <p style="margin:0;font-size:13px;color:#6a6e75;">The link works for {{ $days }} days. If you weren't expecting this, you can ignore this email.</p>
@endsection
