@php
  $moq = $product->minOrderQuantity();
  $colors = json_decode((string) $product->colors, true);
  $colors = is_array($colors) ? array_values(array_filter($colors, function ($c) { return is_string($c) && preg_match('/^#[0-9a-f]{3,8}$/i', $c); })) : [];
  $image = $product->imageUrl();
@endphp
<article class="mh-product">
  <button type="button" class="mh-save" data-save-collection="{{ $product->id }}" data-quantity="{{ $moq }}" data-name="{{ $product->name }}" aria-label="Save {{ $product->name }} to a collection" title="Save to Collection"><i class="bi bi-bookmark"></i></button>
  <a href="{{ route('web.product', $product->slug) }}" class="mh-product-image">
    @if($image)
      <img src="{{ $image }}" alt="{{ $product->name }}" loading="lazy">
    @else
      <span class="mh-product-blank"><i class="bi bi-gift"></i></span>
    @endif
  </a>
  <a href="{{ route('web.product', $product->slug) }}" class="mh-product-name">{{ $product->name }}</a>
  <span class="mh-product-sku">{{ $product->modelno }}{{ $product->modelno ? ' · ' : '' }}MOQ {{ $moq }}</span>
  <strong class="mh-product-price">AED {{ number_format($product->priceForQuantity($moq), 2) }}</strong>
  <div class="mh-product-foot">
    <div class="mh-swatches" aria-hidden="true">
      @foreach(array_slice($colors, 0, 4) as $color)
        <span style="background: {{ $color }}"></span>
      @endforeach
    </div>
  </div>
</article>
