@extends('mail.layouts.brand', ['hero' => [
  'variant' => 'success',
  'title' => 'Message',
  'accent' => 'Received!',
  'text' => 'Thank you, ' . $name . '. Your message has been sent to our team.',
  'icon' => '&#10004;',
]])

@section('title', 'Thank you, ' . $name)
@section('preheader', 'We have received your message and will get back to you soon.')
@section('closing', 'We\'ll get back to you soon!')

@section('content')
  <p style="margin:0 0 10px;">We have received your message and our team will get back to you soon.</p>
  <p style="margin:0 0 10px;">If it is urgent, call us on <a href="tel:{{ preg_replace('/[^0-9+]/', '', config('company.phone')) }}" style="color:#f26b1d;font-weight:700;text-decoration:none;">{{ config('company.phone') }}</a> or email <a href="mailto:{{ config('company.email') }}" style="color:#f26b1d;font-weight:700;text-decoration:none;">{{ config('company.email') }}</a>.</p>
  @include('mail.partials.button', ['url' => url('/'), 'label' => 'Explore Our Gifts'])
@endsection
