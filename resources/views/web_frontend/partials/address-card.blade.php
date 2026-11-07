@php
  $line2 = collect([$addr->city, $addr->state])->filter()->implode(', ');
  if ($addr->pincode) { $line2 .= ($line2 ? ' - ' : '') . $addr->pincode; }
@endphp
<article class="address-card {{ $addr->is_default ? 'is-primary' : '' }}" data-id="{{ $addr->id }}"
  data-address='@json($addr)'>
  <div class="address-card-top">
    <div class="address-card-tags">
      @if($addr->is_default)
        <span class="tag-primary"><i class="bi bi-star-fill"></i> Primary Address</span>
      @endif
      <span class="tag-type"><i class="bi {{ $addr->label === 'Office' ? 'bi-building' : ($addr->label === 'Other' ? 'bi-geo' : 'bi-house-door') }}"></i> {{ $addr->label ?: 'Home' }}</span>
    </div>
    <div class="address-card-actions">
      <button type="button" class="btn-edit" data-action="edit"><i class="bi bi-pencil"></i> Edit</button>
      <button type="button" class="btn-delete" data-action="delete"><i class="bi bi-trash"></i> Delete</button>
    </div>
  </div>

  <div class="address-card-body">
    <div class="address-card-main">
      <div class="address-checkbox">
        <input type="checkbox" class="address-select-checkbox" data-id="{{ $addr->id }}">
      </div>
      <div class="address-pin"><i class="bi bi-geo-alt-fill"></i></div>
      <div class="address-details">
        <h3>{{ $addr->name }}</h3>
        <p><i class="bi bi-telephone"></i><span>{{ $addr->mobile }}</span></p>
        <p><i class="bi bi-geo-alt"></i><span>{{ $addr->address }}{{ $line2 ? ',' : '' }}<br>{{ $line2 }}</span></p>
      </div>
    </div>
    <div class="address-card-side">
      @if($addr->is_default)
        <span class="pill-primary">Primary Address</span>
        <p>This address will be used by default for placing orders.</p>
      @else
        <button type="button" class="btn-set-primary" data-action="primary"><i class="bi bi-star"></i> Set as Primary</button>
        <p>Select this address to use it by default for placing orders.</p>
      @endif
    </div>
  </div>
</article>
