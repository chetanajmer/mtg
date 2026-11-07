@extends('web_frontend.layouts.app')

@section('title', 'Saved Collections | More Than Giftz')
@section('meta')
  <meta name="description" content="View all your saved collections.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/collections-new.css') }}">
@endsection

@section('content')
@php
  $collections = $collections ?? null;
  $search = $search ?? '';
  $totalCollections = $totalCollections ?? 0;
@endphp

<div class="collections-page">
  <div class="container-fluid px-4 px-xl-5">

    <nav class="account-breadcrumb" aria-label="Breadcrumb">
      <a href="/">Home</a>
      <i class="bi bi-chevron-right"></i>
      <a href="{{ route('corporate.portal') }}">My Account</a>
      <i class="bi bi-chevron-right"></i>
      <span>Saved Collections</span>
    </nav>

    <div class="account-layout">

      @include('web_frontend.partials.corporate-sidebar', ['activePage' => 'collections'])

      <main class="account-main">

        <div class="collections-head">
          <h1>Saved Collections</h1>
          <p>Group products for an occasion or team, then send a whole collection to your quote cart in one click.</p>
        </div>

        <div class="collections-toolbar">
          <div class="toolbar-search">
            <i class="bi bi-search"></i>
            <input type="search" name="search" id="collectionSearch" value="{{ $search }}" placeholder="Search by name or description" oninput="filterCollections()" aria-label="Search collections">
          </div>
          <span class="collections-count">
            @if($search !== '')
              {{ $collections->total() }} of {{ $totalCollections }} {{ $totalCollections == 1 ? 'collection' : 'collections' }} match "{{ $search }}"
            @else
              {{ $totalCollections }} {{ $totalCollections == 1 ? 'collection' : 'collections' }}
            @endif
          </span>
          <button type="button" class="btn-create" onclick="openCollectionModal()">
            <i class="bi bi-plus-lg"></i> Create New Collection
          </button>
        </div>

        <div class="collections-grid" id="collectionsGrid">
          @if($collections && $collections->count() > 0)
            @foreach($collections as $collection)
            <div class="collection-card" id="collection-{{ $collection->id }}" data-collection-name="{{ strtolower($collection->name . ' ' . $collection->description) }}">
              <a class="collection-preview" href="{{ route('collections.view', $collection->id) }}" aria-label="Open {{ $collection->name }}" style="grid-template-columns: repeat({{ max(1, $collection->preview_images->count()) }}, 1fr);">
                @forelse($collection->preview_images as $image)
                  <span><img src="{{ $image }}" alt="" loading="lazy" onerror="this.parentNode.classList.add('no-img');this.remove()"></span>
                @empty
                  <span class="no-img wide"><i class="bi {{ $collection->items_count ? 'bi-gift' : 'bi-bookmark-star' }}"></i><small>{{ $collection->items_count ? 'No product photos' : 'No products yet' }}</small></span>
                @endforelse
              </a>
              <div class="collection-body">
                <div class="collection-header">
                  <div class="collection-title">
                    <h3><a href="{{ route('collections.view', $collection->id) }}">{{ $collection->name }}</a></h3>
                    <div class="collection-breadcrumb">Corporate Portal / Saved Collections / {{ $collection->name }}</div>
                  </div>
                  <div style="position: relative;">
                    <button type="button" class="collection-menu-btn" onclick="toggleCollectionMenu({{ $collection->id }})" aria-label="More actions for {{ $collection->name }}">
                      <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <div class="collection-dropdown" id="menu-{{ $collection->id }}" style="display: none;">
                      <a href="{{ route('collections.view', $collection->id) }}"><i class="bi bi-eye"></i> View</a>
                      <a href="#" onclick="openCollectionModal({{ $collection->id }}); return false;"><i class="bi bi-pencil"></i> Edit</a>
                      <a href="#" onclick="deleteCollection({{ $collection->id }}); return false;" class="danger"><i class="bi bi-trash"></i> Delete</a>
                    </div>
                  </div>
                </div>
                <p class="collection-desc" id="desc-{{ $collection->id }}">{{ $collection->description }}</p>
                <div class="collection-stats">
                  <span><i class="bi bi-box-seam"></i> {{ $collection->items_count }} {{ $collection->items_count == 1 ? 'product' : 'products' }}</span>
                  @if($collection->total_pieces)<span><i class="bi bi-stack"></i> {{ number_format($collection->total_pieces) }} pcs</span>@endif
                  <span><i class="bi bi-calendar"></i> {{ optional($collection->updated_at)->format('d M Y') }}</span>
                </div>
                @if($collection->estimated_value > 0)
                  <div class="collection-value"><small>Estimated value</small><strong>AED {{ number_format($collection->estimated_value, 2) }}</strong><small class="vat">excl. VAT</small></div>
                @endif
              </div>
              <div class="collection-footer">
                <a href="{{ route('collections.view', $collection->id) }}" class="btn-view">View <i class="bi bi-arrow-right"></i></a>
                @if($collection->items_count)
                  <button type="button" class="btn-add-cart" onclick="collectionToCart({{ $collection->id }}, this)"><i class="bi bi-cart-plus"></i> Add to quote cart</button>
                @endif
              </div>
            </div>
            @endforeach
            <div class="collections-empty no-results" id="noResults" style="display: none;">
              <div class="collections-empty-icon"><i class="bi bi-search"></i></div>
              <h2>No collections on this page match</h2>
              <p>Press Enter to search all your collections.</p>
            </div>

            @if($collections->hasPages())
            <div class="pagination">
              {{ $collections->links() }}
            </div>
            @endif
          @elseif($search !== '')
            <div class="collections-empty">
              <div class="collections-empty-icon"><i class="bi bi-search"></i></div>
              <h2>No collections match "{{ $search }}"</h2>
              <p>Try another word, or <a href="{{ route('collections.all') }}">show all collections</a>.</p>
            </div>
          @else
            <div class="collections-empty">
              <div class="collections-empty-icon"><i class="bi bi-collection"></i></div>
              <h2>No collections yet</h2>
              <p>Save products together for an event, a team or a budget, and request a quote for all of them at once.</p>
              <button type="button" class="btn-create" onclick="openCollectionModal()">
                <i class="bi bi-plus-lg"></i> Create your first collection
              </button>
            </div>
          @endif
        </div>

      </main>
    </div>
  </div>
</div>

<!-- Create / edit collection -->
<div id="collectionModal" class="cl-modal" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="collectionModalTitle">
  <form class="cl-modal-content" id="collectionForm" onsubmit="saveCollection(event)">
    <div class="cl-modal-header">
      <h2 id="collectionModalTitle">Create New Collection</h2>
      <button type="button" class="cl-close" onclick="closeCollectionModal()" aria-label="Close">&times;</button>
    </div>
    <div class="cl-modal-body">
      <label for="collectionName">Collection name *</label>
      <input type="text" id="collectionName" maxlength="255" required placeholder="e.g. Diwali gifts 2026">
      <label for="collectionDescription">Description <span>(optional)</span></label>
      <textarea id="collectionDescription" rows="3" maxlength="1000" placeholder="Who or what is it for?"></textarea>
      <p class="cl-error" id="collectionError" role="alert"></p>
    </div>
    <div class="cl-modal-actions">
      <button type="button" class="btn btn-outline" onclick="closeCollectionModal()">Cancel</button>
      <button type="submit" class="btn btn-primary" id="collectionSave">Create Collection</button>
    </div>
  </form>
</div>

<div class="cl-toast" id="clToast" role="status" aria-live="polite"></div>
@endsection

@section('scripts')
<script>
const collectionData = {
@foreach($collections ?? [] as $collection)
  {{ $collection->id }}: { name: @json($collection->name), description: @json((string) $collection->description) },
@endforeach
};
const csrf = '{{ csrf_token() }}';
let editingId = null;

function showToast(message, isError) {
  const toast = document.getElementById('clToast');
  toast.textContent = message;
  toast.className = 'cl-toast show' + (isError ? ' error' : '');
  clearTimeout(showToast.timer);
  showToast.timer = setTimeout(() => toast.className = 'cl-toast', 4000);
}

function filterCollections() {
  const term = document.getElementById('collectionSearch').value.trim().toLowerCase();
  let shown = 0;
  document.querySelectorAll('.collection-card').forEach(card => {
    const match = card.dataset.collectionName.includes(term);
    card.style.display = match ? '' : 'none';
    if (match) shown++;
  });
  const none = document.getElementById('noResults');
  if (none) none.style.display = shown ? 'none' : '';
}

function toggleCollectionMenu(id) {
  const menu = document.getElementById('menu-' + id);
  const open = menu.style.display === 'block';
  document.querySelectorAll('.collection-dropdown').forEach(d => d.style.display = 'none');
  menu.style.display = open ? 'none' : 'block';
}

document.addEventListener('click', function (event) {
  if (!event.target.closest('.collection-header')) {
    document.querySelectorAll('.collection-dropdown').forEach(d => d.style.display = 'none');
  }
});

function openCollectionModal(id) {
  editingId = id || null;
  const data = id ? collectionData[id] : { name: '', description: '' };
  document.getElementById('collectionModalTitle').textContent = id ? 'Edit Collection' : 'Create New Collection';
  document.getElementById('collectionSave').textContent = id ? 'Save Changes' : 'Create Collection';
  document.getElementById('collectionName').value = data.name;
  document.getElementById('collectionDescription').value = data.description;
  document.getElementById('collectionError').textContent = '';
  document.querySelectorAll('.collection-dropdown').forEach(d => d.style.display = 'none');
  document.getElementById('collectionModal').style.display = 'flex';
  document.getElementById('collectionModal').classList.add('open');
  document.getElementById('collectionName').focus();
}

function closeCollectionModal() {
  document.getElementById('collectionModal').style.display = 'none';
  document.getElementById('collectionModal').classList.remove('open');
}

document.getElementById('collectionModal').addEventListener('click', function (event) {
  if (event.target === this) closeCollectionModal();
});
document.addEventListener('keydown', function (event) {
  if (event.key === 'Escape') closeCollectionModal();
});

function firstError(data) {
  if (data && data.errors) {
    const key = Object.keys(data.errors)[0];
    return data.errors[key][0];
  }
  return (data && data.message) || 'Something went wrong. Please try again.';
}

function saveCollection(event) {
  event.preventDefault();
  const name = document.getElementById('collectionName').value.trim();
  const description = document.getElementById('collectionDescription').value.trim();
  const error = document.getElementById('collectionError');
  const button = document.getElementById('collectionSave');
  if (!name) {
    error.textContent = 'Please enter a name for the collection.';
    error.style.display = 'block';
    return;
  }
  error.textContent = '';
  error.style.display = 'none';
  button.disabled = true;

  const url = editingId ? '{{ url('collections') }}/' + editingId : '{{ route('collections.create') }}';
  fetch(url, {
    method: editingId ? 'PUT' : 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
    body: JSON.stringify({ name: name, description: description })
  })
  .then(response => response.json().catch(() => ({})).then(data => ({ ok: response.ok, data: data })))
  .then(({ ok, data }) => {
    button.disabled = false;
    if (!ok || !data.success) {
      error.textContent = firstError(data).replace('The name has already been taken.', 'You already have a collection with this name.');
      error.style.display = 'block';
      return;
    }
    if (editingId) {
      // Saved: update the card in place
      const card = document.getElementById('collection-' + editingId);
      card.querySelector('h3 a').textContent = data.collection.name;
      card.querySelector('.collection-desc').textContent = data.collection.description || '';
      card.querySelector('.collection-breadcrumb').textContent = 'Corporate Portal / Saved Collections / ' + data.collection.name;
      card.dataset.collectionName = (data.collection.name + ' ' + (data.collection.description || '')).toLowerCase();
      collectionData[editingId] = { name: data.collection.name, description: data.collection.description || '' };
      closeCollectionModal();
      showToast('Collection saved.');
    } else {
      // Created: open it so products can be added straight away
      window.location.href = data.url;
    }
  })
  .catch(() => {
    button.disabled = false;
    error.textContent = 'Could not save. Please check your connection and try again.';
    error.style.display = 'block';
  });
}

function deleteCollection(id) {
  const name = collectionData[id] ? collectionData[id].name : 'this collection';
  if (!confirm('Delete "' + name + '"? Its saved product list will be removed. Your quote cart and orders are not affected.')) {
    return;
  }
  fetch('{{ url('collections') }}/' + id, {
    method: 'DELETE',
    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
  })
  .then(response => response.json())
  .then(data => {
    if (!data.success) {
      showToast(data.message || 'Could not delete the collection.', true);
      return;
    }
    document.getElementById('collection-' + id).remove();
    delete collectionData[id];
    showToast('"' + name + '" deleted.');
    if (!document.querySelector('.collection-card')) {
      window.location.href = '{{ route('collections.all') }}';
    }
  })
  .catch(() => showToast('Could not delete the collection. Please try again.', true));
}

function collectionToCart(id, button) {
  button.disabled = true;
  fetch('{{ url('collections') }}/' + id + '/to-cart', {
    method: 'POST',
    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
  })
  .then(response => response.json())
  .then(data => {
    button.disabled = false;
    if (!data.success) {
      showToast(data.message || 'Could not add the products.', true);
      return;
    }
    const badge = document.getElementById('cartCount');
    if (badge && data.count !== undefined) badge.textContent = data.count;
    let message = data.message;
    if (data.skipped && data.skipped.length) {
      message += ' Not available any more: ' + data.skipped.join(', ') + '.';
    }
    showToast(message);
    button.innerHTML = '<i class="bi bi-cart-check"></i> View quote cart';
    button.onclick = () => window.location.href = data.cartUrl;
  })
  .catch(() => {
    button.disabled = false;
    showToast('Could not add the products. Please try again.', true);
  });
}
</script>
@endsection
