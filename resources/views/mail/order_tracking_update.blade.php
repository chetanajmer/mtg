@extends('mail.layouts.brand')

@section('title', 'Update on order #' . ($order->orderid ?: $order->id))
@section('preheader', $stepTitle . ($note ? ': ' . \Illuminate\Support\Str::limit($note, 90) : ''))

@section('content')
  <p style="margin:0 0 14px;">Hi {{ $order->customer_name ?: 'there' }},</p>
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 16px;">
    <tr>
      <td style="padding:12px 16px;background:#faf3e3;border-left:4px solid #c9a24a;border-radius:4px;font-family:Arial,Helvetica,sans-serif;">
        <span style="display:block;font-size:12px;letter-spacing:1px;text-transform:uppercase;color:#9a6b16;">Current step</span>
        <strong style="font-size:16px;color:#15171a;">{{ $stepTitle }}</strong>
      </td>
    </tr>
  </table>
  @if($note)
    <p style="margin:0 0 14px;white-space:pre-line;">{{ $note }}</p>
  @endif
  @if(!empty($button))
    @include('mail.partials.button', $button)
    <p style="margin:0 0 14px;font-size:12px;color:#6a6e75;">This button works without logging in and stays valid for {{ \App\Services\OrderWorkflow::LINK_DAYS }} days. Please don't forward this email.</p>
  @endif
  @if(!empty($orderUrl))
    <p style="margin:0 0 6px;font-size:13px;color:#4b4f56;"><a href="{{ $orderUrl }}" style="color:#9a6b16;font-weight:bold;">See your order's progress</a> (log in), or track it any time at <a href="{{ url('/track-order') }}" style="color:#9a6b16;">{{ preg_replace('#^https?://#', '', url('/track-order')) }}</a> with your order number and email.</p>
  @endif
@endsection

@if(empty($orderUrl))
  @section('reason', 'Staff notification from the ' . config('company.name') . ' website.')
@endif
