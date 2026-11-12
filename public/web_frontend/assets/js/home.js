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
