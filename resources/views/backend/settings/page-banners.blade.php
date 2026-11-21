@if(Session::has('role'))
@else
<script>
window.location.href = "{{url('/admin')}}";</script>
@endif
@extends('layouts.app')
@section('content')
<style>
  .pb-pages { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 8px; }
  .pb-pages a { display: flex; justify-content: space-between; align-items: center; padding: 9px 12px; border: 1px solid #e5e7eb; border-radius: 6px; color: #15171a; font-size: 13px; }
  .pb-pages a:hover { border-color: #15171a; }
  .pb-pages a.active { background: #15171a; border-color: #15171a; color: #fff; }
  .pb-pages small { color: inherit; opacity: .75; }
  .pb-note { color: #6b7280; font-size: 12px; }
  .pb-thumb { display: block; width: 260px; max-width: 100%; aspect-ratio: 1920 / 250; object-fit: cover; border-radius: 4px; background: #f3f4f6; }
</style>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title">Page Banners</h4>
                                <ol class="breadcrumb">
                                    <li>The banner strip under the header on every page except the home page. Add several to a page and a different one shows on every visit. Pages without their own banners use the Default banners.</li>
                                </ol>
                            </div>
                        </div>
                        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

                        <div class="card-box">
                            <h4 class="m-t-0 m-b-15">Choose a page</h4>
                            <div class="pb-pages">
                                @foreach($pages as $key => $item)
                                    @php $c = $counts[$key] ?? null; @endphp
                                    <a href="{{ route('page-banners.index', ['page' => $key]) }}" class="{{ $page === $key ? 'active' : '' }}">
                                        <span>{{ $item['label'] }}</span>
                                        <small>{{ $c ? (int) $c->active . ' on' . ((int) $c->total !== (int) $c->active ? ', ' . ((int) $c->total - (int) $c->active) . ' off' : '') : 'none' }}</small>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="card-box">
                            <h4 class="m-t-0 m-b-15">{{ $pages[$page]['label'] }}</h4>
                            @if($page !== 'default' && $banners->where('is_active', true)->isEmpty())
                                <p class="pb-note">No banners switched on here, so this page shows the Default banners (or no strip if there are none).</p>
                            @endif

                            <form method="post" action="{{ route('page-banners.store') }}" enctype="multipart/form-data" class="row m-b-20">
                                @csrf
                                <input type="hidden" name="page" value="{{ $page }}">
                                <div class="col-sm-4">
                                    <label for="pbImages" class="control-label">Banner pictures (you can choose several)</label>
                                    <input type="file" id="pbImages" name="images[]" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple required>
                                </div>
                                <div class="col-sm-3">
                                    <label for="pbAlt" class="control-label">Description (optional)</label>
                                    <input type="text" id="pbAlt" name="alt" class="form-control" maxlength="150" placeholder="e.g. Branded bottles in five colours">
                                </div>
                                <div class="col-sm-3">
                                    <label for="pbLink" class="control-label">Link when clicked (optional)</label>
                                    <input type="text" id="pbLink" name="link" class="form-control" maxlength="255" placeholder="/category/drinkware-and-kitchenware">
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label" style="display:block;">&nbsp;</label>
                                    <button type="submit" class="btn btn-info waves-effect waves-light">Add banners</button>
                                </div>
                            </form>
                            <p class="pb-note">JPG, PNG or WEBP, up to 4 MB each. Best size {{ config('page_banners.recommended_size') }}: a wide strip. On phones the middle of the picture is shown, so keep the main subject away from the far edges.</p>

                            <div class="table-responsive">
                                <table class="table table-actions-bar">
                                    <thead>
                                        <tr>
                                            <th style="width:280px;">Banner</th>
                                            <th>Description and link</th>
                                            <th style="width:200px;">Replace picture</th>
                                            <th style="width:90px;">Position</th>
                                            <th style="width:90px;">On website</th>
                                            <th style="min-width:150px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($banners as $banner)
                                        @php $form = 'pbUpdate' . $banner->id; @endphp
                                        <tr>
                                            <td><a href="{{ $banner->imageUrl() }}" target="_blank" rel="noopener"><img class="pb-thumb" src="{{ $banner->imageUrl() }}" alt=""></a></td>
                                            <td>
                                                <input form="{{ $form }}" type="text" name="alt" class="form-control input-sm" maxlength="150" value="{{ $banner->alt }}" placeholder="Description" aria-label="Description">
                                                <input form="{{ $form }}" type="text" name="link" class="form-control input-sm" style="margin-top:6px;" maxlength="255" value="{{ $banner->link }}" placeholder="Link (optional)" aria-label="Link">
                                            </td>
                                            <td><input form="{{ $form }}" type="file" name="image" class="form-control input-sm" accept=".jpg,.jpeg,.png,.webp" aria-label="Replace the picture"></td>
                                            <td><input form="{{ $form }}" type="number" name="ranking" class="form-control input-sm" min="0" max="9999" required value="{{ $banner->ranking }}" aria-label="Position"></td>
                                            <td><input form="{{ $form }}" type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }} aria-label="Show on the website"></td>
                                            <td>
                                                <form id="{{ $form }}" method="post" action="{{ route('page-banners.update', $banner->id) }}" enctype="multipart/form-data" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm waves-effect waves-light">Save</button>
                                                </form>
                                                <form method="post" action="{{ route('page-banners.destroy', $banner->id) }}" style="display:inline;" onsubmit="return confirm('Remove this banner?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="6" style="text-align:center;">No banners for this page yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
