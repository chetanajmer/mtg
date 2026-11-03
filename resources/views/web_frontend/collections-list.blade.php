@extends('web_frontend.layouts.app')

@section('title', 'Saved Collections | More Than Giftz')
@section('meta')
  <meta name="description" content="View all your saved collections.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/collections-list.css') }}">
@endsection

@section('content')
@php
  $collections = $collections ?? null;
  $search = $search ?? '';
  $totalCollections = $totalCollections ?? 0;
  $userName = session()->get('fullname') ?? session()->get('username') ?? 'User';
@endphp

<div class="dashboard-wrapper">

<aside class="sidebar">
  <button class="company"><i class="bi bi-buildings"></i><span><strong>{{ $userName }}</strong><small>Switch Company <i class="bi bi-arrow-right"></i></small></span></button>
  <nav>
    <a href="{{ route('corporate.portal') }}"><i class="bi bi-gift"></i>Dashboard</a>
    <a href="/orders"><i class="bi bi-cart3"></i>Orders</a>
    <a href="/corporate-addresses"><i class="bi bi-geo-alt"></i>Addresses</a>
    <a href="#branding"><i class="bi bi-image"></i>Branding Assets</a>
    <a class="active" href="/collections-all"><i class="bi bi-bookmark"></i>Saved Collections</a>
    <a href="{{ route('corporate.employees') }}"><i class="bi bi-people-fill"></i>Employees</a>
    <a href="{{ route('corporate.reports') }}"><i class="bi bi-bar-chart-line"></i>Reports</a>
    <a href="/corporate-account-settings"><i class="bi bi-gear"></i>Account Settings</a>
    <a href="{{ route('corporate.logout') }}" style="color: #dc3545;"><i class="bi bi-box-arrow-right"></i>Logout</a>
  </nav>
  <div class="help"><i class="bi bi-headset"></i><div><strong>Need Help?</strong><p>Your dedicated account manager is here.</p></div><a class="outline full help-link" href="mailto:info@morethangiftz.com?subject=Help%20with%20my%20account">Contact Us <i class="bi bi-arrow-right"></i></a><a class="help-call" href="tel:+971557863450"><i class="bi bi-telephone"></i> +971 55 786 3450</a></div>
</aside>

<main class="content">
  <section class="hero" style="min-height: 150px;">
    <div style="width: 100%;">
      <nav style="margin-bottom: 10px; font-size: 14px; color: #6b7280;">
        <a href="{{ route('corporate.portal') }}" style="color: #6b7280; text-decoration: none;">Corporate Portal</a>
        <span style="margin: 0 8px;">›</span>
        <span style="color: #374151;">Saved Collections</span>
      </nav>
      <h1>Saved Collections</h1>
      <p>Group products for an occasion or team, then send a whole collection to your quote cart in one click.</p>
    </div>
  </section>

  <section class="collections-toolbar">
    <form class="search-box" method="GET" action="{{ route('collections.all') }}" role="search">
      <i class="bi bi-search"></i>
      <input type="search" name="search" id="collectionSearch" value="{{ $search }}" placeholder="Search by name or description" oninput="filterCollections()" aria-label="Search collections">
      @if($search !== '')<a class="search-clear" href="{{ route('collections.all') }}" title="Clear search"><i class="bi bi-x-lg"></i></a>@endif
    </form>
    <span class="collections-count">
      @if($search !== '')
        {{ $collections->total() }} of {{ $totalCollections }} {{ $totalCollections == 1 ? 'collection' : 'collections' }} match "{{ $search }}"
      @else
        {{ $totalCollections }} {{ $totalCollections == 1 ? 'collection' : 'collections' }}
      @endif
    </span>
    <button type="button" class="btn btn-primary" onclick="openCollectionModal()">
      <i class="bi bi-plus-lg"></i> Create New Collection
    </button>
  </section>

  <section class="collections-grid" id="collectionsGrid">
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
          <div class="collection-title">
            <h3><a href="{{ route('collections.view', $collection->id) }}">{{ $collection->name }}</a></h3>
            <div class="collection-actions">
              <button type="button" class="collection-menu" onclick="toggleCollectionMenu({{ $collection->id }})" aria-label="More actions for {{ $collection->name }}">
                <i class="bi bi-three-dots-vertical"></i>
              </button>
              <div class="collection-dropdown" id="menu-{{ $collection->id }}" style="display: none;">
                <a href="{{ route('collections.view', $collection->id) }}"><i class="bi bi-eye"></i> Open</a>
                <a href="#" onclick="openCollectionModal({{ $collection->id }}); return false;"><i class="bi bi-pencil"></i> Rename / edit</a>
                <a href="#" onclick="deleteCollection({{ $collection->id }}); return false;" class="danger"><i class="bi bi-trash"></i> Delete</a>
              </div>
            </div>
          </div>
          <p class="collection-desc" id="desc-{{ $collection->id }}">{{ $collection->description }}</p>
          <div class="collection-stats">
            <span><i class="bi bi-box-seam"></i> {{ $collection->items_count }} {{ $collection->items_count == 1 ? 'product' : 'products' }}</span>
            @if($collection->total_pieces)<span><i class="bi bi-stack"></i> {{ number_format($collection->total_pieces) }} pcs</span>@endif
            <span><i class="bi bi-clock"></i> {{ optional($collection->updated_at)->format('d M Y') }}</span>
          </div>
          @if($collection->estimated_value > 0)
            <div class="collection-value"><small>Estimated value</small><strong>AED {{ number_format($collection->estimated_value, 2) }}</strong><small class="vat">excl. VAT</small></div>
          @endif
        </div>
        <div class="collection-footer">
          <a href="{{ route('collections.view', $collection->id) }}" class="btn btn-outline">{{ $collection->items_count ? 'View' : 'Add products' }} <i class="bi bi-arrow-right"></i></a>
          @if($collection->items_count)
            <button type="button" class="btn btn-primary" onclick="collectionToCart({{ $collection->id }}, this)"><i class="bi bi-cart-plus"></i> Add to quote cart</button>
          @endif
        </div>
      </div>
      @endforeach
      <div class="empty-state no-results" id="noResults" style="display: none;">
        <i class="bi bi-search"></i>
        <h2>No collections on this page match</h2>
        <p>Press Enter to search all your collections.</p>
      </div>

      @if($collections->hasPages())
      <div class="pagination">
        {{ $collections->links() }}
      </div>
      @endif
    @elseif($search !== '')
      <div class="empty-state">
        <i class="bi bi-search"></i>
        <h2>No collections match "{{ $search }}"</h2>
        <p>Try another word, or <a href="{{ route('collections.all') }}">show all collections</a>.</p>
      </div>
    @else
      <div class="empty-state">
        <i class="bi bi-collection"></i>
        <h2>No collections yet</h2>
        <p>Save products together for an event, a team or a budget, and request a quote for all of them at once.</p>
        <button type="button" class="btn btn-primary" onclick="openCollectionModal()">
          <i class="bi bi-plus-lg"></i> Create your first collection
        </button>
      </div>
    @endif
  </section>
</main>
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
  if (!event.target.closest('.collection-actions')) {
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
  document.getElementById('collectionName').focus();
}

function closeCollectionModal() {
  document.getElementById('collectionModal').style.display = 'none';
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
    return;
  }
  error.textContent = '';
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
      return;
    }
    if (editingId) {
      // Saved: update the card in place
      const card = document.getElementById('collection-' + editingId);
      card.querySelector('h3 a').textContent = data.collection.name;
      card.querySelector('.collection-desc').textContent = data.collection.description || '';
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
