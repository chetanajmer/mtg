@extends('web_frontend.layouts.app')

@section('title', 'All Collections | More Than Giftz')
@section('meta')
  <meta name="description" content="View all your saved collections.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/collections-list.css') }}">
@endsection

@section('content')
@php
  $collections = $collections ?? null;
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
    <a href="#employees"><i class="bi bi-people-fill"></i>Employees</a>
    <a href="#reports"><i class="bi bi-bar-chart-line"></i>Reports</a>
    <a href="/corporate-account-settings"><i class="bi bi-gear"></i>Account Settings</a>
    <a href="{{ route('corporate.logout') }}" style="color: #dc3545;"><i class="bi bi-box-arrow-right"></i>Logout</a>
  </nav>
  <div class="help"><i class="bi bi-headset"></i><div><strong>Need Help?</strong><p>Your dedicated account manager is here.</p></div><button class="outline full" data-toast="Contact request started.">Contact Us <i class="bi bi-arrow-right"></i></button></div>
</aside>

<main class="content">
  <section class="hero" style="min-height: 150px;">
    <div style="width: 100%;">
      <nav style="margin-bottom: 10px; font-size: 14px; color: #6b7280;">
        <a href="{{ route('corporate.portal') }}" style="color: #6b7280; text-decoration: none;">Corporate Portal</a>
        <span style="margin: 0 8px;">›</span>
        <span style="color: #374151;">Saved Collections</span>
      </nav>
      <h1>All Saved Collections</h1>
      <p>Manage all your product collections in one place.</p>
    </div>
  </section>

  <section class="collections-toolbar">
    <div class="search-box">
      <i class="bi bi-search"></i>
      <input type="text" id="collectionSearch" placeholder="Search collections..." oninput="filterCollections()">
    </div>
    <button class="btn btn-primary" onclick="window.location.href='{{ route('corporate.portal') }}#collections'">
      <i class="bi bi-plus-lg"></i> Create New Collection
    </button>
  </section>

  <section class="collections-grid">
    @if($collections && $collections->count() > 0)
      @foreach($collections as $collection)
      <div class="collection-card" data-collection-name="{{ strtolower($collection->name) }}">
        <div class="collection-header">
          <div class="collection-icon">
            <i class="bi bi-bookmark-star"></i>
          </div>
          <button class="collection-menu" onclick="toggleCollectionMenu({{ $collection->id }})">
            <i class="bi bi-three-dots-vertical"></i>
          </button>
          <div class="collection-dropdown" id="menu-{{ $collection->id }}" style="display: none;">
            <a href="/collections/{{ $collection->id }}">
              <i class="bi bi-eye"></i> View
            </a>
            <a href="#" onclick="editCollection({{ $collection->id }})">
              <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="#" onclick="deleteCollection({{ $collection->id }})" style="color: #ef4444;">
              <i class="bi bi-trash"></i> Delete
            </a>
          </div>
        </div>
        <div class="collection-body">
          <h3>{{ $collection->name }}</h3>
          <p>{{ $collection->description ?? 'No description' }}</p>
          <div class="collection-stats">
            <div class="stat">
              <i class="bi bi-box-seam"></i>
              <span>{{ $collection->items_count ?? 0 }} Products</span>
            </div>
            <div class="stat">
              <i class="bi bi-clock"></i>
              <span>{{ $collection->updated_at ? $collection->updated_at->format('d M Y') : 'N/A' }}</span>
            </div>
          </div>
        </div>
        <div class="collection-footer">
          <a href="/collections/{{ $collection->id }}" class="btn btn-outline">
            View Collection <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
      @endforeach
      
      @if($collections->hasPages())
      <div class="pagination">
        {{ $collections->appends(['search' => request('search')])->links() }}
      </div>
      @endif
    @else
      <div class="empty-state">
        <i class="bi bi-collection"></i>
        <h2>No Collections Yet</h2>
        <p>You haven't created any collections yet. Start by creating your first collection!</p>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('corporate.portal') }}#collections'">
          <i class="bi bi-plus-lg"></i> Create First Collection
        </button>
      </div>
    @endif
  </section>
</main>
</div>
@endsection

@section('scripts')

<script>
function filterCollections() {
  const searchTerm = document.getElementById('collectionSearch').value.toLowerCase();
  const collectionCards = document.querySelectorAll('.collection-card');

  collectionCards.forEach(card => {
    const name = card.dataset.collectionName;
    
    if (name.includes(searchTerm)) {
      card.classList.remove('hidden');
    } else {
      card.classList.add('hidden');
    }
  });
}

function toggleCollectionMenu(collectionId) {
  const menu = document.getElementById('menu-' + collectionId);
  const isVisible = menu.style.display === 'block';
  
  // Close all other menus
  document.querySelectorAll('.collection-dropdown').forEach(dropdown => {
    dropdown.style.display = 'none';
  });
  
  // Toggle current menu
  menu.style.display = isVisible ? 'none' : 'block';
}

function editCollection(id) {
  window.location.href = '/collections/' + id;
}

function deleteCollection(id) {
  if (!confirm('Are you sure you want to delete this collection? This action cannot be undone.')) {
    return;
  }

  fetch('{{ route("collections.delete", ["id"]) }}'.replace('id', id), {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Collection deleted successfully!');
      location.reload();
    } else {
      alert('Error: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error deleting collection');
  });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
  if (!event.target.closest('.collection-menu')) {
    document.querySelectorAll('.collection-dropdown').forEach(dropdown => {
      dropdown.style.display = 'none';
    });
  }
});
</script>
@endsection
