@extends('web_frontend.layouts.app')

@section('title', $product->metatitle ?? ($product->name ?? 'Product'))
@section('meta')
  <meta name="description" content="{{ $product->metadesc ?? '' }}">
  <meta name="keywords" content="{{ $product->metakey ?? '' }}">
@endsection

@section('content')
    <!-- BREADCRUMB -->
    <div class="container-fluid px-4 px-xl-5">
      <div class="breadcrumb-row">
        <span>Home</span><i class="bi bi-chevron-right"></i>
        <span>Product</span><i class="bi bi-chevron-right"></i>
        <span>{{ $product->name }}</span>
      </div>
    </div>

    <!-- PRODUCT AREA -->
    <section class="container-fluid px-4 px-xl-5 product-section">
      <div class="row g-4">

        <!-- PRODUCT GALLERY -->
        <div class="col-xl-4 col-lg-5">
          <div class="product-gallery">
            <div class="gallery-main">
              @if($product->featured == 'online' || $product->most_selling == 'online')
              <span class="best-seller"><i class="bi bi-star-fill"></i> Bestseller</span>
              @endif
              <button class="gallery-arrow arrow-left" data-gallery="prev" aria-label="Previous image">
                <i class="bi bi-chevron-left"></i>
              </button>
              @php
                $mainImage = '';
                if($product->brand != '42' && $product->thumbnail) {
                  $thumbPath = public_path('upload/product/thumbnail/' . $product->thumbnail);
                  $prodPath = public_path('upload/product/' . $product->thumbnail);
                  if(file_exists($thumbPath)) {
                    $mainImage = asset('upload/product/thumbnail/' . rawurlencode($product->thumbnail));
                  } elseif(file_exists($prodPath)) {
                    $mainImage = asset('upload/product/' . rawurlencode($product->thumbnail));
                  }
                } elseif($product->brand == '42' && $product->thumbnail) {
                  $mainImage = $product->thumbnail;
                } elseif($product->bulk_image) {
                  $bulkImages = explode(',', $product->bulk_image);
                  if($product->brand != '42') {
                    $thumbPath = public_path('upload/product/thumbnail/' . $bulkImages[0]);
                    $prodPath = public_path('upload/product/' . $bulkImages[0]);
                    if(file_exists($thumbPath)) {
                      $mainImage = asset('upload/product/thumbnail/' . rawurlencode($bulkImages[0]));
                    } elseif(file_exists($prodPath)) {
                      $mainImage = asset('upload/product/' . rawurlencode($bulkImages[0]));
                    }
                  } else {
                    $mainImage = $bulkImages[0];
                  }
                } elseif($product->image1) {
                  if($product->brand != '42') {
                    $thumbPath = public_path('upload/product/thumbnail/' . $product->image1);
                    $prodPath = public_path('upload/product/' . $product->image1);
                    if(file_exists($thumbPath)) {
                      $mainImage = asset('upload/product/thumbnail/' . rawurlencode($product->image1));
                    } elseif(file_exists($prodPath)) {
                      $mainImage = asset('upload/product/' . rawurlencode($product->image1));
                    }
                  } else {
                    $mainImage = $product->image1;
                  }
                } else {
                  $mainImage = asset('upload/product/product-main.jpg');
                }
              @endphp
              <img id="mainProductImage" src="{{ $mainImage }}" alt="{{ $product->name }}">
              <button class="gallery-arrow arrow-right" data-gallery="next" aria-label="Next image">
                <i class="bi bi-chevron-right"></i>
              </button>
            </div>

            <div class="thumbnail-row">
              @php
                $images = [];
                if($product->brand != '42' && $product->thumbnail) {
                  // Check in thumbnail folder first, then in product folder
                  $thumbPath = public_path('upload/product/thumbnail/' . $product->thumbnail);
                  $prodPath = public_path('upload/product/' . $product->thumbnail);
                  if(file_exists($thumbPath)) {
                    $images[] = asset('upload/product/thumbnail/' . rawurlencode($product->thumbnail));
                  } elseif(file_exists($prodPath)) {
                    $images[] = asset('upload/product/' . rawurlencode($product->thumbnail));
                  }
                } elseif($product->brand == '42' && $product->thumbnail) {
                  $images[] = $product->thumbnail;
                }
                if($product->bulk_image) {
                  $bulkImages = explode(',', $product->bulk_image);
                  foreach($bulkImages as $img) {
                    $img = trim($img);
                    if($product->brand != '42') {
                      // Check in thumbnail folder first, then in product folder
                      $thumbPath = public_path('upload/product/thumbnail/' . $img);
                      $prodPath = public_path('upload/product/' . $img);
                      if(file_exists($thumbPath)) {
                        $images[] = asset('upload/product/thumbnail/' . rawurlencode($img));
                      } elseif(file_exists($prodPath)) {
                        $images[] = asset('upload/product/' . rawurlencode($img));
                      }
                    } else {
                      $images[] = $img;
                    }
                  }
                }
                if($product->image1) {
                  if($product->brand != '42') {
                    $thumbPath = public_path('upload/product/thumbnail/' . $product->image1);
                    $prodPath = public_path('upload/product/' . $product->image1);
                    if(file_exists($thumbPath)) {
                      $images[] = asset('upload/product/thumbnail/' . rawurlencode($product->image1));
                    } elseif(file_exists($prodPath)) {
                      $images[] = asset('upload/product/' . rawurlencode($product->image1));
                    }
                  } else {
                    $images[] = $product->image1;
                  }
                }
                if($product->image2) {
                  if($product->brand != '42') {
                    $thumbPath = public_path('upload/product/thumbnail/' . $product->image2);
                    $prodPath = public_path('upload/product/' . $product->image2);
                    if(file_exists($thumbPath)) {
                      $images[] = asset('upload/product/thumbnail/' . rawurlencode($product->image2));
                    } elseif(file_exists($prodPath)) {
                      $images[] = asset('upload/product/' . rawurlencode($product->image2));
                    }
                  } else {
                    $images[] = $product->image2;
                  }
                }
                if($product->image3) {
                  if($product->brand != '42') {
                    $thumbPath = public_path('upload/product/thumbnail/' . $product->image3);
                    $prodPath = public_path('upload/product/' . $product->image3);
                    if(file_exists($thumbPath)) {
                      $images[] = asset('upload/product/thumbnail/' . rawurlencode($product->image3));
                    } elseif(file_exists($prodPath)) {
                      $images[] = asset('upload/product/' . rawurlencode($product->image3));
                    }
                  } else {
                    $images[] = $product->image3;
                  }
                }
                if($product->image4) {
                  if($product->brand != '42') {
                    $thumbPath = public_path('upload/product/thumbnail/' . $product->image4);
                    $prodPath = public_path('upload/product/' . $product->image4);
                    if(file_exists($thumbPath)) {
                      $images[] = asset('upload/product/thumbnail/' . rawurlencode($product->image4));
                    } elseif(file_exists($prodPath)) {
                      $images[] = asset('upload/product/' . rawurlencode($product->image4));
                    }
                  } else {
                    $images[] = $product->image4;
                  }
                }
                if($product->image5) {
                  if($product->brand != '42') {
                    $thumbPath = public_path('upload/product/thumbnail/' . $product->image5);
                    $prodPath = public_path('upload/product/' . $product->image5);
                    if(file_exists($thumbPath)) {
                      $images[] = asset('upload/product/thumbnail/' . rawurlencode($product->image5));
                    } elseif(file_exists($prodPath)) {
                      $images[] = asset('upload/product/' . rawurlencode($product->image5));
                    }
                  } else {
                    $images[] = $product->image5;
                  }
                }
                $images = array_unique($images);
              @endphp
              @foreach($images as $index => $img)
              <button class="thumb {{ $index == 0 ? 'active' : '' }}" data-image="{{ $img }}">
                <img src="{{ $img }}" alt="{{ $product->name }}">
              </button>
              @endforeach
            </div>
          </div>
        </div>

        <!-- PRODUCT INFORMATION -->
        <div class="col-xl-4 col-lg-7">
          <div class="product-info">
            @if($product->newarrival == 'online')
            <div class="sustainable-pill"><i class="bi bi-star-fill"></i> New Arrival</div>
            @endif

            <h1>{{ $product->name }}</h1>
            <div class="product-subtitle">{{ $product->subheading ?? '' }}</div>

            @if($product->review_count > 0)
            <div class="rating-row">
              <div class="stars">
                @for($i=1; $i<=5; $i++)
                  @if($i <= floor($product->rating ?? 0))
                    <i class="bi bi-star-fill"></i>
                  @elseif($i == ceil($product->rating ?? 0) && ($product->rating ?? 0) % 1 != 0)
                    <i class="bi bi-star-half"></i>
                  @else
                    <i class="bi bi-star"></i>
                  @endif
                @endfor
              </div>
              <strong>{{ $product->rating ?? 0 }}</strong>
              <span>({{ $product->review_count ?? 0 }} reviews)</span>
            </div>
            @endif

            <p class="product-description">
              {!! $product->shortdescription ?? '' !!}
            </p>

            @if($product->specifications)
            @php
              $specs = json_decode($product->specifications, true);
              $specs = is_array($specs) ? array_slice($specs, 0, 4) : [];
            @endphp
            @if(count($specs) > 0)
            <div class="feature-strip">
              @foreach($specs as $spec)
              <div class="feature">
                <i class="bi bi-check-circle"></i>
                <span>{!! $spec['value'] ?? '' !!}</span>
              </div>
              @endforeach
            </div>
            @endif
            @endif

            @if($product->colors)
            @php
              $colors = json_decode($product->colors, true);
              $colors = is_array($colors) ? $colors : [];
              $allColors = \App\Models\Color::all()->keyBy('code');
            @endphp
            @if(count($colors) > 0)
            <div class="option-section">
              <div class="option-label">Color: <span id="selectedColor">Select Color</span></div>
              <div class="color-options">
                @foreach($colors as $index => $color)
                @php
                  $colorName = isset($allColors[$color]) ? ucfirst($allColors[$color]->name) : 'Color ' . ($index + 1);
                @endphp
                <button class="color-swatch {{ $index == 0 ? 'selected' : '' }}" data-color="{{ $colorName }}" style="--swatch:{{ $color }}" aria-label="{{ $colorName }}"></button>
                @endforeach
              </div>
            </div>
            @endif
            @endif

            @if($product->branding_options)
            @php
              $brandingOpts = explode(',', $product->branding_options);
            @endphp
            @if(count($brandingOpts) > 0)
            <div class="option-section branding-section">
              <div class="option-label">Branding Options</div>
              <div class="branding-options">
                @foreach($brandingOpts as $index => $option)
                <button class="branding-option {{ $index == 0 ? 'selected' : '' }}">
                  <i class="bi bi-{{ $index == 0 ? 'check-circle' : 'circle' }}"></i>
                  <span>{{ trim($option) }}</span>
                </button>
                @endforeach
              </div>
            </div>
            @endif
            @endif

            @php
              // The quantity starts at, and can't go below, this product's MOQ
              $moq = max(1, (int) $product->min_order_quantity);
              $moqUnit = $product->moq_unit ?: 'Pieces';
              $startUnitPrice = $product->priceForQuantity($moq);
            @endphp
            <div class="quantity-pricing-container">
              <div class="quantity-wrapper">
                <div class="option-label">Quantity</div>
                <div class="quantity-box">
                  <button id="qtyMinus" type="button" class="qty-btn" disabled>−</button>
                  <input id="quantity" type="text" value="{{ $moq }}" class="qty-input" readonly tabindex="-1" aria-label="Quantity" style="cursor: default;">
                  <button id="qtyPlus" type="button" class="qty-btn">+</button>
                </div>
              </div>
              <div class="pricing-wrapper">
                @php
                  // Default to cost price
                  $unitPrice = $product->cost_price ?? 0;

                  // If tier pricing is enabled and has records, use the highest quantity range tier's price
                  if($product->tier_pricing_enabled === 'yes' && isset($tier_prices) && count($tier_prices) > 0) {
                    $highestTier = $tier_prices->sortByDesc('min_qty')->first();
                    $unitPrice = $highestTier->price;
                  }
                @endphp
                <div class="pricing-text">Unit Price: <span class="price-highlight" id="totalPrice">AED {{ number_format($unitPrice, 2) }}</span></div>
                <span class="minimum-text" id="unitPriceText">AED {{ number_format($unitPrice, 2) }} per piece × {{ $moq }} {{ $moqUnit }}</span>
                <span class="minimum-text">Minimum order quantity: {{ $moq }} {{ $moqUnit }}</span>              </div>
            </div>

            <div class="product-buttons">
              <button type="button" class="btn btn-dark quote-button" id="addToQuoteBtn"><i class="bi bi-cart3"></i> Add to Quote</button>
              <button class="btn sample-button"><i class="bi bi-gift"></i> Request a Sample</button>
              <button type="button" class="btn sample-button mh-save-product-btn" data-save-collection="{{ $product->id }}" data-quantity="{{ $moq }}" data-name="{{ $product->name }}"><i class="bi bi-bookmark"></i> Save to Collection</button>
            </div>
          </div>
        </div>

        <!-- TIER PRICING -->
        <div class="col-xl-4">
          <aside class="pricing-card">
            @if(count($tier_prices) > 0)
            @php
              // Savings are measured against the first tier; quantities below it pay the normal price
              $firstTier = $tier_prices->first();
              $saveUpTo = round((($firstTier->price - $tier_prices->min('price')) / $firstTier->price) * 100);
            @endphp
            <div class="pricing-header">
              <div>
                <h2><i class="bi bi-coin"></i> Tier Pricing <span>(Per Piece)</span></h2>
                <p>Transparent pricing. No hidden charges.</p>
              </div>
              @if($saveUpTo > 0)
              <span class="save-pill">Save up to {{ $saveUpTo }}%</span>
              @endif
            </div>

            <div class="table-wrap">
              <table class="tier-table">
                <thead>
                  <tr>
                    <th>Quantity Range</th>
                    <th>Price (AED)</th>
                    <th>Your Savings</th>
                  </tr>
                </thead>
                <tbody>
                  @if($moq < $firstTier->min_qty)
                    <tr><td>{{ $moq }} - {{ $firstTier->min_qty - 1 }}</td><td>AED {{ number_format($product->displayPrice(), 2) }}</td><td>–</td></tr>
                  @endif
                  @foreach($tier_prices as $tier)
                    @php
                      $savings = round((($firstTier->price - $tier->price) / $firstTier->price) * 100);
                    @endphp
                    <tr>
                      <td>{{ max($tier->min_qty, $moq) }} - {{ $tier->max_qty ?: 'Unlimited' }}</td>
                      <td>AED {{ number_format($tier->price, 2) }}</td>
                      <td>{{ $savings > 0 ? $savings . '%' : '–' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @endif

            <div class="custom-quantity">
              <div class="custom-quantity-icon"><i class="bi bi-coin"></i></div>
              <div>
                <strong>Need a custom quantity?</strong>
                <p>Get a personalized quote for larger volumes.</p>
              </div>
              <a href="{{ route('custom-order.create', ['product' => $product->name]) }}">Request a Quote <i class="bi bi-arrow-right"></i></a>
            </div>

            <div class="benefit-grid">
              <div class="benefit"><i class="bi bi-bounding-box-circles"></i><span>Free Customization<small>(Logo / Message)</small></span></div>
              <div class="benefit"><i class="bi bi-box-seam"></i><span>Bulk Order Discounts</span></div>
              <div class="benefit"><i class="bi bi-truck"></i><span>Fast Delivery<small>(UAE &amp; GCC)</small></span></div>
              <div class="benefit"><i class="bi bi-person"></i><span>Dedicated Account Manager</span></div>
              <div class="benefit"><i class="bi bi-credit-card"></i><span>Secure Payments</span></div>
              <div class="benefit"><i class="bi bi-arrow-counterclockwise"></i><span>Easy Returns<small>(For damaged items)</small></span></div>
            </div>
          </aside>
        </div>

      </div>
    </section>

    <!-- DETAILS -->
    <section class="container-fluid px-4 px-xl-5">
      <div class="details-card">
        <nav class="details-tabs">
          <button class="detail-tab active" data-tab="details">Product Details</button>
          <button class="detail-tab" data-tab="specifications">Specifications</button>
          <button class="detail-tab" data-tab="branding">Branding Guide</button>
          <button class="detail-tab" data-tab="shipping">Shipping &amp; Delivery</button>
          <button class="detail-tab" data-tab="reviews">Reviews</button>
        </nav>

        <div class="details-content">
          <div class="detail-panel active" id="details">
            <div class="details-copy">
              {!! $product->description ?? '' !!}
            </div>

            @if($product->specifications)
            @php
              $allSpecs = json_decode($product->specifications, true);
              $allSpecs = is_array($allSpecs) ? $allSpecs : [];
            @endphp
            @if(count($allSpecs) > 0)
            <div class="key-features">
              <h3>Key Features</h3>
              <div class="feature-columns">
                <ul>
                  @foreach($allSpecs as $spec)
                  <li><strong>{{ $spec['name'] ?? '' }}:</strong> {{ $spec['value'] ?? '' }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
            @endif
            @endif
          </div>

          <div class="detail-panel" id="specifications">
            @if($product->specifications)
            @php
              $detailSpecs = json_decode($product->specifications, true);
              $detailSpecs = is_array($detailSpecs) ? $detailSpecs : [];
            @endphp
            @if(count($detailSpecs) > 0)
            <div class="details-copy">
              <h3>Product Specifications</h3>
              <table class="table table-bordered">
                @foreach($detailSpecs as $spec)
                <tr>
                  <td><strong>{{ $spec['name'] ?? '' }}</strong></td>
                  <td>{{ $spec['value'] ?? '' }}</td>
                </tr>
                @endforeach
              </table>
            </div>
            @else
            <div class="placeholder-panel"><h3>Specifications</h3><p>No specifications available.</p></div>
            @endif
            @else
            <div class="placeholder-panel"><h3>Specifications</h3><p>{!! $product->specification ?? 'No specifications available.' !!}</p></div>
            @endif
          </div>

          <div class="detail-panel" id="branding">
            @if($product->branding_options)
            <div class="details-copy">
              <h3>Branding Options</h3>
              <p>Available branding methods for this product:</p>
              <ul>
                @php
                  $brandingList = explode(',', $product->branding_options);
                @endphp
                @foreach($brandingList as $option)
                <li>{{ trim($option) }}</li>
                @endforeach
              </ul>
            </div>
            @else
            <div class="placeholder-panel"><h3>Branding Guide</h3><p>Custom branding options are available. Contact us for more details.</p></div>
            @endif
          </div>

          <div class="detail-panel" id="shipping">
            <div class="placeholder-panel"><h3>Shipping &amp; Delivery</h3>
            <p>Fast delivery available across UAE &amp; GCC.</p>
            @if($product->estimated_delivery_time)
            <p><strong>Estimated Delivery:</strong> {{ $product->estimated_delivery_time }}</p>
            @endif
            </div>
          </div>

          <div class="detail-panel" id="reviews">
            @if($product->reviews_enabled == 'yes' && $product->review_count > 0)
            <div class="details-copy">
              <h3>Customer Reviews</h3>
              <div class="rating-summary">
                <div class="stars">
                  @for($i=1; $i<=5; $i++)
                    @if($i <= floor($product->rating ?? 0))
                      <i class="bi bi-star-fill"></i>
                    @elseif($i == ceil($product->rating ?? 0) && ($product->rating ?? 0) % 1 != 0)
                      <i class="bi bi-star-half"></i>
                    @else
                      <i class="bi bi-star"></i>
                    @endif
                  @endfor
                </div>
                <strong>{{ $product->rating ?? 0 }}</strong> out of 5
              </div>
              <p>Based on {{ $product->review_count ?? 0 }} reviews</p>
            </div>
            @else
            <div class="placeholder-panel"><h3>Reviews</h3><p>No reviews yet. Be the first to review this product!</p></div>
            @endif
          </div>
        </div>
      </div>
    </section>
@include('web_frontend.partials.save-to-collection')
@endsection

@section('scripts')
<script>
// Image Gallery Slider
document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.getElementById('mainProductImage');
    const thumbs = document.querySelectorAll('.thumb');
    const galleryArrows = document.querySelectorAll('.gallery-arrow');
    let currentImageIndex = 0;
    const images = [];

    // Collect all thumbnail images
    thumbs.forEach((thumb, index) => {
        const img = thumb.querySelector('img');
        if (img) {
            images.push({
                src: thumb.getAttribute('data-image'),
                thumbSrc: img.src
            });
        }
    });

    // Thumbnail click handler
    thumbs.forEach((thumb, index) => {
        thumb.addEventListener('click', function() {
            // Remove active class from all thumbs
            thumbs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked thumb
            this.classList.add('active');
            // Update main image
            const newSrc = this.getAttribute('data-image');
            if (mainImage && newSrc) {
                mainImage.src = newSrc;
            }
            currentImageIndex = index;
        });
    });

    // Arrow navigation
    galleryArrows.forEach(arrow => {
        arrow.addEventListener('click', function() {
            const direction = this.getAttribute('data-gallery');
            
            if (direction === 'next') {
                currentImageIndex = (currentImageIndex + 1) % images.length;
            } else if (direction === 'prev') {
                currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            }

            // Update main image
            if (mainImage && images[currentImageIndex]) {
                mainImage.src = images[currentImageIndex].src;
            }

            // Update active thumbnail
            thumbs.forEach((thumb, index) => {
                thumb.classList.remove('active');
                if (index === currentImageIndex) {
                    thumb.classList.add('active');
                }
            });
        });
    });

    // Tabs functionality
    const tabs = document.querySelectorAll('.detail-tab');
    const panels = document.querySelectorAll('.detail-panel');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');

            // Hide all panels
            panels.forEach(panel => {
                panel.classList.remove('active');
            });

            // Show target panel
            const targetPanel = document.getElementById(targetTab);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }
        });
    });

    // Color swatch selection
    const colorSwatches = document.querySelectorAll('.color-swatch');
    const selectedColorSpan = document.getElementById('selectedColor');

    colorSwatches.forEach(swatch => {
        swatch.addEventListener('click', function() {
            colorSwatches.forEach(s => s.classList.remove('selected'));
            this.classList.add('selected');
            const colorName = this.getAttribute('data-color');
            if (selectedColorSpan && colorName) {
                selectedColorSpan.textContent = colorName;
            }
        });
    });

    // Branding option selection
    const brandingOptions = document.querySelectorAll('.branding-option');
    brandingOptions.forEach(option => {
        option.addEventListener('click', function() {
            brandingOptions.forEach(o => {
                o.classList.remove('selected');
                const icon = o.querySelector('i');
                if(icon) icon.className = 'bi bi-circle';
            });
            this.classList.add('selected');
            const icon = this.querySelector('i');
            if(icon) icon.className = 'bi bi-check-circle';
        });
    });

    // Quantity controls (+ / - buttons only): never below the MOQ, with a live total
    const qtyMinus = document.getElementById('qtyMinus');
    const qtyPlus = document.getElementById('qtyPlus');
    const quantityInput = document.getElementById('quantity');
    const moq = {{ $moq }};
    const moqUnit = @json($moqUnit);
    const basePrice = {{ $product->displayPrice() }};
    // Same rule as Product::priceForQuantity() on the server
    const tiers = @json(collect($tier_prices)->map(function ($t) { return ['min' => (int) $t->min_qty, 'max' => $t->max_qty ? (int) $t->max_qty : null, 'price' => (float) $t->price]; })->values());

    function unitPriceFor(qty) {
        // Always return the highest tier price if tiers exist
        if (tiers.length > 0) {
            // Find the tier with the highest min_qty (last tier)
            let highestTier = tiers[0];
            tiers.forEach(function (t) {
                if (t.min > highestTier.min) {
                    highestTier = t;
                }
            });
            return highestTier.price;
        }
        return basePrice;
    }

    function formatAed(value) {
        return 'AED ' + value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function currentQty() {
        return parseInt(quantityInput.value, 10) || 0;
    }

    function refreshTotal() {
        const qty = Math.max(currentQty(), moq);
        const unit = unitPriceFor(qty);
        document.getElementById('totalPrice').textContent = formatAed(unit);
        document.getElementById('unitPriceText').textContent = formatAed(unit) + ' per piece × ' + qty + ' ' + moqUnit;
        qtyMinus.disabled = currentQty() <= moq;
    }

    function setQty(qty) {
        quantityInput.value = Math.max(moq, qty);
        refreshTotal();
    }

    if (qtyMinus && qtyPlus && quantityInput) {
        qtyMinus.addEventListener('click', function() { setQty(currentQty() - 1); });
        qtyPlus.addEventListener('click', function() { setQty(currentQty() + 1); });
        refreshTotal();
    }

    // Add to Quote
    const addToQuoteBtn = document.getElementById('addToQuoteBtn');
    if (addToQuoteBtn) {
        addToQuoteBtn.addEventListener('click', function() {
            setQty(currentQty());
            addToQuoteBtn.disabled = true;
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ product_id: {{ $product->id }}, quantity: currentQty() })
            })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                const cartCount = document.getElementById('cartCount');
                if (data.success && cartCount) cartCount.textContent = data.items;
                showQuoteMessage(data.success ? 'Added to Quote!' : (data.message || 'Could not add to quote'), data.success);
            })
            .catch(function () { showQuoteMessage('Could not add to quote. Please try again.', false); })
            .finally(function () { addToQuoteBtn.disabled = false; });
        });
    }

    function showQuoteMessage(message, ok) {
        const note = document.createElement('div');
        note.style.cssText = 'position:fixed;top:20px;right:20px;color:#fff;padding:12px 24px;border-radius:8px;z-index:2000;background:' + (ok ? '#22c55e' : '#dc2626');
        note.textContent = message;
        document.body.appendChild(note);
        setTimeout(function () { note.remove(); }, 2500);
    }
});
</script>
@endsection