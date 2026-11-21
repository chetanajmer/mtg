@extends('mail.layouts.brand', ['hero' => [
  'variant' => 'success',
  'title' => 'Order',
  'accent' => 'Update',
  'text' => 'There is an update on order #' . ($order->orderid ?: $order->id) . '.',
  'icon' => '&#10004;',
]])

@section('title', 'Update on order #' . ($order->orderid ?: $order->id))
@section('preheader', $stepTitle . ($note ? ': ' . \Illuminate\Support\Str::limit($note, 90) : ''))

@section('content')
  <p style="margin:0 0 14px;">Hi {{ $order->customer_name ?: 'there' }},</p>
  @include('mail.partials.id_badge', ['label' => 'Order ID', 'id' => '#' . ($order->orderid ?: $order->id), 'dateLabel' => 'Current step', 'date' => $stepTitle])
  @if($note)
    <p style="margin:0 0 14px;white-space:pre-line;">{{ $note }}</p>
  @endif
  @if(!empty($button))
    @include('mail.partials.button', $button)
    <p style="margin:0 0 14px;font-size:12px;color:#6a6e75;">This button works without logging in and stays valid for {{ \App\Services\OrderWorkflow::LINK_DAYS }} days. Please don't forward this email.</p>
  @endif
  @if(!empty($orderUrl))
    <p style="margin:0 0 6px;font-size:13px;color:#4b4f56;"><a href="{{ $orderUrl }}" style="color:#f26b1d;font-weight:bold;">See your order's progress</a> (log in), or track it any time at <a href="{{ url('/track-order') }}" style="color:#f26b1d;">{{ preg_replace('#^https?://#', '', url('/track-order')) }}</a> with your order number and email.</p>
  @endif
@endsection

@if(empty($orderUrl))
  @section('reason', 'Staff notification from the ' . config('company.name') . ' website.')
@endif
