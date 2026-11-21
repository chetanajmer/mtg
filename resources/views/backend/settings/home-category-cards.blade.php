@if(Session::has('role'))
@else
<script>
window.location.href = "{{url('/admin')}}";</script>
@endif
@extends('layouts.app')
@section('content')
@php $max = \App\Models\HomeCategoryCard::MAX_LINKS; @endphp
<style>
  .hcc-subs { min-height: 92px; }
  .hcc-note { color: #6b7280; font-size: 12px; }
  .hcc-off { color: #9b1c1c; font-size: 12px; }
</style>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title">Home Category Cards</h4>
                                <ol class="breadcrumb">
                                    <li>The cards under Search by Category on the homepage, also listed in the footer's Categories column. Six fit in one row.</li>
                                </ol>
                            </div>
                        </div>
                        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

                        <div class="card-box">
                            <h4 class="m-t-0 m-b-20">Add a card</h4>
                            <form method="post" action="{{ route('home-category-cards.store') }}" enctype="multipart/form-data" class="row">
                                @csrf
                                <div class="col-sm-3">
                                    <label for="newCategory" class="control-label">Category</label>
                                    <select id="newCategory" name="category_id" class="form-control hcc-category" data-subs="newSubs" required>
                                        <option value="">Choose a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (string) old('category_id') === (string) $category->id ? 'selected' : '' }}>{{ $category->catname }}{{ $category->is_active === 'online' ? '' : ' (offline)' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label for="newTitle" class="control-label">Title on the card (optional)</label>
                                    <input type="text" id="newTitle" name="title" class="form-control" maxlength="100" value="{{ old('title') }}" placeholder="The category's name">
                                </div>
                                <div class="col-sm-3">
                                    <label for="newSubs" class="control-label">Links on the card (up to {{ $max }})</label>
                                    <select id="newSubs" name="subcategory_ids[]" class="form-control hcc-subs" multiple>
                                        @foreach($subcategories as $categoryId => $subs)
                                            <optgroup label="{{ optional($categories->firstWhere('id', $categoryId))->catname }}" data-category="{{ $categoryId }}">
                                                @foreach($subs as $sub)
                                                    <option value="{{ $sub->id }}">{{ $sub->catname }}{{ $sub->is_active === 'online' ? '' : ' (offline)' }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <span class="hcc-note">Ctrl/Cmd-click to choose several. None chosen: the category's first {{ $max }}.</span>
                                </div>
                                <div class="col-sm-3">
                                    <label for="newImage" class="control-label">Picture</label>
                                    <input type="file" id="newImage" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
                                    <label for="newRanking" class="control-label" style="margin-top: 8px;">Position (optional)</label>
                                    <input type="number" id="newRanking" name="ranking" class="form-control" min="0" max="9999" placeholder="Last">
                                </div>
                                <div class="col-sm-12" style="margin-top: 12px;">
                                    <button type="submit" class="btn btn-info waves-effect waves-light">Add card</button>
                                    <span class="hcc-note" style="margin-left: 10px;">Square picture, JPG, PNG or WEBP, up to 2 MB. 520 × 520 matches the current cards.</span>
                                </div>
                            </form>
                        </div>

                        <div class="card-box">
                            <h4 class="m-t-0 m-b-20">Cards ({{ $cards->count() }})</h4>
                            <div class="table-responsive">
                                <table class="table table-actions-bar">
                                    <thead>
                                        <tr>
                                            <th style="width:110px;">Picture</th>
                                            <th>Category and title</th>
                                            <th style="width:260px;">Links on the card</th>
                                            <th style="width:200px;">Replace picture</th>
                                            <th style="width:90px;">Position</th>
                                            <th style="width:90px;">On website</th>
                                            <th style="min-width:150px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($cards as $card)
                                        @php $form = 'cardUpdate' . $card->id; @endphp
                                        <tr>
                                            <td><img src="{{ $card->imageUrl() }}" alt="" style="display:block;width:96px;height:96px;object-fit:cover;border-radius:6px;"></td>
                                            <td>
                                                <select form="{{ $form }}" name="category_id" class="form-control input-sm hcc-category" data-subs="subs{{ $card->id }}" required aria-label="Category">
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ (int) $card->category_id === (int) $category->id ? 'selected' : '' }}>{{ $category->catname }}{{ $category->is_active === 'online' ? '' : ' (offline)' }}</option>
                                                    @endforeach
                                                </select>
                                                <input form="{{ $form }}" type="text" name="title" class="form-control input-sm" style="margin-top:6px;" maxlength="100" value="{{ $card->title }}" placeholder="{{ optional($card->category)->catname }}" aria-label="Title on the card">
                                                @if(optional($card->category)->is_active !== 'online')<span class="hcc-off">This category is offline, so the card is not shown.</span>@endif
                                            </td>
                                            <td>
                                                <select form="{{ $form }}" id="subs{{ $card->id }}" name="subcategory_ids[]" class="form-control input-sm hcc-subs" multiple aria-label="Links on the card">
                                                    @foreach($subcategories as $categoryId => $subs)
                                                        <optgroup label="{{ optional($categories->firstWhere('id', $categoryId))->catname }}" data-category="{{ $categoryId }}">
                                                            @foreach($subs as $sub)
                                                                <option value="{{ $sub->id }}" {{ in_array((int) $sub->id, array_map('intval', $card->subcategory_ids ?: []), true) ? 'selected' : '' }}>{{ $sub->catname }}{{ $sub->is_active === 'online' ? '' : ' (offline)' }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                                <span class="hcc-note">{{ $card->subcategory_ids ? '' : 'None chosen: shows the first ' . $max . '.' }}</span>
                                            </td>
                                            <td><input form="{{ $form }}" type="file" name="image" class="form-control input-sm" accept=".jpg,.jpeg,.png,.webp" aria-label="Replace the picture"></td>
                                            <td><input form="{{ $form }}" type="number" name="ranking" class="form-control input-sm" min="0" max="9999" required value="{{ $card->ranking }}" aria-label="Position"></td>
                                            <td><input form="{{ $form }}" type="checkbox" name="is_active" value="1" {{ $card->is_active ? 'checked' : '' }} aria-label="Show on the homepage"></td>
                                            <td>
                                                <form id="{{ $form }}" method="post" action="{{ route('home-category-cards.update', $card->id) }}" enctype="multipart/form-data" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm waves-effect waves-light">Save</button>
                                                </form>
                                                <form method="post" action="{{ route('home-category-cards.destroy', $card->id) }}" style="display:inline;" onsubmit="return confirm('Remove this card from the homepage?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="7" style="text-align:center;">No cards yet. Search by Category stays hidden on the homepage until you add one.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<script>
  // Each links list shows only the subcategories of the category chosen next to it
  document.querySelectorAll('.hcc-category').forEach(function (select) {
    var list = document.getElementById(select.dataset.subs);
    function sync(clear) {
      list.querySelectorAll('optgroup').forEach(function (group) {
        var mine = group.dataset.category === select.value;
        group.hidden = !mine;
        group.disabled = !mine;
        if (!mine && clear) { group.querySelectorAll('option').forEach(function (o) { o.selected = false; }); }
      });
    }
    select.addEventListener('change', function () { sync(true); });
    sync(false);
  });
</script>
@endsection
