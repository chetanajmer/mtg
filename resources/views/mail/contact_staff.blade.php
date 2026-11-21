@extends('mail.layouts.brand', ['hero' => [
  'variant' => 'light',
  'title' => 'New Website',
  'accent' => 'Inquiry Received!',
  'text' => 'You have received a new inquiry from your website. Here are the details:',
  'icon' => '&#9993;',
]])

@section('title', 'New message from the contact form')
@section('preheader', $name . ': ' . \Illuminate\Support\Str::limit($msg, 90))
@section('reason', 'Sent from the Contact Us page of ' . config('company.name') . '. Reply to this email to answer the customer directly.')
@section('signoff')@endsection
@section('closing', 'Reply to this email to answer the customer directly.')

@section('content')
  @include('mail.partials.details', ['rows' => [
    ['&#128100;', 'Name', $name],
    ['&#9993;', 'Email', $email],
    ['&#128222;', 'Phone', $phone ?: '-'],
    ['&#128197;', 'Submitted On', now()->format('d M Y, h:i A')],
    ['&#128172;', 'Message', $msg],
  ]])

  @include('mail.partials.button', ['url' => 'mailto:' . $email, 'label' => 'Reply to ' . \Illuminate\Support\Str::limit($name, 24)])
@endsection
