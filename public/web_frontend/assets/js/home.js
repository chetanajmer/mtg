// Hero gift box (home page only), one 14s loop timed in milliseconds:
//   0-1.25s     the box drops in from above the hero, lands with a squash and a little bounce
//   1.3-2.5s    it spins round twice, with a small hop
//   2.6s        the lid bursts up; confetti, ribbons and sparks pop out
//   2.8-5.0s    the products fly out in arcs to their places on the right, then rest
//   8.6-10.3s   they fly back in; 10.8s the lid drops back on
//   12.1-12.9s  the box shoots back up out of view, ready to drop again
// While the lid is off, a fountain of confetti, ribbons and stars streams out of the box and falls inside the hero.
// Paths are measured from the page and rebuilt when the window size changes. Reduced motion: no movement,
// the products simply stay in their places. Small screens hide the box (home.css); the fountain then comes
// from the middle of the hero.
window.addEventListener('load', function() {
  // The search and category pages share #homePage; only the home page has the hero
  const hero = document.querySelector('#homePage > .mh-hero');
  if (!hero || !hero.animate || window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }
  const root = hero.querySelector('.mh-gift');
  const box = root.querySelector('.mh-gift-box');
  const base = root.querySelector('.mh-gift-base');
  const lid = root.querySelector('.mh-gift-lid');
  const glow = root.querySelector('.mh-gift-glow');
  const burst = root.querySelector('.mh-gift-burst');
  const items = Array.from(root.querySelectorAll('.mh-gift-item'));
  const LOOP = 14000;
  const OPEN = [2600, 10600]; // the lid is off between these times: the fountain runs
  const ns = 'http://www.w3.org/2000/svg';
  const gold = ['#d4a43c', '#e9c46a', '#f6e7c1', '#b8862b', '#1d1d1f', '#c9a24a', '#fff8e6'];
  const ribbonGold = ['#d4a43c', '#f3dfae', '#1d1d1f', '#e9c46a', '#b8862b', '#fff3d6', '#d4a43c', '#2b2b2e'];
  let running = [];
  let seed = 7;
  function random() { seed = (seed * 9301 + 49297) % 233280; return seed / 233280; } // same pattern every load
  function k(ms) { return ms / LOOP; }

  function ribbon(className, i) {
    const svg = document.createElementNS(ns, 'svg');
    svg.setAttribute('class', className);
    svg.setAttribute('viewBox', '0 0 40 140');
    const path = document.createElementNS(ns, 'path');
    path.setAttribute('d', i % 2 ? 'M20 138 C 4 122, 36 108, 20 92 S 2 64, 20 50 S 38 22, 18 4' : 'M20 138 C 36 124, 4 110, 20 94 S 38 66, 20 52 S 2 24, 22 4');
    path.setAttribute('stroke', ribbonGold[i % ribbonGold.length]);
    path.setAttribute('pathLength', '100');
    svg.appendChild(path);
    return svg;
  }

  // Sparks, confetti and ribbon streamers that pop out of the box as the lid bursts
  for (let i = 0; i < 24; i++) {
    const spark = document.createElement('span');
    if (i % 3 === 1) { spark.className = 'dot'; }
    burst.appendChild(spark);
  }
  const party = document.createElement('div');
  party.className = 'mh-gift-party';
  box.appendChild(party);
  const confetti = [];
  for (let i = 0; i < 42; i++) {
    const piece = document.createElement('span');
    piece.className = 'mh-confetti' + (i % 5 === 0 ? ' round' : (i % 4 === 0 ? ' long' : ''));
    piece.style.background = gold[i % gold.length];
    party.appendChild(piece);
    confetti.push({ el: piece, angle: -165 + random() * 150, speed: .55 + random() * .6, spin: 360 + random() * 720, flip: 540 + random() * 720, delay: random() * 150 });
  }
  const streamers = ribbonGold.map(function(colour, i) {
    const svg = ribbon('mh-ribbon', i);
    party.appendChild(svg);
    return { el: svg, path: svg.firstChild, angle: -70 + i * (140 / (ribbonGold.length - 1)) + (random() - .5) * 10, lift: .75 + random() * .45, delay: (i % 3) * 50 };
  });

  // Each step's easing goes on the step; the loop's own clock stays linear so the times hold
  function play(el, frames, easing) {
    frames.forEach(function(frame) { if (!frame.easing) { frame.easing = easing || 'linear'; } });
    const anim = el.animate(frames, { duration: LOOP, iterations: Infinity });
    running.push(anim);
    return anim;
  }

  function start() {
    running.forEach(function(a) { a.cancel(); });
    running = [];
    root.mhLoop = null;
    if (getComputedStyle(root).display === 'none') { return; }

    const h = hero.getBoundingClientRect();
    const b = box.getBoundingClientRect();
    const lidHeight = lid.getBoundingClientRect().height;
    const opening = base.getBoundingClientRect();
    const ox = opening.left + opening.width / 2;
    const oy = opening.top + opening.height * .15;
    const unit = h.width / 1440; // distances scale with the hero
    const above = -(b.bottom - h.top + lidHeight * 1.6 + 30); // fully out of view above the hero
    const hop = -b.height * .08;
    function pose(y, sx, sy, turn) { return 'translateY(' + y.toFixed(1) + 'px) scale(' + sx + ', ' + sy + ') perspective(900px) rotateY(' + turn + 'deg)'; }

    // The box: drop, land, spin twice, squash as it bursts, bounce when the lid lands, shoot back up
    root.mhLoop = play(box, [
      { transform: pose(above, .9, 1.1, 0), offset: 0, easing: 'cubic-bezier(.55, 0, 1, .45)' },
      { transform: pose(0, 1.14, .84, 0), offset: k(800), easing: 'ease-out' },
      { transform: pose(hop * 1.6, .95, 1.07, 0), offset: k(980), easing: 'ease-in' },
      { transform: pose(0, 1.05, .95, 0), offset: k(1120), easing: 'ease-out' },
      { transform: pose(0, 1, 1, 0), offset: k(1300), easing: 'cubic-bezier(.45, 0, .55, 1)' },
      { transform: pose(hop, 1, 1, 360), offset: k(1900), easing: 'cubic-bezier(.45, 0, .55, 1)' },
      { transform: pose(0, 1, 1, 720), offset: k(2500), easing: 'ease-out' },
      { transform: pose(0, 1.07, .93, 720), offset: k(2580), easing: 'ease-out' },
      { transform: pose(0, .96, 1.05, 720), offset: k(2720), easing: 'ease-in-out' },
      { transform: pose(0, 1, 1, 720), offset: k(2900) },
      { transform: pose(0, 1, 1, 720), offset: k(10800), easing: 'ease-out' },
      { transform: pose(0, 1.05, .95, 720), offset: k(10950), easing: 'ease-in-out' },
      { transform: pose(0, 1, 1, 720), offset: k(11150) },
      { transform: pose(0, 1, 1, 720), offset: k(12100), easing: 'ease-out' },
      { transform: pose(0, 1.08, .92, 720), offset: k(12280), easing: 'cubic-bezier(.4, 0, 1, 1)' },
      { transform: pose(above, .9, 1.1, 720), offset: k(12900) },
      { transform: pose(above, .9, 1.1, 720), offset: 1 }
    ]);

    // The lid bursts up, hovers above the open box, and drops back on
    play(lid, [
      { transform: 'translateY(0%) rotate(0deg)', offset: 0 }, { transform: 'translateY(0%) rotate(0deg)', offset: k(2580), easing: 'cubic-bezier(.2, .8, .3, 1)' },
      { transform: 'translateY(-160%) rotate(-12deg)', offset: k(2950), easing: 'ease-in-out' }, { transform: 'translateY(-125%) rotate(-7deg)', offset: k(3400), easing: 'ease-in-out' },
      { transform: 'translateY(-140%) rotate(-5deg)', offset: k(6800), easing: 'ease-in-out' }, { transform: 'translateY(-125%) rotate(-7deg)', offset: k(10300), easing: 'ease-in' },
      { transform: 'translateY(0%) rotate(0deg)', offset: k(10800), easing: 'ease-out' }, { transform: 'translateY(-8%) rotate(0deg)', offset: k(10950), easing: 'ease-in' },
      { transform: 'translateY(0%) rotate(0deg)', offset: k(11150) }, { transform: 'translateY(0%) rotate(0deg)', offset: 1 }
    ]);

    play(glow, [
      { opacity: 0, offset: 0 }, { opacity: 0, offset: k(2550) }, { opacity: 1, offset: k(2900) }, { opacity: .55, offset: k(4200) },
      { opacity: .55, offset: k(9800) }, { opacity: 0, offset: k(11000) }, { opacity: 0, offset: 1 }
    ]);

    // Sparks fly out of the box as it bursts
    Array.from(burst.children).forEach(function(spark, i) {
      const angle = (i / burst.children.length) * Math.PI * 2 + (i % 2 ? .2 : -.15);
      const dist = (140 + (i * 53) % 220) * unit;
      const x = Math.cos(angle) * dist * 1.3;
      const y = Math.sin(angle) * dist * .6 - 40 * unit;
      const rest = 'translate(0px, 0px) scale(.2) rotate(0deg)';
      play(spark, [
        { opacity: 0, transform: rest, offset: 0 }, { opacity: 0, transform: rest, offset: k(2560) },
        { opacity: 1, transform: 'translate(0px, 0px) scale(.6) rotate(0deg)', offset: k(2620), easing: 'ease-out' },
        { opacity: 0, transform: 'translate(' + x + 'px, ' + y + 'px) scale(1.2) rotate(160deg)', offset: k(3900) },
        { opacity: 0, transform: rest, offset: 1 }
      ]);
    });

    // Party popper: confetti shoots up and out of the box, tumbles as it falls, then fades
    confetti.forEach(function(c) {
      const rad = c.angle * Math.PI / 180;
      const reach = h.height * c.speed;
      const peakX = Math.cos(rad) * reach * .9;
      const peakY = Math.sin(rad) * reach;
      const endX = Math.cos(rad) * reach * 1.5;
      const endY = peakY + h.height * .55;
      const t0 = 2580 + c.delay;
      const hidden = 'translate(0px, 0px) rotate(0deg) rotateX(0deg) scale(.4)';
      play(c.el, [
        { opacity: 0, transform: hidden, offset: 0 }, { opacity: 0, transform: hidden, offset: k(t0) },
        { opacity: 1, transform: hidden, offset: k(t0 + 50), easing: 'cubic-bezier(.15, .75, .35, 1)' },
        { opacity: 1, transform: 'translate(' + peakX + 'px, ' + peakY + 'px) rotate(' + (c.spin * .4) + 'deg) rotateX(' + (c.flip * .4) + 'deg) scale(1)', offset: k(t0 + 650), easing: 'cubic-bezier(.4, 0, .8, .6)' },
        { opacity: 1, transform: 'translate(' + ((peakX + endX) / 2) + 'px, ' + ((peakY + endY) / 2) + 'px) rotate(' + (c.spin * .75) + 'deg) rotateX(' + (c.flip * .75) + 'deg) scale(1)', offset: k(t0 + 1700) },
        { opacity: 0, transform: 'translate(' + endX + 'px, ' + endY + 'px) rotate(' + c.spin + 'deg) rotateX(' + c.flip + 'deg) scale(.9)', offset: k(t0 + 2650) },
        { opacity: 0, transform: hidden, offset: 1 }
      ]);
    });

    // Ribbon streamers unfurl out of the box, curl outward, drift and fade
    streamers.forEach(function(r) {
      const t0 = 2600 + r.delay;
      const rise = -h.height * .42 * r.lift;
      const side = r.angle * 2.2 * unit;
      const startPose = 'translate(0px, 0px) rotate(' + (r.angle * .3) + 'deg) scale(.2)';
      play(r.el, [
        { opacity: 0, transform: startPose, offset: 0 }, { opacity: 0, transform: startPose, offset: k(t0) },
        { opacity: 1, transform: startPose, offset: k(t0 + 50), easing: 'cubic-bezier(.2, .8, .3, 1)' },
        { opacity: 1, transform: 'translate(' + side + 'px, ' + rise + 'px) rotate(' + r.angle + 'deg) scale(1)', offset: k(t0 + 720), easing: 'ease-in-out' },
        { opacity: 1, transform: 'translate(' + (side * 1.35) + 'px, ' + (rise * .7) + 'px) rotate(' + (r.angle * 1.3) + 'deg) scale(1)', offset: k(t0 + 1920) },
        { opacity: 0, transform: 'translate(' + (side * 1.5) + 'px, ' + (rise * .35) + 'px) rotate(' + (r.angle * 1.45) + 'deg) scale(.95)', offset: k(t0 + 2880) },
        { opacity: 0, transform: startPose, offset: 1 }
      ]);
      play(r.path, [
        { strokeDashoffset: 100, offset: 0 }, { strokeDashoffset: 100, offset: k(t0) },
        { strokeDashoffset: 0, offset: k(t0 + 660), easing: 'ease-out' }, { strokeDashoffset: 0, offset: 1 }
      ]);
    });

    // Each product: out of the box in an arc to its place, rest, then back in before the lid lands
    items.forEach(function(item, i) {
      const r = item.getBoundingClientRect();
      const dx = ox - (r.left + r.width / 2);
      const dy = oy - (r.top + r.height / 2);
      const arc = h.height * .35;
      const inBox = 'translate(' + dx + 'px, ' + dy + 'px) scale(.12)';
      const mid = 'translate(' + (dx * .45) + 'px, ' + (dy * .55 - arc) + 'px) scale(.6)';
      const s = i * 300;
      play(item, [
        { opacity: 0, transform: inBox, offset: 0 }, { opacity: 0, transform: inBox, offset: k(2750 + s) },
        { opacity: 1, transform: inBox, offset: k(2800 + s) }, { opacity: 1, transform: mid, offset: k(3450 + s) },
        { opacity: 1, transform: 'translate(0px, ' + (-8 * unit) + 'px) scale(1.04)', offset: k(4100 + s) },
        { opacity: 1, transform: 'translate(0px, 0px) scale(1)', offset: k(4400 + s) }, { opacity: 1, transform: 'translate(0px, 0px) scale(1)', offset: k(9200 - s) },
        { opacity: 1, transform: 'translate(0px, ' + (-14 * unit) + 'px) scale(1.02)', offset: k(9450 - s) },
        { opacity: 1, transform: mid, offset: k(9900 - s) }, { opacity: 1, transform: inBox, offset: k(10450 - s) },
        { opacity: 0, transform: inBox, offset: k(10500 - s) }, { opacity: 0, transform: inBox, offset: 1 }
      ], 'ease-in-out');
    });
  }

  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(start, 250);
  });
  start();

  // Fountain: confetti, ribbons and stars stream out of the open box and fall inside the hero, then relaunch
  const layer = document.createElement('div');
  layer.className = 'mh-fall';
  layer.setAttribute('aria-hidden', 'true');
  hero.appendChild(layer);
  const count = window.matchMedia('(max-width: 767px)').matches ? 18 : 44;

  // From the box opening, or the middle of the hero when the box isn't shown (small screens)
  function origin() {
    const hr = hero.getBoundingClientRect();
    const op = base.getBoundingClientRect();
    if (root.mhLoop && op.width) { return { x: op.left - hr.left + op.width / 2, y: op.top - hr.top + op.height * .1, w: op.width }; }
    return { x: hr.width / 2, y: hr.height * .62, w: hr.width * .2 };
  }

  function boxOpen() {
    if (!root.mhLoop || root.mhLoop.currentTime === null) { return true; }
    const t = root.mhLoop.currentTime % LOOP;
    return t > OPEN[0] && t < OPEN[1];
  }

  function launch(p) {
    if (!boxOpen()) {
      p.timer = setTimeout(function() { launch(p); }, 250 + Math.random() * 600);
      return;
    }
    const o = origin();
    const w = hero.clientWidth;
    const hh = hero.clientHeight;
    const side = Math.random() * 2 - 1;
    const x0 = o.x + (Math.random() - .5) * o.w * .5;
    const rise = (.2 + Math.random() * .35) * hh * (p.kind === 'ribbon' ? 1.15 : 1);
    const peakX = x0 + side * w * (.1 + Math.random() * .16);
    const endX = x0 + side * w * (.25 + Math.random() * .3) + (Math.random() - .5) * 120;
    const endY = hh + 40;
    const sway = 20 + Math.random() * 35;
    const spin = (p.kind === 'ribbon' ? 120 : 360) + Math.random() * 540;
    const flip = p.kind === 'confetti' ? 360 + Math.random() * 720 : 0;
    const fall = endY - (o.y - rise);
    const duration = (p.kind === 'ribbon' ? 1.4 : 1) * (1800 + fall * (4 + Math.random() * 2.5));
    function at(x, y, turn, size) { return 'translate(' + x.toFixed(1) + 'px, ' + y.toFixed(1) + 'px) rotate(' + turn.toFixed(0) + 'deg) rotateX(' + (flip * turn / spin || 0).toFixed(0) + 'deg) scale(' + size + ')'; }
    const twinkle = p.kind === 'star' ? .55 : 1;
    p.anim = p.el.animate([
      { opacity: 0, transform: at(x0, o.y, 0, .3), offset: 0 },
      { opacity: 1, transform: at(x0, o.y - 10, spin * .02, .8), offset: .02, easing: 'cubic-bezier(.15, .8, .35, 1)' },
      { opacity: 1, transform: at(peakX, o.y - rise, spin * .25, 1), offset: .22, easing: 'cubic-bezier(.45, 0, .85, .55)' },
      { opacity: 1, transform: at(peakX + (endX - peakX) * .35 - sway, o.y - rise + fall * .3, spin * .5, twinkle), offset: .5 },
      { opacity: 1, transform: at(peakX + (endX - peakX) * .7 + sway, o.y - rise + fall * .68, spin * .8, 1), offset: .8 },
      { opacity: .85, transform: at(endX, endY, spin, twinkle), offset: 1 }
    ], { duration: duration });
    p.anim.onfinish = function() { p.timer = setTimeout(function() { launch(p); }, Math.random() * 400); };
  }

  for (let i = 0; i < count; i++) {
    let el;
    let kind = 'confetti';
    if (i % 6 === 5) {
      kind = 'ribbon';
      el = ribbon('mh-fall-piece mh-fall-ribbon', i);
    } else if (i % 6 === 2) {
      kind = 'star';
      el = document.createElement('span');
      el.className = 'mh-fall-piece mh-fall-star' + (i % 4 === 2 ? ' glint' : '');
    } else {
      el = document.createElement('span');
      el.className = 'mh-fall-piece mh-fall-confetti' + (i % 5 === 0 ? ' round' : (i % 4 === 0 ? ' long' : ''));
      el.style.background = gold[i % gold.length];
    }
    layer.appendChild(el);
    const piece = { el: el, kind: kind };
    // Staggered first launches, so the fountain builds up instead of firing all at once
    piece.timer = setTimeout(function() { launch(piece); }, i * 120 + Math.random() * 300);
  }
});

document.addEventListener('DOMContentLoaded', function() {
  const toastBox = document.getElementById('homeToast');
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  let toastTimer;

  function toast(message) {
    if (!toastBox) { return; }
    toastBox.textContent = message;
    toastBox.hidden = false;
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function() { toastBox.hidden = true; }, 3500);
  }

  function post(url, body) {
    return fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify(body)
    });
  }

  // Header menus close when something else is clicked
  document.addEventListener('click', function(event) {
    document.querySelectorAll('.mh-menu[open], .mh-categories[open]').forEach(function(menu) {
      if (!menu.contains(event.target)) { menu.open = false; }
    });
  });

  // Things that arrive later
  document.querySelectorAll('[data-soon]').forEach(function(button) {
    button.addEventListener('click', function() { toast(this.dataset.soon); });
  });

  const searchInput = document.getElementById('homeSearch');
  document.querySelectorAll('[data-focus-search]').forEach(function(button) {
    button.addEventListener('click', function() { searchInput.focus(); });
  });

  // Voice search, where the browser supports it
  const voiceButton = document.getElementById('voiceSearch');
  if (voiceButton) {
    const Recognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!Recognition) {
      voiceButton.addEventListener('click', function() { toast('Voice search isn\'t available in this browser.'); });
    } else {
      voiceButton.addEventListener('click', function() {
        const recognition = new Recognition();
        recognition.onstart = function() { voiceButton.classList.add('listening'); };
        recognition.onend = function() { voiceButton.classList.remove('listening'); };
        recognition.onerror = function() { toast('We couldn\'t hear that. Please try again or type your search.'); };
        recognition.onresult = function(event) {
          searchInput.value = event.results[0][0].transcript;
          searchInput.form.submit();
        };
        recognition.start();
      });
    }
  }

  // New arrivals: every few seconds the row slides on by a full set of cards (5 on a
  // desktop screen, fewer on smaller ones), looping back to the start. A card that
  // leaves on the left joins the end of the row, with copies added when too few
  // products would otherwise leave a gap.
  const track = document.getElementById('arrivalsTrack');
  if (track && track.children.length > 1) {
    const prev = document.getElementById('arrivalsPrev');
    const next = document.getElementById('arrivalsNext');
    const dotsBox = document.getElementById('arrivalsDots');
    const carousel = track.closest('.mh-carousel');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const products = Array.from(track.children);
    const count = products.length;
    const SLIDE_EVERY_MS = 5000;
    let offset = 0;
    let nudge = 0; // distance still to travel after a slide
    let stepSize = 0;
    let perSlide = 0;
    let pages = 1;
    let page = 0;
    let lastSlide = Date.now();
    let hovering = false;
    let touching = false;
    let lastFrame = null;

    products.forEach(function(card, i) {
      card.dataset.slide = i;
    });

    function addCopy() {
      products.forEach(function(card) {
        const copy = card.cloneNode(true);
        copy.setAttribute('aria-hidden', 'true');
        copy.querySelectorAll('a, button').forEach(function(el) { el.tabIndex = -1; });
        track.appendChild(copy);
      });
    }

    function refreshDots() {
      Array.from(dotsBox.children).forEach(function(dot, i) {
        dot.classList.toggle('active', i === page);
        dot.setAttribute('aria-current', i === page ? 'true' : 'false');
      });
    }

    function buildDots() {
      dotsBox.innerHTML = '';
      if (pages < 2) { return; }
      for (let i = 0; i < pages; i++) {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.setAttribute('aria-label', 'Show set ' + (i + 1) + ' of ' + pages);
        dot.addEventListener('click', function() { goToPage(i, i < page ? -1 : 1); });
        dotsBox.appendChild(dot);
      }
      refreshDots();
    }

    function measure() {
      const width = track.children[0].getBoundingClientRect().width;
      if (width <= 0) { return; } // hidden for now: keep the last measurement
      const styles = getComputedStyle(track);
      const gap = parseFloat(styles.columnGap) || 0;
      const inner = track.clientWidth - parseFloat(styles.paddingLeft) - parseFloat(styles.paddingRight);
      stepSize = width + gap;
      const fits = Math.max(1, Math.floor((inner + gap + 1) / stepSize));

      if (fits !== perSlide) {
        perSlide = fits;
        pages = count > perSlide ? Math.ceil(count / perSlide) : 1;
        page = 0;
        dotsBox.hidden = pages < 2;
        buildDots();
      }
      // Always enough cards to fill the row while it moves
      while (track.children.length * stepSize < track.clientWidth + stepSize * (perSlide + 1)) { addCopy(); }
      // Back to the start of the current set, in case the card size changed
      nudge = 0;
      offset = 0;
      const start = page * perSlide;
      let guard = track.children.length;
      while (Number(track.children[0].dataset.slide) !== start && guard-- > 0) {
        track.appendChild(track.children[0]);
      }
      track.style.setProperty('--shift', '0px');
    }

    // Moves to a set of cards, forwards or backwards, always the short way round the loop
    function goToPage(target, direction) {
      if (pages < 2 || target === page) { return; }
      const from = page * perSlide;
      const to = target * perSlide;
      const cards = direction > 0 ? (to - from + count) % count : (from - to + count) % count;
      nudge += direction * cards * stepSize;
      page = target;
      lastSlide = Date.now();
      refreshDots();
    }

    // With no more products than fit in the row, it still slides, round to the same products
    function slideRound(direction) {
      nudge += direction * perSlide * stepSize;
      lastSlide = Date.now();
    }

    function slideNext() {
      if (pages < 2) { slideRound(1); } else { goToPage((page + 1) % pages, 1); }
    }

    function slidePrev() {
      if (pages < 2) { slideRound(-1); } else { goToPage((page - 1 + pages) % pages, -1); }
    }

    function isHeld() {
      return hovering || touching || carousel.querySelector(':focus-visible') !== null;
    }

    function place() {
      if (stepSize <= 0) { return; }
      while (offset >= stepSize) {
        track.appendChild(track.children[0]);
        offset -= stepSize;
      }
      while (offset < 0) {
        track.insertBefore(track.children[track.children.length - 1], track.children[0]);
        offset += stepSize;
      }
      track.style.setProperty('--shift', (-offset) + 'px');
    }

    function frame(now) {
      const seconds = lastFrame === null ? 0 : Math.min((now - lastFrame) / 1000, 0.05);
      lastFrame = now;
      if (nudge !== 0) {
        const part = reducedMotion || Math.abs(nudge) < 0.5 ? nudge : nudge * Math.min(1, seconds * 6);
        offset += part;
        nudge -= part;
        if (nudge === 0) { offset = Math.round(offset / stepSize) * stepSize; } // land exactly on a card
        place();
      }
      requestAnimationFrame(frame);
    }

    next.addEventListener('click', slideNext);
    prev.addEventListener('click', slidePrev);

    // Holds still only while the mouse is on a card, so it can be clicked
    track.addEventListener('pointerenter', function(event) {
      if (event.pointerType === 'mouse') { hovering = true; }
    });
    track.addEventListener('pointerleave', function(event) {
      if (event.pointerType === 'mouse') { hovering = false; }
    });

    // Swipe on touch screens
    let touchX = null;
    let touchY = null;
    track.addEventListener('touchstart', function(event) {
      touchX = event.touches[0].clientX;
      touchY = event.touches[0].clientY;
      touching = true;
    }, { passive: true });
    track.addEventListener('touchend', function(event) {
      touching = false;
      if (touchX === null) { return; }
      const dx = event.changedTouches[0].clientX - touchX;
      const dy = event.changedTouches[0].clientY - touchY;
      touchX = null;
      if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) {
        if (dx < 0) { slideNext(); } else { slidePrev(); }
      }
    }, { passive: true });
    track.addEventListener('touchcancel', function() { touching = false; touchX = null; }, { passive: true });

    setInterval(function() {
      if (!isHeld() && !document.hidden && Date.now() - lastSlide >= SLIDE_EVERY_MS) { slideNext(); }
    }, 250);

    window.addEventListener('resize', measure);
    window.addEventListener('load', measure);
    measure();
    requestAnimationFrame(frame);
  }

  // Our Brands and Our Clientele: each row of logos moves in a never-ending loop
  document.querySelectorAll('.mh-logo-row').forEach(function(row) {
    const logos = Array.from(row.children);
    if (!logos.length) { return; }
    const SPEED = 30; // pixels per second
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let offset = 0;
    let stepSize = 0;
    let hovering = false;
    let lastFrame = null;

    function measure() {
      const width = row.children[0].getBoundingClientRect().width;
      if (width <= 0) { return; } // hidden for now: keep the last measurement
      stepSize = width;
      // Always one logo more than fits, so no gap shows as the row moves
      while (row.children.length * stepSize < row.clientWidth + stepSize * 2) {
        logos.forEach(function(logo) {
          const copy = logo.cloneNode(true);
          copy.alt = '';
          copy.removeAttribute('title');
          copy.loading = 'eager';
          row.appendChild(copy);
        });
      }
    }

    function move(distance) {
      offset += distance;
      while (stepSize > 0 && offset >= stepSize) {
        row.appendChild(row.children[0]);
        offset -= stepSize;
      }
      row.style.setProperty('--shift', (-offset) + 'px');
    }

    function frame(now) {
      const seconds = lastFrame === null ? 0 : Math.min((now - lastFrame) / 1000, 0.05);
      lastFrame = now;
      if (!hovering) { move(SPEED * seconds); }
      requestAnimationFrame(frame);
    }

    row.addEventListener('pointerenter', function(event) {
      if (event.pointerType === 'mouse') { hovering = true; }
    });
    row.addEventListener('pointerleave', function(event) {
      if (event.pointerType === 'mouse') { hovering = false; }
    });
    window.addEventListener('resize', measure);
    window.addEventListener('load', measure);
    measure();

    // With reduced motion the row changes one logo at a time, without moving
    if (reducedMotion) {
      setInterval(function() {
        if (!hovering && !document.hidden) { move(stepSize); }
      }, 3500);
    } else {
      requestAnimationFrame(frame);
    }
  });

  // Newsletter
  const newsletter = document.getElementById('newsletterForm');
  if (newsletter) {
    const message = document.getElementById('newsletterMsg');
    newsletter.addEventListener('submit', function(event) {
      event.preventDefault();
      const input = newsletter.elements.email;
      message.hidden = false;
      if (!input.checkValidity()) {
        message.textContent = 'Please enter a valid email address.';
        return;
      }
      post(newsletter.dataset.url, { email: input.value.trim() })
        .then(function(response) {
          if (!response.ok) { throw new Error('Request failed'); }
          return response.text();
        })
        .then(function(text) {
          message.textContent = text;
          input.value = '';
        })
        .catch(function() { message.textContent = 'Something went wrong. Please try again.'; });
    });
  }
});
