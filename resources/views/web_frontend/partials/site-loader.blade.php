{{-- Loading screen for a new visit: gift boxes drawn in lines turn into the logo while the bar fills, then the screen zooms in to show the page while the logo travels to its place in the header. Shown once, then again only after 30 minutes away. Built by mtg-scratch/build_site_loader.py. --}}
<style>
  html.mtg-loading { overflow: hidden; scrollbar-gutter: stable; background: #000; }
  #mtgLoader { position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 2147483000; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 16px; overflow: hidden; background: #000; }
  #mtgLoader[hidden] { display: none; }
  #mtgLoader.is-done { pointer-events: none; background: transparent; }
  /* The black screen zooms towards the viewer and fades away; the logo moves separately */
  .mtg-loader-back { position: absolute; top: 0; right: 0; bottom: 0; left: 0; background: #000; }
  #mtgLoader.is-done .mtg-loader-back { animation: mtgZoomFade 1s ease-in-out forwards; }
  #mtgLoader.is-done .mtg-loader-progress { animation: mtgZoomFade .7s ease-in forwards; }
  #mtgLoader.no-target .mtg-loader-art { opacity: 0; transform: scale(1.5); transition: opacity .6s ease, transform .9s ease-in; }
  .mtg-loader-art { position: relative; z-index: 1; width: 280px; max-width: 70vw; transition: transform 1s cubic-bezier(.6, 0, .2, 1); }
  .mtg-loader-art img { display: block; width: 100%; height: auto; opacity: 0; animation: mtgFadeIn .45s ease 2.2s forwards; }
  .mtg-loader-art svg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: visible; }
  .mtg-loader-art path { fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; stroke-dasharray: 1 2; stroke-dashoffset: 1; opacity: 0; }
  .mtg-white { stroke: #fff; }
  .mtg-gold { stroke: #efb73e; }
  .mtg-loader-boxes path { animation: mtgDraw .6s ease-out calc(var(--i) * 55ms) forwards, mtgErase .5s ease-in 1.55s forwards; }
  .mtg-loader-word path { stroke: #fff; stroke-width: 1.6; animation: mtgDraw .65s ease-in-out calc(1.6s + var(--i) * 18ms) forwards; }
  .mtg-loader-word { animation: mtgFadeOut .3s ease 2.5s forwards; }
  .mtg-loader-progress { position: relative; z-index: 1; display: flex; align-items: center; gap: 10px; margin-top: 4px; }
  .mtg-loader-percent { min-width: 38px; font-family: 'Nunito Sans', Arial, sans-serif; font-size: 14px; font-weight: 700; line-height: 1; font-variant-numeric: tabular-nums; text-align: right; color: #efb73e; }
  .mtg-loader-track { width: 220px; max-width: 55vw; height: 4px; overflow: hidden; background: rgba(255, 255, 255, .18); border-radius: 4px; }
  .mtg-loader-bar { width: 0; height: 100%; background: #efb73e; border-radius: 4px; transition: width .12s linear; }
  @keyframes mtgDraw { from { stroke-dashoffset: 1; opacity: 1; } to { stroke-dashoffset: 0; opacity: 1; } }
  @keyframes mtgErase { from { stroke-dashoffset: 0; opacity: 1; } 99% { stroke-dashoffset: -1; opacity: 1; } to { stroke-dashoffset: -1; opacity: 0; } }
  @keyframes mtgFadeIn { to { opacity: 1; } }
  @keyframes mtgFadeOut { to { opacity: 0; } }
  @keyframes mtgZoomFade { to { opacity: 0; transform: scale(1.6); } }
  @media (prefers-reduced-motion: reduce) {
    .mtg-loader-art svg { display: none; }
    .mtg-loader-art img { opacity: 1; animation: none; }
    .mtg-loader-bar { transition: none; }
    #mtgLoader.is-done { animation: mtgFadeOut .3s ease forwards; }
    #mtgLoader.is-done .mtg-loader-back, #mtgLoader.is-done .mtg-loader-progress { animation: none; }
    .mtg-loader-art { transition: none; }
  }
</style>
<div id="mtgLoader" hidden>
  <div class="mtg-loader-back"></div>
  <div class="mtg-loader-art">
    <img src="{{ asset('web_frontend/assets/images/home/logo.png') }}" alt="More Than Giftz. It's an experience." width="520" height="475">
    <svg viewBox="0 0 520 475" aria-hidden="true" focusable="false">
      <g class="mtg-loader-boxes">
      <path class="mtg-white" style="--i:0" d="M182 205V375H338V205"/>
      <path class="mtg-white" style="--i:1" d="M170 165H350V205H170Z"/>
      <path class="mtg-gold" style="--i:2" d="M245 165V375M275 165V375"/>
      <path class="mtg-gold" style="--i:3" d="M260 165C232 150 200 120 214 104C230 88 254 130 260 165C266 130 290 88 306 104C320 120 288 150 260 165Z"/>
      <path class="mtg-white" style="--i:4" d="M80 300V375H164V300"/>
      <path class="mtg-white" style="--i:5" d="M72 278H172V300H72Z"/>
      <path class="mtg-gold" style="--i:6" d="M114 278V375M130 278V375"/>
      <path class="mtg-white" style="--i:7" d="M90 238V278H154V238"/>
      <path class="mtg-white" style="--i:8" d="M84 222H160V238H84Z"/>
      <path class="mtg-gold" style="--i:9" d="M122 222V278"/>
      <path class="mtg-gold" style="--i:10" d="M122 222C110 214 96 200 104 192C112 186 120 204 122 222C124 204 132 186 140 192C148 200 134 214 122 222Z"/>
      <path class="mtg-white" style="--i:11" d="M356 270V375H440V270"/>
      <path class="mtg-white" style="--i:12" d="M348 248H448V270H348Z"/>
      <path class="mtg-gold" style="--i:13" d="M390 248V375M406 248V375"/>
      <path class="mtg-gold" style="--i:14" d="M398 248C384 238 368 222 378 212C388 206 396 226 398 248C400 226 408 206 418 212C428 222 412 238 398 248Z"/>
      <path class="mtg-white" style="--i:15" d="M46 375H474"/>
      </g>
      <g class="mtg-loader-word">
      <path style="--i:0" d="M107 76l5 2l4 5l0 42l-2 4l-6 5l34 0l-4-2l-4-6l0-40l2-5l6-5l8 1l3 2l2 5l0 42l-5 6l-3 2l35 0l-5-2l-2-3l-2-5l0-35l1-5l3-5l5-3l10 1l5 6l1 5l0 35l-2 6l-6 5l34 0l-4-2l-4-6l0-39l-1-1l0-5l-2-5l-7-6l-8-3l-11 1l-6 3l-8 10l-3-7l-4-3l-9-4l-7 0l-8 3l-6 6l-2 4l-1 0l1-2l-1-10z"/>
      <path style="--i:1" d="M254 67l-13 5l-8 7l-6 12l-1 15l4 12l10 12l12 5l12 0l12-5l10-10l5-12l0-15l-5-12l-9-8l-10-5z"/>
      <path style="--i:2" d="M255 71l6 0l7 5l4 13l-1 28l-3 8l-6 6l-3 1l-5-1l-5-6l-4-15l0-18l1-1l1-10l3-6z"/>
      <path style="--i:3" d="M344 69l-6-2l-8 2l-7 6l-4 7l-1 0l1-2l-1-12l-13 5l-12 3l4 2l4 4l0 44l-5 6l-3 2l34 0l-4-2l-4-6l0-37l3-7l4-4l3 0l4 5l5 4l6-1l5-6l0-6z"/>
      <path style="--i:4" d="M375 67l-11 5l-10 11l-4 11l0 15l4 11l5 7l5 5l11 4l11 0l12-5l9-10l3-8l-2 0l-3 5l-9 7l-7 2l-8-1l-4-2l-7-10l-2-8l0-8l42 0l0-9l-5-12l-7-6l-9-4z"/>
      <path style="--i:5" d="M376 71l5-1l3 1l4 3l4 12l-1 8l-23-1l0-5l3-11z"/>
      <path style="--i:6" d="M141 140l-5 8l-8 8l-10 6l-6 1l-1 3l1 2l10-1l2 1l0 46l3 6l5 5l5 2l12 0l8-4l5-6l2-5l-2-1l-5 6l-5 2l-5-1l-4-6l-1-4l0-41l1-1l18 0l1-6l-19 0l-1-2z"/>
      <path style="--i:7" d="M172 143l5 2l3 3l0 69l-5 6l-3 2l35 0l-8-7l-1-5l0-34l4-10l6-4l9 1l5 4l2 8l0 36l-2 6l-7 5l36 0l-6-3l-3-6l0-41l-2-8l-7-7l-6-3l-14 0l-9 5l-6 8l0-35z"/>
      <path style="--i:8" d="M267 162l-7 8l0 6l2 4l4 2l6-1l4-4l2-13l4-3l7 0l6 4l2 8l0 9l-3 3l-17 5l-13 7l-5 6l-2 5l0 6l2 5l4 5l4 2l9 1l12-4l9-8l2 6l5 5l8 1l6-2l6-6l2-6l-2 0l-4 4l-4 0l-2-4l0-41l-3-7l-5-4l-10-4l-15 0l-8 2z"/>
      <path style="--i:9" d="M296 188l1 20l-1 3l-8 7l-5 1l-5-2l-2-3l-2-5l1-7l2-4l9-7l7-3z"/>
      <path style="--i:10" d="M397 165l-7-6l-10-3l-9 1l-5 2l-7 6l-2 5l-1 0l-1-13l-22 7l-3 0l1 2l4 1l3 3l0 47l-5 6l-3 2l35 0l-7-5l-2-6l0-36l3-8l7-5l9 1l5 4l2 7l0 38l-2 5l-6 5l36 0l-7-3l-3-7l0-41z"/>
      <path style="--i:11" d="M201 239l-5-1l-5 2l-4 3l-6 9l-2 0l-7-4l-9-2l-15 0l-8 2l-8 4l-8 7l-4 7l-1 11l5 11l9 9l-6 2l-5 4l-2 4l0 7l4 7l9 4l-11 3l-9 9l0 9l2 4l6 6l18 5l26 0l10-2l14-6l9-9l3-10l-1-7l-2-4l-10-8l-8-3l-11-1l-1-1l-32-1l-3-3l-1-3l4-5l14 4l11 0l12-3l8-4l8-8l4-9l-1-13l-8-12l5-2l7 4l5-1l5-6l0-4z"/>
      <path style="--i:12" d="M152 251l7 0l4 2l6 5l4 12l0 7l-4 11l-3 4l-7 4l-10-1l-6-7l-3-7l0-5l-1-1l1-10l3-7l5-5z"/>
      <path style="--i:13" d="M241 257l-26 8l4 2l4 3l1 5l0 37l-1 5l-5 5l-4 2l37 0l-5-2l-4-6z"/>
      <path style="--i:14" d="M231 231l-5 2l-2 2l-2 4l0 5l4 7l10 1l5-4l2-4l0-5l-5-6z"/>
      <path style="--i:15" d="M275 235l-7 7l-2 4l-1 12l-3 3l-5 1l-3 2l1 4l1-1l9 0l0 43l-3 10l-4 3l-2 0l0 1l37 0l-1-1l-3 0l-4-2l-3-7l0-48l8-1l16 0l0 46l2 6l5 6l7 3l9 0l5-2l7-7l3-6l-2-1l-4 5l-4 2l-4 0l-5-5l-1-3l0-45l3-1l14 0l1-6l-18-1l-1-19l-7 10l-11 10l-7 3l-15 0l-1-2l0-17l1-3l3-3l2 0l4 3l2 9l6 4l4-1l4-4l1-6l-5-6l-9-3l-9 0z"/>
      <path style="--i:16" d="M351 257l-2 22l2-1l7-11l9-6l3-1l16 0l1 1l-40 63l60 1l2-4l2-23l-8 11l-9 9l-7 3l-18 0l0-2l40-62z"/>
      <path style="--i:17" d="M127 337l2-5l4-4l4-2l28 1l13 4l6 5l1 6l-3 6l-6 5l-9 3l-16 1l-14-3l-6-4l-3-6z"/>
      </g>
    </svg>
  </div>
  <div class="mtg-loader-progress">
    <div class="mtg-loader-track" role="progressbar" aria-label="Loading the website" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
      <div class="mtg-loader-bar"></div>
    </div>
    <span class="mtg-loader-percent" aria-hidden="true">0%</span>
  </div>
</div>
<script>
  (function () {
    var loader = document.getElementById('mtgLoader');
    var COOKIE = 'mtg_visit';
    var isNewVisit = document.cookie.indexOf(COOKIE + '=') === -1;
    // The visit lasts until 30 minutes pass without opening a page
    document.cookie = COOKIE + '=1; path=/; max-age=1800; SameSite=Lax';
    // Without cookies every page would look like a new visit, so the screen is skipped
    if (!isNewVisit || document.cookie.indexOf(COOKIE + '=') === -1) {
      loader.parentNode.removeChild(loader);
      return;
    }

    var MIN_TIME = 2900; // long enough for the boxes to become the logo
    var MAX_TIME = 6000; // a slow picture never keeps the visitor waiting longer than this
    var ZOOM_TIME = 1000;
    var track = loader.querySelector('.mtg-loader-track');
    var bar = loader.querySelector('.mtg-loader-bar');
    var percent = loader.querySelector('.mtg-loader-percent');
    var root = document.documentElement;
    var start = Date.now();
    var progress = 0;
    var loaded = false;

    loader.querySelectorAll('path').forEach(function (path) { path.setAttribute('pathLength', '1'); });
    loader.hidden = false;
    root.classList.add('mtg-loading');
    window.addEventListener('load', function () { loaded = true; });

    var timer = setInterval(function () {
      var elapsed = Date.now() - start;
      if (elapsed >= MAX_TIME) { loaded = true; }
      // Eases towards 90% while the page loads, and only reaches 100% once it has
      var target = loaded ? 100 : 90 * (1 - Math.exp(-elapsed / 1500));
      progress = Math.max(progress, Math.min(target, 100 * elapsed / MIN_TIME));
      bar.style.width = progress + '%';
      percent.textContent = Math.floor(progress) + '%';
      track.setAttribute('aria-valuenow', Math.floor(progress));
      if (progress < 100) { return; }

      clearInterval(timer);
      setTimeout(function () {
        root.classList.remove('mtg-loading');
        var calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var headerLogo = document.querySelector('.mh-logo img');
        var to = headerLogo ? headerLogo.getBoundingClientRect() : null;
        var canLand = to && to.width > 0 && to.bottom > 0 && to.top < window.innerHeight;
        if (canLand && !calm) {
          // The logo travels to its place in the header, where the real one takes over when it lands
          var art = loader.querySelector('.mtg-loader-art');
          var from = art.getBoundingClientRect();
          var moveX = (to.left + to.width / 2) - (from.left + from.width / 2);
          var moveY = (to.top + to.height / 2) - (from.top + from.height / 2);
          headerLogo.style.visibility = 'hidden';
          art.style.transform = 'translate(' + moveX + 'px, ' + moveY + 'px) scale(' + (to.width / from.width) + ')';
        } else if (!calm) {
          loader.classList.add('no-target');
        }
        loader.classList.add('is-done');
        setTimeout(function () {
          if (headerLogo) { headerLogo.style.visibility = ''; }
          if (loader.parentNode) { loader.parentNode.removeChild(loader); }
        }, ZOOM_TIME + 30);
      }, 200);
    }, 40);
  })();
</script>
