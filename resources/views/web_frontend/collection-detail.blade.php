@extends('web_frontend.layouts.app')

@section('title', 'Collection | More Than Giftz')
@section('meta')
  <meta name="description" content="View and manage your saved collection.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/collection-detail.css') }}">
@endsection

@section('content')
@php
  $collection = $collection ?? null;
  $products = $products ?? collect();
  $userName = session()->get('fullname') ?? session()->get('username') ?? 'User';
  $totalItems = $collection ? $collection->items->count() : 0;
  $totalQuantity = $collection ? $collection->items->sum('quantity') : 0;
  $totalValue = 0;
  if ($collection) {
    foreach ($collection->items as $item) {
      if ($item->product) {
        $totalValue += ($item->product->price ?? 0) * $item->quantity;
      }
    }
  }
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
        <a href="/collections-all" style="color: #6b7280; text-decoration: none;">Saved Collections</a>
        <span style="margin: 0 8px;">›</span>
        <span style="color: #374151;">{{ $collection->name }}</span>
      </nav>
      <h1>{{ $collection->name }}</h1>
      <p>{{ $collection->description ?? 'No description' }}</p>
    </div>
  </section>

  <section class="collection-actions">
    <div class="action-buttons">
      <button class="btn btn-primary" onclick="showAddProductModal()">
        <i class="bi bi-plus-lg"></i> Add Products
      </button>
      <button class="btn btn-outline" onclick="showEditCollectionModal()">
        <i class="bi bi-pencil"></i> Edit Collection
      </button>
      <button class="btn btn-danger" onclick="deleteCollection({{ $collection->id }})">
        <i class="bi bi-trash"></i> Delete Collection
      </button>
    </div>
    <div class="collection-summary">
      <div class="summary-item">
        <small>Total Products</small>
        <strong>{{ $totalItems }}</strong>
      </div>
      <div class="summary-item">
        <small>Total Quantity</small>
        <strong>{{ $totalQuantity }}</strong>
      </div>
      <div class="summary-item">
        <small>Estimated Value</small>
        <strong>AED {{ number_format($totalValue, 2) }}</strong>
      </div>
    </div>
  </section>

  <section class="collection-items">
    @if($collection->items->count() > 0)
      <div class="items-table">
        <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
          <thead style="background: #f9fafb;">
            <tr>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Product</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">SKU</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Unit Price</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Quantity</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Total</th>
              <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($collection->items as $item)
              @if($item->product)
              <tr style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 16px;">
                  <div style="display: flex; align-items: center; gap: 12px;">
                    @if($item->product->image1)
                      <img src="{{ asset('upload/product/' . $item->product->image1) }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                    @else
                      <div style="width: 50px; height: 50px; background: #f3f4f6; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-image" style="color: #9ca3af;"></i>
                      </div>
                    @endif
                    <span style="color: #374151; font-weight: 500;">{{ $item->product->name }}</span>
                  </div>
                </td>
                <td style="padding: 16px; color: #6b7280;">{{ $item->product->modelno ?? 'N/A' }}</td>
                <td style="padding: 16px; color: #6b7280;">AED {{ number_format($item->product->price ?? 0, 2) }}</td>
                <td style="padding: 16px;">
                  <input type="number" value="{{ $item->quantity }}" min="1" 
                    onchange="updateQuantity({{ $item->id }}, this.value)"
                    style="width: 80px; padding: 6px; border: 1px solid #e5e7eb; border-radius: 4px;">
                </td>
                <td style="padding: 16px; color: #374151; font-weight: 600;">
                  AED {{ number_format(($item->product->price ?? 0) * $item->quantity, 2) }}
                </td>
                <td style="padding: 16px;">
                  <button onclick="removeItem({{ $item->id }})" style="background: #ef4444; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">
                    <i class="bi bi-trash"></i> Remove
                  </button>
                </td>
              </tr>
              @endif
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <i class="bi bi-inbox" style="font-size: 64px; color: #d1d5db; margin-bottom: 20px;"></i>
        <h2 style="color: #374151; margin-bottom: 10px;">No Products Yet</h2>
        <p style="color: #6b7280; margin-bottom: 30px;">Start adding products to your collection.</p>
        <button onclick="showAddProductModal()" style="display: inline-block; padding: 12px 24px; background: #15171a; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">
          Add Products
        </button>
      </div>
    @endif
  </section>

  @if($collection->items->count() > 0)
  <section class="collection-footer">
    <button class="btn btn-primary btn-large" onclick="proceedToEnquiry()">
      <i class="bi bi-cart-check"></i> Proceed to Enquiry
    </button>
  </section>
  @endif
</main>
</div>

<!-- Add Product Modal -->
<div id="addProductModal" class="modal" style="display: none;">
  <div class="modal-content" style="max-width: 800px;">
    <div class="modal-header">
      <h2>Add Products to Collection</h2>
      <button class="close-btn" onclick="hideAddProductModal()">&times;</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label for="productSearch">Search Products</label>
        <input type="text" id="productSearch" placeholder="Search by name or SKU..." oninput="filterProducts()">
      </div>
      <div class="products-list" id="productsList" style="max-height: 400px; overflow-y: auto;">
        @foreach($products as $product)
        <div class="product-item" data-product-id="{{ $product->id }}" data-product-name="{{ strtolower($product->name) }}" data-product-sku="{{ strtolower($product->modelno ?? '') }}">
          <div style="display: flex; align-items: center; gap: 12px; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; margin-bottom: 8px;">
            @if($product->image1)
              <img src="{{ asset('upload/product/' . $product->image1) }}" alt="{{ $product->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
            @else
              <div style="width: 60px; height: 60px; background: #f3f4f6; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-image" style="color: #9ca3af;"></i>
              </div>
            @endif
            <div style="flex: 1;">
              <strong style="color: #374151;">{{ $product->name }}</strong>
              <small style="color: #6b7280; display: block;">SKU: {{ $product->modelno ?? 'N/A' }}</small>
              <small style="color: #15171a; font-weight: 600;">AED {{ number_format($product->price ?? 0, 2) }}</small>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
              <input type="number" id="quantity-{{ $product->id }}" value="1" min="1" style="width: 60px; padding: 6px; border: 1px solid #e5e7eb; border-radius: 4px;">
              <button onclick="addProductToCollection({{ $product->id }})" style="background: #15171a; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                Add
              </button>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<!-- Edit Collection Modal -->
<div id="editCollectionModal" class="modal" style="display: none;">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Edit Collection</h2>
      <button class="close-btn" onclick="hideEditCollectionModal()">&times;</button>
    </div>
    <div class="modal-body">
      <form onsubmit="updateCollection(event)">
        @csrf
        <div class="form-group">
          <label for="editCollectionName">Collection Name *</label>
          <input type="text" id="editCollectionName" value="{{ $collection->name }}" required>
        </div>
        <div class="form-group">
          <label for="editCollectionDescription">Description</label>
          <textarea id="editCollectionDescription" rows="3">{{ $collection->description ?? '' }}</textarea>
        </div>
        <div class="form-actions">
          <button type="button" class="btn btn-outline" onclick="hideEditCollectionModal()">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')

<script>
const collectionId = {{ $collection->id }};

function showAddProductModal() {
  document.getElementById('addProductModal').style.display = 'flex';
}

function hideAddProductModal() {
  document.getElementById('addProductModal').style.display = 'none';
}

function showEditCollectionModal() {
  document.getElementById('editCollectionModal').style.display = 'flex';
}

function hideEditCollectionModal() {
  document.getElementById('editCollectionModal').style.display = 'none';
}

function filterProducts() {
  const searchTerm = document.getElementById('productSearch').value.toLowerCase();
  const productItems = document.querySelectorAll('.product-item');

  productItems.forEach(item => {
    const name = item.dataset.productName;
    const sku = item.dataset.productSku;
    
    if (name.includes(searchTerm) || sku.includes(searchTerm)) {
      item.classList.remove('hidden');
    } else {
      item.classList.add('hidden');
    }
  });
}

function addProductToCollection(productId) {
  const quantity = document.getElementById('quantity-' + productId).value;
  
  fetch('{{ route("collections.add-item", ["id"]) }}'.replace('id', collectionId), {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
      product_id: productId,
      quantity: quantity
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Product added to collection!');
      location.reload();
    } else {
      alert('Error: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error adding product');
  });
}

function removeItem(itemId) {
  if (!confirm('Are you sure you want to remove this item?')) {
    return;
  }

  fetch('{{ route("collections.remove-item", ["id", "itemId"]) }}'.replace('id', collectionId).replace('itemId', itemId), {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Item removed from collection!');
      location.reload();
    } else {
      alert('Error: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error removing item');
  });
}

function updateQuantity(itemId, quantity) {
  if (quantity < 1) {
    alert('Quantity must be at least 1');
    return;
  }

  fetch('{{ route("collections.update-item", ["id", "itemId"]) }}'.replace('id', collectionId).replace('itemId', itemId), {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
      quantity: quantity
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      location.reload();
    } else {
      alert('Error: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error updating quantity');
  });
}

function updateCollection(event) {
  event.preventDefault();
  
  const name = document.getElementById('editCollectionName').value.trim();
  const description = document.getElementById('editCollectionDescription').value.trim();

  fetch('{{ route("collections.update", ["id"]) }}'.replace('id', collectionId), {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
      name: name,
      description: description
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Collection updated successfully!');
      hideEditCollectionModal();
      location.reload();
    } else {
      alert('Error: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error updating collection');
  });
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
      window.location.href = '/collections-all';
    } else {
      alert('Error: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error deleting collection');
  });
}

function proceedToEnquiry() {
  alert('This will redirect to the enquiry/order workflow with the collection items.');
  // TODO: Integrate with existing cart/enquiry workflow
}

// Close modals when clicking outside
window.onclick = function(event) {
  const addModal = document.getElementById('addProductModal');
  const editModal = document.getElementById('editCollectionModal');
  
  if (event.target == addModal) {
    hideAddProductModal();
  }
  if (event.target == editModal) {
    hideEditCollectionModal();
  }
}
</script>
@endsection
