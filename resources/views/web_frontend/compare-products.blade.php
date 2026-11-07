@extends('web_frontend.layouts.app')

@section('title', 'Compare Corporate Gifts')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Caveat:wght@500&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--ink:#111;--muted:#666;--line:#e6e6e6;--peach:#fdf1e7;--gold:#fdeccf;--green:#e8f5e8;--gtext:#1d6b2a;--bg:#fff}
.compare-body{font-family:Inter,system-ui,-apple-system,Segoe UI,Arial,sans-serif;color:var(--ink);background:var(--bg);font-size:14px}
.compare-body a{color:inherit;text-decoration:none}
.compare-body button{font:inherit;cursor:pointer}
.compare-body svg{width:1em;height:1em;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex:none}

/* hero */
.wrap{padding:0 36px}
.hero{display:flex;justify-content:space-between;align-items:flex-start;padding-top:24px}
.eyebrow{font-size:12.5px;letter-spacing:2.3px;font-weight:500;margin-bottom:5px}
h1{font-size:50px;font-weight:800;letter-spacing:-1.5px;line-height:1.1}
.hero p{font-size:20px;color:#555;margin-top:6px;font-weight:300;letter-spacing:.1px}
.bulk{width:466px;background:var(--peach);border-radius:4px;display:flex;align-items:center;gap:22px;padding:24px 28px;margin-top:6px;margin-right:0}
.bulk .ico{font-size:46px;stroke-width:1.6}
.bulk h3{font-size:19px;font-weight:600;margin-bottom:5px}
.bulk p{font-size:14.5px;margin:0;color:#555;font-weight:400;line-height:1.35}

/* compare grid */
.cmp{display:grid;grid-template-columns:278px repeat(3,1fr);gap:4px;margin-top:17px}
.card{border:1px solid var(--line);border-radius:3px;background:#fff;padding:14px 14px 10px}
.sel h4{font-size:15px;font-weight:600}
.sel small{display:block;font-size:14px;color:#444;margin:2px 0 11px}
.sbox{display:flex;align-items:center;gap:8px;border:1px solid #ddd;height:33px;padding:0 10px;font-size:12.5px;color:#777;margin-bottom:8px;border-radius:2px}
.opt{display:flex;align-items:center;gap:14px;height:41px;margin-bottom:6px;padding:0 6px;font-size:13px;border-radius:2px}
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
.pt{display:flex;gap:18px;align-items:center;padding-top:20px}
.pimg{width:98px;height:158px;background:#f1f1f1;border-radius:3px;display:grid;place-items:center;flex:none;position:relative;overflow:hidden}
.pimg.sp{margin-top:0}
.bottle{width:50px;height:132px;background:linear-gradient(90deg,#222,#000 50%,#2b2b2b);border-radius:8px 8px 6px 6px;position:relative;margin-top:14px}
.bottle:before{content:"";position:absolute;top:-18px;left:7px;width:36px;height:18px;border:5px solid #111;border-bottom:0;border-radius:12px 12px 0 0}
.bottle:after{content:"";position:absolute;top:-6px;left:2px;right:2px;height:8px;background:#111;border-radius:3px}
.speaker{width:78px;height:96px;background:repeating-linear-gradient(45deg,#2a2a2a 0 2px,#161616 2px 4px);border-radius:12px;display:grid;place-items:center;color:#ddd;font-weight:800;font-size:12px;border:3px solid #111}
.note{width:84px;height:132px;background:#161616;border-radius:3px 6px 6px 3px;color:#d9c9a0;font-family:Georgia,serif;font-size:10.5px;line-height:1.25;display:grid;place-items:center;text-align:center;box-shadow:inset 6px 0 0 #000;position:relative}
.pen{position:absolute;right:-6px;top:30px;width:5px;height:96px;background:#111;border-radius:3px;transform:rotate(3deg);z-index:2}
.pimg.nb{background:#ececec;overflow:visible}
.pi h3{font-size:17px;font-weight:600;margin-bottom:6px}
.pi p{font-size:14.5px;color:#555;margin-bottom:10px;font-weight:300}
.badge{display:inline-flex;align-items:center;gap:8px;font-size:13px;padding:8px 14px;border-radius:5px;font-weight:500}
.b-g{background:#e6f5e8;color:#1b5e27}.b-g svg{fill:#2e9e44;stroke:none}
.b-b{background:#e4efff;color:#1b3f8c}.b-b svg{fill:#1e63e9;stroke:none}
.b-o{background:#fdf0dc;color:#6b3a00}.b-o svg{fill:#d98a00;stroke:none}
.feat{list-style:none;margin-top:14px}
.feat li{display:flex;align-items:center;gap:15px;font-size:14px;color:#666;height:23.2px;font-weight:300}
.feat svg{font-size:15px;color:#222;stroke-width:2.4}

/* table */
.tbl{display:grid;grid-template-columns:278px repeat(6,1fr);margin-top:6px;border:1px solid var(--line);border-bottom:0;background:#fff}
.tbl>div{height:32px;display:flex;align-items:center;justify-content:center;font-size:14.5px;border-bottom:1px solid var(--line);border-right:1px solid var(--line)}
.tbl>div:nth-child(7n+1){justify-content:flex-start;padding-left:22px}
.tbl>.h{background:#f4f4f4;font-weight:600;height:36px;font-size:14.5px}
.tbl>.h:first-child{font-size:15px}
.tbl>.sel{background:var(--gold);color:#b35a00;font-weight:600;border-color:#f3cf93}
.tbl>.sel:first-child{gap:9px;color:#b35a00}
.tbl>.sel:first-child svg{fill:#e88a00;stroke:#e88a00;font-size:17px}
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
.tag{position:absolute;right:20px;top:20px;font-family:Caveat,cursive;font-size:30px;line-height:1.05;transform:rotate(-14deg);text-align:left;width:120px}

@media(max-width:1100px){
 .nav,.sub .r,.bulk{display:none}
 .cmp,.tbl,.sav{min-width:1100px}
 .scroll{overflow-x:auto}
}
</style>

<div class="compare-body">
<main class="wrap">
  <section class="hero">
    <div>
      <div class="eyebrow">COMPARE. PLAN. SAVE MORE.</div>
      <h1>Compare Corporate Gifts</h1>
      <p>Compare features, pricing and bulk savings across multiple products to find the best fit for your team.</p>
    </div>
    <div class="bulk">
      <svg class="ico" viewBox="0 0 24 24"><rect x="3" y="12" width="5" height="9"/><rect x="9.5" y="7" width="5" height="14"/><rect x="16" y="3" width="5" height="18"/></svg>
      <div><h3>Bulk Save Comparison</h3><p>See real cost-per-unit at different quantities and choose the best value for your budget.</p></div>
    </div>
  </section>

  <div class="scroll">
  <section class="cmp">
    <div class="card sel">
      <h4>Select Products to Compare</h4><small>(up to 3)</small>
      <div class="sbox"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>Search products...</div>
      <div class="opt on"><span class="cb"><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg></span><span class="thumb"><i class="t-b"></i></span>Insulated Bottle</div>
      <div class="opt on"><span class="cb"><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg></span><span class="thumb"><i class="t-s"></i></span>Wireless Speaker</div>
      <div class="opt on"><span class="cb"><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg></span><span class="thumb"><i class="t-n"></i></span>Notebook Set</div>
      <div class="opt"><span class="cb"><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg></span><span class="thumb"><i class="t-t"></i></span>Tote Bag</div>
      <div class="opt"><span class="cb"><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg></span><span class="thumb"><i class="t-w"></i></span>Welcome Kit</div>
    </div>

    <div class="card prod">
      <button class="rm"><svg viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>Remove</button>
      <div class="pt">
        <div class="pimg"><div class="bottle"></div></div>
        <div class="pi"><h3>Premium Vacuum Bottle</h3><p>500ml | 12h Hot &amp; 24h Cold</p>
          <span class="badge b-g"><svg viewBox="0 0 24 24"><path d="M20 4C9 4 4 9 4 15c0 2 1 4 3 5 0-5 3-9 9-11-4 3-6 6-6 11 7 0 10-6 10-16z"/></svg>Sustainable Choice</span></div>
      </div>
      <ul class="feat">
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>304 Grade Stainless Steel</li>
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>Leak Proof</li>
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>BPA Free</li>
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>Multiple Color Options</li>
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>Custom Branding Available</li>
      </ul>
    </div>

    <div class="card prod">
      <button class="rm"><svg viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>Remove</button>
      <div class="pt">
        <div class="pimg" style="background:#f4f4f4"><div class="speaker">JBL</div></div>
        <div class="pi"><h3>Wireless Bluetooth Speaker</h3><p>Compact | 10h Playtime</p>
          <span class="badge b-b"><svg viewBox="0 0 24 24"><path d="M12 2l3 6.5 7 .8-5.200 4.800 1.400 7L12 17.600 5.800 21.100l1.400-7L2 9.300l7-.8z"/></svg>Best for Events</span></div>
      </div>
      <ul class="feat">
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>High Quality Sound</li>
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>10 Hours Battery Life</li>
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>Compact &amp; Portable</li>
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>Custom Logo Printing</li>
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>Multiple Color Options</li>
      </ul>
    </div>

    <div class="card prod">
      <button class="rm"><svg viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>Remove</button>
      <div class="pt">
        <div class="pimg nb"><div class="note">Good<br>Ideas<br>Better<br>Teams<span class="pen"></span></div></div>
        <div class="pi"><h3>Premium Notebook Set</h3><p>A5 | Hard Cover | With Pen</p>
          <span class="badge b-o"><svg viewBox="0 0 24 24"><path d="M3 8l4.500 4L12 5l4.500 7L21 8l-2 11H5z"/></svg>Most Popular</span></div>
      </div>
      <ul class="feat">
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>A5 Hard Cover Notebook</li>
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>Premium Metal Pen</li>
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>Custom Branding (Logo/Name)</li>
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>Gift Box Packaging</li>
        <li><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg>Multiple Color Options</li>
      </ul>
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
    <button class="btn"><svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>Add Another Product</button>
    <button class="btn"><svg viewBox="0 0 24 24"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8zM14 3v5h5M9 13h6M9 17h6"/></svg>Request a Quote for Selected Items</button>
    <button class="btn dark">Proceed with Selection <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
    <div class="tag">Thoughtful Gifts Stronger Teams</div>
  </div>
</main>
</div>

<script>
// qty tiers + unit prices for [Bottle, Speaker, Notebook]
const tiers=[
 {q:'50 – 99',u:[45,60,28],n:50},
 {q:'100 – 249',u:[38,52,24],n:100},
 {q:'250 – 499',u:[32,45,20],n:250},
 {q:'500 – 999',u:[28,38,16],n:500},
 {q:'1,000+',u:[24,32,14],n:1000}
];
const fmt=n=>'AED '+n.toLocaleString('en-US');
let sel=3;
const tbl=document.getElementById('tbl');
function render(){
  let h='<div class="h">Quantity</div>'+['Unit Price (AED)','Total Price (AED)'].concat(['Unit Price (AED)','Total Price (AED)'],['Unit Price (AED)','Total Price (AED)']).map(t=>`<div class="h">${t}</div>`).join('');
  tiers.forEach((t,i)=>{
    const c=i===sel?' sel':'';
    h+=`<div class="${c}" data-i="${i}">${i===sel?'<svg viewBox="0 0 24 24"><path d="M12 2l3 6.500 7 .8-5.200 4.800 1.400 7L12 17.600 5.800 21.100l1.400-7L2 9.300l7-.8z"/></svg>':''}${t.q}</div>`;
    t.u.forEach(u=>{h+=`<div class="${c}" data-i="${i}">${fmt(u)}</div><div class="${c}" data-i="${i}">${fmt(u*t.n)}</div>`});
  });
  tbl.innerHTML=h;
  const base=tiers[0].u;
  document.getElementById('savLabel').textContent='Your Savings at '+tiers[sel].q.replace('+',' +')+' pcs';
  tiers[sel].u.forEach((u,k)=>{
    const d=base[k]-u;
    document.getElementById('s'+k).textContent='Save AED '+d+' per unit';
    document.getElementById('p'+k).textContent='('+Math.round(d/base[k]*100)+'% savings)';
  });
}
tbl.addEventListener('click',e=>{const c=e.target.closest('[data-i]');if(c){sel=+c.dataset.i;render()}});
render();

// checkbox toggle in "Select Products" list
document.querySelectorAll('.opt').forEach(o=>o.addEventListener('click',()=>{
  const on=document.querySelectorAll('.opt.on').length;
  if(o.classList.contains('on')||on<3)o.classList.toggle('on');
}));
// remove buttons
document.querySelectorAll('.rm').forEach(b=>b.addEventListener('click',()=>{b.closest('.prod').style.visibility='hidden'}));
</script>
@endsection

@section('scripts')
<style>
.compare-section {
  background: #ffffff;
  padding-top: 20px;
}

/* Page Heading */
.compare-header {
  padding: 20px 0 30px 0;
}

.small-heading {
  font-size: 13px;
  color: #888;
  font-weight: 600;
  letter-spacing: 2px;
  margin-bottom: 8px;
  text-transform: uppercase;
}

.main-heading {
  font-size: 42px;
  font-weight: 700;
  color: #000;
  margin-bottom: 12px;
  line-height: 1.2;
}

.subtitle {
  font-size: 15px;
  color: #666;
  line-height: 1.6;
  max-width: 600px;
}

.bulk-save-card {
  background: #faf8f0;
  padding: 30px 25px;
  border-radius: 16px;
  text-align: center;
  border: 1px solid #f0e8d0;
}

.card-icon {
  font-size: 36px;
  color: #b8860b;
  margin-bottom: 12px;
}

.bulk-save-card h4 {
  font-size: 16px;
  font-weight: 700;
  color: #000;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.bulk-save-card p {
  font-size: 13px;
  color: #666;
  line-height: 1.5;
}

/* Product Selection Panel */
.product-selection-panel {
  background: #ffffff;
  border: 1px solid #e5e5e5;
  border-radius: 16px;
  padding: 24px;
  position: sticky;
  top: 100px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.panel-title {
  font-size: 16px;
  font-weight: 700;
  color: #000;
  margin-bottom: 18px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.search-box-wrapper {
  margin-bottom: 18px;
}

.search-input-group {
  position: relative;
  display: flex;
  align-items: center;
}

.search-input-group i {
  position: absolute;
  left: 14px;
  color: #aaa;
  font-size: 14px;
}

.search-input-group input {
  padding-left: 38px;
  padding-right: 14px;
  padding-top: 10px;
  padding-bottom: 10px;
  border: 1px solid #e5e5e5;
  border-radius: 8px;
  font-size: 13px;
  width: 100%;
  background: #fafafa;
}

.search-input-group input:focus {
  outline: none;
  border-color: #b8860b;
  background: #fff;
}

.product-list {
  max-height: 450px;
  overflow-y: auto;
}

.product-item {
  margin-bottom: 8px;
}

.product-checkbox {
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  padding: 10px 12px;
  border-radius: 8px;
  transition: background 0.2s;
  width: 100%;
  box-sizing: border-box;
}

.product-checkbox:hover {
  background: #f5f5f5;
}

.product-checkbox input[type="checkbox"] {
  width: 16px;
  height: 16px;
  accent-color: #b8860b;
  cursor: pointer;
  flex-shrink: 0;
}

.product-thumb {
  width: 44px;
  height: 44px;
  border-radius: 6px;
  overflow: hidden;
  flex-shrink: 0;
  background: #f5f5f5;
}

.product-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-name {
  font-size: 13px;
  color: #333;
  font-weight: 500;
  flex: 1;
  line-height: 1.4;
  word-wrap: break-word;
}

/* Comparison Cards */
.comparison-cards {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  margin-bottom: 30px;
}

.compare-card {
  background: #ffffff;
  border: 1px solid #e5e5e5;
  border-radius: 16px;
  padding: 20px;
  position: relative;
  transition: box-shadow 0.2s;
}

.compare-card:hover {
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.remove-btn {
  position: absolute;
  top: 12px;
  right: 12px;
  background: #fff;
  border: 1px solid #e5e5e5;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 11px;
  color: #666;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 500;
}

.remove-btn:hover {
  background: #f5f5f5;
  border-color: #ccc;
}

.card-image {
  width: 100%;
  height: 180px;
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: 16px;
  background: #f8f8f8;
  display: flex;
  align-items: center;
  justify-content: center;
}

.card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.card-title {
  font-size: 16px;
  font-weight: 700;
  color: #000;
  margin-bottom: 6px;
  line-height: 1.3;
}

.card-description {
  font-size: 13px;
  color: #888;
  margin-bottom: 12px;
  font-weight: 500;
}

.product-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
  margin-bottom: 14px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.product-badge.sustainable {
  background: #e8f5e9;
  color: #2e7d32;
}

.product-badge.events {
  background: #fff3e0;
  color: #f57c00;
}

.product-badge.popular {
  background: #ede7f6;
  color: #5e35b1;
}

.feature-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.feature-list li {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  font-size: 12px;
  color: #444;
  margin-bottom: 7px;
  line-height: 1.4;
}

.feature-list li i {
  color: #b8860b;
  font-size: 13px;
  margin-top: 1px;
  flex-shrink: 0;
}

/* Pricing Table */
.pricing-table-wrapper {
  overflow-x: auto;
  background: #ffffff;
  border: 1px solid #e5e5e5;
  border-radius: 16px;
  padding: 24px;
  margin-bottom: 24px;
}

.comparison-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 800px;
}

.comparison-table th {
  background: #fafafa;
  padding: 14px 12px;
  text-align: center;
  font-weight: 600;
  color: #000;
  border-bottom: 2px solid #e5e5e5;
  font-size: 13px;
}

.comparison-table .sub-header th {
  background: #f5f5f5;
  font-size: 12px;
  padding: 10px 12px;
  font-weight: 500;
  color: #666;
  border-bottom: 1px solid #e5e5e5;
}

.quantity-column {
  width: 130px;
  text-align: left;
}

.product-column {
  background: #fafafa;
  font-weight: 600;
}

.comparison-table td {
  padding: 14px 12px;
  text-align: center;
  border-bottom: 1px solid #f0f0f0;
  color: #333;
  font-size: 13px;
}

.qty-cell {
  font-weight: 600;
  color: #000;
  text-align: left;
  font-size: 13px;
}

.highlight-row {
  background: #fff8e6 !important;
}

.highlight-row .qty-cell i {
  color: #f57c00;
  margin-right: 4px;
  font-size: 12px;
}

.bold-price {
  font-weight: 700;
  color: #000;
}

/* Savings Section */
.savings-section {
  background: #f0f9f1;
  padding: 24px;
  border-radius: 16px;
  margin-bottom: 24px;
  border: 1px solid #d4e8d5;
}

.savings-title {
  font-size: 15px;
  font-weight: 700;
  color: #2e7d32;
  margin-bottom: 18px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.savings-cards {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
}

.savings-card {
  background: #ffffff;
  padding: 18px;
  border-radius: 10px;
  text-align: center;
  border: 1px solid #e5e5e5;
}

.savings-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.savings-amount {
  font-size: 15px;
  font-weight: 700;
  color: #2e7d32;
}

.savings-percent {
  font-size: 12px;
  color: #666;
  font-weight: 500;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  margin-bottom: 20px;
}

.action-buttons .btn {
  padding: 12px 20px;
  font-size: 13px;
  font-weight: 600;
  border-radius: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-add-product {
  background: #ffffff;
  border: 2px solid #000;
  color: #000;
}

.btn-add-product:hover {
  background: #f5f5f5;
}

.btn-quote {
  background: #ffffff;
  border: 2px solid #000;
  color: #000;
}

.btn-quote:hover {
  background: #f5f5f5;
}

.btn-proceed {
  background: #000;
  border: 2px solid #000;
  color: #fff;
}

.btn-proceed:hover {
  background: #333;
}

/* Bottom Tagline */
.bottom-tagline {
  text-align: right;
  font-family: 'Georgia', serif;
  font-size: 22px;
  color: #888;
  margin-top: 25px;
  font-style: italic;
  font-weight: 400;
}

/* Responsive */
@media (max-width: 991px) {
  .comparison-cards {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .savings-cards {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .compare-header .row {
    flex-direction: column;
  }
  
  .compare-header .col-md-4 {
    margin-top: 20px;
  }
  
  .comparison-cards {
    grid-template-columns: 1fr;
  }
  
  .savings-cards {
    grid-template-columns: 1fr;
  }
  
  .action-buttons {
    flex-direction: column;
  }
  
  .action-buttons .btn {
    width: 100%;
    justify-content: center;
  }
}
</style>

<script>
// Product data with pricing
const productData = {
  'insulated-bottle': {
    name: 'Premium Vacuum Bottle',
    pricing: [
      { qty: '50-99', unit: 45, total: 4500 },
      { qty: '100-249', unit: 38, total: 9500 },
      { qty: '250-499', unit: 32, total: 16000 },
      { qty: '500-999', unit: 28, total: 28000 },
      { qty: '1000+', unit: 24, total: 24000 }
    ],
    basePrice: 45
  },
  'wireless-speaker': {
    name: 'Wireless Bluetooth Speaker',
    pricing: [
      { qty: '50-99', unit: 60, total: 6000 },
      { qty: '100-249', unit: 52, total: 13000 },
      { qty: '250-499', unit: 45, total: 22500 },
      { qty: '500-999', unit: 38, total: 38000 },
      { qty: '1000+', unit: 32, total: 32000 }
    ],
    basePrice: 60
  },
  'notebook-set': {
    name: 'Premium Notebook Set',
    pricing: [
      { qty: '50-99', unit: 28, total: 2800 },
      { qty: '100-249', unit: 24, total: 6000 },
      { qty: '250-499', unit: 20, total: 10000 },
      { qty: '500-999', unit: 16, total: 16000 },
      { qty: '1000+', unit: 14, total: 14000 }
    ],
    basePrice: 28
  },
  'tote-bag': {
    name: 'Custom Tote Bag',
    pricing: [
      { qty: '50-99', unit: 35, total: 3500 },
      { qty: '100-249', unit: 30, total: 7500 },
      { qty: '250-499', unit: 25, total: 12500 },
      { qty: '500-999', unit: 22, total: 22000 },
      { qty: '1000+', unit: 18, total: 18000 }
    ],
    basePrice: 35
  },
  'welcome-kit': {
    name: 'Corporate Welcome Kit',
    pricing: [
      { qty: '50-99', unit: 75, total: 7500 },
      { qty: '100-249', unit: 65, total: 16250 },
      { qty: '250-499', unit: 55, total: 27500 },
      { qty: '500-999', unit: 48, total: 48000 },
      { qty: '1000+', unit: 42, total: 42000 }
    ],
    basePrice: 75
  }
};

let selectedProducts = ['insulated-bottle', 'wireless-speaker', 'notebook-set'];
let selectedQuantity = '500-999';

// Product search functionality
document.getElementById('productSearch').addEventListener('input', function(e) {
  const searchTerm = e.target.value.toLowerCase();
  const productItems = document.querySelectorAll('.product-item');
  
  productItems.forEach(item => {
    const productName = item.querySelector('.product-name').textContent.toLowerCase();
    if (productName.includes(searchTerm)) {
      item.style.display = 'block';
    } else {
      item.style.display = 'none';
    }
  });
});

// Checkbox change handler
document.querySelectorAll('.product-checkbox input').forEach(checkbox => {
  checkbox.addEventListener('change', function() {
    const product = this.dataset.product;
    
    if (this.checked) {
      if (selectedProducts.length >= 3) {
        this.checked = false;
        alert('You can compare up to 3 products only.');
        return;
      }
      selectedProducts.push(product);
    } else {
      selectedProducts = selectedProducts.filter(p => p !== product);
    }
    
    updateComparisonCards();
    updatePricingTable();
    updateSavings();
  });
});

// Remove product from comparison
function removeProduct(product) {
  const checkbox = document.querySelector(`input[data-product="${product}"]`);
  if (checkbox) {
    checkbox.checked = false;
  }
  selectedProducts = selectedProducts.filter(p => p !== product);
  updateComparisonCards();
  updatePricingTable();
  updateSavings();
}

// Update comparison cards
function updateComparisonCards() {
  const container = document.getElementById('comparisonCards');
  const allCards = container.querySelectorAll('.compare-card');
  
  allCards.forEach(card => {
    const product = card.dataset.product;
    if (selectedProducts.includes(product)) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });
}

// Update pricing table
function updatePricingTable() {
  // Rebuild table based on selected products
  const table = document.querySelector('.comparison-table');
  const thead = table.querySelector('thead');
  const tbody = table.querySelector('tbody');
  
  // Update headers
  const headerRow = thead.querySelector('tr:first-child');
  const subHeaderRow = thead.querySelector('.sub-header');
  
  // Clear existing product columns (keep quantity column)
  while (headerRow.children.length > 1) {
    headerRow.removeChild(headerRow.lastChild);
  }
  while (subHeaderRow.children.length > 1) {
    subHeaderRow.removeChild(subHeaderRow.lastChild);
  }
  
  // Add product columns
  selectedProducts.forEach(product => {
    const data = productData[product];
    const th = document.createElement('th');
    th.colSpan = 2;
    th.className = 'product-column';
    th.textContent = data.name;
    headerRow.appendChild(th);
    
    const thUnit = document.createElement('th');
    thUnit.textContent = 'Unit Price (AED)';
    subHeaderRow.appendChild(thUnit);
    
    const thTotal = document.createElement('th');
    thTotal.textContent = 'Total Price (AED)';
    subHeaderRow.appendChild(thTotal);
  });
  
  // Update tbody rows
  const quantities = ['50-99', '100-249', '250-499', '500-999', '1000+'];
  tbody.innerHTML = '';
  
  quantities.forEach((qty, index) => {
    const tr = document.createElement('tr');
    tr.dataset.qty = qty;
    
    if (qty === '500-999') {
      tr.className = 'highlight-row';
    }
    
    // Quantity cell
    const qtyCell = document.createElement('td');
    qtyCell.className = 'qty-cell';
    if (qty === '500-999') {
      qtyCell.innerHTML = '<i class="bi bi-star-fill"></i> ' + qty;
    } else {
      qtyCell.textContent = qty;
    }
    tr.appendChild(qtyCell);
    
    // Product pricing cells
    selectedProducts.forEach(product => {
      const data = productData[product];
      const pricing = data.pricing[index];
      
      const unitCell = document.createElement('td');
      unitCell.className = 'price-cell';
      if (qty === '500-999') {
        unitCell.classList.add('bold-price');
      }
      unitCell.textContent = pricing.unit;
      tr.appendChild(unitCell);
      
      const totalCell = document.createElement('td');
      totalCell.className = 'total-cell';
      if (qty === '500-999') {
        totalCell.classList.add('bold-price');
      }
      totalCell.textContent = pricing.total.toLocaleString();
      tr.appendChild(totalCell);
    });
    
    tbody.appendChild(tr);
  });
}

// Update savings section
function updateSavings() {
  const container = document.querySelector('.savings-cards');
  container.innerHTML = '';
  
  selectedProducts.forEach(product => {
    const data = productData[product];
    const highlightedPricing = data.pricing.find(p => p.qty === '500-999');
    const basePricing = data.pricing[0];
    
    const savings = basePricing.unit - highlightedPricing.unit;
    const savingsPercent = Math.round((savings / basePricing.unit) * 100);
    
    const card = document.createElement('div');
    card.className = 'savings-card';
    card.dataset.product = product;
    card.innerHTML = `
      <div class="savings-info">
        <span class="savings-amount">Save AED ${savings} per unit</span>
        <span class="savings-percent">(${savingsPercent}% savings)</span>
      </div>
    `;
    container.appendChild(card);
  });
}

// Scroll to selection panel
function scrollToSelection() {
  document.querySelector('.product-selection-panel').scrollIntoView({ behavior: 'smooth' });
}

// Request quote
function requestQuote() {
  if (selectedProducts.length === 0) {
    alert('Please select at least one product to request a quote.');
    return;
  }
  window.location.href = '/cart';
}

// Proceed with selection
function proceedWithSelection() {
  if (selectedProducts.length === 0) {
    alert('Please select at least one product to proceed.');
    return;
  }
  // Add logic to proceed with selected products
  alert('Proceeding with selected products: ' + selectedProducts.join(', '));
}

// Initialize
updateComparisonCards();
updatePricingTable();
updateSavings();
</script>
@endsection
