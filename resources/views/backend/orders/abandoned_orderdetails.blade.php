@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="page-title">Abandoned Cart</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <p>
                                <strong>{{ $customer ? trim($customer->fname . ' ' . $customer->lname) : 'Guest' }}</strong>
                                @if($customer && $customer->email) &middot; {{ $customer->email }}@endif
                                @if($customer && $customer->mobile) &middot; {{ $customer->mobile }}@endif
                            </p>

                            @php $value = 0; @endphp
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th class="text-right">Qty</th>
                                        <th class="text-right">Unit (AED)</th>
                                        <th class="text-right">Total (AED)</th>
                                        <th>Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $item)
                                        @php $value += $item->price * $item->quantity; @endphp
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->modelno ?: '-' }}</td>
                                            <td class="text-right">{{ $item->quantity }}</td>
                                            <td class="text-right">{{ number_format($item->price, 2) }}</td>
                                            <td class="text-right">{{ number_format($item->price * $item->quantity, 2) }}</td>
                                            <td>{{ $item->created_at ? $item->created_at->format('d M Y') : '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr><td colspan="4" class="text-right"><strong>Value (excl. VAT)</strong></td><td class="text-right"><strong>{{ number_format($value, 2) }}</strong></td><td></td></tr>
                                </tfoot>
                            </table>

                            <a href="{{ url('abandoned_order') }}" class="btn btn-default waves-effect">Back to Abandoned Carts</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
