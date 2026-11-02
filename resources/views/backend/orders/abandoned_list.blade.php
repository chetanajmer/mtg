@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="page-title">Abandoned Carts</h4>
                        <p class="text-muted">Quote carts with items and no activity for 24 hours or more.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <div class="table-responsive">
                                <table class="table table-actions-bar">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Products</th>
                                            <th>Pieces</th>
                                            <th>Value (AED)</th>
                                            <th>Last activity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($items_array as $cart)
                                            <tr>
                                                <td>{{ $cart->customer }}@if($cart->email)<br><small>{{ $cart->email }}</small>@endif</td>
                                                <td>{{ $cart->products }}</td>
                                                <td>{{ $cart->quantity }}</td>
                                                <td>{{ number_format($cart->value, 2) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($cart->last_activity)->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('abandoned_orderdetails/' . $cart->key) }}" class="btn btn-sm btn-info waves-effect waves-light">View</a>
                                                    @if($cart->email)
                                                        <button type="button" class="btn btn-sm btn-default waves-effect send-reminder" data-id="{{ $cart->key }}">Send reminder</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" style="text-align: center;">No abandoned carts.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('.send-reminder').forEach(function (button) {
        button.addEventListener('click', function () {
            if (!confirm('Email this customer a reminder about their cart?')) return;
            button.disabled = true;
            fetch('{{ route('send_abandoned_mail') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ id: button.dataset.id })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) { alert(data.message); })
            .catch(function () { alert('Could not send the reminder.'); })
            .finally(function () { button.disabled = false; });
        });
    });
    </script>
@endsection
