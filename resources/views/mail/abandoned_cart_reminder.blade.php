@extends('mail.layouts.brand')

@section('title', 'You left some items in your quote cart')
@section('preheader', 'Your cart is saved. Send it to us whenever you are ready.')

@section('content')
  <p style="margin:0 0 14px;">Hi {{ $customer->fname ?: 'there' }}, your cart is saved. Send it to us whenever you're ready and we'll prepare a quote.</p>
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;font-family:Arial,Helvetica,sans-serif;font-size:14px;margin:0 0 4px;">
    @foreach($items as $item)
    <tr>
      <td style="padding:10px 8px;border-bottom:1px solid #efe7da;">{{ $item->name }}</td>
      <td align="right" style="padding:10px 8px;border-bottom:1px solid #efe7da;white-space:nowrap;color:#6a6e75;">{{ $item->quantity }} pcs</td>
    </tr>
    @endforeach
  </table>
  @include('mail.partials.button', ['url' => url('/cart'), 'label' => 'Open my cart'])
@endsection
