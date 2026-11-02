{{-- Products of one Store Enquiries order. Expects $orders (cartorders rows). --}}
@php $itemsTotal = 0; @endphp
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Product</th>
            <th>SKU</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Unit (AED)</th>
            <th class="text-right">Total (AED)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $row)
            @php
                $line = $row->subtotal !== null ? (float) $row->subtotal : ((float) $row->price * (int) $row->quantity);
                $itemsTotal += $line;
            @endphp
            <tr>
                <td>{{ $row->name }}</td>
                <td>{{ $row->modelno ?: '-' }}</td>
                <td class="text-right">{{ $row->quantity }}</td>
                <td class="text-right">{{ $row->price !== null ? number_format($row->price, 2) : '-' }}</td>
                <td class="text-right">{{ $line ? number_format($line, 2) : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        @if($itemsTotal > 0)
        <tr><td colspan="4" class="text-right">Products</td><td class="text-right">{{ number_format($itemsTotal, 2) }}</td></tr>
        @endif
        <tr><td colspan="4" class="text-right"><strong>Order total{{ $itemsTotal > 0 ? ' (incl. VAT)' : '' }}</strong></td><td class="text-right"><strong>{{ number_format((float) $orders->first()->ordertotal, 2) }}</strong></td></tr>
    </tfoot>
</table>
