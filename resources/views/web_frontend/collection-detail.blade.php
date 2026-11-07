@extends('web_frontend.layouts.app')

@section('title', $collection->name . ' | Saved Collections | More Than Giftz')
@section('meta')
  <meta name="description" content="View and manage your saved collection.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/collection-detail.css') }}">
@endsection

@section('content')
@php
  $rows = $rows ?? collect();
  $totals = $totals ?? ['products' => 0, 'pieces' => 0, 'value' => 0];
  $userName = session()->get('fullname') ?? session()->get('username') ?? 'User';
  $hasAvailable = $rows->contains(function ($row) { return $row->product && $row->available; });
@endphp

<div class="dashboard-wrapper">

<aside class="sidebar">
  <button class="company"><i class="bi bi-buildings"></i><span><strong>{{ $userName }}</strong><small>Switch Company <i class="bi bi-arrow-right"></i></small></span></button>
  <nav>
    <a href="{{ route('corporate.portal') }}"><i class="bi bi-gift"></i>Dashboard</a>
    <a href="/orders"><i class="bi bi-cart3"></i>Orders</a>
    <a href="/corporate-addresses"><i class="bi bi-geo-alt"></i>Addresses</a>
    <a href="{{ route('corporate.portal') }}#branding"><i class="bi bi-image"></i>Branding Assets</a>
    <a class="active" href="/collections-all"><i class="bi bi-bookmark"></i>Saved Collections</a>
    <a href="{{ route('corporate.employees') }}"><i class="bi bi-people-fill"></i>Employees</a>
    <a href="{{ route('corporate.reports') }}"><i class="bi bi-bar-chart-line"></i>Reports</a>
    <a href="{{ route('planner.index') }}"><i class="bi bi-calculator"></i>Budget Planner</a>
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
        <a href="/collections-all" style="color: #6b7280; text-decoration: none;">Saved Collections</a>
        <span style="margin: 0 8px;">›</span>
        <span style="color: #374151;">{{ $collection->name }}</span>
      </nav>
      <h1>{{ $collection->name }}</h1>
      @if($collection->description)<p>{{ $collection->description }}</p>@endif
    </div>
  </section>

  <section class="collection-actions">
    <div class="action-buttons">
      <button type="button" class="btn btn-primary" onclick="openPicker()">
        <i class="bi bi-plus-lg"></i> Add Products
      </button>
      <button type="button" class="btn btn-outline" onclick="showEditCollectionModal()">
        <i class="bi bi-pencil"></i> Edit Collection
      </button>
      <button type="button" class="btn btn-danger" onclick="deleteCollection()">
        <i class="bi bi-trash"></i> Delete Collection
      </button>
    </div>
    <div class="collection-summary">
      <div class="summary-item">
        <small>Products</small>
        <strong>{{ $totals['products'] }}</strong>
      </div>
      <div class="summary-item">
        <small>Pieces for the quote</small>
        <strong>{{ number_format($totals['pieces']) }}</strong>
      </div>
      <div class="summary-item">
        <small>Estimated value (excl. VAT)</small>
        <strong>AED {{ number_format($totals['value'], 2) }}</strong>
      </div>
    </div>
  </section>

  <section class="collection-items">
    @if($rows->count() > 0)
      <div class="items-table">
        <table class="cd-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Model no.</th>
              <th>Unit price</th>
              <th>Quantity</th>
              <th>Total</th>
              <th><span class="sr-only">Actions</span></th>
            </tr>
          </thead>
          <tbody>
            @foreach($rows as $row)
              @if(!$row->product)
              <tr class="cd-row gone" id="item-{{ $row->item->id }}">
                <td colspan="5">
                  <div class="cd-product"><span class="cd-thumb"><i class="bi bi-x-circle"></i></span><span><strong>Product no longer available</strong><small>It was removed from the catalogue and will be skipped when you request a quote.</small></span></div>
                </td>
                <td class="cd-actions"><button type="button" class="cd-remove" onclick="removeItem({{ $row->item->id }}, 'this product')"><i class="bi bi-trash"></i> Remove</button></td>
              </tr>
              @else
              <tr class="cd-row {{ $row->available ? '' : 'gone' }}" id="item-{{ $row->item->id }}">
                <td data-label="Product">
                  <div class="cd-product">
                    <span class="cd-thumb">@if($row->image)<img src="{{ $row->image }}" alt="" loading="lazy" onerror="this.remove()">@endif<i class="bi bi-gift"></i></span>
                    <span>
                      <a href="{{ route('web.product', $row->product->slug) }}" class="cd-name">{{ $row->product->name }}</a>
                      @if(!$row->available)<small class="cd-warn">Not available at the moment; skipped when you request a quote.</small>@endif
                    </span>
                  </div>
                </td>
                <td data-label="Model no." class="cd-muted">{{ $row->product->modelno ?: '-' }}</td>
                <td data-label="Unit price" class="cd-muted">AED {{ number_format($row->unit, 2) }}</td>
                <td data-label="Quantity">
                  <input type="number" class="cd-qty" value="{{ $row->item->quantity }}" min="1" aria-label="Quantity of {{ $row->product->name }}"
                    onchange="updateQuantity({{ $row->item->id }}, this)">
                  @if($row->item->quantity < $row->moq)
                    <small class="cd-warn">Minimum order is {{ number_format($row->moq) }}; the quote uses {{ number_format($row->moq) }}.</small>
                  @else
                    <small class="cd-note">Min. {{ number_format($row->moq) }}</small>
                  @endif
                </td>
                <td data-label="Total" class="cd-total">AED {{ number_format($row->total, 2) }}</td>
                <td class="cd-actions"><button type="button" class="cd-remove" onclick="removeItem({{ $row->item->id }}, @json($row->product->name))"><i class="bi bi-trash"></i> Remove</button></td>
              </tr>
              @endif
            @endforeach
          </tbody>
        </table>
      </div>
      <p class="cd-footnote">Prices are estimates for the quantity shown (tier prices apply). Final prices are confirmed in your quote.</p>
    @else
      <div class="cd-empty">
        <i class="bi bi-inbox"></i>
        <h2>No products yet</h2>
        <p>Search the catalogue and add the products you want in this collection.</p>
        <button type="button" class="btn btn-primary" onclick="openPicker()"><i class="bi bi-plus-lg"></i> Add Products</button>
      </div>
    @endif
  </section>

  @if($hasAvailable)
  <section class="collection-footer">
    <button type="button" class="btn btn-primary btn-large" id="proceedButton" onclick="proceedToEnquiry()">
      <i class="bi bi-cart-check"></i> Add all to quote cart
    </button>
  </section>
  @endif
</main>
</div>

<!-- Product picker: searches the whole catalogue -->
<div id="addProductModal" class="modal" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="pickerTitle">
  <div class="modal-content picker">
    <div class="modal-header">
      <h2 id="pickerTitle">Add Products to "{{ $collection->name }}"</h2>
      <button type="button" class="close-btn" onclick="closePicker()" aria-label="Close">&times;</button>
    </div>
    <div class="modal-body">
      <div class="picker-search">
        <i class="bi bi-search"></i>
        <input type="search" id="productSearch" placeholder="Search all products by name or model number" autocomplete="off" aria-label="Search products">
      </div>
      <p class="picker-status" id="pickerStatus" role="status"></p>
      <div class="products-list" id="productsList"></div>
      <button type="button" class="btn btn-outline picker-more" id="pickerMore" style="display: none;" onclick="loadProducts(false)">Show more</button>
    </div>
    <div class="form-actions picker-done">
      <button type="button" class="btn btn-primary" onclick="closePicker()">Done</button>
    </div>
  </div>
</div>

<!-- Edit Collection Modal -->
<div id="editCollectionModal" class="modal" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="editTitle">
  <div class="modal-content">
    <div class="modal-header">
      <h2 id="editTitle">Edit Collection</h2>
      <button type="button" class="close-btn" onclick="hideEditCollectionModal()" aria-label="Close">&times;</button>
    </div>
    <div class="modal-body">
      <form onsubmit="updateCollection(event)">
        <div class="form-group">
          <label for="editCollectionName">Collection name *</label>
          <input type="text" id="editCollectionName" value="{{ $collection->name }}" maxlength="255" required>
        </div>
        <div class="form-group">
          <label for="editCollectionDescription">Description</label>
          <textarea id="editCollectionDescription" rows="3" maxlength="1000">{{ $collection->description ?? '' }}</textarea>
        </div>
        <p class="cd-error" id="editError" role="alert"></p>
        <div class="form-actions">
          <button type="button" class="btn btn-outline" onclick="hideEditCollectionModal()">Cancel</button>
          <button type="submit" class="btn btn-primary" id="editSave">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="cd-toast" id="cdToast" role="status" aria-live="polite"></div>
@endsection

@section('scripts')

<script>
const collectionUrl = '{{ url('collections/' . $collection->id) }}';
const searchUrl = '{{ route('collections.products') }}';
const csrf = '{{ csrf_token() }}';
const jsonHeaders = { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf };
let pickerPage = 1, pickerTerm = '', pickerTimer = null, pickerChanged = false, pickerRequest = 0;

function showToast(message, isError) {
  const toast = document.getElementById('cdToast');
  toast.textContent = message;
  toast.className = 'cd-toast show' + (isError ? ' error' : '');
  clearTimeout(showToast.timer);
  showToast.timer = setTimeout(() => toast.className = 'cd-toast', 4000);
}

// A message to show after the page reloads
function reloadWith(message) {
  try { sessionStorage.setItem('cdToast', message); } catch (e) {}
  location.reload();
}
try {
  const pending = sessionStorage.getItem('cdToast');
  if (pending) { sessionStorage.removeItem('cdToast'); showToast(pending); }
} catch (e) {}

function errorText(data, fallback) {
  if (data && data.errors) { const key = Object.keys(data.errors)[0]; return data.errors[key][0]; }
  return (data && data.message) || fallback;
}

function send(url, method, body) {
  return fetch(url, { method: method, headers: jsonHeaders, body: body ? JSON.stringify(body) : undefined })
    .then(response => response.json().catch(() => ({})).then(data => ({ ok: response.ok && data.success !== false, data: data })));
}

/* ---------- Product picker ---------- */
function openPicker() {
  document.getElementById('addProductModal').style.display = 'flex';
  const input = document.getElementById('productSearch');
  input.focus();
  if (!document.getElementById('productsList').children.length) loadProducts(true);
}

function closePicker() {
  document.getElementById('addProductModal').style.display = 'none';
  if (pickerChanged) reloadWith('Collection updated.');
}

document.getElementById('productSearch').addEventListener('input', function () {
  clearTimeout(pickerTimer);
  pickerTimer = setTimeout(() => { pickerTerm = this.value.trim(); loadProducts(true); }, 300);
});

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text == null ? '' : String(text);
  return div.innerHTML;
}

function loadProducts(reset) {
  const list = document.getElementById('productsList');
  const status = document.getElementById('pickerStatus');
  const more = document.getElementById('pickerMore');
  if (reset) { pickerPage = 1; list.innerHTML = ''; }
  const request = ++pickerRequest;
  status.textContent = 'Loading...';
  more.style.display = 'none';
  fetch(searchUrl + '?collection={{ $collection->id }}&page=' + pickerPage + '&q=' + encodeURIComponent(pickerTerm), { headers: { 'Accept': 'application/json' } })
    .then(response => response.json())
    .then(data => {
      if (request !== pickerRequest) return; // a newer search has started
      if (!data.success) { status.textContent = data.message || 'Could not load products.'; return; }
      data.products.forEach(p => list.insertAdjacentHTML('beforeend', productRow(p)));
      status.textContent = data.total
        ? (pickerTerm ? data.total + (data.total == 1 ? ' product matches "' : ' products match "') + pickerTerm + '"' : data.total + ' products in the catalogue')
        : 'No products match "' + pickerTerm + '". Try another word or a model number.';
      pickerPage = data.nextPage || pickerPage;
      more.style.display = data.nextPage ? '' : 'none';
    })
    .catch(() => { if (request === pickerRequest) status.textContent = 'Could not load products. Please check your connection.'; });
}

function productRow(p) {
  const image = p.image ? '<img src="' + escapeHtml(p.image) + '" alt="" loading="lazy" onerror="this.remove()">' : '';
  const already = p.inCollection ? '<small class="picker-in" id="in-' + p.id + '">' + p.inCollection + ' already in this collection</small>' : '<small class="picker-in" id="in-' + p.id + '"></small>';
  return '<div class="product-item">'
    + '<span class="cd-thumb">' + image + '<i class="bi bi-gift"></i></span>'
    + '<div class="picker-info"><a href="' + escapeHtml(p.url) + '" target="_blank" rel="noopener"><strong>' + escapeHtml(p.name) + '</strong></a>'
    + '<small>' + (p.modelno ? 'Model ' + escapeHtml(p.modelno) + ' · ' : '') + 'From AED ' + Number(p.price).toFixed(2) + ' · Min. ' + p.moq + '</small>' + already + '</div>'
    + '<div class="picker-add"><input type="number" id="quantity-' + p.id + '" value="' + p.moq + '" min="1" aria-label="Quantity">'
    + '<button type="button" class="btn btn-primary" id="add-' + p.id + '" onclick="addProductToCollection(' + p.id + ')">Add</button></div>'
    + '</div>';
}

function addProductToCollection(productId) {
  const input = document.getElementById('quantity-' + productId);
  const button = document.getElementById('add-' + productId);
  const quantity = parseInt(input.value, 10);
  if (!quantity || quantity < 1) { input.focus(); showToast('Please enter a quantity of 1 or more.', true); return; }
  button.disabled = true;
  send(collectionUrl + '/items', 'POST', { product_id: productId, quantity: quantity }).then(({ ok, data }) => {
    button.disabled = false;
    if (!ok) { showToast(errorText(data, 'Could not add the product.'), true); return; }
    pickerChanged = true;
    button.textContent = 'Added';
    button.classList.add('added');
    document.getElementById('in-' + productId).textContent = data.quantity + ' in this collection';
    setTimeout(() => { button.textContent = 'Add more'; button.classList.remove('added'); }, 1500);
  }).catch(() => { button.disabled = false; showToast('Could not add the product. Please try again.', true); });
}

/* ---------- Items ---------- */
function removeItem(itemId, name) {
  if (!confirm('Remove ' + name + ' from this collection?')) return;
  send(collectionUrl + '/items/' + itemId, 'DELETE').then(({ ok, data }) => {
    if (!ok) { showToast(errorText(data, 'Could not remove the product.'), true); return; }
    reloadWith('Product removed.');
  }).catch(() => showToast('Could not remove the product. Please try again.', true));
}

function updateQuantity(itemId, input) {
  const quantity = parseInt(input.value, 10);
  if (!quantity || quantity < 1) { showToast('Quantity must be at least 1.', true); input.value = input.defaultValue; return; }
  input.disabled = true;
  send(collectionUrl + '/items/' + itemId, 'PUT', { quantity: quantity }).then(({ ok, data }) => {
    if (!ok) { input.disabled = false; input.value = input.defaultValue; showToast(errorText(data, 'Could not update the quantity.'), true); return; }
    reloadWith('Quantity updated.');
  }).catch(() => { input.disabled = false; showToast('Could not update the quantity. Please try again.', true); });
}

/* ---------- Collection ---------- */
function showEditCollectionModal() {
  document.getElementById('editError').textContent = '';
  document.getElementById('editCollectionModal').style.display = 'flex';
  document.getElementById('editCollectionName').focus();
}

function hideEditCollectionModal() {
  document.getElementById('editCollectionModal').style.display = 'none';
}

function updateCollection(event) {
  event.preventDefault();
  const name = document.getElementById('editCollectionName').value.trim();
  const description = document.getElementById('editCollectionDescription').value.trim();
  const error = document.getElementById('editError');
  const button = document.getElementById('editSave');
  if (!name) { error.textContent = 'Please enter a name for the collection.'; return; }
  button.disabled = true;
  send(collectionUrl, 'PUT', { name: name, description: description }).then(({ ok, data }) => {
    button.disabled = false;
    if (!ok) { error.textContent = errorText(data, 'Could not save.').replace('The name has already been taken.', 'You already have a collection with this name.'); return; }
    reloadWith('Collection saved.');
  }).catch(() => { button.disabled = false; error.textContent = 'Could not save. Please check your connection and try again.'; });
}

function deleteCollection() {
  if (!confirm('Delete "' + @json($collection->name) + '"? Its saved product list will be removed. Your quote cart and orders are not affected.')) return;
  send(collectionUrl, 'DELETE').then(({ ok, data }) => {
    if (!ok) { showToast(errorText(data, 'Could not delete the collection.'), true); return; }
    window.location.href = '{{ route('collections.all') }}';
  }).catch(() => showToast('Could not delete the collection. Please try again.', true));
}

// Put every product of this collection into the quote cart (MOQ and tier prices apply), then open the cart
function proceedToEnquiry() {
  const button = document.getElementById('proceedButton');
  button.disabled = true;
  send('{{ route('collections.to-cart', $collection->id) }}', 'POST').then(({ ok, data }) => {
    if (!ok) { button.disabled = false; showToast(errorText(data, 'Could not add the products to your quote cart.'), true); return; }
    if (data.skipped && data.skipped.length) {
      // Say what was left out before opening the cart
      showToast(data.message + ' Not available any more: ' + data.skipped.join(', ') + '.');
      setTimeout(() => window.location.href = data.cartUrl, 3500);
      return;
    }
    window.location.href = data.cartUrl;
  }).catch(() => { button.disabled = false; showToast('Could not add the products to your quote cart. Please try again.', true); });
}

// Close windows by clicking outside them or pressing Escape
window.addEventListener('click', function (event) {
  if (event.target === document.getElementById('addProductModal')) closePicker();
  if (event.target === document.getElementById('editCollectionModal')) hideEditCollectionModal();
});
document.addEventListener('keydown', function (event) {
  if (event.key !== 'Escape') return;
  if (document.getElementById('addProductModal').style.display === 'flex') closePicker();
  hideEditCollectionModal();
});
</script>
@endsection
