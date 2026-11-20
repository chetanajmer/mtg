@extends('layouts.app')

@section('content')
@php
    $current = $order->state($selected);
    $type = \App\Services\OrderWorkflow::type($selected);
    $data = (array) $current->data;
    $openBefore = $order->stepStates->filter(function ($s) use ($selected) { return $s->step < $selected && $s->state !== 'done'; });
    $blocked = $openBefore->isNotEmpty();
    $daysInStep = $order->step_started_at ? $order->step_started_at->diffInDays(now()) : 0;
    $stepEvents = $order->events->where('step', $selected);
    $showAll = request('timeline') === 'all';
    $timeline = $showAll ? $order->events : $stepEvents;
    $isImage = function ($p) { return preg_match('/\.(jpe?g|png|webp|gif|svg)$/i', $p); };
    $old = function ($k, $d = '') { return old($k, $d); };
    $formErr = session('form');
@endphp
<style>
.ow { --ow-ink:#1d2430; --ow-muted:#6b7480; --ow-line:#e3e7ec; --ow-bg:#f5f7f9; --ow-card:#fff; --ow-accent:#b5761a; --ow-accent-soft:#fbf1e1;
      --ow-done:#1f8a4c; --ow-done-soft:#e3f4ea; --ow-wait:#6a4fc4; --ow-wait-soft:#eeeafb; --ow-bad:#c0392b; --ow-bad-soft:#fbe7e4; --ow-blue:#2563c9; --ow-blue-soft:#e6eefb;
      font-family: "Segoe UI", Roboto, Arial, sans-serif; color: var(--ow-ink); font-size: 14px; }
.ow * { box-sizing: border-box; }
.ow a { color: var(--ow-blue); }
.ow-card { background: var(--ow-card); border: 1px solid var(--ow-line); border-radius: 12px; }
.ow-head { display: flex; flex-wrap: wrap; gap: 20px; justify-content: space-between; align-items: flex-start; padding: 22px 24px; margin-bottom: 16px; }
.ow-head h1 { font-size: 24px; font-weight: 600; margin: 4px 0 6px; color: var(--ow-ink); }
.ow-back { font-size: 13px; text-decoration: none; }
.ow-cust { color: var(--ow-muted); line-height: 1.6; }
.ow-cust strong { color: var(--ow-ink); }
.ow-figs { display: flex; gap: 28px; flex-wrap: wrap; }
.ow-fig small { display: block; font-size: 11px; text-transform: uppercase; letter-spacing: .06em; color: var(--ow-muted); }
.ow-fig b { font-size: 18px; font-weight: 600; font-variant-numeric: tabular-nums; }
.ow-pill { display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
.ow-pill.done { background: var(--ow-done-soft); color: var(--ow-done); }
.ow-pill.in_progress { background: var(--ow-blue-soft); color: var(--ow-blue); }
.ow-pill.waiting_customer { background: var(--ow-wait-soft); color: var(--ow-wait); }
.ow-pill.changes_requested { background: var(--ow-bad-soft); color: var(--ow-bad); }
.ow-pill.not_started { background: var(--ow-bg); color: var(--ow-muted); }
.ow-strip { display: grid; grid-template-columns: repeat(10, 1fr); gap: 4px; padding: 14px 16px; margin-bottom: 16px; }
.ow-strip a { display: block; text-decoration: none; text-align: center; color: var(--ow-muted); font-size: 11px; line-height: 1.3; }
.ow-strip span { display: block; height: 6px; border-radius: 3px; background: var(--ow-line); margin-bottom: 6px; }
.ow-strip .done span { background: var(--ow-done); } .ow-strip .in_progress span { background: var(--ow-blue); }
.ow-strip .waiting_customer span { background: var(--ow-wait); } .ow-strip .changes_requested span { background: var(--ow-bad); }
.ow-strip .sel { color: var(--ow-ink); font-weight: 600; }
.ow-grid { display: grid; grid-template-columns: 260px minmax(0, 1fr); gap: 16px; align-items: start; }
.ow-steps { padding: 8px; }
.ow-steps a { display: grid; grid-template-columns: 28px 1fr; gap: 10px; padding: 10px; border-radius: 8px; text-decoration: none; color: var(--ow-ink); align-items: center; }
.ow-steps a:hover { background: var(--ow-bg); }
.ow-steps a.sel { background: var(--ow-accent-soft); }
.ow-num { width: 28px; height: 28px; border-radius: 50%; display: grid; place-items: center; font-weight: 600; font-size: 12px; background: var(--ow-bg); color: var(--ow-muted); border: 1px solid var(--ow-line); }
.ow-num.done { background: var(--ow-done); color: #fff; border-color: var(--ow-done); }
.ow-num.in_progress { background: var(--ow-blue); color: #fff; border-color: var(--ow-blue); }
.ow-num.waiting_customer { background: var(--ow-wait); color: #fff; border-color: var(--ow-wait); }
.ow-num.changes_requested { background: var(--ow-bad); color: #fff; border-color: var(--ow-bad); }
.ow-steps .t { font-weight: 500; line-height: 1.3; } .ow-steps .s { font-size: 12px; color: var(--ow-muted); }
.ow-main { display: grid; gap: 16px; min-width: 0; }
.ow-step { padding: 22px 24px; }
.ow-step-top { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center; }
.ow-step h2 { font-size: 19px; font-weight: 600; margin: 0; color: var(--ow-ink); }
.ow-hint { color: var(--ow-muted); margin: 6px 0 18px; }
.ow-facts { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; background: var(--ow-bg); border-radius: 10px; padding: 14px 16px; margin-bottom: 18px; }
.ow-facts small { display: block; font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: var(--ow-muted); }
.ow-facts b { font-weight: 600; word-break: break-word; }
.ow-form { border: 1px solid var(--ow-line); border-radius: 10px; padding: 16px; margin-bottom: 14px; }
.ow-form h3 { font-size: 15px; font-weight: 600; margin: 0 0 12px; color: var(--ow-ink); }
.ow-fields { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 12px; }
.ow-field label { display: block; font-size: 12px; font-weight: 600; color: var(--ow-muted); margin-bottom: 4px; }
.ow-field input, .ow-field select, .ow-field textarea { width: 100%; border: 1px solid #cfd5dc; border-radius: 8px; padding: 8px 10px; font: inherit; background: #fff; color: var(--ow-ink); }
.ow-field textarea { min-height: 70px; resize: vertical; }
.ow-field.full { grid-column: 1 / -1; }
.ow-checks { display: grid; gap: 6px; }
.ow-checks label { display: flex; gap: 8px; align-items: center; font-weight: 400; color: var(--ow-ink); font-size: 14px; }
.ow-checks input { width: auto; }
.ow-actions { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; margin-top: 14px; }
.ow-btn { border: 0; border-radius: 8px; padding: 9px 16px; font-weight: 600; cursor: pointer; font: inherit; font-weight: 600; }
.ow-btn.primary { background: var(--ow-ink); color: #fff; }
.ow-btn.good { background: var(--ow-done); color: #fff; }
a.ow-btn { display: inline-block; text-decoration: none; }
.ow-invoice { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin: 14px 0; padding: 12px 14px; border: 1px solid #cfe6d5; background: #f2faf4; border-radius: 10px; }
.ow-invoice b, .ow-invoice small { display: block; }
.ow-invoice small { color: #4b5563; margin-top: 2px; }
.ow-invoice form { margin: 0; }
.ow-btn.warn { background: #fff; color: var(--ow-bad); border: 1px solid var(--ow-bad); }
.ow-btn:focus-visible, .ow a:focus-visible { outline: 2px solid var(--ow-accent); outline-offset: 2px; }
.ow-alert { border-radius: 10px; padding: 12px 14px; margin-bottom: 14px; }
.ow-alert.err { background: var(--ow-bad-soft); color: var(--ow-bad); }
.ow-alert.ok { background: var(--ow-done-soft); color: var(--ow-done); }
.ow-alert.info { background: var(--ow-accent-soft); color: #7a4d0f; }
.ow-tl { padding: 18px 24px; }
.ow-tl-top { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 8px; flex-wrap: wrap; }
.ow-tl h3 { font-size: 16px; font-weight: 600; margin: 0; }
.ow-ev { display: grid; grid-template-columns: 12px 1fr; gap: 12px; padding: 12px 0; border-top: 1px solid var(--ow-line); }
.ow-dot { width: 10px; height: 10px; border-radius: 50%; margin-top: 5px; background: var(--ow-line); }
.ow-dot.customer { background: var(--ow-accent); }
.ow-meta { font-size: 12px; color: var(--ow-muted); display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
.ow-vis { font-size: 11px; padding: 1px 7px; border-radius: 999px; background: var(--ow-bg); }
.ow-vis.customer { background: var(--ow-accent-soft); color: #7a4d0f; }
.ow-files { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px; }
.ow-files a { display: block; width: 72px; height: 72px; border: 1px solid var(--ow-line); border-radius: 8px; overflow: hidden; font-size: 11px; text-align: center; line-height: 72px; text-decoration: none; background: var(--ow-bg); }
.ow-files img { width: 100%; height: 100%; object-fit: cover; }
.ow-items { padding: 18px 24px; overflow-x: auto; }
.ow-items table { width: 100%; border-collapse: collapse; min-width: 520px; }
.ow-items th, .ow-items td { text-align: left; padding: 9px 8px; border-bottom: 1px solid var(--ow-line); }
.ow-items th { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: var(--ow-muted); }
.ow-items td.n { text-align: right; font-variant-numeric: tabular-nums; }
@media (max-width: 900px) { .ow-grid { grid-template-columns: 1fr; } .ow-strip a { font-size: 0; } }
</style>

<div class="content-page"><div class="content"><div class="container-fluid ow">

    <div class="ow-card ow-head">
        <div>
            <a class="ow-back" href="{{ route('orders.index') }}">← Store Enquiries</a>
            <h1>Order #{{ $order->orderid }}</h1>
            <div class="ow-cust">
                <strong>{{ $order->customer_name ?: 'Customer' }}</strong>@if($order->customer_company) · {{ $order->customer_company }}@endif<br>
                @if($order->customer_email){{ $order->customer_email }}@endif @if($order->customer_phone) · {{ $order->customer_phone }}@endif
            </div>
        </div>
        <div class="ow-figs">
            <div class="ow-fig"><small>Order value</small><b>{{ $order->totalLabel() }}</b></div>
            <div class="ow-fig"><small>Received</small><b>{{ optional($order->created_at)->format('d M Y') }}</b></div>
            <div class="ow-fig"><small>Now at</small><b>Step {{ $order->current_step }}</b> <span class="ow-pill {{ $order->step_state }}">{{ \App\Models\OrderStepState::label($order->step_state) }}</span></div>
            <div class="ow-fig"><small>In this step</small><b>{{ $daysInStep }} {{ $daysInStep == 1 ? 'day' : 'days' }}</b></div>
        </div>
    </div>

    <nav class="ow-card ow-strip" aria-label="Order progress">
        @foreach($steps as $n => $s)
            @php $st = $order->state($n)->state; @endphp
            <a href="?step={{ $n }}" class="{{ $st }} {{ $n == $selected ? 'sel' : '' }}" title="{{ $n }}. {{ $s['title'] }} · {{ \App\Models\OrderStepState::label($st) }}"><span></span>{{ $n }}. {{ \Illuminate\Support\Str::limit($s['title'], 14) }}</a>
        @endforeach
    </nav>

    <div class="ow-grid">
        <nav class="ow-card ow-steps" aria-label="Steps">
            @foreach($steps as $n => $s)
                @php $st = $order->state($n); @endphp
                <a href="?step={{ $n }}" class="{{ $n == $selected ? 'sel' : '' }}">
                    <span class="ow-num {{ $st->state }}">{{ $st->state === 'done' ? '✓' : $n }}</span>
                    <span><span class="t">{{ $s['title'] }}</span><br><span class="s">{{ \App\Models\OrderStepState::label($st->state) }}@if($st->completed_at) · {{ $st->completed_at->format('d M') }}@endif</span></span>
                </a>
            @endforeach
        </nav>

        <div class="ow-main">
            <section class="ow-card ow-step">
                @if(session('success'))<div class="ow-alert ok">{{ session('success') }}</div>@endif
                @if($errors->any())
                    <div class="ow-alert err">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                @endif

                <div class="ow-step-top">
                    <h2>Step {{ $selected }} · {{ $steps[$selected]['title'] }}</h2>
                    <span class="ow-pill {{ $current->state }}">{{ \App\Models\OrderStepState::label($current->state) }}</span>
                </div>
                <p class="ow-hint">{{ $steps[$selected]['hint'] }}</p>

                {{-- What has been recorded for this step --}}
                @php
                    $facts = [];
                    if ($type === 'payment') { $facts['Amount due'] = 'AED ' . number_format(\App\Services\OrderWorkflow::amountDue($order, $selected), 2); }
                    $labels = ['confirmed_total' => 'Confirmed total', 'round' => 'Versions sent', 'amount' => 'Amount received', 'method' => 'Method', 'reference' => 'Reference',
                        'paid_on' => 'Paid on', 'start_date' => 'Started', 'expected_finish' => 'Expected finish', 'finished_on' => 'Finished', 'packed_qty' => 'Packed pieces',
                        'courier' => 'Courier', 'tracking_number' => 'Tracking number', 'dispatch_date' => 'Dispatched', 'delivered_on' => 'Delivered', 'received_by' => 'Received by', 'rating' => 'Rating', 'last_decision' => 'Last customer decision'];
                    foreach ($labels as $k => $label) {
                        if (!isset($data[$k]) || $data[$k] === '') continue;
                        $v = $data[$k];
                        if (in_array($k, ['confirmed_total', 'amount'])) $v = 'AED ' . number_format($v, 2);
                        if ($k === 'method') $v = \App\Services\OrderWorkflow::PAYMENT_METHODS[$v] ?? $v;
                        if ($k === 'last_decision') $v = \App\Services\OrderWorkflow::DECISIONS[$v] ?? $v;
                        if (preg_match('/^\d{4}-\d{2}-\d{2}/', (string) $v)) $v = \Carbon\Carbon::parse($v)->format('d M Y');
                        if ($k === 'rating') $v = str_repeat('★', (int) $v) . str_repeat('☆', 5 - (int) $v);
                        $facts[$label] = $v;
                    }
                @endphp
                @if($facts)
                    <div class="ow-facts">@foreach($facts as $k => $v)<div><small>{{ $k }}</small><b>{{ $v }}</b></div>@endforeach</div>
                @endif

                @if($type === 'approval' && $current->state === 'changes_requested' && ($data['last_decision'] ?? null) === 'rejected')
                    <div class="ow-alert err">The customer <b>rejected</b> version {{ $data['round'] }}. Read their reason and any files they sent in the timeline below, then send a new version.</div>
                @endif
                @php $ideas = $type === 'approval' ? $stepEvents->where('type', 'idea')->count() : 0; @endphp
                @if($ideas)
                    <div class="ow-alert info">The customer shared {{ $ideas }} design idea{{ $ideas > 1 ? 's' : '' }} / file set{{ $ideas > 1 ? 's' : '' }} on this step. See the timeline below.</div>
                @endif

                {{-- Steps 3 and 7: payment invoice, issued once the payment is recorded --}}
                @php $invoiceType = \App\Services\OrderInvoices::typeForStep($selected); @endphp
                @if($invoiceType && $current->state === 'done')
                    @php $stepInvoice = $order->invoices->firstWhere('type', $invoiceType); $invoiceLabel = \App\Services\OrderInvoices::TYPES[$invoiceType]['label']; @endphp
                    <div class="ow-invoice">
                        @if($stepInvoice)
                            <div><b>Invoice {{ $stepInvoice->number }}</b><small>Issued {{ optional($stepInvoice->issued_at)->format('d M Y, H:i') }}{{ $stepInvoice->issued_by ? ' by ' . $stepInvoice->issued_by : '' }} · AED {{ number_format($stepInvoice->amount, 2) }} received · visible to the customer</small></div>
                            <a class="ow-btn primary" href="{{ route('orders.invoice.show', [$order->id, $stepInvoice->id]) }}" target="_blank" rel="noopener">View / print invoice</a>
                        @else
                            <div><b>{{ ucfirst($invoiceLabel) }} invoice</b><small>Issue the invoice confirming the AED {{ number_format($data['amount'] ?? 0, 2) }} {{ $invoiceType }} was received. The customer will see it on their order page and get an email.</small></div>
                            <form method="POST" action="{{ route('orders.invoice.issue', [$order->id, $invoiceType]) }}" onsubmit="return confirm('Issue the {{ $invoiceLabel }} invoice now? It gets a permanent number and is shown to the customer.');">@csrf
                                <button class="ow-btn good">Generate invoice</button>
                            </form>
                        @endif
                    </div>
                @endif

                @if($blocked && $current->state !== 'done')
                    <div class="ow-alert info">Finish step {{ $openBefore->first()->step }} ({{ $steps[$openBefore->first()->step]['title'] }}) before working on this step.@if($isSuper) As a superadmin you can tick "Override order" on a form to skip ahead.@endif</div>
                @endif

                @php $canAct = $current->state !== 'done' && (!$blocked || $isSuper); @endphp
                @if($canAct)
                    @php
                        $form = function ($action) use ($order, $selected) { return route('orders.act', [$order->id, $selected, $action]); };
                        $override = $blocked && $isSuper;
                    @endphp

                    @if($type === 'review')
                        <form class="ow-form" method="POST" action="{{ $form('confirm') }}">@csrf
                            <h3>Confirm the order</h3>
                            <p style="margin:0 0 10px;color:#7a5200">@if($order->isCustom())Custom request: agree the price, then enter the total including VAT.@else Enter the agreed total including VAT.@endif It becomes the order value and sets the line prices and payment amounts.</p>
                            <div class="ow-fields">
                                <div class="ow-field"><label for="confirmed_total">Confirmed total (AED)</label><input id="confirmed_total" name="confirmed_total" type="number" step="0.01" min="0" required value="{{ $old('confirmed_total', $order->total) }}"></div>
                                <div class="ow-field full"><label for="msg_confirm">Message to the customer (optional)</label><textarea id="msg_confirm" name="message">{{ $formErr === 'confirm' ? old('message') : '' }}</textarea></div>
                            </div>
                            <div class="ow-actions">@if($override)<label><input type="checkbox" name="override" value="1"> Override order</label>@endif<button class="ow-btn good">Confirm order</button></div>
                        </form>
                    @endif

                    @if($type === 'approval')
                        @if($current->state !== 'waiting_customer')
                            <form class="ow-form" method="POST" action="{{ $form('send') }}" enctype="multipart/form-data">@csrf
                                <h3>{{ empty($data['round']) ? 'Send version 1 to the customer' : 'Send version ' . ($data['round'] + 1) . ' to the customer' }}</h3>
                                <div class="ow-fields">
                                    <div class="ow-field full"><label for="files_send">{{ $selected == 2 ? 'Mockup files' : 'Sample photos' }} (images or PDF, up to 10 MB each)</label><input id="files_send" name="files[]" type="file" multiple required accept=".jpg,.jpeg,.png,.webp,.pdf"></div>
                                    <div class="ow-field full"><label for="msg_send">Message to the customer</label><textarea id="msg_send" name="message" placeholder="What changed in this version, what to check">{{ $formErr === 'send' ? old('message') : '' }}</textarea></div>
                                </div>
                                <div class="ow-actions">@if($override)<label><input type="checkbox" name="override" value="1"> Override order</label>@endif<button class="ow-btn primary">Send to customer</button></div>
                            </form>
                        @else
                            <form class="ow-form" method="POST" action="{{ $form('decision') }}">@csrf
                                <h3>Customer's decision on version {{ $data['round'] }}</h3>
                                <div class="ow-fields">
                                    <div class="ow-field full"><label for="msg_decision">Customer's comments (required when changes are requested or the version is rejected)</label><textarea id="msg_decision" name="message">{{ $formErr === 'decision' ? old('message') : '' }}</textarea></div>
                                </div>
                                <div class="ow-actions">
                                    <button class="ow-btn good" name="decision" value="approved">Customer approved</button>
                                    <button class="ow-btn warn" name="decision" value="changes">Customer requested changes</button>
                                    <button class="ow-btn warn" name="decision" value="rejected">Customer rejected</button>
                                </div>
                            </form>
                        @endif
                    @endif

                    @if($type === 'payment')
                        <form class="ow-form" method="POST" action="{{ $form('record') }}" enctype="multipart/form-data">@csrf
                            <h3>Record the payment</h3>
                            <div class="ow-fields">
                                <div class="ow-field"><label for="amount">Amount received (AED)</label><input id="amount" name="amount" type="number" step="0.01" min="0.01" required value="{{ $old('amount', \App\Services\OrderWorkflow::amountDue($order, $selected)) }}"></div>
                                <div class="ow-field"><label for="method">Method</label><select id="method" name="method" required>@foreach(\App\Services\OrderWorkflow::PAYMENT_METHODS as $k => $v)<option value="{{ $k }}" {{ $old('method') === $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
                                <div class="ow-field"><label for="reference">Reference / transaction no.</label><input id="reference" name="reference" required value="{{ $old('reference') }}"></div>
                                <div class="ow-field"><label for="paid_on">Received on</label><input id="paid_on" name="paid_on" type="date" required value="{{ $old('paid_on', date('Y-m-d')) }}"></div>
                                <div class="ow-field full"><label for="files_pay">Receipt (optional)</label><input id="files_pay" name="files[]" type="file" multiple accept=".jpg,.jpeg,.png,.webp,.pdf"></div>
                                <div class="ow-field full"><label for="msg_pay">Message to the customer (optional)</label><textarea id="msg_pay" name="message">{{ $formErr === 'record' ? old('message') : '' }}</textarea></div>
                            </div>
                            <div class="ow-actions">@if($override)<label><input type="checkbox" name="override" value="1"> Override order</label>@endif<button class="ow-btn good">Record payment</button></div>
                        </form>
                    @endif

                    @if($type === 'production')
                        @if(empty($data['start_date']))
                            <form class="ow-form" method="POST" action="{{ $form('start') }}">@csrf
                                <h3>Start production</h3>
                                <div class="ow-fields">
                                    <div class="ow-field"><label for="start_date">Start date</label><input id="start_date" name="start_date" type="date" required value="{{ $old('start_date', date('Y-m-d')) }}"></div>
                                    <div class="ow-field"><label for="expected_finish">Expected finish</label><input id="expected_finish" name="expected_finish" type="date" required value="{{ $old('expected_finish') }}"></div>
                                    <div class="ow-field full"><label for="msg_start">Message to the customer (optional)</label><textarea id="msg_start" name="message">{{ $formErr === 'start' ? old('message') : '' }}</textarea></div>
                                </div>
                                <div class="ow-actions">@if($override)<label><input type="checkbox" name="override" value="1"> Override order</label>@endif<button class="ow-btn primary">Start production</button></div>
                            </form>
                        @else
                            <form class="ow-form" method="POST" action="{{ $form('finish') }}">@csrf
                                <h3>Finish production</h3>
                                <div class="ow-fields">
                                    <div class="ow-field"><label for="finished_on">Finished on</label><input id="finished_on" name="finished_on" type="date" required value="{{ $old('finished_on', date('Y-m-d')) }}"></div>
                                    <div class="ow-field full"><label for="msg_finish">Message to the customer (optional)</label><textarea id="msg_finish" name="message">{{ $formErr === 'finish' ? old('message') : '' }}</textarea></div>
                                </div>
                                <div class="ow-actions"><button class="ow-btn good">Mark production finished</button></div>
                            </form>
                        @endif
                    @endif

                    @if($type === 'checklist')
                        <form class="ow-form" method="POST" action="{{ $form('complete') }}" enctype="multipart/form-data">@csrf
                            <h3>Quality check</h3>
                            <div class="ow-fields">
                                <div class="ow-field full ow-checks">
                                    @foreach(\App\Services\OrderWorkflow::QC_CHECKS as $k => $v)
                                        <label><input type="checkbox" name="checks[]" value="{{ $k }}" {{ in_array($k, (array) old('checks', [])) ? 'checked' : '' }}> {{ $v }}</label>
                                    @endforeach
                                </div>
                                <div class="ow-field"><label for="packed_qty">Pieces packed</label><input id="packed_qty" name="packed_qty" type="number" min="1" required value="{{ $old('packed_qty', $order->items->sum('quantity')) }}"></div>
                                <div class="ow-field"><label for="files_qc">QC / packing photos (optional)</label><input id="files_qc" name="files[]" type="file" multiple accept=".jpg,.jpeg,.png,.webp,.pdf"></div>
                                <div class="ow-field full"><label for="msg_qc">Message to the customer (optional)</label><textarea id="msg_qc" name="message">{{ $formErr === 'complete' ? old('message') : '' }}</textarea></div>
                            </div>
                            <div class="ow-actions">@if($override)<label><input type="checkbox" name="override" value="1"> Override order</label>@endif<button class="ow-btn good">Complete quality check</button></div>
                        </form>
                    @endif

                    @if($type === 'shipment')
                        @if(empty($data['tracking_number']))
                            <form class="ow-form" method="POST" action="{{ $form('dispatch') }}" enctype="multipart/form-data">@csrf
                                <h3>Dispatch</h3>
                                <div class="ow-fields">
                                    <div class="ow-field"><label for="courier">Courier</label><input id="courier" name="courier" list="couriers" required value="{{ $old('courier') }}"><datalist id="couriers"><option>Aramex</option><option>DHL</option><option>FedEx</option><option>Emirates Post</option><option>Own vehicle</option></datalist></div>
                                    <div class="ow-field"><label for="tracking_number">Tracking / AWB number</label><input id="tracking_number" name="tracking_number" required value="{{ $old('tracking_number') }}"></div>
                                    <div class="ow-field"><label for="dispatch_date">Dispatch date</label><input id="dispatch_date" name="dispatch_date" type="date" required value="{{ $old('dispatch_date', date('Y-m-d')) }}"></div>
                                    <div class="ow-field"><label for="files_dispatch">Dispatch proof (optional)</label><input id="files_dispatch" name="files[]" type="file" multiple accept=".jpg,.jpeg,.png,.webp,.pdf"></div>
                                    <div class="ow-field full"><label for="msg_dispatch">Message to the customer (optional)</label><textarea id="msg_dispatch" name="message">{{ $formErr === 'dispatch' ? old('message') : '' }}</textarea></div>
                                </div>
                                <div class="ow-actions">@if($override)<label><input type="checkbox" name="override" value="1"> Override order</label>@endif<button class="ow-btn primary">Mark dispatched</button></div>
                            </form>
                        @else
                            <form class="ow-form" method="POST" action="{{ $form('deliver') }}" enctype="multipart/form-data">@csrf
                                <h3>Delivery</h3>
                                <div class="ow-fields">
                                    <div class="ow-field"><label for="delivered_on">Delivered on</label><input id="delivered_on" name="delivered_on" type="date" required value="{{ $old('delivered_on', date('Y-m-d')) }}"></div>
                                    <div class="ow-field"><label for="received_by">Received by</label><input id="received_by" name="received_by" required value="{{ $old('received_by') }}"></div>
                                    <div class="ow-field full"><label for="files_deliver">Proof of delivery (signed note or photo)</label><input id="files_deliver" name="files[]" type="file" multiple required accept=".jpg,.jpeg,.png,.webp,.pdf"></div>
                                    <div class="ow-field full"><label for="msg_deliver">Message to the customer (optional)</label><textarea id="msg_deliver" name="message">{{ $formErr === 'deliver' ? old('message') : '' }}</textarea></div>
                                </div>
                                <div class="ow-actions"><button class="ow-btn good">Mark delivered</button></div>
                            </form>
                        @endif
                    @endif

                    @if($type === 'closure')
                        <form class="ow-form" method="POST" action="{{ $form('close') }}">@csrf
                            <h3>Close the project</h3>
                            <div class="ow-fields"><div class="ow-field full"><label for="msg_close">Closing message to the customer (optional)</label><textarea id="msg_close" name="message">{{ $formErr === 'close' ? old('message') : '' }}</textarea></div></div>
                            <div class="ow-actions">@if($override)<label><input type="checkbox" name="override" value="1"> Override order</label>@endif<button class="ow-btn good">Close project</button></div>
                        </form>
                    @endif

                    @if($type === 'feedback')
                        <form class="ow-form" method="POST" action="{{ $form('rate') }}">@csrf
                            <h3>Customer rating</h3>
                            <div class="ow-fields">
                                <div class="ow-field"><label for="rating">Rating</label><select id="rating" name="rating" required>@for($r = 5; $r >= 1; $r--)<option value="{{ $r }}">{{ $r }} / 5</option>@endfor</select></div>
                                <div class="ow-field full"><label for="msg_rate">Customer's comment (optional)</label><textarea id="msg_rate" name="message">{{ $formErr === 'rate' ? old('message') : '' }}</textarea></div>
                            </div>
                            <div class="ow-actions">@if($override)<label><input type="checkbox" name="override" value="1"> Override order</label>@endif<button class="ow-btn good">Record rating</button></div>
                        </form>
                    @endif
                @elseif($current->state === 'done')
                    <div class="ow-alert ok">This step is done{{ $current->completed_at ? ' (' . $current->completed_at->format('d M Y') . ')' : '' }}. You can still add notes below.</div>
                @endif

                <form class="ow-form" method="POST" action="{{ route('orders.act', [$order->id, $selected, 'note']) }}" enctype="multipart/form-data">@csrf
                    <h3>Add a note</h3>
                    <div class="ow-fields">
                        <div class="ow-field full"><label for="note_msg">Note</label><textarea id="note_msg" name="message" required>{{ $formErr === 'note' ? old('message') : '' }}</textarea></div>
                        <div class="ow-field"><label for="note_vis">Who can see it</label><select id="note_vis" name="visibility"><option value="internal">Staff only</option><option value="customer">Customer too (emails them)</option></select></div>
                        <div class="ow-field"><label for="note_files">Files (optional)</label><input id="note_files" name="files[]" type="file" multiple accept=".jpg,.jpeg,.png,.webp,.pdf"></div>
                    </div>
                    <div class="ow-actions"><button class="ow-btn primary">Add note</button></div>
                </form>
            </section>

            <section class="ow-card ow-tl">
                <div class="ow-tl-top">
                    <h3>{{ $showAll ? 'Full timeline' : 'Timeline for this step' }}</h3>
                    <a href="?step={{ $selected }}{{ $showAll ? '' : '&timeline=all' }}">{{ $showAll ? 'Show this step only' : 'Show all steps (' . $order->events->count() . ')' }}</a>
                </div>
                @forelse($timeline as $ev)
                    <div class="ow-ev">
                        <span class="ow-dot {{ $ev->visibility }}"></span>
                        <div>
                            <div class="ow-meta">
                                <b style="color:var(--ow-ink)">{{ $ev->actor_name ?: 'System' }}</b>
                                <span>{{ optional($ev->created_at)->format('d M Y, H:i') }}</span>
                                @if($showAll && $ev->step)<span>Step {{ $ev->step }}</span>@endif
                                <span class="ow-vis {{ $ev->visibility }}">{{ $ev->visibility === 'customer' ? 'Customer can see' : 'Staff only' }}</span>
                            </div>
                            <div style="margin-top:4px; white-space:pre-line">{{ $ev->message }}</div>
                            @if($ev->files)
                                <div class="ow-files">
                                    @foreach($ev->files as $f)
                                        @php $ext = strtoupper(pathinfo($f, PATHINFO_EXTENSION)); @endphp
                                        <a href="{{ asset('storage/' . $f) }}" target="_blank" rel="noopener" title="{{ basename($f) }}" @if(!$isImage($f) && $ext !== 'PDF') download @endif>@if($isImage($f))<img src="{{ asset('storage/' . $f) }}" alt="Attachment">@else {{ $ext }} @endif</a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="ow-hint" style="margin:8px 0 0">Nothing recorded for this step yet.</p>
                @endforelse
            </section>

            <section class="ow-card ow-items">
                <h3 style="font-size:16px;font-weight:600;margin:0 0 8px">Products</h3>
                <table>
                    <thead><tr><th>Product</th><th>SKU</th><th class="n" style="text-align:right">Qty</th><th class="n" style="text-align:right">Unit (AED)</th><th class="n" style="text-align:right">Total (AED)</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr><td>{{ $item->name }}</td><td>{{ $item->modelno ?: '-' }}</td><td class="n">{{ $item->quantity }}</td>
                                <td class="n">{{ $item->price !== null ? number_format($item->price, 2) : '-' }}</td>
                                <td class="n">{{ $item->subtotal !== null ? number_format($item->subtotal, 2) : '-' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
                <p style="margin:10px 0 0"><a href="{{ url('printinvoice/' . $order->orderid) }}" target="_blank" rel="noopener">Print invoice</a></p>
            </section>
        </div>
    </div>

</div></div></div>
@endsection
