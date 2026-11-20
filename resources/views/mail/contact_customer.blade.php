@extends('mail.layouts.brand')

@section('title', 'Thank you, ' . $name)
@section('preheader', 'We have received your message and will get back to you soon.')

@section('content')
  <p style="margin:0 0 10px;">We have received your message and our team will get back to you soon.</p>
  <p style="margin:0 0 10px;">If it is urgent, call us on <a href="tel:{{ preg_replace('/[^0-9+]/', '', config('company.phone')) }}" style="color:#9a6b16;">{{ config('company.phone') }}</a> or email <a href="mailto:{{ config('company.email') }}" style="color:#9a6b16;">{{ config('company.email') }}</a>.</p>
@endsection
