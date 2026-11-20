@extends('web_frontend.layouts.app')

@section('title', 'Compare Corporate Gifts')

@section('content')
<style>
/* Compare Gifts, in the site's look (same type, colours and buttons as the Budget Planner and home page).
   Everything is scoped to .compare-body so nothing here reaches the header or footer. */
.compare-body{--ink:#15171a;--text:#2b2d31;--sub:#4b4f56;--muted:#6a6e75;--line:#e5e6e8;--field:#d7d9dc;--gold:#b98a35;--gold-text:#9a6b16;--gold-soft:#fbf5ea;--gold-line:#e2c07a;--warm:#f6f1ed;--panel:#f9f9f9;--green:#17752c;--green-soft:#e4f4e8;
  font-family:'Nunito Sans',Arial,Helvetica,sans-serif;color:var(--ink);background:#fff;font-size:14px}
.compare-body *,.compare-body *::before,.compare-body *::after{box-sizing:border-box;margin:0;padding:0}
.compare-body a{color:inherit;text-decoration:none}
.compare-body button{font:inherit;cursor:pointer}
.compare-body svg{width:1em;height:1em;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex:none}
.compare-body :focus-visible{outline:2px solid var(--gold-text);outline-offset:2px}

/* Hero: full width, flush under the header; the products sit on the right of the picture */
.compare-body .wrap{padding:0 32px}
.compare-body .hero{display:grid;grid-template-columns:minmax(0,1fr);align-items:center;margin:0 -32px;background:var(--warm)}
.compare-body .compare-hero-art,.compare-body .hero-text{grid-area:1/1}
.compare-body .compare-hero-art{display:block;width:100%;height:auto}
.compare-body .hero-text{position:relative;max-width:min(610px,43%);padding:24px 35px}
.compare-body .eyebrow{display:block;margin-bottom:10px;font-size:11px;font-weight:800;letter-spacing:3px;color:var(--ink)}
.compare-body h1{margin:0 0 14px;font-size:52px;font-weight:800;line-height:1;letter-spacing:-1.4px;color:#0d0e10}
.compare-body .hero p{max-width:520px;font-size:19px;line-height:1.17;color:#3d4148}
.compare-body .bulk{display:flex;align-items:center;gap:18px;width:466px;max-width:100%;margin-top:24px;padding:16px 20px;background:rgba(255,255,255,.82);border:1px solid #e7dfd5;border-radius:6px}
.compare-body .bulk .ico{font-size:34px;stroke-width:1.6;color:var(--ink)}
.compare-body .bulk h3{margin-bottom:4px;font-size:16px;font-weight:800;color:var(--ink)}
.compare-body .bulk p{margin:0;font-size:13.5px;line-height:1.35;color:var(--sub)}
@media(max-width:1250px){
 .compare-body .hero{display:flex;flex-direction:column;align-items:stretch}
 .compare-body .hero-text{max-width:100%;padding:24px clamp(16px,4vw,32px)}
 .compare-body h1{font-size:36px}
 .compare-body .hero p{font-size:16px}
}

/* Product picker and the compared products */
.compare-body .cmp{display:grid;grid-template-columns:278px repeat(var(--n,3),1fr);gap:12px;margin-top:20px;align-items:stretch;transition:grid-template-columns .3s ease}
.compare-body .card{padding:14px 14px 12px;background:#fff;border:1px solid var(--line);border-radius:6px}
.compare-body .sel h4{font-size:16.5px;font-weight:800;color:var(--ink)}
.compare-body .sel small{display:block;margin:2px 0 11px;font-size:13px;color:var(--muted)}
.compare-body .sbox{display:flex;align-items:center;gap:8px;height:38px;margin-bottom:8px;padding:0 12px;border:1px solid var(--field);border-radius:6px;font-size:13px;color:var(--muted)}
/* 5 products visible (41px row + 6px gap each), the rest scroll */
.compare-body #productList{max-height:229px;overflow-y:auto;padding-right:4px}
.compare-body #productList::-webkit-scrollbar{width:6px}
.compare-body #productList::-webkit-scrollbar-thumb{background:#c9ccd0;border-radius:3px}
.compare-body .opt{display:flex;align-items:center;gap:14px;height:41px;margin-bottom:6px;padding:0 6px;border-radius:5px;font-size:13px;cursor:pointer}
.compare-body .opt:hover{background:var(--panel)}
.compare-body .opt.on{background:var(--gold-soft)}
.compare-body .cb{display:grid;place-items:center;flex:none;width:17px;height:17px;background:#fff;border:1.5px solid #9a9ea4;border-radius:3px}
.compare-body .on .cb{background:var(--ink);border-color:var(--ink);color:#fff}
.compare-body .cb svg{display:none;width:12px;height:12px;stroke-width:3}
.compare-body .on .cb svg{display:block}
.compare-body .thumb{display:grid;place-items:center;width:36px;height:36px}
.compare-body .t-b{width:13px;height:32px;background:var(--ink);border-radius:3px}
.compare-body .t-s{width:36px;height:22px;background:#222;border-radius:50%/60%}
.compare-body .t-n{width:25px;height:34px;background:#1c1c1c;border-radius:2px}
.compare-body .t-t{width:22px;height:28px;background:#1c1c1c;border-radius:2px}
.compare-body .t-w{width:26px;height:26px;background:#2a2a2a;border-radius:3px}
.compare-body .opt:nth-child(n+4) .thumb{background:#f1f2f3}

.compare-body .prod{position:relative;padding:12px 14px 12px 18px}
.compare-body .rm{position:absolute;top:12px;right:11px;display:flex;align-items:center;gap:6px;background:none;border:0;font-size:12px;color:var(--sub)}
.compare-body .rm:hover{color:var(--ink)}
.compare-body .rm svg{font-size:15px}
.compare-body .pt{display:flex;gap:18px;align-items:flex-start;padding-top:34px}
.compare-body .pimg{position:relative;display:grid;place-items:center;flex:none;width:98px;height:158px;background:#f1f2f3;border-radius:5px;overflow:hidden}
.compare-body .pimg img{width:100%;height:100%;object-fit:cover}
.compare-body a.pimg{text-decoration:none;cursor:pointer}
.compare-body .pi{min-width:0}
.compare-body .pi h3{margin-bottom:6px;font-size:16.5px;font-weight:800;line-height:1.25}
.compare-body .pi h3 a.plink{color:inherit;text-decoration:none}
.compare-body .pi h3 a.plink:hover{color:var(--gold-text);text-decoration:underline}
.compare-body .pi p{margin-bottom:10px;font-size:14px;line-height:1.4;color:var(--sub)}
.compare-body .bottle{position:relative;width:50px;height:132px;margin-top:14px;background:linear-gradient(90deg,#222,#000 50%,#2b2b2b);border-radius:8px 8px 6px 6px}
.compare-body .bottle:before{content:"";position:absolute;top:-18px;left:7px;width:36px;height:18px;border:5px solid #111;border-bottom:0;border-radius:12px 12px 0 0}
.compare-body .bottle:after{content:"";position:absolute;top:-6px;left:2px;right:2px;height:8px;background:#111;border-radius:3px}
.compare-body .speaker{display:grid;place-items:center;width:78px;height:96px;background:repeating-linear-gradient(45deg,#2a2a2a 0 2px,#161616 2px 4px);border:3px solid #111;border-radius:12px;color:#ddd;font-weight:800;font-size:12px}
.compare-body .note{position:relative;display:grid;place-items:center;width:84px;height:132px;background:#161616;border-radius:3px 6px 6px 3px;box-shadow:inset 6px 0 0 #000;color:#d9c9a0;font-size:10.5px;line-height:1.25;text-align:center}
.compare-body .pen{position:absolute;right:-6px;top:30px;z-index:2;width:5px;height:96px;background:#111;border-radius:3px;transform:rotate(3deg)}
.compare-body .pimg.nb{background:#ececec;overflow:visible}
.compare-body .badge{display:inline-flex;align-items:center;gap:8px;padding:6px 12px;border-radius:5px;font-size:12.5px;font-weight:700}
.compare-body .b-g{background:var(--green-soft);color:var(--green)}.compare-body .b-g svg{fill:var(--green);stroke:none}
.compare-body .b-b{background:#f1f2f3;color:var(--ink)}.compare-body .b-b svg{fill:var(--ink);stroke:none}
.compare-body .b-o{background:var(--gold-soft);color:var(--gold-text)}.compare-body .b-o svg{fill:var(--gold);stroke:none}
.compare-body .feat{list-style:none;margin:12px 0 0;padding:0}
.compare-body .feat li{display:flex;align-items:flex-start;gap:12px;padding:3px 0;font-size:14px;line-height:1.45;color:var(--sub)}
.compare-body .feat svg{margin:3px 0;font-size:15px;color:var(--ink);stroke-width:2.4}

/* Comparison cards container */
.compare-body #comparisonCards{display:contents}
/* Shown in the product area while nothing is selected */
.compare-body .empty-cmp{grid-column:2 / -1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px 24px;text-align:center;background:var(--panel);border:1px dashed var(--field);border-radius:6px}
.compare-body .empty-cmp .ec-icon{display:grid;place-items:center;width:60px;height:60px;margin-bottom:16px;background:var(--warm);border-radius:50%}
.compare-body .empty-cmp .ec-icon svg{width:28px;height:28px;fill:none;stroke:var(--gold-text);stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.compare-body .empty-cmp h3{margin:0 0 8px;font-size:20px;font-weight:800;color:var(--ink)}
.compare-body .empty-cmp p{max-width:440px;margin:0 0 20px;font-size:14.5px;line-height:1.5;color:var(--sub)}
.compare-body .empty-cmp .ec-steps{display:flex;flex-wrap:wrap;justify-content:center;gap:22px;margin:0;padding:0;list-style:none}
.compare-body .empty-cmp .ec-steps li{display:flex;align-items:center;gap:8px;font-size:13.5px;font-weight:700;color:var(--text)}
.compare-body .empty-cmp .ec-steps span{display:grid;place-items:center;width:24px;height:24px;background:var(--ink);border-radius:50%;color:#fff;font-size:12px;font-weight:800}

/* Price per quantity table */
.compare-body .tbl{display:grid;grid-template-columns:278px repeat(var(--n,3),1fr);margin-top:12px;background:#fff;border:1px solid var(--line);border-bottom:0;border-radius:6px;overflow:hidden}
.compare-body .tbl>div{display:flex;align-items:center;justify-content:center;height:34px;font-size:14px;border-bottom:1px solid var(--line);border-right:1px solid var(--line);cursor:pointer}
.compare-body .tbl>.q{justify-content:flex-start;padding-left:22px}
.compare-body .tbl>.h{flex-direction:column;min-height:48px;height:auto;padding:6px 10px;background:var(--panel);font-size:14px;font-weight:800;line-height:1.3;text-align:center;cursor:default}
.compare-body .tbl>.h.q{flex-direction:row;padding-left:22px;font-size:15px}
.compare-body .tbl>.sel{background:var(--gold-soft);border-color:var(--gold-line);color:var(--gold-text);font-weight:800}
.compare-body .tbl>.sel.q{display:flex;align-items:center;justify-content:flex-start;gap:9px;padding-left:22px;color:var(--gold-text)}
.compare-body .tbl>.sel.q svg{fill:var(--gold);stroke:var(--gold);font-size:17px}
.compare-body .tbl>div:not(.h):hover{background:var(--gold-soft)}

/* Savings row */
.compare-body .sav{display:grid;grid-template-columns:278px repeat(var(--n,3),1fr);gap:4px;margin-top:10px}
.compare-body .sav>div{display:flex;flex-direction:column;align-items:center;justify-content:center;height:72px;background:var(--green-soft);border-radius:5px}
.compare-body .sav>div:first-child{flex-direction:row;justify-content:flex-start;gap:20px;padding-left:14px;font-size:14px;font-weight:800;color:var(--ink)}
.compare-body .sav .ico{font-size:32px;color:var(--green);stroke-width:1.5}
.compare-body .sav b{margin-bottom:5px;font-size:17px;font-weight:800}
.compare-body .sav span{font-size:14px;color:var(--green)}
/* Fewer than 3 products: hide the unused savings boxes */
.compare-body .sav>div:has(> b:empty){display:none}

/* Actions: the same buttons as the rest of the site */
.compare-body .act{position:relative;display:flex;align-items:center;gap:16px;padding:30px 0 40px}
.compare-body .btn{display:inline-flex;align-items:center;justify-content:center;gap:12px;height:46px;padding:0 22px;background:#fff;border:1px solid var(--ink);border-radius:5px;color:var(--ink);font-size:13px;font-weight:700}
.compare-body .btn:hover{background:var(--ink);color:#fff}
.compare-body .btn.dark{padding:0 34px;background:var(--ink);color:#fff}
.compare-body .btn.dark:hover{background:#33363b;border-color:#33363b}
.compare-body .btn svg{font-size:18px}

@media(max-width:1100px){
 .compare-body .bulk{display:none}
 .compare-body .cmp,.compare-body .tbl,.compare-body .sav{min-width:1100px}
 .compare-body .scroll{overflow-x:auto}
}

/* Tablets and phones: stack the layout instead of a 1100px wide grid
   (that grid made the whole page, header included, wider than the screen) */
@media(max-width:900px){
 .compare-body .wrap{padding:0 16px}
 .compare-body .hero{margin:0 -16px}
 .compare-body .hero-text{padding:24px 16px}
 .compare-body .scroll{overflow:visible}
 .compare-body .cmp,.compare-body .tbl,.compare-body .sav{min-width:0}

 /* Selector on top, product cards below (2 per row on tablets, 1 on phones) */
 .compare-body .cmp{grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:10px}
 .compare-body .cmp>.sel,.compare-body .empty-cmp{grid-column:1 / -1}
 .compare-body .empty-cmp{padding:28px 16px}
 .compare-body .empty-cmp .ec-steps{flex-direction:column;align-items:flex-start;gap:10px}
 .compare-body .prod{padding:12px 14px 12px}
 .compare-body .pt{padding-top:30px;gap:14px}
 .compare-body .pimg{width:90px;height:120px}

 /* Price table scrolls sideways on its own; the quantity column stays visible */
 .compare-body .tbl{grid-template-columns:96px repeat(var(--n,3),minmax(120px,1fr));overflow-x:auto;-webkit-overflow-scrolling:touch;margin-top:10px}
 .compare-body .tbl>div{font-size:13px;padding:0 8px;text-align:center}
 .compare-body .tbl>.q,.compare-body .tbl>.h.q,.compare-body .tbl>.sel.q{padding-left:10px;position:sticky;left:0;z-index:1;background:#fff;box-shadow:1px 0 0 var(--line)}
 .compare-body .tbl>.h.q{background:#f4f4f4}
 .compare-body .tbl>.sel.q{background:var(--gold);gap:5px}
 .compare-body .tbl>.h{font-size:13px;min-height:56px}
 .compare-body .tbl>div:not(.h){height:auto;min-height:40px;padding:5px 8px;flex-direction:column;line-height:1.25}
 .compare-body .tbl>div:not(.h).q{flex-direction:row}
 .compare-body .tbl>div:not(.h) span{margin-left:0!important}

 /* Savings: label on its own row, one box per product under it */
 .compare-body .sav{grid-template-columns:repeat(var(--n,3),minmax(0,1fr))}
 .compare-body .sav>div{height:auto;min-height:64px;padding:10px 6px;text-align:center}
 .compare-body .sav>div:first-child{grid-column:1 / -1;gap:12px;padding:10px 14px;text-align:left}
 .compare-body .sav .ico{font-size:28px}
 .compare-body .sav b{font-size:14px}
 .compare-body .sav span{font-size:13px}

 /* Buttons full width, one under the other */
 .compare-body .act{flex-direction:column;align-items:stretch;gap:12px;padding:24px 0 32px}
 .compare-body .btn,.compare-body .btn.dark{width:100%;justify-content:center;height:auto;min-height:46px;padding:10px 16px;text-align:center}
}
@media(max-width:480px){
 .compare-body h1{font-size:30px;letter-spacing:-.6px}
 .compare-body .hero p{font-size:15px}
 .compare-body .eyebrow{font-size:11px;letter-spacing:1.6px}
 .compare-body .pi h3{font-size:15.5px}
 .compare-body .pi p{font-size:13.5px}
}
/* Messages after adding to the quote cart. They stack in #ctStack (added to <body>), newest at the bottom. */
#ctStack{position:fixed;top:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:12px;width:min(380px,calc(100vw - 32px));pointer-events:none}
.ct-toast{position:relative;top:auto;right:auto;display:flex;align-items:center;gap:14px;width:100%;max-width:none;padding:14px 16px 16px;overflow:hidden;background:#fff;border:1px solid #e5e6e8;border-radius:8px;box-shadow:0 10px 30px rgba(21,23,26,.12);font-family:'Nunito Sans',Arial,Helvetica,sans-serif;pointer-events:auto;transform:translateX(calc(100% + 40px));transition:transform .35s ease,opacity .35s ease}
.ct-toast.show{transform:translateX(0)}
.ct-toast.leaving{transform:translateX(calc(100% + 40px));opacity:0}
.ct-toast .toaster-icon{display:grid;place-items:center;flex:none;width:40px;height:40px;background:#15171a;border-radius:50%}
.ct-toast .toaster-icon i{font-size:19px;color:#fff}
.ct-toast[data-type="success"] .toaster-icon{background:#17752c}
.ct-toast[data-type="error"] .toaster-icon{background:#b42318}
.ct-toast[data-type="info"] .toaster-icon{background:#9a6b16}
.ct-toast .toaster-message h4{margin:0 0 3px;font-size:15px;font-weight:800;color:#15171a}
.ct-toast .toaster-message p{margin:0;font-size:13px;line-height:1.45;color:#4b4f56}
.ct-toast .toaster-close{display:grid;place-items:center;align-self:flex-start;width:28px;height:28px;margin:-4px -6px 0 0;border-radius:50%;color:#6a6e75;font-size:18px}
.ct-toast .toaster-close:hover{background:#f1f2f3;color:#15171a}
.ct-toast .ct-bar{position:absolute;left:0;bottom:0;width:100%;height:3px;background:#b98a35;transform:scaleX(0);transform-origin:left}
.ct-toast[data-type="success"] .ct-bar{background:#17752c}
.ct-toast[data-type="error"] .ct-bar{background:#b42318}
.ct-toast.show .ct-bar{animation:ctBar var(--ct-time,3.5s) linear forwards}
.ct-toast:hover .ct-bar{animation-play-state:paused}
.ct-toast .ct-link{display:inline-flex;align-items:center;gap:6px;margin-top:8px;padding:6px 14px;background:#15171a;border-radius:5px;color:#fff;font-size:13px;font-weight:700;text-decoration:none}
.ct-toast .ct-link:hover{background:#33363b;color:#fff}
.ct-toast .ct-link[hidden]{display:none}
/* Header cart badge bump when products are added */
.cart-count.bump{animation:cartBump .5s ease}
@keyframes cartBump{0%{transform:scale(1)}40%{transform:scale(1.35)}100%{transform:scale(1)}}
@keyframes ctBar{from{transform:scaleX(1)}to{transform:scaleX(0)}}
/* Phones: this page is wider than the screen, so pin the messages to the visible left edge */
@media(max-width:600px){#ctStack{top:12px;left:16px;right:auto}}
@media (prefers-reduced-motion:reduce){.ct-toast,.ct-toast .ct-bar,.cart-count.bump{animation:none!important;transition:opacity .2s}}
</style>

<div class="compare-body">
<main class="wrap">
  <section class="hero">
    <img class="compare-hero-art" src="{{ asset('web_frontend/assets/images/compare-hero-v4.webp') }}" width="2000" height="688" alt="">
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
    // With nothing selected this shows the "No products selected yet" message
    loadProductDetails();
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
        grid.style.setProperty('--n', 3);
        container.innerHTML = `
            <div class="card empty-cmp">
                <div class="ec-icon"><svg viewBox="0 0 24 24"><path d="M20 12v9H4v-9M2 7h20v5H2zM12 21V7M12 7H7.5a2.500 2.500 0 1 1 0-5C11 2 12 7 12 7zM12 7h4.500a2.500 2.500 0 1 0 0-5C13 2 12 7 12 7z"/></svg></div>
                <h3>No products selected yet</h3>
                <p>Pick up to 3 gifts from the list on the left to compare their features, bulk prices and savings side by side.</p>
                <ol class="ec-steps">
                    <li><span>1</span>Search or tick a product</li>
                    <li><span>2</span>Compare prices by quantity</li>
                    <li><span>3</span>Request a quote in one click</li>
                </ol>
            </div>`;
        return;
    }

    // Update grid columns based on number of selected products
    const numProducts = selectedProductIds.length;
    grid.style.setProperty('--n', numProducts);

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
    table.style.setProperty('--n', products.length);

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

    // Nothing selected: hide the empty green savings bar
    container.style.display = selectedProductIds.length === 0 ? 'none' : '';
    if (selectedProductIds.length === 0) {
        label.textContent = 'Your Savings';
        // Reset to default 3 columns
        container.style.setProperty('--n', 3);
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
    container.style.setProperty('--n', numProducts);

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