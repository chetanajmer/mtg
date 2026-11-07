@extends('web_frontend.layouts.app')

@section('title', 'Branding Assets | More Than Giftz')
@section('meta')
  <meta name="description" content="Manage your uploaded branding assets, logos and documents.">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/account-settings.css') }}">
<link rel="stylesheet" href="{{ asset('web_frontend/assets/css/dashboard-new.css') }}">
<style>
.branding-assets-page {
  --gold: #b8860b;
  --gold-dark: #8b6914;
  --gold-text: #8b6914;
  --cream: #fff8dc;
  --cream-border: #d4b896;
  --ink: #000000;
  --text: #333333;
  --muted: #666666;
  --line: #e0e0e0;
  --line-soft: #f5f5f5;
  background: #ffffff;
  color: var(--text);
  padding: 14px 0 60px;
  font-family: Arial, Helvetica, sans-serif;
}

/* Welcome Banner */
.branding-banner {
  background: var(--cream);
  border: 1px solid var(--cream-border);
  border-radius: 8px;
  padding: 24px 32px;
  margin-bottom: 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 32px;
}
.branding-banner-content {
  flex: 1;
}
.branding-banner h1 {
  font-size: 32px;
  font-weight: 700;
  color: var(--ink);
  margin: 0 0 8px;
  letter-spacing: -0.5px;
}
.branding-banner p {
  font-size: 15px;
  color: var(--muted);
  margin: 0;
}
.branding-banner-image {
  width: 200px;
  height: 100px;
  background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
  border-radius: 8px;
  display: grid;
  place-items: center;
  color: #fff;
  font-size: 18px;
  font-weight: 700;
  flex-shrink: 0;
}

/* Search and Filter Toolbar */
.branding-toolbar {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 24px;
  flex-wrap: wrap;
}
.search-input {
  flex: 1;
  min-width: 200px;
  position: relative;
}
.search-input input {
  width: 100%;
  height: 44px;
  border: 1px solid var(--line);
  border-radius: 4px;
  padding: 0 44px 0 16px;
  font-size: 14px;
  color: var(--text);
  background: #fff;
  outline: none;
  transition: border-color .2s;
}
.search-input input:focus {
  border-color: var(--gold);
}
.search-input i {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--muted);
  font-size: 18px;
}

.filter-select {
  min-width: 150px;
}
.filter-select select {
  width: 100%;
  height: 44px;
  border: 1px solid var(--line);
  border-radius: 4px;
  padding: 0 40px 0 16px;
  font-size: 14px;
  color: var(--text);
  background: #fff;
  outline: none;
  cursor: pointer;
  appearance: none;
}
.filter-select-wrapper {
  position: relative;
}
.filter-select-wrapper i {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--muted);
  pointer-events: none;
}

.btn-upload {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 24px;
  background: var(--ink);
  color: #fff;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all .2s;
}
.btn-upload:hover {
  background: #333;
}
.btn-upload i {
  font-size: 18px;
}

/* Asset Cards Grid */
.assets-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 20px;
}

.asset-card {
  background: #fff;
  border: 1px solid var(--line);
  border-radius: 8px;
  overflow: hidden;
  transition: box-shadow .2s;
  position: relative;
}
.asset-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, .12);
}

.asset-card-menu {
  position: absolute;
  top: 12px;
  right: 12px;
  z-index: 10;
}
.asset-card-menu button {
  background: #fff;
  border: 1px solid var(--line);
  border-radius: 6px;
  width: 36px;
  height: 36px;
  display: grid;
  place-items: center;
  cursor: pointer;
  transition: all .2s;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.asset-card-menu button:hover {
  background: #f5f5f5;
  border-color: #999;
}
.asset-card-menu button i {
  font-size: 18px;
  color: var(--muted);
}
.asset-card .dropdown-menu {
  display: none;
  position: absolute;
  top: 100%;
  right: 0;
  background: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
  z-index: 1000;
  min-width: 160px;
  padding: 8px;
  margin-top: 8px;
}
.asset-card .dropdown-menu.show {
  display: block;
}
.asset-card .dropdown-item {
  width: 100%;
  padding: 10px 14px;
  text-align: left;
  border: none;
  background: none;
  cursor: pointer;
  font-size: 14px;
  color: #333333;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 10px;
  white-space: nowrap;
  font-weight: 500;
  line-height: 1;
  box-sizing: border-box;
  margin: 0;
  border-radius: 8px;
}
.asset-card .dropdown-item i {
  font-size: 16px;
  width: 20px;
  height: 20px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.asset-card .dropdown-item[data-action="edit"] {
  background-color: #fff3e0 !important;
  color: #333333 !important;
  border: 1px solid #ff9800 !important;
}
.asset-card .dropdown-item[data-action="edit"] i {
  color: #ff9800 !important;
}
.asset-card .dropdown-item[data-action="edit"]:hover {
  background-color: #ffe0b2 !important;
  border-color: #f57c00 !important;
}
.asset-card .dropdown-item[data-action="delete"] {
  color: #d32f2f !important;
  background-color: #ffebee !important;
  border: 1px solid #ef5350 !important;
}
.asset-card .dropdown-item[data-action="delete"] i {
  color: #ef5350 !important;
}
.asset-card .dropdown-item[data-action="delete"]:hover {
  background-color: #ffcdd2 !important;
  border-color: #e53935 !important;
}
.asset-card .dropdown-divider {
  height: 1px;
  background-color: #e0e0e0;
  margin: 6px 0;
  width: 100%;
}

.asset-preview {
  width: 100%;
  height: 180px;
  background: var(--line-soft);
  display: grid;
  place-items: center;
  overflow: hidden;
}
.asset-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.asset-preview .document-icon {
  font-size: 64px;
  color: var(--muted);
}
.asset-preview .document-icon.pdf { color: #dc3545; }
.asset-preview .document-icon.ai { color: #ff6b00; }
.asset-preview .document-icon.svg { color: #0d6efd; }

.asset-details {
  padding: 16px;
}
.asset-name {
  font-size: 15px;
  font-weight: 700;
  color: var(--ink);
  margin: 0 0 8px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.asset-meta {
  font-size: 12px;
  color: var(--muted);
  margin-bottom: 16px;
}

.asset-actions {
  display: flex;
  gap: 8px;
}
.btn-preview {
  flex: 1;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 10px 16px;
  border: 1px solid var(--line);
  border-radius: 4px;
  background: #fff;
  color: var(--text);
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all .2s;
}
.btn-preview:hover {
  border-color: var(--gold);
  color: var(--gold);
}
.btn-download {
  flex: 1;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 10px 16px;
  background: var(--ink);
  color: #fff;
  border: none;
  border-radius: 4px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all .2s;
}
.btn-download:hover {
  background: #333;
}

/* Modal */
.branding-modal {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, .5);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 1100;
  padding: 20px;
}
.branding-modal.active { display: flex; }
.branding-modal-content {
  background: #fff;
  border-radius: 8px;
  padding: 32px;
  max-width: 500px;
  width: 100%;
  box-shadow: 0 20px 50px rgba(0, 0, 0, .25);
  max-height: 85vh;
  overflow-y: auto;
  position: relative;
}
.branding-modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 24px;
}
.branding-modal-header h3 {
  font-size: 20px;
  font-weight: 700;
  color: var(--ink);
  margin: 0;
}
.branding-modal-close {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: var(--muted);
}
.form-group {
  margin-bottom: 20px;
}
.form-group label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: var(--ink);
  margin-bottom: 8px;
}
.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  border: 1px solid #d0d0d0 !important;
  border-radius: 4px;
  padding: 0 16px;
  font-size: 14px;
  color: var(--text);
  background: #fff;
  outline: none;
  box-sizing: border-box;
}
.form-group input {
  height: 44px;
}
.form-group select {
  height: 44px;
}
.form-group textarea {
  height: 100px;
  padding: 12px;
  resize: vertical;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: var(--gold) !important;
}
.form-group input[type="file"] {
  padding: 8px 16px;
  cursor: pointer;
}

.file-upload-area {
  border: 2px dashed #b8860b;
  border-left: 4px solid #b8860b;
  background: #faf8f0;
  border-radius: 8px;
  padding: 20px 16px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  max-width: 100%;
}

.file-upload-area:hover {
  background: #f5f0e0;
  border-color: #996b0a;
}

.file-upload-area.dragover {
  background: #efe8d0;
  border-color: #7a5000;
}

.upload-icon {
  font-size: 32px;
  color: #b8860b;
  margin-bottom: 8px;
}

.upload-title {
  font-size: 14px;
  font-weight: 700;
  color: #1e1b4b;
  margin-bottom: 4px;
}

.upload-subtitle {
  font-size: 12px;
  color: #6b7280;
  margin: 0;
}

.file-upload-area .selected-file {
  margin-top: 12px;
  padding: 8px 12px;
  background: #fff;
  border-radius: 4px;
  font-size: 13px;
  color: #374151;
  display: none;
}

.file-upload-area .selected-file.show {
  display: block;
}

.form-actions {
  display: flex;
  gap: 12px;
  margin-top: 24px;
}
.btn-cancel {
  flex: 1;
  padding: 12px 24px;
  border: 1px solid var(--line);
  border-radius: 4px;
  background: #fff !important;
  color: var(--text) !important;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}
.btn-save {
  flex: 1;
  padding: 12px 24px;
  background: #000000 !important;
  color: #ffffff !important;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}
.btn-save:hover {
  background: #333 !important;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  border: 1px dashed var(--line);
  border-radius: 8px;
  background: var(--line-soft);
}
.empty-state-icon {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  background: var(--cream);
  display: grid;
  place-items: center;
  margin: 0 auto 20px;
}
.empty-state-icon i {
  font-size: 32px;
  color: var(--gold);
}
.empty-state h3 {
  font-size: 20px;
  font-weight: 700;
  color: var(--ink);
  margin: 0 0 8px;
}
.empty-state p {
  font-size: 14px;
  color: var(--muted);
  margin: 0;
}

/* Responsive */
@media (max-width: 991.98px) {
  .branding-banner {
    flex-direction: column;
    text-align: center;
  }
  .branding-banner-image {
    width: 100%;
    max-width: 300px;
  }
  .branding-toolbar {
    flex-direction: column;
    align-items: stretch;
  }
  .search-input,
  .filter-select {
    width: 100%;
  }
  .assets-grid {
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  }
}

@media (max-width: 767.98px) {
  .branding-assets-page {
    padding-top: 10px;
  }
  .branding-banner h1 {
    font-size: 24px;
  }
  .assets-grid {
    grid-template-columns: 1fr;
  }
  .asset-actions {
    flex-direction: column;
  }
}
</style>
@endsection

@section('content')
@php
  $userName = session()->get('fullname') ?? session()->get('username') ?? 'User';
  $brandingOptions = \App\Models\Branding_option::all();
@endphp

<div class="branding-assets-page">
  <div class="container-fluid px-4 px-xl-5">

    <nav class="account-breadcrumb" aria-label="Breadcrumb">
      <a href="/">Home</a>
      <i class="bi bi-chevron-right"></i>
      <a href="{{ route('corporate.portal') }}">My Account</a>
      <i class="bi bi-chevron-right"></i>
      <span>Branding Assets</span>
    </nav>

    <div class="account-layout">

      @include('web_frontend.partials.corporate-sidebar', ['activePage' => 'branding'])

      <main class="account-main">

        <!-- Welcome Banner -->
        <div class="branding-banner">
          <div class="branding-banner-content">
            <h1>Branding Assets</h1>
            <p>Manage your uploaded branding assets, logos and documents.</p>
          </div>
          <div class="branding-banner-image">
            Corporate<br>Gifting
          </div>
        </div>

        <!-- Search and Filter Toolbar -->
        <div class="branding-toolbar">
          <div class="search-input">
            <input type="text" id="searchAssets" placeholder="Search by name..." value="{{ request('search') }}">
            <i class="bi bi-search"></i>
          </div>
          <div class="filter-select filter-select-wrapper">
            <select id="filterType">
              <option value="all" {{ request('file_type') == 'all' || !request('file_type') ? 'selected' : '' }}>All Types</option>
              <option value="PNG" {{ request('file_type') == 'PNG' ? 'selected' : '' }}>PNG</option>
              <option value="JPG" {{ request('file_type') == 'JPG' ? 'selected' : '' }}>JPG</option>
              <option value="JPEG" {{ request('file_type') == 'JPEG' ? 'selected' : '' }}>JPEG</option>
              <option value="PDF" {{ request('file_type') == 'PDF' ? 'selected' : '' }}>PDF</option>
              <option value="AI" {{ request('file_type') == 'AI' ? 'selected' : '' }}>AI</option>
              <option value="SVG" {{ request('file_type') == 'SVG' ? 'selected' : '' }}>SVG</option>
            </select>
            <i class="bi bi-chevron-down"></i>
          </div>
          <button type="button" class="btn-upload" id="btnUploadAsset">
            <i class="bi bi-cloud-upload"></i>
            Upload Asset
          </button>
        </div>

        <!-- Assets Grid -->
        @if($assets->count() > 0)
          <div class="assets-grid">
            @foreach($assets as $asset)
              <div class="asset-card" data-id="{{ $asset->id }}">
                <div class="asset-card-menu">
                  <button type="button" class="dropdown-toggle">
                    <i class="bi bi-three-dots-vertical"></i>
                  </button>
                  <div class="dropdown-menu">
                    <button type="button" class="dropdown-item" data-action="edit">
                      <i class="bi bi-pencil"></i> Edit
                    </button>
                    <div class="dropdown-divider"></div>
                    <button type="button" class="dropdown-item" data-action="delete">
                      <i class="bi bi-trash"></i> Delete
                    </button>
                  </div>
                </div>
                <div class="asset-preview">
                  @if(in_array(strtolower($asset->file_type), ['png', 'jpg', 'jpeg', 'webp']))
                    <img src="{{ asset('storage/' . $asset->file_path) }}" alt="{{ $asset->asset_name }}">
                  @else
                    <i class="bi bi-file-earmark document-icon {{ strtolower($asset->file_type) }}"></i>
                  @endif
                </div>
                <div class="asset-details">
                  <h3 class="asset-name" title="{{ $asset->asset_name }}">{{ $asset->asset_name }}</h3>
                  <p class="asset-meta">{{ $asset->file_type }} • {{ $asset->file_size_formatted }}</p>
                  <div class="asset-actions">
                    <button type="button" class="btn-preview" data-id="{{ $asset->id }}">
                      <i class="bi bi-eye"></i> Preview
                    </button>
                    <a href="{{ route('branding-assets.download', $asset->id) }}" class="btn-download">
                      <i class="bi bi-download"></i> Download
                    </a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
          {{ $assets->appends(request()->except('page'))->render() }}
        @else
          <div class="empty-state">
            <div class="empty-state-icon">
              <i class="bi bi-image"></i>
            </div>
            <h3>No branding assets yet</h3>
            <p>Upload your first branding asset to get started.</p>
          </div>
        @endif

        <!-- Branding Options Section -->
        @if($brandingOptions->count() > 0)
          <div style="margin-top: 40px;">
            <h2 style="font-size: 20px; font-weight: 700; color: var(--ink); margin-bottom: 20px;">Branding Options</h2>
            <div class="assets-grid">
              @foreach($brandingOptions as $option)
                <div class="asset-card">
                  <div class="asset-preview">
                    @if($option->image)
                      <img src="{{ asset('upload/branding_options/' . $option->image) }}" alt="{{ $option->name }}">
                    @else
                      <i class="bi bi-file-earmark document-icon"></i>
                    @endif
                  </div>
                  <div class="asset-details">
                    <h3 class="asset-name" title="{{ $option->name }}">{{ $option->name }}</h3>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

      </main>
    </div>
  </div>
</div>

<!-- Toaster Notification -->
<div class="toaster-notification" id="toaster">
  <div class="toaster-content">
    <i class="toaster-icon bi bi-check-circle"></i>
    <span class="toaster-message"></span>
  </div>
</div>

<!-- Upload Modal -->
<div class="branding-modal" id="uploadModal">
  <div class="branding-modal-content">
    <div class="branding-modal-header">
      <h3>Upload Branding Asset</h3>
      <button type="button" class="branding-modal-close" id="closeUploadModal">&times;</button>
    </div>
    <form id="uploadForm">
      @csrf
      <div class="form-group">
        <label for="assetName">Asset Name *</label>
        <input type="text" id="assetName" name="asset_name" required placeholder="e.g., MTG Logo">
      </div>
      <div class="form-group">
        <label for="assetFile">Upload File *</label>
        <div class="file-upload-area" id="uploadFileArea">
          <div class="upload-icon">
            <i class="bi bi-cloud-upload"></i>
          </div>
          <p class="upload-title">Drop files here</p>
          <p class="upload-subtitle">Drag files here or click to select</p>
          <input type="file" id="assetFile" name="file" required accept="image/*,.pdf,.ai,.svg" style="display: none;">
        </div>
      </div>
      <div class="form-group">
        <label for="assetDescription">Description</label>
        <textarea id="assetDescription" name="description" placeholder="Add a description (optional)"></textarea>
      </div>
      <div class="form-actions">
        <button type="button" class="btn-cancel" id="cancelUpload">Cancel</button>
        <button type="submit" class="btn-save">Upload</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="branding-modal" id="editModal">
  <div class="branding-modal-content" style="max-width: 600px;">
    <div class="branding-modal-header">
      <h3>Edit Branding Asset</h3>
      <button type="button" class="branding-modal-close" id="closeEditModal">&times;</button>
    </div>
    <form id="editForm">
      @csrf
      <input type="hidden" id="editAssetId">
      <input type="hidden" id="editAssetPath">
      <div class="form-group">
        <label for="editAssetName">Asset Name *</label>
        <input type="text" id="editAssetName" name="asset_name" required>
      </div>
      <div class="form-group">
        <label for="editAssetFile">Upload File (Optional)</label>
        <div class="file-upload-area" id="editFileArea">
          <div class="upload-icon">
            <i class="bi bi-cloud-upload"></i>
          </div>
          <p class="upload-title">Drop files here</p>
          <p class="upload-subtitle">Drag files here or click to select</p>
          <input type="file" id="editAssetFile" name="file" accept="image/*,.pdf,.ai,.svg" style="display: none;">
        </div>
      </div>
      <div class="form-group" id="editImagePreviewGroup" style="display: none;">
        <label>Current Image</label>
        <div id="editImagePreview" style="width: 100%; border: 1px solid #e0e0e0; border-radius: 4px; padding: 12px; background: #f8f9fa; text-align: center;">
          <img id="editImagePreviewImg" src="" alt="Current image" style="max-width: 100%; max-height: 300px; object-fit: contain;">
        </div>
      </div>
      <div class="form-group">
        <label for="editAssetDescription">Description</label>
        <textarea id="editAssetDescription" name="description" placeholder="Add a description (optional)"></textarea>
      </div>
      <div class="form-actions">
        <button type="button" class="btn-cancel" id="cancelEdit">Cancel</button>
        <button type="submit" class="btn-save">Update</button>
      </div>
    </form>
  </div>
</div>

<!-- Preview Modal -->
<div class="branding-modal" id="previewModal">
  <div class="branding-modal-content" style="max-width: 800px;">
    <div class="branding-modal-header">
      <h3 id="previewTitle">Preview</h3>
      <button type="button" class="branding-modal-close" id="closePreviewModal">&times;</button>
    </div>
    <div id="previewContent" style="min-height: 300px; display: grid; place-items: center;">
      <!-- Preview content will be loaded here -->
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
.toaster-notification {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9999;
  opacity: 0;
  visibility: hidden;
  transform: translateX(100%);
  transition: all 0.3s ease;
}

.toaster-notification.show {
  opacity: 1;
  visibility: visible;
  transform: translateX(0);
}

.toaster-content {
  background: #28a745;
  color: white;
  padding: 16px 24px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  gap: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  min-width: 300px;
}

.toaster-icon {
  font-size: 20px;
}

.toaster-message {
  font-size: 14px;
  font-weight: 500;
}
</style>
<script>
$(document).ready(function() {
  // Toaster function
  function showToaster(message) {
    $('#toaster .toaster-message').text(message);
    $('#toaster').addClass('show');
    setTimeout(function() {
      $('#toaster').removeClass('show');
    }, 3000);
  }

  // Upload Modal
  $('#btnUploadAsset').on('click', function() {
    $('#uploadModal').addClass('active');
  });

  $('#closeUploadModal, #cancelUpload').on('click', function() {
    $('#uploadModal').removeClass('active');
    $('#uploadForm')[0].reset();
    resetFileUploadArea('#uploadFileArea');
  });

  // File Upload Area - Upload Modal
  setupFileUploadArea('#uploadFileArea', '#assetFile');
  
  // File Upload Area - Edit Modal
  setupFileUploadArea('#editFileArea', '#editAssetFile');

  function setupFileUploadArea(areaId, inputId) {
    var $area = $(areaId);
    var $input = $(inputId);

    // Click to select file
    $area.on('click', function() {
      $input.click();
    });

    // Handle file selection
    $input.on('change', function() {
      var file = this.files[0];
      if (file) {
        $area.find('.upload-title').text(file.name);
        $area.find('.upload-subtitle').text('File selected');
      }
    });

    // Drag and drop events
    $area.on('dragover', function(e) {
      e.preventDefault();
      e.stopPropagation();
      $area.addClass('dragover');
    });

    $area.on('dragleave', function(e) {
      e.preventDefault();
      e.stopPropagation();
      $area.removeClass('dragover');
    });

    $area.on('drop', function(e) {
      e.preventDefault();
      e.stopPropagation();
      $area.removeClass('dragover');
      
      var files = e.originalEvent.dataTransfer.files;
      if (files.length > 0) {
        $input[0].files = files;
        $area.find('.upload-title').text(files[0].name);
        $area.find('.upload-subtitle').text('File selected');
      }
    });
  }

  function resetFileUploadArea(areaId) {
    var $area = $(areaId);
    $area.find('.upload-title').text('Drop files here');
    $area.find('.upload-subtitle').text('Drag files here or click to select');
  }

  // Upload Form
  $('#uploadForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
      url: '{{ route('branding-assets.store') }}',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if (response.success) {
          showToaster(response.message);
          $('#uploadModal').removeClass('active');
          $('#uploadForm')[0].reset();
          location.reload();
        } else {
          showToaster(response.message);
        }
      },
      error: function() {
        showToaster('Error uploading asset');
      }
    });
  });

  // Edit Modal
  $('#closeEditModal, #cancelEdit').on('click', function() {
    $('#editModal').removeClass('active');
    $('#editForm')[0].reset();
  });

  // Edit Form
  $('#editForm').on('submit', function(e) {
    e.preventDefault();
    var id = $('#editAssetId').val();
    var formData = new FormData(this);
    formData.append('_method', 'PUT');

    $.ajax({
      url: '{{ route('branding-assets.update', '__ID__') }}'.replace('__ID__', id),
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if (response.success) {
          showToaster(response.message);
          $('#editModal').removeClass('active');
          $('#editForm')[0].reset();
          location.reload();
        } else {
          showToaster(response.message);
        }
      },
      error: function() {
        showToaster('Error updating asset');
      }
    });
  });

  // Dropdown Menu Toggle
  $(document).on('click', '.dropdown-toggle', function(e) {
    e.stopPropagation();
    var $menu = $(this).siblings('.dropdown-menu');
    var isOpen = $menu.hasClass('show');

    // Close all other dropdowns
    $('.dropdown-menu').removeClass('show');

    // Toggle current dropdown
    if (!isOpen) {
      $menu.addClass('show');
    }
  });

  // Close dropdowns when clicking outside
  $(document).on('click', function() {
    $('.dropdown-menu').removeClass('show');
  });

  // Edit Action
  $(document).on('click', '.dropdown-item[data-action="edit"]', function(e) {
    e.stopPropagation();
    var card = $(this).closest('.asset-card');
    var id = card.data('id');

    $.get('{{ route('branding-assets.show', '__ID__') }}'.replace('__ID__', id), function(data) {
      $('#editAssetId').val(data.id);
      $('#editAssetPath').val(data.file_path);
      $('#editAssetName').val(data.asset_name);
      $('#editAssetDescription').val(data.description || '');

      // Show image preview if it's an image file
      if (['PNG', 'JPG', 'JPEG', 'WEBP'].includes(data.file_type)) {
        $('#editImagePreviewGroup').show();
        $('#editImagePreviewImg').attr('src', '/storage/' + data.file_path);
      } else {
        $('#editImagePreviewGroup').hide();
      }

      $('#editModal').addClass('active');
    });
  });

  // Delete Action
  $(document).on('click', '.dropdown-item[data-action="delete"]', function(e) {
    e.stopPropagation();
    var card = $(this).closest('.asset-card');
    var id = card.data('id');

    if (confirm('Are you sure you want to delete this asset?')) {
      $.ajax({
        url: '{{ route('branding-assets.destroy', '__ID__') }}'.replace('__ID__', id),
        type: 'DELETE',
        data: {
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          if (response.success) {
            showToaster(response.message);
            location.reload();
          } else {
            showToaster(response.message);
          }
        },
        error: function() {
          showToaster('Error deleting asset');
        }
      });
    }
  });

  // Search
  $('#searchAssets').on('keyup', function() {
    var search = $(this).val();
    var url = new URL(window.location.href);
    url.searchParams.set('search', search);
    url.searchParams.delete('page');
    window.location.href = url.toString();
  });

  // Filter
  $('#filterType').on('change', function() {
    var type = $(this).val();
    var url = new URL(window.location.href);
    url.searchParams.set('file_type', type);
    url.searchParams.delete('page');
    window.location.href = url.toString();
  });

  // Preview
  $('.btn-preview').on('click', function() {
    var id = $(this).data('id');
    $.get('{{ route('branding-assets.show', '__ID__') }}'.replace('__ID__', id), function(data) {
      $('#previewTitle').text(data.asset_name);
      var previewContent = $('#previewContent');

      if (['PNG', 'JPG', 'JPEG', 'WEBP'].includes(data.file_type)) {
        var imagePath = '/storage/' + data.file_path;
        previewContent.html('<img src="' + imagePath + '" style="max-width: 100%; max-height: 500px; object-fit: contain;">');
      } else if (data.file_type === 'PDF') {
        var pdfPath = '/storage/' + data.file_path;
        previewContent.html('<embed src="' + pdfPath + '" type="application/pdf" width="100%" height="500px">');
      } else {
        previewContent.html('<p>Preview not available for this file type.</p><a href="' + '{{ route('branding-assets.download', '__ID__') }}'.replace('__ID__', id) + '" class="btn-download" style="display: inline-block; margin-top: 16px;">Download File</a>');
      }

      $('#previewModal').addClass('active');
    });
  });

  $('#closePreviewModal').on('click', function() {
    $('#previewModal').removeClass('active');
  });

  // Close modals on outside click
  $('.branding-modal').on('click', function(e) {
    if (e.target === this) {
      $(this).removeClass('active');
    }
  });
});
</script>
@endsection
