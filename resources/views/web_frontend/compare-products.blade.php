@extends('web_frontend.layouts.app')

@section('title', 'Compare Corporate Gifts')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--ink:#111;--muted:#666;--line:#e6e6e6;--peach:#fdf1e7;--gold:#fdeccf;--green:#e8f5e8;--gtext:#1d6b2a;--bg:#fff}
.compare-body{font-family:Inter,system-ui,-apple-system,Segoe UI,Arial,sans-serif;color:var(--ink);background:var(--bg);font-size:14px}
.compare-body a{color:inherit;text-decoration:none}
.compare-body button{font:inherit;cursor:pointer}
.compare-body svg{width:1em;height:1em;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex:none}

/* hero */
.wrap{padding:0 36px}
/* Banner: products sit on the right of the image, so the text stays on the left */
/* Full width, flush under the header: cancel .wrap's 36px side padding */
.hero{display:flex;align-items:center;margin:0 -36px;min-height:340px;padding:44px 36px;border-radius:0;overflow:hidden;background:#f6e9da url('{{ asset('web_frontend/assets/images/compare-hero-new.webp') }}') right center/cover no-repeat}
.hero-text{max-width:560px}
.eyebrow{font-size:12.5px;letter-spacing:2.3px;font-weight:500;margin-bottom:5px}
h1{font-size:50px;font-weight:800;letter-spacing:-1.5px;line-height:1.1}
.hero p{font-size:20px;color:#555;margin-top:6px;font-weight:300;letter-spacing:.1px}
.bulk{width:466px;max-width:100%;background:rgba(255,255,255,.72);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.8);border-radius:6px;display:flex;align-items:center;gap:22px;padding:18px 24px;margin-top:22px;box-shadow:0 6px 20px rgba(120,80,30,.08)}
@media(max-width:900px){
 .hero{min-height:0;padding:28px 36px;background-image:linear-gradient(90deg,rgba(250,240,228,.95) 0%,rgba(250,240,228,.85) 55%,rgba(250,240,228,.35) 100%),url('{{ asset('web_frontend/assets/images/compare-hero-new.webp') }}');background-position:center,70% center}
 h1{font-size:36px}
 .hero p{font-size:16px}
}
.bulk .ico{font-size:46px;stroke-width:1.6}
.bulk h3{font-size:19px;font-weight:600;margin-bottom:5px}
.bulk p{font-size:14.5px;margin:0;color:#555;font-weight:400;line-height:1.35}

/* compare grid */
.cmp{display:grid;grid-template-columns:278px repeat(3,1fr);gap:4px;margin-top:17px;transition:grid-template-columns 0.3s ease;align-items:stretch}
.card{border:1px solid var(--line);border-radius:3px;background:#fff;padding:14px 14px 10px}
.sel h4{font-size:15px;font-weight:600}
.sel small{display:block;font-size:14px;color:#444;margin:2px 0 11px}
.sbox{display:flex;align-items:center;gap:8px;border:1px solid #ddd;height:33px;padding:0 10px;font-size:12.5px;color:#777;margin-bottom:8px;border-radius:2px}
/* 5 products visible (41px row + 6px gap each), the rest scroll */
#productList{max-height:229px;overflow-y:auto;padding-right:4px}
#productList::-webkit-scrollbar{width:6px}
#productList::-webkit-scrollbar-thumb{background:#ccc;border-radius:3px}
.opt{display:flex;align-items:center;gap:14px;height:41px;margin-bottom:6px;padding:0 6px;font-size:13px;border-radius:2px;cursor:pointer}
.opt.on{background:var(--peach)}
.cb{width:17px;height:17px;border:1.5px solid #bbb;border-radius:3px;background:#fff;display:grid;place-items:center;flex:none}
.on .cb{background:#1e63e9;border-color:#1e63e9;color:#fff}
.cb svg{width:12px;height:12px;stroke-width:3;display:none}
.on .cb svg{display:block}
.thumb{width:36px;height:36px;display:grid;place-items:center}
.t-b{width:13px;height:32px;background:#111;border-radius:3px}
.t-s{width:36px;height:22px;background:#222;border-radius:50%/60%}
.t-n{width:25px;height:34px;background:#1c1c1c;border-radius:2px}
.t-t{width:22px;height:28px;background:#1c1c1c;border-radius:2px}
.t-w{width:26px;height:26px;background:#2a2a2a;border-radius:3px}
.opt:nth-child(n+4) .thumb{background:#f3f3f3}

.prod{position:relative;padding:12px 14px 10px 18px}
.rm{position:absolute;right:11px;top:12px;font-size:11.5px;display:flex;align-items:center;gap:6px;background:none;border:0;color:#333}
.rm svg{font-size:15px}
.pt{display:flex;gap:18px;align-items:flex-start;padding-top:34px}
.pimg{width:98px;height:158px;background:#f1f1f1;border-radius:3px;display:grid;place-items:center;flex:none;position:relative;overflow:hidden}
.pimg.sp{margin-top:0}
.pimg img{width:100%;height:100%;object-fit:cover}
a.pimg{text-decoration:none;cursor:pointer}
.pi h3 a.plink{color:inherit;text-decoration:none}
.pi h3 a.plink:hover{color:#c8102e;text-decoration:underline}
.bottle{width:50px;height:132px;background:linear-gradient(90deg,#222,#000 50%,#2b2b2b);border-radius:8px 8px 6px 6px;position:relative;margin-top:14px}
.bottle:before{content:"";position:absolute;top:-18px;left:7px;width:36px;height:18px;border:5px solid #111;border-bottom:0;border-radius:12px 12px 0 0}
.bottle:after{content:"";position:absolute;top:-6px;left:2px;right:2px;height:8px;background:#111;border-radius:3px}
.speaker{width:78px;height:96px;background:repeating-linear-gradient(45deg,#2a2a2a 0 2px,#161616 2px 4px);border-radius:12px;display:grid;place-items:center;color:#ddd;font-weight:800;font-size:12px;border:3px solid #111}
.note{width:84px;height:132px;background:#161616;border-radius:3px 6px 6px 3px;color:#d9c9a0;font-family:Georgia,serif;font-size:10.5px;line-height:1.25;display:grid;place-items:center;text-align:center;box-shadow:inset 6px 0 0 #000;position:relative}
.pen{position:absolute;right:-6px;top:30px;width:5px;height:96px;background:#111;border-radius:3px;transform:rotate(3deg);z-index:2}
.pimg.nb{background:#ececec;overflow:visible}
.pi{min-width:0}
.pi h3{font-size:17px;font-weight:600;margin-bottom:6px;line-height:1.25}
.pi p{font-size:14.5px;color:#555;margin-bottom:10px;font-weight:300;line-height:1.4}
.badge{display:inline-flex;align-items:center;gap:8px;font-size:13px;padding:8px 14px;border-radius:5px;font-weight:500}
.b-g{background:#e6f5e8;color:#1b5e27}.b-g svg{fill:#2e9e44;stroke:none}
.b-b{background:#e4efff;color:#1b3f8c}.b-b svg{fill:#1e63e9;stroke:none}
.b-o{background:#fdf0dc;color:#6b3a00}.b-o svg{fill:#d98a00;stroke:none}
.feat{list-style:none;margin:12px 0 0;padding:0}
.feat li{display:flex;align-items:flex-start;gap:12px;font-size:14px;color:#666;font-weight:300;line-height:1.45;padding:3px 0}
.feat svg{font-size:15px;color:#222;stroke-width:2.4;margin:3px 0}

/* Comparison cards container */
#comparisonCards{display:contents}

/* table */
.tbl{display:grid;grid-template-columns:278px repeat(6,1fr);margin-top:6px;border:1px solid var(--line);border-bottom:0;background:#fff}
.tbl>div{height:32px;display:flex;align-items:center;justify-content:center;font-size:14.5px;border-bottom:1px solid var(--line);border-right:1px solid var(--line)}
.tbl>.q{justify-content:flex-start;padding-left:22px}
.tbl>.h{background:#f4f4f4;font-weight:600;min-height:48px;height:auto;padding:6px 10px;font-size:14.5px;flex-direction:column;text-align:center;line-height:1.3}
.tbl>.h.q{font-size:15px;flex-direction:row;padding-left:22px}
.tbl>.sel{background:var(--gold);color:#b35a00;font-weight:600;border-color:#f3cf93}
.tbl>.sel.q{gap:9px;color:#b35a00;display:flex;align-items:center;justify-content:flex-start;padding-left:22px}
.tbl>.sel.q svg{fill:#e88a00;stroke:#e88a00;font-size:17px}
.tbl>div{cursor:pointer}.tbl>.h{cursor:default}
.tbl>div:not(.h):hover{background:#fff8ee}

.sav{display:grid;grid-template-columns:278px repeat(3,1fr);gap:2px;margin-top:10px}
.sav>div{background:var(--green);height:72px;display:flex;flex-direction:column;align-items:center;justify-content:center}
.sav>div:first-child{flex-direction:row;gap:20px;justify-content:flex-start;padding-left:14px;font-weight:600;font-size:14px;color:#222}
.sav .ico{font-size:34px;color:#1d6b2a;stroke-width:1.5}
.sav b{font-size:17px;font-weight:600;margin-bottom:5px}
.sav span{color:var(--gtext);font-size:14.5px}

/* actions */
.act{display:flex;align-items:center;gap:40px;padding:34px 0 40px;position:relative}
.btn{display:flex;align-items:center;gap:15px;height:41px;padding:0 26px;border:1px solid #888;background:#fff;border-radius:3px;font-size:14px;font-weight:500}
.btn.dark{background:#0d0d0d;color:#fff;border-color:#0d0d0d;padding:0 48px;justify-content:center;gap:12px;font-size:14px}
.btn svg{font-size:19px}

/* Compare page toasts (scoped, override the site-wide .toaster-notification look).
   They stack in #ctStack, newest at the bottom. */
#ctStack{position:fixed;top:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:12px;width:min(380px,calc(100vw - 32px));pointer-events:none}
.ct-toast.leaving{transform:translateX(calc(100% + 40px));opacity:0}
.ct-toast{position:relative;top:auto;right:auto;max-width:none;width:100%;pointer-events:auto;padding:16px 18px 18px;gap:14px;align-items:center;border:1px solid #f3dcc4;border-radius:16px;background:linear-gradient(135deg,#fffaf5 0%,#fdf1e7 100%);box-shadow:0 14px 40px rgba(179,90,0,.14),0 2px 6px rgba(0,0,0,.05);overflow:hidden;font-family:Inter,system-ui,-apple-system,Segoe UI,Arial,sans-serif;transform:translateX(calc(100% + 40px));transition:transform .45s cubic-bezier(.22,1.2,.36,1),opacity .3s ease}
.ct-toast.show{transform:translateX(0)}
.ct-toast .toaster-icon{width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,#ffd9a8,#f5a524);box-shadow:0 6px 14px rgba(245,165,36,.35)}
.ct-toast.show .toaster-icon{animation:ctPop .55s .15s cubic-bezier(.34,1.56,.64,1) both}
.ct-toast .toaster-icon i{font-size:22px;color:#fff}
.ct-toast[data-type="error"]{border-color:#f6cfcf;background:linear-gradient(135deg,#fffafa,#fdeeee)}
.ct-toast[data-type="error"] .toaster-icon{background:linear-gradient(135deg,#ffb4b4,#e5484d);box-shadow:0 6px 14px rgba(229,72,77,.3)}
.ct-toast[data-type="success"] .toaster-icon{background:linear-gradient(135deg,#b8f0c8,#22c55e);box-shadow:0 6px 14px rgba(34,197,94,.3)}
.ct-toast .toaster-message h4{font-size:15px;font-weight:700;color:#3d2410;margin:0 0 3px;letter-spacing:-.1px}
.ct-toast .toaster-message p{font-size:13px;color:#7a5a3e;line-height:1.45;margin:0}
.ct-toast .toaster-close{width:28px;height:28px;border-radius:50%;display:grid;place-items:center;color:#a07a55;font-size:18px;align-self:flex-start;margin:-4px -6px 0 0}
.ct-toast .toaster-close:hover{background:rgba(179,90,0,.08);color:#5a3410}
.ct-toast .ct-bar{position:absolute;left:0;bottom:0;height:3px;width:100%;background:linear-gradient(90deg,#f5a524,#ffcf87);transform-origin:left;transform:scaleX(0)}
.ct-toast.show .ct-bar{animation:ctBar var(--ct-time,3.5s) linear forwards}
.ct-toast[data-type="error"] .ct-bar{background:linear-gradient(90deg,#e5484d,#ffb4b4)}
.ct-toast:hover .ct-bar{animation-play-state:paused}
.ct-toast .ct-link{display:inline-flex;align-items:center;gap:6px;margin-top:8px;font-size:13px;font-weight:600;color:#fff;background:#111;padding:6px 14px;border-radius:20px;text-decoration:none}
.ct-toast .ct-link:hover{background:#333;color:#fff}
.ct-toast .ct-link[hidden]{display:none}
.ct-toast[data-type="success"]{border-color:#cdeed6;background:linear-gradient(135deg,#fbfffc,#ecf9f0)}
.ct-toast[data-type="success"] .ct-bar{background:linear-gradient(90deg,#22c55e,#a7f0bf)}
.ct-toast[data-type="success"] .toaster-message h4{color:#14532d}
.ct-toast[data-type="success"] .toaster-message p{color:#3f6b4e}
.ct-toast[data-type="info"]{border-color:#d6e4fb;background:linear-gradient(135deg,#fbfdff,#eef4fe)}
.ct-toast[data-type="info"] .toaster-icon{background:linear-gradient(135deg,#b9d2fb,#3b82f6);box-shadow:0 6px 14px rgba(59,130,246,.3)}
.ct-toast[data-type="info"] .ct-bar{background:linear-gradient(90deg,#3b82f6,#b9d2fb)}
.ct-toast[data-type="info"] .toaster-message h4{color:#1e3a6b}
.ct-toast[data-type="info"] .toaster-message p{color:#4a5f82}
/* Header cart badge bump when products are added */
.cart-count.bump{animation:cartBump .5s cubic-bezier(.34,1.56,.64,1)}
@keyframes cartBump{0%{transform:scale(1)}40%{transform:scale(1.45)}100%{transform:scale(1)}}
@keyframes ctPop{0%{transform:scale(.4) rotate(-12deg);opacity:0}100%{transform:scale(1) rotate(0);opacity:1}}
@keyframes ctBar{from{transform:scaleX(1)}to{transform:scaleX(0)}}
/* Phones: this page is wider than the screen, so pin the toast to the visible left edge */
@media(max-width:600px){#ctStack{top:12px;left:16px;right:auto}}
@media (prefers-reduced-motion:reduce){.ct-toast,.ct-toast .toaster-icon,.ct-toast .ct-bar{animation:none!important;transition:opacity .2s}}

@media(max-width:1100px){
 .nav,.sub .r,.bulk{display:none}
 .cmp,.tbl,.sav{min-width:1100px}
 .scroll{overflow-x:auto}
}
</style>

<div class="compare-body">
<main class="wrap">
  <section class="hero">
    <div class="hero-text">
      <div class="eyebrow">COMPARE. PLAN. SAVE MORE.</div>
      <h1>Compare Corporate Gifts</h1>
      <p>Compare features, pricing and bulk savings across multiple products to find the best fit for your team.</p>
      <div class="bulk">
        <svg class="ico" viewBox="0 0 24 24"><rect x="3" y="12" width="5" height="9"/><rect x="9.5" y="7" width="5" height="14"/><rect x="16" y="3" width="5" height="18"/></svg>
        <div><h3>Bulk Save Comparison</h3><p>See real cost-per-unit at different quantities and choose the best value for your budget.</p></div>
      </div>
    </div>
  </section>

  <div class="scroll">
  <section class="cmp">
    <div class="card sel">
      <h4>Select Products to Compare</h4><small>(up to 3)</small>
      <div class="sbox">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="text" id="productSearch" placeholder="Search products..." style="border:none;background:transparent;outline:none;width:100%;font-size:12.5px;color:#333;">
      </div>
      <div id="productList" class="loading">Loading products</div>
    </div>

    <div id="comparisonCards">
      <!-- Comparison cards will be dynamically loaded here -->
    </div>
  </section>

  <section class="tbl" id="tbl"></section>

  <section class="sav">
    <div><svg class="ico" viewBox="0 0 24 24"><ellipse cx="9" cy="6" rx="6" ry="2.500"/><path d="M3 6v4c0 1.400 2.700 2.500 6 2.500s6-1.100 6-2.500V6M3 10v4c0 1.400 2.700 2.500 6 2.500s6-1.100 6-2.500v-4M3 14v4c0 1.400 2.700 2.500 6 2.500s6-1.100 6-2.500v-4"/><ellipse cx="17" cy="15" rx="5" ry="2"/><path d="M12 15v3c0 1.100 2.200 2 5 2s5-.9 5-2v-3"/></svg><span id="savLabel">Your Savings at 500–999 pcs</span></div>
    <div><b id="s0"></b><span id="p0"></span></div>
    <div><b id="s1"></b><span id="p1"></span></div>
    <div><b id="s2"></b><span id="p2"></span></div>
  </section>
  </div>

  <div class="act">
    <button class="btn" id="requestQuote"><svg viewBox="0 0 24 24"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8zM14 3v5h5M9 13h6M9 17h6"/></svg>Request a Quote for Selected Items</button>
    <button class="btn dark" id="proceedWithSelection">Proceed with Selection <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
  </div>
</main>
</div>

<script>
const COMPARE_PLACEHOLDER_IMG = @json(asset('web_frontend/assets/images/product-main.jpg'));
const MAX_PRODUCTS = 3;
let selectedProductIds = @json($selectedProducts ? $selectedProducts->keys()->toArray() : []);
let selectedQuantity = 500;
let allProducts = [];
let productDetails = {};

// Load products on page load
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
    if (selectedProductIds.length > 0) {
        loadProductDetails();
    }
});

// Load available products
async function loadProducts(search = '') {
    try {
        // Selected products stay in the list (shown ticked) so they can be unticked
        const response = await fetch(`/api/compare/search?search=${encodeURIComponent(search)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        allProducts = data.products || [];
        renderProductList();
    } catch (error) {
        console.error('Error loading products:', error);
        document.getElementById('productList').innerHTML = '<div style="padding:10px;color:#999;">Error loading products</div>';
    }
}

// Render product selection list
function renderProductList() {
    const container = document.getElementById('productList');
    if (allProducts.length === 0) {
        container.innerHTML = '<div style="padding:10px;color:#999;">No products found</div>';
        return;
    }

    container.innerHTML = allProducts.map(product => `
        <div class="opt ${selectedProductIds.includes(product.id) ? 'on' : ''}" data-id="${product.id}">
            <span class="cb"><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg></span>
            <span class="thumb">
                ${product.thumbnail ? `<img src="${escapeHtml(product.thumbnail)}" alt="${escapeHtml(product.name)}" style="width:100%;height:100%;object-fit:cover;" onerror="this.onerror=null;this.src=COMPARE_PLACEHOLDER_IMG;">` : '<i class="t-b"></i>'}
            </span>
            <span style="flex:1;font-size:13px;color:#333;">${escapeHtml(product.name)}</span>
        </div>
    `).join('');

    // Add click handlers
    container.querySelectorAll('.opt').forEach(opt => {
        opt.addEventListener('click', () => toggleProduct(parseInt(opt.dataset.id)));
    });
}

// Toggle product selection
function toggleProduct(productId) {
    const index = selectedProductIds.indexOf(productId);
    if (index > -1) {
        selectedProductIds.splice(index, 1);
    } else {
        if (selectedProductIds.length >= MAX_PRODUCTS) {
            showToast("You've picked 3 lovely gifts!", 'That is the most we can compare side by side. Just remove one to try another.', 'limit');
            return;
        }
        selectedProductIds.push(productId);
    }

    saveSelection();
    renderProductList();
    loadProductDetails();
}

// Save selection to session
async function saveSelection() {
    try {
        await fetch('/compare/save-selection', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_ids: selectedProductIds })
        });
    } catch (error) {
        console.error('Error saving selection:', error);
    }
}

// Load product details including tier pricing
let detailsRequest = 0; // only the newest request may update the page (answers can arrive out of order)
async function loadProductDetails() {
    const requestNo = ++detailsRequest;
    if (selectedProductIds.length === 0) {
        renderComparisonCards();
        renderPricingTable();
        renderSavings();
        return;
    }

    try {
        const response = await fetch('/api/compare/product-details', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_ids: selectedProductIds,
                selected_quantity: selectedQuantity
            })
        });
        const data = await response.json();
        if (requestNo !== detailsRequest) return; // a newer selection is already loading
        productDetails = data.products;
        renderComparisonCards();
        renderPricingTable();
        renderSavings();
    } catch (error) {
        console.error('Error loading product details:', error);
    }
}

// Render comparison cards
function renderComparisonCards() {
    const container = document.getElementById('comparisonCards');
    const grid = document.querySelector('.cmp');
    container.innerHTML = '';

    if (selectedProductIds.length === 0) {
        grid.style.gridTemplateColumns = '278px repeat(3, 1fr)';
        return;
    }

    // Update grid columns based on number of selected products
    const numProducts = selectedProductIds.length;
    grid.style.gridTemplateColumns = `278px repeat(${numProducts}, 1fr)`;

    selectedProductIds.forEach(productId => {
        const product = productDetails[productId];
        if (!product) return;

        const features = parseFeatures(product.specification);
        let badgeHtml = '';
        if (product.badge) {
            const badgeIcons = {
                'crown': '<svg viewBox="0 0 24 24"><path d="M2 4l3 12h14l3-12-6 7-4-7-4 7-6-7zM5 18h14v2H5z"/></svg>',
                'star': '<svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>',
                'tag': '<svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82zM7 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>'
            };
            const icon = badgeIcons[product.badge.icon] || '';
            badgeHtml = `<div class="badge ${product.badge.class}">${icon}${product.badge.text}</div>`;
        }

        const card = document.createElement('div');
        card.className = 'card prod';
        card.dataset.productId = productId;
        card.innerHTML = `
            <button class="rm" onclick="removeProduct(${productId})"><svg viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>Remove</button>
            <div class="pt">
                <a class="pimg" href="${escapeHtml(product.url || '#')}" title="View ${escapeHtml(product.name)}">
                    ${product.thumbnail ? `<img src="${escapeHtml(product.thumbnail)}" alt="${escapeHtml(product.name)}" style="width:100%;height:100%;object-fit:cover;" onerror="this.onerror=null;this.src=COMPARE_PLACEHOLDER_IMG;">` : '<div class="bottle"></div>'}
                </a>
                <div class="pi">
                    <h3><a class="plink" href="${escapeHtml(product.url || '#')}">${escapeHtml(product.name)}</a></h3>
                    <p>${escapeHtml(shortText(product.description, 7))}</p>
                    ${badgeHtml}
                </div>
            </div>
            <ul class="feat">
                ${features.map(f => `<li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg><span>${escapeHtml(f)}</span></li>`).join('')}
            </ul>
        `;
        container.appendChild(card);
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text == null ? '' : String(text);
    return div.innerHTML;
}

// HTML from the admin editor -> plain text (keeps line breaks between blocks)
function stripHtml(html) {
    const div = document.createElement('div');
    div.innerHTML = String(html || '')
        .replace(/<\s*br\s*\/?>/gi, '\n')
        .replace(/<\/\s*(p|li|div|h[1-6]|tr)\s*>/gi, '\n');
    return div.textContent.replace(/ /g, ' ');
}

// First N words of a text, with "..." when it was longer
function shortText(text, maxWords) {
    const words = stripHtml(text).trim().split(/\s+/).filter(Boolean);
    return words.length > maxWords ? words.slice(0, maxWords).join(' ') + '...' : words.join(' ');
}

// Features from the specification: one per line, list item or comma ("10,000mAh" keeps its comma)
function parseFeatures(spec) {
    let items = [];
    try {
        const parsed = JSON.parse(spec);
        if (Array.isArray(parsed)) {
            items = parsed.map(f => typeof f === 'object' && f ? (f.value || f.label || '') : f);
        }
    } catch (e) { /* not JSON: HTML or plain text */ }

    if (!items.length) {
        items = stripHtml(spec).split(/\n|;|,\s+/);
    }

    items = items.map(f => String(f).trim().replace(/[.\s]+$/, '')).filter(Boolean);
    return items.length ? items : ['No specifications available'];
}

// Render pricing table
function renderPricingTable() {
    const table = document.getElementById('tbl');
    if (selectedProductIds.length === 0) {
        table.innerHTML = '';
        return;
    }

    const products = selectedProductIds.map(id => productDetails[id]).filter(Boolean);
    if (!products.length) return;

    // One column per product, same widths as the product boxes above
    table.style.gridTemplateColumns = `278px repeat(${products.length}, 1fr)`;

    const rows = quantityRows(products);
    const star = '<svg viewBox="0 0 24 24"><path d="M12 2l3 6.500 7 .8-5.200 4.800 1.400 7L12 17.600 5.800 21.100l1.400-7L2 9.300l7-.8z"/></svg>';

    let html = '<div class="h q">Quantity</div>';
    products.forEach(product => {
        html += `<div class="h"><a href="${escapeHtml(product.url || '#')}" style="color:inherit;text-decoration:none;">${escapeHtml(product.name)}</a><br><small style="font-weight:500;color:#777;">Unit Price (AED)</small></div>`;
    });

    rows.forEach(row => {
        const isSelected = selectedQuantity >= row.min && (row.max === null || selectedQuantity <= row.max);
        const cls = isSelected ? ' sel' : '';
        html += `<div class="q${cls}" onclick="selectQuantity(${row.min})">${isSelected ? star : ''}${row.label}</div>`;

        // Each product's own price at this quantity; the lowest one is marked
        const prices = products.map(product => row.min < product.moq ? null : unitPriceFor(product, row.min));
        const valid = prices.filter(p => p !== null);
        const best = valid.length > 1 ? Math.min(...valid) : null;

        products.forEach((product, i) => {
            const price = prices[i];
            const text = price === null
                ? `<span style="color:#999;font-size:13px;">Min. ${product.moq} pcs</span>`
                : `AED ${price.toFixed(2)}${price === best ? '<span style="color:#1d6b2a;font-size:12px;font-weight:600;margin-left:8px;">Lowest</span>' : ''}`;
            html += `<div class="${cls}" onclick="selectQuantity(${row.min})">${text}</div>`;
        });
    });

    table.innerHTML = html;
}

// Same rule as Product::priceForQuantity(): the tier containing the quantity, else the normal price
function unitPriceFor(product, qty) {
    const tier = (product.tier_pricing || []).filter(t => t.min_qty <= qty && (t.max_qty === null || t.max_qty >= qty))
        .sort((a, b) => b.min_qty - a.min_qty)[0];
    return tier ? tier.price : Number(product.display_price || 0);
}

// Quantity rows: every tier start of every selected product, e.g. 25–49, 50–99, 100+
function quantityRows(products) {
    // Nobody can order below the smallest MOQ, so the first row starts there
    const minMoq = Math.min(...products.map(p => Math.max(1, Number(p.moq) || 1)));
    const starts = new Set([minMoq]);
    products.forEach(product => (product.tier_pricing || []).forEach(t => starts.add(Math.max(minMoq, t.min_qty))));
    const sorted = [...starts].sort((a, b) => a - b);
    return sorted.map((min, i) => {
        const max = i < sorted.length - 1 ? sorted[i + 1] - 1 : null;
        return { min, max, label: max === null ? `${min.toLocaleString('en-US')}+` : (max === min ? `${min}` : `${min.toLocaleString('en-US')} – ${max.toLocaleString('en-US')}`) };
    });
}

// Render savings section
function renderSavings() {
    const container = document.querySelector('.sav');
    const label = document.getElementById('savLabel');

    if (selectedProductIds.length === 0) {
        label.textContent = 'Your Savings';
        // Reset to default 3 columns
        container.style.gridTemplateColumns = '278px repeat(3, 1fr)';
        // Clear all savings elements
        for (let i = 0; i < 3; i++) {
            const sEl = document.getElementById(`s${i}`);
            const pEl = document.getElementById(`p${i}`);
            if (sEl) sEl.textContent = '';
            if (pEl) pEl.textContent = '';
        }
        return;
    }

    const numProducts = selectedProductIds.length;
    container.style.gridTemplateColumns = `278px repeat(${numProducts}, 1fr)`;

    const products = selectedProductIds.map(id => productDetails[id]).filter(Boolean);
    const selectedRow = quantityRows(products).find(r => selectedQuantity >= r.min && (r.max === null || selectedQuantity <= r.max));

    if (selectedRow) {
        label.textContent = `Your Savings at ${selectedRow.label} pcs`;
    } else {
        label.textContent = 'Your Savings';
    }

    selectedProductIds.forEach((productId, index) => {
        const product = productDetails[productId];
        const sEl = document.getElementById(`s${index}`);
        const pEl = document.getElementById(`p${index}`);
        // Details for a just-ticked product may still be loading
        if (!product) {
            if (sEl) sEl.textContent = '';
            if (pEl) pEl.textContent = '';
            return;
        }
        if (sEl) sEl.textContent = `Save AED ${product.savings_per_unit.toFixed(2)} per unit`;
        if (pEl) pEl.textContent = `(${product.savings_percent}% savings)`;
    });
}

// Select quantity tier
function selectQuantity(minQty) {
    selectedQuantity = minQty;
    loadProductDetails();
}

// Remove product from comparison
function removeProduct(productId) {
    selectedProductIds = selectedProductIds.filter(id => id !== productId);
    saveSelection();
    renderProductList();
    loadProductDetails();
}

// Product search
document.getElementById('productSearch').addEventListener('input', function(e) {
    loadProducts(e.target.value);
});

// Request quote
document.getElementById('requestQuote').addEventListener('click', async function() {
    if (selectedProductIds.length === 0) {
        showToast('Pick a gift first', 'Choose at least one product above, then we will prepare your quote.');
        return;
    }

    const button = this;
    const products = selectedProductIds.map(id => ({
        id: id,
        quantity: selectedQuantity
    }));

    button.disabled = true;
    try {
        const response = await fetch('/api/compare/add-to-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ products })
        });
        const data = await response.json();
        if (response.ok && data.success) {
            // Stay on the page: update the header cart badge and confirm
            const badge = document.getElementById('cartCount');
            if (badge && typeof data.count !== 'undefined') {
                badge.textContent = data.count;
                if (data.added.length) {
                    badge.classList.remove('bump'); void badge.offsetWidth; badge.classList.add('bump');
                }
            }
            const cartLink = { text: 'View cart', href: data.redirect_url };
            const already = data.already_in_cart || [];
            const unavailable = data.skipped && data.skipped.length ? ` ${data.skipped.length} not available.` : '';
            const names = list => list.length === 1 ? list[0] : list.slice(0, -1).join(', ') + ' and ' + list[list.length - 1];

            // New products: success message
            if (data.added.length) {
                const addedNames = data.added.map(p => p.name);
                showToast('Added to your quote cart!',
                    `${names(addedNames)} ${addedNames.length === 1 ? 'has' : 'have'} been added to your cart.` + unavailable,
                    'success', cartLink);
            }
            // Products that were already in the cart: separate message, nothing added for them
            if (already.length) {
                showToast('Already in your quote cart',
                    `${names(already)} ${already.length === 1 ? 'is' : 'are'} already in your cart, so ${already.length === 1 ? 'it was' : 'they were'} not added again. You can change the quantity in the cart.` + (data.added.length ? '' : unavailable),
                    'info', data.added.length ? null : cartLink);
            }
        } else {
            showToast('Oops, that did not work', data.message || 'We could not add the products to your cart. Please try again in a moment.', 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showToast('Oops, that did not work', 'We could not add the products to your cart. Please try again in a moment.', 'error');
    } finally {
        button.disabled = false;
    }
});

// Proceed with selection
document.getElementById('proceedWithSelection').addEventListener('click', async function() {
    if (selectedProductIds.length === 0) {
        showToast('Pick a gift first', 'Choose at least one product above to continue.');
        return;
    }

    const products = selectedProductIds.map(id => ({
        id: id,
        quantity: selectedQuantity
    }));

    try {
        const response = await fetch('/api/compare/add-to-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ products })
        });
        const data = await response.json();
        if (data.success) {
            window.location.href = data.redirect_url;
        } else {
            showToast('Oops, that did not work', 'We could not add the products to your cart. Please try again in a moment.', 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showToast('Oops, that did not work', 'We could not add the products to your cart. Please try again in a moment.', 'error');
    }
});

// Toast messages. type: 'limit' (gift), 'warning', 'error', 'success', 'info'.
// Several can show at once; they stack in #ctStack and each closes on its own.
const TOAST_TIME = 4000;
const TOAST_MAX = 3;
const TOAST_ICONS = { limit: 'bi-gift', warning: 'bi-hand-index-thumb', error: 'bi-emoji-frown', success: 'bi-check-lg', info: 'bi-cart-check' };

function toastStack() {
    let stack = document.getElementById('ctStack');
    if (!stack) {
        // Directly under <body>, so "fixed" is measured from the screen and not a wide page wrapper
        stack = document.createElement('div');
        stack.id = 'ctStack';
        stack.setAttribute('aria-live', 'polite');
        document.body.appendChild(stack);
    }
    return stack;
}

function showToast(title, message, type = 'warning', link = null) {
    const stack = toastStack();

    // The same message twice in a row: restart that toast instead of adding a copy
    const same = [...stack.children].find(t => !t.classList.contains('leaving') && t.dataset.key === type + '|' + title + '|' + message);
    if (same) closeToast(same, true);

    const toast = document.createElement('div');
    toast.className = 'toaster-notification ct-toast';
    toast.setAttribute('role', 'status');
    toast.dataset.type = type;
    toast.dataset.key = type + '|' + title + '|' + message;
    toast.style.setProperty('--ct-time', TOAST_TIME + 'ms');
    toast.innerHTML = `
        <div class="toaster-icon"><i class="bi ${TOAST_ICONS[type] || TOAST_ICONS.warning}"></i></div>
        <div class="toaster-message"><h4></h4><p></p>${link ? '<a class="ct-link"></a>' : ''}</div>
        <button type="button" class="toaster-close" aria-label="Close"><i class="bi bi-x"></i></button>
        <span class="ct-bar" aria-hidden="true"></span>`;
    toast.querySelector('h4').textContent = title;
    toast.querySelector('p').textContent = message;
    if (link) {
        const a = toast.querySelector('.ct-link');
        a.textContent = link.text;
        a.href = link.href;
    }
    toast.querySelector('.toaster-close').addEventListener('click', () => closeToast(toast));
    stack.appendChild(toast);

    // Keep the stack short
    const open = [...stack.children].filter(t => !t.classList.contains('leaving'));
    open.slice(0, Math.max(0, open.length - TOAST_MAX)).forEach(t => closeToast(t, true));

    // Slide in, then count down; hovering pauses the countdown (the bar pauses in CSS)
    requestAnimationFrame(() => requestAnimationFrame(() => toast.classList.add('show')));
    let left = TOAST_TIME, started = Date.now();
    toast._timer = setTimeout(() => closeToast(toast), left);
    toast.addEventListener('mouseenter', () => { clearTimeout(toast._timer); left -= Date.now() - started; });
    toast.addEventListener('mouseleave', () => {
        if (toast.classList.contains('leaving')) return;
        started = Date.now();
        left = Math.max(left, 800);
        toast._timer = setTimeout(() => closeToast(toast), left);
    });
    return toast;
}

function closeToast(toast, instant = false) {
    if (!toast || toast.classList.contains('leaving')) return;
    clearTimeout(toast._timer);
    toast.classList.add('leaving');
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), instant ? 0 : 450);
}

// Close every open toast (kept for older callers)
function hideToast() {
    document.querySelectorAll('#ctStack .ct-toast').forEach(t => closeToast(t));
}
</script>
@endsection