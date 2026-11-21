@extends('layouts.app')

@section('content')
@php
  $config = config('gift_planner');
  $reuseDays = (int) $config['reuse_days'];
@endphp
<style>
  .pl-admin .pl-note { color: #6b7280; font-size: 12px; }
  .pl-admin .table td { vertical-align: top; }
  .pl-admin .pl-tag { display: inline-block; padding: 1px 8px; border-radius: 10px; font-size: 11px; background: #eef0f3; color: #15171a; }
  .pl-admin .pl-tag.old { background: #fdecec; color: #9b1c1c; }
  .pl-admin .pl-bundles { display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); gap: 10px; }
  .pl-admin .pl-bundle { border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px; font-size: 12px; }
  .pl-admin .pl-bundle img, .pl-admin .pl-bundle .pl-nopic { display: block; width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 4px; margin-bottom: 6px; background: #f3f4f6; }
  .pl-admin .pl-bundle .pl-nopic { display: flex; align-items: center; justify-content: center; color: #9ca3af; }
  .pl-admin .pl-bundle ul { margin: 4px 0 0 14px; padding: 0; color: #4b4f56; }
</style>

<div class="content-page">
  <div class="content">
    <div class="container pl-admin">
      <h4 class="page-title">Budget Planner: Saved Recommendations</h4>
      <p class="pl-note">The AI's bundle choices, saved by the customer's choices (occasion, team size, budget, preferences and View More round). A customer making the same choices gets the saved bundles with no AI cost; prices are always worked out fresh. A choice is renewed after {{ $reuseDays }} days. Remove one to have the AI choose again next time.</p>

      @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

      <div class="card-box">
        <form method="GET" action="{{ route('planner-recommendations.index') }}" class="form-inline">
          <select name="occasion" class="form-control" aria-label="Occasion">
            <option value="">All occasions</option>
            @foreach($config['occasions'] as $key => $item)
              <option value="{{ $key }}" {{ $occasion === $key ? 'selected' : '' }}>{{ $item['label'] }}</option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-primary">Show</button>
          <a href="{{ route('planner-pictures.index') }}" class="btn btn-default">Planner Pictures</a>
        </form>
      </div>

      <div class="card-box">
        <h5>Saved recommendations ({{ $saved->total() }})</h5>
        <div class="table-responsive">
          <table class="table">
            <thead><tr><th style="width: 22%;">Customer's choices</th><th>Bundles (picture shared by everyone with this occasion and these products)</th><th style="width: 15%;">Use</th></tr></thead>
            <tbody>
              @forelse($saved as $row)
                @php $expired = $row->updated_at->lt(now()->subDays($reuseDays)); @endphp
                <tr>
                  <td>
                    <strong>{{ $row->occasion_label }}</strong><br>
                    {{ $config['team_sizes'][$row->team]['label'] ?? $row->team }} · {{ $config['budgets'][$row->budget]['label'] ?? $row->budget }}
                    @if($row->preferences)<br><small class="pl-note">{{ collect($row->preferences)->map(function ($key) use ($config) { return $config['preferences'][$key]['label'] ?? $key; })->implode(', ') }}</small>@endif
                    @if($row->round)<br><span class="pl-tag">View more #{{ $row->round }}</span>@endif
                  </td>
                  <td>
                    <div class="pl-bundles">
                      @foreach($row->plan as $i => $bundle)
                        @php $picture = $pictures[$keys[$row->id][$i]] ?? null; @endphp
                        <div class="pl-bundle">
                          @if($picture)
                            <a href="{{ $picture->url() }}" target="_blank" rel="noopener"><img src="{{ $picture->url() }}" alt="{{ $bundle['name'] }}" loading="lazy"></a>
                          @else
                            <div class="pl-nopic">No picture yet</div>
                          @endif
                          <strong>{{ $bundle['name'] }}</strong>
                          <div class="pl-note">{{ $bundle['description'] }}</div>
                          <ul>
                            @foreach($bundle['ids'] ?? [] as $productId)
                              <li>{{ $products[$productId] ?? 'Product #' . $productId . ' (removed)' }}</li>
                            @endforeach
                          </ul>
                        </div>
                      @endforeach
                    </div>
                  </td>
                  <td>
                    Saved {{ $row->updated_at->format('d M Y') }}
                    @if($expired)<br><span class="pl-tag old">Due for renewal</span>@endif
                    <br><small class="pl-note">Reused {{ number_format($row->times_reused) }} {{ $row->times_reused == 1 ? 'time' : 'times' }}{{ $row->last_reused_at ? ', last ' . $row->last_reused_at->format('d M Y') : '' }}</small>
                    <form method="POST" action="{{ route('planner-recommendations.destroy', $row->id) }}" onsubmit="return confirm('Remove this saved recommendation? The next customer with these choices will get a new AI choice.');" style="margin-top: 8px;">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="3" class="pl-note">No saved recommendations yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        {{ $saved->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>
@endsection
