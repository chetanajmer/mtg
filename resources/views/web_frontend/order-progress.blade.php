@extends('web_frontend.layouts.app')

@section('title', 'Order #' . $order->orderid . ' | More Than Giftz')
@section('meta')
  <meta name="robots" content="noindex">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/order-progress.css') }}">
@endsection

@section('content')
@php
  $labels = \App\Services\OrderWorkflow::CUSTOMER_LABELS;
  $isImage = function ($p) { return preg_match('/\.(jpe?g|png|webp|gif|svg)$/i', $p); };
  $fileIcon = function ($p) {
      $ext = strtolower(pathinfo($p, PATHINFO_EXTENSION));
      return [$ext === 'pdf' ? 'bi-file-earmark-pdf' : ($ext === 'zip' ? 'bi-file-earmark-zip' : 'bi-file-earmark-richtext'), strtoupper($ext)];
  };
  $accept = '.' . implode(',.', \App\Services\OrderWorkflow::CUSTOMER_FILE_TYPES);
  $fileHint = 'Up to ' . \App\Services\OrderWorkflow::CUSTOMER_MAX_FILES . ' files, 10 MB each: ' . strtoupper(implode(', ', \App\Services\OrderWorkflow::CUSTOMER_FILE_TYPES));
  // Share box only where there is no decision form (the decision form has its own upload and "send idea only" button)
  $shareBox = array_diff_key($shares, $actions);
  $errTarget = session('form') === 'share' && $shareBox ? 'share' : ($actions ? 'respond' : null);
  $fmt = function ($d) { return $d ? \Carbon\Carbon::parse($d)->format('d M Y') : ''; };
  $doneCount = $order->stepStates->where('state', 'done')->count();
  $finished = $order->current_step == 10 && $order->step_state === 'done';
  $currentName = $finished ? 'Order completed' : $steps[$order->current_step]['customer'];
  $currentState = $finished ? 'done' : $order->step_state;
  $events = $order->events->where('visibility', 'customer');
  $ship = (array) optional($order->state(8))->data;
  $waitingStep = $order->stepStates->firstWhere('state', 'waiting_customer');
  $rateOpen = optional($order->state(10))->state === 'in_progress';
  $who = function ($ev) use ($mode, $order) {
      if ($ev->actor_type === 'customer') { return $mode === 'account' ? 'You' : ($ev->actor_name ?: 'Customer'); }
      return 'More Than Giftz';
  };
  $latestSend = function ($step) use ($order) {
      $round = (int) (optional($order->state($step))->data['round'] ?? 0);
      return $order->events->first(function ($e) use ($step, $round) {
          return $e->step == $step && $e->type === 'send' && (int) (($e->data ?? [])['round'] ?? 0) === $round;
      });
  };
  $qty = $order->items->sum('quantity');
@endphp

<div class="container-fluid px-4 px-xl-5">
  <nav class="breadcrumb-row" aria-label="Breadcrumb">
    <a href="{{ url('/') }}">Home</a><i class="bi bi-chevron-right"></i>
    @if($mode === 'account')
      <a href="{{ url('/orders') }}">My Orders</a><i class="bi bi-chevron-right"></i>
    @else
      <a href="{{ route('track.form') }}">Track order</a><i class="bi bi-chevron-right"></i>
    @endif
    <span>Order #{{ $order->orderid }}</span>
  </nav>
</div>

<section class="container-fluid px-4 px-xl-5 op">

  @if(session('success'))
    <div class="op-flash ok" role="status"><i class="bi bi-check-circle-fill"></i>{{ session('success') }}</div>
  @endif
  {{-- e.g. the upload filter refusing a file type --}}
  @if(session('error'))
    <div class="op-flash bad" role="alert"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>
  @endif
  {{-- With a form on the page the error shows inside it instead --}}
  @if($errors->any() && !$errTarget)
    <div class="op-flash bad" role="alert"><i class="bi bi-exclamation-circle-fill"></i>{{ $errors->first() }}</div>
  @endif
  @if($stale)
    <div class="op-flash info" role="status"><i class="bi bi-info-circle-fill"></i>A newer version has been sent since this email. The latest version is shown below.</div>
  @endif

  {{-- Header --}}
  <header class="op-card op-head">
    <div class="op-head-main">
      <small class="op-eyebrow">Order #{{ $order->orderid }}</small>
      <h1>{{ $currentName }}</h1>
      <div class="op-head-meta">
        <span class="op-pill {{ $currentState }}">{{ $finished ? 'Completed' : $labels[$currentState] }}</span>
        <span><i class="bi bi-calendar3"></i>Placed {{ $order->created_at->format('d M Y') }}</span>
        @if($order->customer_company)<span><i class="bi bi-buildings"></i>{{ $order->customer_company }}</span>@endif
      </div>
    </div>
    <div class="op-figs">
      <div><small>Order value</small><b>AED {{ number_format($order->total, 2) }}</b></div>
      <div><small>Items</small><b>{{ $qty }} pcs</b></div>
      <div><small>Progress</small><b>{{ $doneCount }}/10</b></div>
    </div>
    <div class="op-bar" role="img" aria-label="{{ $doneCount }} of 10 steps done">
      @foreach($steps as $n => $def)
        <span class="{{ optional($order->state($n))->state }}" title="{{ $n }}. {{ $def['customer'] }}"></span>
      @endforeach
    </div>
  </header>

  {{-- What the customer can do now --}}
  @foreach($actions as $step => $url)
    @php $state = $order->state($step); $sd = (array) $state->data; @endphp
    @if(\App\Services\OrderWorkflow::type($step) === 'approval')
      @php $send = $latestSend($step); @endphp
      <section class="op-card op-action" id="respond">
        <div class="op-action-head">
          <span class="op-action-icon"><i class="bi bi-hand-thumbs-up"></i></span>
          <div>
            <h2>{{ $step == 2 ? 'Your design mockup is ready' : 'Your sample is ready' }} &middot; version {{ $sd['round'] ?? 1 }}</h2>
            <p>Please check it, then approve it, ask for changes or reject it. You can also attach your own design or reference. Nothing moves forward until you approve.</p>
          </div>
        </div>
        @if($send && $send->files)
          <div class="op-files big">
            @foreach($send->files as $f)
              <a href="{{ asset('storage/' . $f) }}" target="_blank" rel="noopener">
                @if($isImage($f))<img src="{{ asset('storage/' . $f) }}" alt="Version {{ $sd['round'] ?? 1 }} file">@else<span><i class="bi {{ $fileIcon($f)[0] }}"></i>Open {{ $fileIcon($f)[1] }}</span>@endif
              </a>
            @endforeach
          </div>
        @endif
        @if($send && trim(preg_replace('/^Version \d+ sent for approval\.\s*/', '', $send->message)))
          <p class="op-quote">{{ preg_replace('/^Version \d+ sent for approval\.\s*/', '', $send->message) }}</p>
        @endif
        <form method="POST" action="{{ $url }}" class="op-form" enctype="multipart/form-data">
          @csrf
          @if($errors->any() && $errTarget === 'respond')
            <div class="op-flash bad" role="alert"><i class="bi bi-exclamation-circle-fill"></i>{{ $errors->first() }}</div>
          @endif
          <label for="msg{{ $step }}">Comments <span>(required to request changes or reject)</span></label>
          <textarea id="msg{{ $step }}" name="message" rows="3" maxlength="5000" placeholder="e.g. Please make the logo slightly bigger">{{ old('message') }}</textarea>
          <label for="files{{ $step }}" class="op-file-label">Your own design, idea or reference <span>(optional)</span></label>
          <input id="files{{ $step }}" class="op-file" type="file" name="files[]" multiple accept="{{ $accept }}">
          <small class="op-file-hint">{{ $fileHint }}</small>
          <div class="op-btns">
            <button class="op-btn good" name="decision" value="approved"><i class="bi bi-check-lg"></i>Approve version {{ $sd['round'] ?? 1 }}</button>
            <button class="op-btn ghost" name="decision" value="changes"><i class="bi bi-pencil"></i>Request changes</button>
            <button class="op-btn bad" name="decision" value="rejected"><i class="bi bi-x-lg"></i>Reject</button>
          </div>
          @if(isset($shares[$step]))
            <p class="op-later">Not ready to decide?
              <button class="op-link" name="do" value="share" formaction="{{ $shares[$step] }}" formnovalidate>Just send my comments and files</button>
              and decide later.</p>
          @endif
        </form>
      </section>
    @else
      <section class="op-card op-action" id="respond">
        <div class="op-action-head">
          <span class="op-action-icon"><i class="bi bi-star"></i></span>
          <div>
            <h2>How did we do?</h2>
            <p>Your order is complete. A quick rating helps us improve.</p>
          </div>
        </div>
        <form method="POST" action="{{ $url }}" class="op-form">
          @csrf
          @if($errors->any() && $errTarget === 'respond')
            <div class="op-flash bad" role="alert"><i class="bi bi-exclamation-circle-fill"></i>{{ $errors->first() }}</div>
          @endif
          <fieldset class="op-stars">
            <legend>Your rating</legend>
            @for($i = 5; $i >= 1; $i--)
              <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" @if(old('rating') == $i) checked @endif>
              <label for="star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}"><i class="bi bi-star-fill"></i><span class="visually-hidden">{{ $i }} stars</span></label>
            @endfor
          </fieldset>
          <label for="msg_rate">Comments <span>(optional)</span></label>
          <textarea id="msg_rate" name="message" rows="3" maxlength="5000">{{ old('message') }}</textarea>
          <div class="op-btns"><button class="op-btn dark"><i class="bi bi-send"></i>Send rating</button></div>
        </form>
      </section>
    @endif
  @endforeach

  {{-- Customer's own design / idea while a design or sample step is open --}}
  @foreach($shareBox as $step => $url)
    @php $sd = (array) optional($order->state($step))->data; @endphp
    <section class="op-card op-share" id="share">
      <div class="op-action-head">
        <span class="op-action-icon soft"><i class="bi bi-lightbulb"></i></span>
        <div>
          <h2>Have your own design or idea?</h2>
          <p>
            @if(($sd['last_decision'] ?? null) === 'rejected')
              You rejected version {{ $sd['round'] }}. Send us your design, logo files or a reference and we will use it for the next version.
            @elseif(!empty($sd['round']))
              We are working on version {{ (int) $sd['round'] + 1 }} of your {{ $step == 2 ? 'design' : 'sample' }}. Anything you send now goes straight to our team.
            @else
              We are preparing your {{ $step == 2 ? 'design mockup' : 'sample' }}. Share your artwork, logo files or a picture of what you have in mind.
            @endif
          </p>
        </div>
      </div>
      <form method="POST" action="{{ $url }}" class="op-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="do" value="share">
        @if($errors->any() && $errTarget === 'share')
          <div class="op-flash bad" role="alert"><i class="bi bi-exclamation-circle-fill"></i>{{ $errors->first() }}</div>
        @endif
        <label for="idea{{ $step }}">Describe your idea <span>(optional if you attach files)</span></label>
        <textarea id="idea{{ $step }}" name="message" rows="3" maxlength="5000" placeholder="e.g. Use our attached logo in white, centred on the lid">{{ session('form') === 'share' ? old('message') : '' }}</textarea>
        <label for="ideafiles{{ $step }}" class="op-file-label">Files <span>(optional)</span></label>
        <input id="ideafiles{{ $step }}" class="op-file" type="file" name="files[]" multiple accept="{{ $accept }}">
        <small class="op-file-hint">{{ $fileHint }}</small>
        <div class="op-btns"><button class="op-btn dark"><i class="bi bi-send"></i>Send to our team</button></div>
      </form>
    </section>
  @endforeach

  @if($mode === 'track' && ($waitingStep || $rateOpen))
    <div class="op-flash info"><i class="bi bi-info-circle-fill"></i>
      <span>{{ $waitingStep ? 'We are waiting for your approval on ' . strtolower($steps[$waitingStep->step]['customer']) . '.' : 'Your order is complete and we would love your rating.' }}
      Use the button in our email, or <a href="{{ route('corporate.login') }}">log in</a> and open My Orders.</span>
    </div>
  @endif

  <div class="op-grid">
    <div class="op-main">
      {{-- 10 steps --}}
      <section class="op-card op-pad">
        <h2 class="op-h2">Progress</h2>
        <ol class="op-steps">
          @foreach($steps as $n => $def)
            @php
              $st = $order->state($n);
              $s = optional($st)->state ?: 'not_started';
              $d = (array) optional($st)->data;
              $detail = '';
              switch ($def['type']) {
                case 'review': if ($s === 'done' && isset($d['confirmed_total'])) { $detail = 'Confirmed for AED ' . number_format($d['confirmed_total'], 2); } break;
                case 'approval': if (!empty($d['round'])) { $detail = 'Version ' . $d['round'] . ($s === 'done' ? ' approved' : ($s === 'changes_requested' ? (($d['last_decision'] ?? '') === 'rejected' ? ' rejected: we are preparing a new version' : ': we are making your changes') : ($s === 'waiting_customer' ? ' sent for your approval' : ''))); } break;
                case 'payment':
                  if ($s === 'done' && isset($d['amount'])) { $detail = 'AED ' . number_format($d['amount'], 2) . ' received ' . $fmt($d['paid_on'] ?? null); }
                  elseif (($due = \App\Services\OrderWorkflow::amountDue($order, $n)) > 0) { $detail = 'AED ' . number_format($due, 2) . ' due'; }
                  break;
                case 'production': if ($s === 'done' && !empty($d['finished_on'])) { $detail = 'Finished ' . $fmt($d['finished_on']); } elseif (!empty($d['expected_finish'])) { $detail = 'Expected to finish ' . $fmt($d['expected_finish']); } break;
                case 'checklist': if ($s === 'done' && !empty($d['packed_qty'])) { $detail = $d['packed_qty'] . ' pieces checked and packed'; } break;
                case 'shipment': if (!empty($d['delivered_on'])) { $detail = 'Delivered ' . $fmt($d['delivered_on']); } elseif (!empty($d['courier'])) { $detail = 'Dispatched with ' . $d['courier'] . ' · tracking ' . $d['tracking_number']; } break;
                case 'feedback': if (!empty($d['rating'])) { $detail = 'You rated us ' . $d['rating'] . '/5'; } break;
              }
              $when = $s === 'done' ? optional($st->completed_at)->format('d M Y') : ($s !== 'not_started' ? 'Since ' . optional($st->started_at)->format('d M Y') : '');
            @endphp
            <li class="{{ $s }} @if(!$finished && $n == $order->current_step) now @endif">
              <span class="op-dot">@if($s === 'done')<i class="bi bi-check-lg"></i>@else{{ $n }}@endif</span>
              <div class="op-step-body">
                <div class="op-step-top">
                  <strong>{{ $def['customer'] }}</strong>
                  <span class="op-pill {{ $s }}">{{ $labels[$s] }}</span>
                </div>
                @if($detail || $when)
                  <p>{{ $detail }}@if($detail && $when) &middot; @endif<span class="op-when">{{ $when }}</span></p>
                @endif
              </div>
            </li>
          @endforeach
        </ol>
      </section>

      {{-- Timeline --}}
      <section class="op-card op-pad">
        <h2 class="op-h2">Updates</h2>
        @if($events->isEmpty())
          <p class="op-muted">No updates yet.</p>
        @else
          <ul class="op-feed">
            @foreach($events as $ev)
              <li class="{{ $ev->actor_type === 'customer' ? 'mine' : '' }}">
                <div class="op-feed-top">
                  <strong>{{ $steps[$ev->step]['customer'] ?? 'Update' }}</strong>
                  <time datetime="{{ $ev->created_at->toIso8601String() }}">{{ $ev->created_at->format('d M Y, h:i A') }}</time>
                </div>
                <p>{{ $ev->message }}</p>
                @if($ev->files)
                  <div class="op-files">
                    @foreach($ev->files as $f)
                      <a href="{{ asset('storage/' . $f) }}" target="_blank" rel="noopener" title="{{ basename($f) }}" @if(!$isImage($f) && $fileIcon($f)[1] !== 'PDF') download @endif>
                        @if($isImage($f))<img src="{{ asset('storage/' . $f) }}" alt="Attachment">@else<span><i class="bi {{ $fileIcon($f)[0] }}"></i>{{ $fileIcon($f)[1] }}</span>@endif
                      </a>
                    @endforeach
                  </div>
                @endif
                <small class="op-by">{{ $who($ev) }}</small>
              </li>
            @endforeach
          </ul>
        @endif
      </section>
    </div>

    <aside class="op-side">
      {{-- Delivery --}}
      <section class="op-card op-pad">
        <h2 class="op-h2"><i class="bi bi-truck"></i>Delivery</h2>
        @if(!empty($ship['courier']))
          <dl class="op-dl">
            <div><dt>Courier</dt><dd>{{ $ship['courier'] }}</dd></div>
            <div><dt>Tracking number</dt><dd class="op-mono">{{ $ship['tracking_number'] }}</dd></div>
            <div><dt>Dispatched</dt><dd>{{ $fmt($ship['dispatch_date'] ?? null) }}</dd></div>
            @if(!empty($ship['delivered_on']))
              <div><dt>Delivered</dt><dd>{{ $fmt($ship['delivered_on']) }}@if(!empty($ship['received_by'])), received by {{ $ship['received_by'] }}@endif</dd></div>
            @endif
          </dl>
        @else
          <p class="op-muted">Courier and tracking number appear here once your order is dispatched.</p>
        @endif
      </section>

      {{-- Products --}}
      <section class="op-card op-pad">
        <h2 class="op-h2"><i class="bi bi-box-seam"></i>Products</h2>
        <ul class="op-items">
          @foreach($order->items as $item)
            <li>
              <span class="op-thumb">@if($item->thumbnail)<img src="{{ asset('upload/product/thumbnail/' . $item->thumbnail) }}" alt="" onerror="this.remove()">@endif<i class="bi bi-gift"></i></span>
              <div><strong>{{ $item->name }}</strong><small>{{ (int) $item->quantity }} pcs @if($item->color) &middot; {{ $item->color }}@endif</small></div>
            </li>
          @endforeach
        </ul>
      </section>

      <section class="op-card op-pad op-help">
        <i class="bi bi-headset"></i>
        <div>
          <strong>Questions about this order?</strong>
          <p>Reply to any of our order emails or <a href="{{ url('/contactus') }}">contact us</a> quoting order #{{ $order->orderid }}.</p>
        </div>
      </section>

      @if($mode === 'account')
        <a class="op-btn ghost full" href="{{ url('/orders') }}"><i class="bi bi-arrow-left"></i>Back to My Orders</a>
      @elseif($mode === 'track')
        <a class="op-btn ghost full" href="{{ route('track.form') }}"><i class="bi bi-search"></i>Track another order</a>
      @endif
    </aside>
  </div>
</section>
@endsection
