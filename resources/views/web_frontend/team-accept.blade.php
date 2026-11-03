@extends('web_frontend.layouts.app')

@section('title', 'Join your team | More Than Giftz')
@section('meta')
  <meta name="robots" content="noindex">
@endsection

@section('content')
@php $companyName = $company ? ($company->company ?: trim($company->fname . ' ' . $company->lname)) : 'your company'; @endphp
<style>
  .ta-wrap { max-width: 480px; margin: 40px auto 60px; padding: 0 16px; }
  .ta-card { background: #fff; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,.12); padding: 28px 24px; }
  .ta-card h1 { font-size: 24px; margin: 0 0 8px; }
  .ta-card p { color: #4b5563; }
  .ta-card label { display: block; font-weight: 600; font-size: 14px; margin: 14px 0 6px; color: #374151; }
  .ta-card input { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 15px; }
  .ta-card input[readonly] { background: #f3f4f6; color: #6b7280; }
  .ta-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
  .ta-btn { margin-top: 20px; width: 100%; padding: 12px; border: 0; border-radius: 6px; background: #15171a; color: #fff; font-weight: 600; font-size: 15px; cursor: pointer; }
  .ta-err { background: #fdecea; color: #b42318; border-radius: 8px; padding: 12px 14px; margin: 12px 0; font-size: 14px; }
  .ta-role { display: inline-block; padding: 2px 10px; border-radius: 999px; background: #e8effb; color: #2563c9; font-weight: 600; font-size: 13px; }
  .ta-card a { color: #8c5109; }
</style>
<div class="ta-wrap">
  <div class="ta-card">
    @if($problem)
      <h1>Invitation not available</h1>
      <div class="ta-err">{{ $problem }}</div>
      <p><a href="{{ route('corporate.login') }}">Go to the login page</a></p>
    @else
      <h1>Join {{ $companyName }}</h1>
      <p>You've been invited to use the {{ $companyName }} account on More Than Giftz as <span class="ta-role">{{ $member->roleLabel() }}</span>. Set your name and password to finish.</p>
      @if($errors->any())<div class="ta-err">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
      <form method="POST" action="{{ route('team.accept.submit', $token) }}">@csrf
        <label for="ta-email">Email</label>
        <input id="ta-email" type="email" value="{{ $member->email }}" readonly>
        <div class="ta-row">
          <div><label for="ta-fname">First name</label><input id="ta-fname" name="fname" value="{{ old('fname', explode(' ', $member->name)[0]) }}" required maxlength="100"></div>
          <div><label for="ta-lname">Last name</label><input id="ta-lname" name="lname" value="{{ old('lname', trim(substr($member->name, strlen(explode(' ', $member->name)[0])))) }}" maxlength="100"></div>
        </div>
        <label for="ta-pass">Password <small>(at least 8 characters)</small></label>
        <input id="ta-pass" type="password" name="password" required minlength="8" autocomplete="new-password">
        <label for="ta-pass2">Confirm password</label>
        <input id="ta-pass2" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password">
        <button class="ta-btn">Create my login</button>
      </form>
    @endif
  </div>
</div>
@endsection
