{{-- "Save to Collection" pop-up, opened by any button with data-save-collection="product id" --}}
<div class="mh-save-modal" id="saveCollectionModal" role="dialog" aria-modal="true" aria-labelledby="saveCollectionTitle" hidden
  data-logged-in="{{ session()->has('userid') ? '1' : '0' }}"
  data-login-url="{{ route('corporate.login', ['redirect' => request()->getRequestUri()]) }}"
  data-list-url="{{ route('collections.for-product') }}"
  data-create-url="{{ route('collections.create') }}"
  data-add-url="{{ route('collections.add-item', 0) }}">
  <div class="mh-save-box">
    <button type="button" class="mh-save-close" id="saveCollectionClose" aria-label="Close"><i class="bi bi-x-lg"></i></button>
    <h2 id="saveCollectionTitle">Save to Collection</h2>
    <p class="mh-save-product" id="saveCollectionProduct"></p>
    <div class="mh-save-list" id="saveCollectionList" aria-live="polite"></div>
    <form class="mh-save-new" id="saveCollectionForm" novalidate>
      <label for="saveCollectionName">Create a new collection</label>
      <div>
        <input type="text" id="saveCollectionName" maxlength="255" autocomplete="off" placeholder="e.g. Diwali Gifts 2026">
        <button type="submit" id="saveCollectionCreate" disabled>Create &amp; Save</button>
      </div>
    </form>
    <p class="mh-save-error" id="saveCollectionError" role="alert" hidden></p>
  </div>
</div>
<script src="{{ asset('web_frontend/assets/js/save-to-collection.js') }}" defer></script>
