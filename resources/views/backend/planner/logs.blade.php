@extends('layouts.app')

@section('content')
@php
  $total = (int) ($summary->total ?? 0);
  $aiShare = $total ? round($summary->ai / $total * 100) : 0;
@endphp
<style>
  .pl-admin .pl-cards { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-bottom: 16px; }
  .pl-admin .pl-cards .card-box { margin: 0; padding: 16px; }
  .pl-admin .pl-cards small, .pl-admin .pl-cards span { display: block; color: #6b7280; font-size: 12px; }
  .pl-admin .pl-cards strong { display: block; font-size: 22px; color: #15171a; margin: 4px 0; }
  .pl-admin .pl-note { color: #6b7280; font-size: 12px; }
  .pl-admin .pl-filters label { font-size: 12px; color: #6b7280; margin-bottom: 4px; }
  .pl-admin .table td { vertical-align: top; }
  .pl-admin .pl-tag { display: inline-block; padding: 1px 8px; border-radius: 10px; font-size: 11px; background: #eef0f3; color: #15171a; }
  .pl-admin .pl-tag.ai { background: #f6eedc; color: #7a5a14; }
  .pl-admin .pl-bundle { margin-bottom: 6px; }
  .pl-admin .pl-bundle summary { cursor: pointer; }
  .pl-admin .pl-bundle ul { margin: 4px 0 0 16px; padding: 0; font-size: 12px; color: #4b4f56; }
  @media (max-width: 991px) { .pl-admin .pl-cards { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
</style>

<div class="content-page">
  <div class="content">
    <div class="container pl-admin">
      <div class="row">
        <div class="col-sm-12">
          <h4 class="page-title">Budget Planner Log</h4>
          <p class="pl-note">One row each time the Budget Planner showed bundles: Show Recommendations, a new occasion, or View More Options. Prices are per person, as the customer saw them.</p>
        </div>
      </div>

      <div class="card-box pl-filters">
        <form method="GET" action="{{ route('planner-logs.index') }}" class="row">
          <div class="col-md-2 col-sm-6"><label for="pl-from">From</label><input type="date" id="pl-from" name="from" value="{{ $filters['from'] }}" class="form-control"></div>
          <div class="col-md-2 col-sm-6"><label for="pl-to">To</label><input type="date" id="pl-to" name="to" value="{{ $filters['to'] }}" class="form-control"></div>
          <div class="col-md-3 col-sm-6"><label for="pl-q">Customer or occasion</label><input type="search" id="pl-q" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Name, email, company or occasion"></div>
          <div class="col-md-3 col-sm-6">
            <label for="pl-occasion">Occasion</label>
            <select id="pl-occasion" name="occasion" class="form-control">
              <option value="">All occasions</option>
              @foreach(config('gift_planner.occasions') as $key => $occasion)
                <option value="{{ $key }}" {{ $filters['occasion'] === $key ? 'selected' : '' }}>{{ $occasion['label'] }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 col-sm-6">
            <label for="pl-source">Bundles by</label>
            <select id="pl-source" name="source" class="form-control">
              <option value="">AI or standard</option>
              <option value="ai" {{ $filters['source'] === 'ai' ? 'selected' : '' }}>AI</option>
              <option value="reused" {{ $filters['source'] === 'reused' ? 'selected' : '' }}>AI, reused (no AI cost)</option>
              <option value="standard" {{ $filters['source'] === 'standard' ? 'selected' : '' }}>Standard</option>
            </select>
          </div>
          <div class="col-sm-12" style="margin-top: 10px;">
            <button type="submit" class="btn btn-primary">Show log</button>
            <a href="{{ route('planner-logs.index') }}" class="btn btn-default">Reset</a>
          </div>
        </form>
      </div>

      <div class="pl-cards">
        <div class="card-box"><small>Results shown</small><strong>{{ number_format($total) }}</strong><span>for these filters</span></div>
        <div class="card-box"><small>Customers</small><strong>{{ number_format($summary->customers ?? 0) }}</strong><span>logged-in customers who used it</span></div>
        <div class="card-box"><small>Chosen by AI</small><strong>{{ $aiShare }}%</strong><span>{{ number_format($summary->ai ?? 0) }} {{ ($summary->ai ?? 0) == 1 ? 'result' : 'results' }}, of which {{ number_format($summary->reused ?? 0) }} reused from earlier with no AI cost</span></div>
        <div class="card-box"><small>Top occasion</small><strong>{{ optional($topOccasion)->occasion_label ?: '-' }}</strong><span>{{ $topOccasion ? number_format($topOccasion->n) . ($topOccasion->n == 1 ? ' result' : ' results') : 'no results yet' }}</span></div>
      </div>

      <div class="card-box">
        <h5>Results ({{ $logs->total() }})</h5>
        <div class="table-responsive">
          <table class="table">
            <thead><tr><th>Date</th><th>Customer</th><th>Choices</th><th>Bundles shown</th></tr></thead>
            <tbody>
              @forelse($logs as $log)
                <tr>
                  <td style="white-space: nowrap;">{{ $log->created_at->format('d M Y') }}<br><small class="pl-note">{{ $log->created_at->format('H:i') }}</small></td>
                  <td>
                    @if($log->storeuser_id)
                      {{ trim($log->fname . ' ' . $log->lname) ?: ($log->email ?: 'Customer #' . $log->storeuser_id) }}
                      @if($log->company)<br><small class="pl-note">{{ $log->company }}</small>@endif
                      @if($log->email)<br><small class="pl-note">{{ $log->email }}</small>@endif
                    @else
                      <span class="pl-note">Visitor (not logged in)</span>
                    @endif
                  </td>
                  <td>
                    <strong>{{ $log->occasion_label }}</strong><br>
                    {{ $log->team_label }} · {{ $log->budget_label }}
                    @if($log->preferences)<br><small class="pl-note">{{ implode(', ', $log->preferences) }}</small>@endif
                    <br><span class="pl-tag {{ $log->ai_written ? 'ai' : '' }}">{{ $log->ai_written ? 'AI' : 'Standard' }}</span>
                    @if($log->reused)<span class="pl-tag" title="Same choices as an earlier customer: the saved AI result was used, no AI cost">Reused</span>@endif
                    @if($log->round)<span class="pl-tag">View more #{{ $log->round }}</span>@endif
                  </td>
                  <td>
                    @forelse($log->bundles ?: [] as $bundle)
                      <details class="pl-bundle">
                        <summary><strong>{{ $bundle['name'] }}</strong> <small class="pl-note">{{ $bundle['label'] }} · AED {{ number_format($bundle['price'], 2) }} / person · {{ count($bundle['items']) }} item{{ count($bundle['items']) === 1 ? '' : 's' }}</small></summary>
                        <ul>
                          @foreach($bundle['items'] as $item)
                            <li>{{ $item['name'] }} · {{ number_format($item['quantity']) }} pcs · AED {{ number_format($item['price'], 2) }} each</li>
                          @endforeach
                        </ul>
                      </details>
                    @empty
                      <span class="pl-note">No bundle fitted these choices</span>
                    @endforelse
                  </td>
                </tr>
              @empty
                <tr><td colspan="4" class="pl-note">Nothing logged for these filters yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        {{ $logs->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>
@endsection
