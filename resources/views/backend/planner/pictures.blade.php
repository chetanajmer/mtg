@extends('layouts.app')

@section('content')
<style>
  .pl-admin .pl-note { color: #6b7280; font-size: 12px; }
  .pl-admin .pl-tag { display: inline-block; padding: 1px 8px; border-radius: 10px; font-size: 11px; background: #eef0f3; color: #15171a; }
  .pl-admin .pl-tag.logo { background: #f6eedc; color: #7a5a14; }
  .pl-admin .pl-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 14px; }
  .pl-admin .pl-pic { border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px; font-size: 12px; display: flex; flex-direction: column; }
  .pl-admin .pl-pic img { display: block; width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 4px; margin-bottom: 8px; background: #f3f4f6; }
  .pl-admin .pl-pic ul { margin: 4px 0 6px 14px; padding: 0; color: #4b4f56; }
  .pl-admin .pl-pic form { margin-top: auto; }
</style>

<div class="content-page">
  <div class="content">
    <div class="container pl-admin">
      <h4 class="page-title">Budget Planner: Pictures</h4>
      <p class="pl-note">Bundle pictures made by the AI. Each is made once and reused whenever the same products are shown for the same occasion, whatever the team size or budget. Pictures with a customer's logo are only ever shown to that customer. Remove a picture you don't like and the next customer shown that bundle gets a new one.</p>

      @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

      <div class="card-box">
        <form method="GET" action="{{ route('planner-pictures.index') }}" class="form-inline">
          <select name="occasion" class="form-control" aria-label="Occasion">
            <option value="">All occasions</option>
            @foreach(config('gift_planner.occasions') as $key => $item)
              <option value="{{ $key }}" {{ $occasion === $key ? 'selected' : '' }}>{{ $item['label'] }}</option>
            @endforeach
          </select>
          <select name="kind" class="form-control" aria-label="Kind">
            <option value="">All pictures</option>
            <option value="shared" {{ $kind === 'shared' ? 'selected' : '' }}>Shared (no customer logo)</option>
            <option value="logo" {{ $kind === 'logo' ? 'selected' : '' }}>With a customer's logo</option>
          </select>
          <button type="submit" class="btn btn-primary">Show</button>
          <a href="{{ route('planner-recommendations.index') }}" class="btn btn-default">Saved Recommendations</a>
        </form>
        <p class="pl-note" style="margin: 10px 0 0;">{{ number_format($totals->pictures) }} {{ $totals->pictures == 1 ? 'picture' : 'pictures' }}, shown {{ number_format($totals->uses) }} {{ $totals->uses == 1 ? 'time' : 'times' }} in all. Every showing after the first cost nothing.</p>
      </div>

      <div class="card-box">
        <div class="pl-grid">
          @forelse($images as $image)
            <div class="pl-pic">
              <a href="{{ $image->url() }}" target="_blank" rel="noopener"><img src="{{ $image->url() }}" alt="Bundle picture for {{ $image->occasion_label }}" loading="lazy"></a>
              <strong>{{ $image->occasion_label }}</strong>
              @if($image->storeuser_id)
                <span><span class="pl-tag logo">With logo</span> {{ $image->company ?: ($image->email ?: 'Customer #' . $image->storeuser_id) }}</span>
              @endif
              <ul>
                @foreach($image->product_names ?: [] as $name)
                  <li>{{ $name }}</li>
                @endforeach
              </ul>
              <span class="pl-note">Made {{ $image->created_at->format('d M Y') }} · shown {{ number_format($image->times_used) }} {{ $image->times_used == 1 ? 'time' : 'times' }}{{ $image->last_used_at ? ', last ' . $image->last_used_at->format('d M Y') : '' }}</span>
              <form method="POST" action="{{ route('planner-pictures.destroy', $image->id) }}" onsubmit="return confirm('Remove this picture? The next customer shown this bundle will get a new one, which costs a new picture.');" style="padding-top: 8px;">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
              </form>
            </div>
          @empty
            <p class="pl-note">No pictures saved yet.</p>
          @endforelse
        </div>
        {{ $images->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>
@endsection
