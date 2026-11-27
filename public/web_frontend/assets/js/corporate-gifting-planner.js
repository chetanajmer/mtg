document.addEventListener('DOMContentLoaded', function() {
  const planner = document.getElementById('budgetPlanner');
  const grid = document.getElementById('bundlesGrid');
  const empty = document.getElementById('plannerEmpty');
  const error = document.getElementById('plannerError');
  const subtitle = document.getElementById('plannerSubtitle');
  const showBtn = document.getElementById('showRecommendations');
  const moreBtn = document.getElementById('viewMore');
  const peopleInput = document.getElementById('teamSize');
  const budgetInput = document.getElementById('budgetAmount');
  const budgetHint = document.getElementById('budgetHint');
  const productsGrid = document.getElementById('productsGrid');
  const showLabel = showBtn.innerHTML;
  const moreLabel = moreBtn.innerHTML;
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  const loggedIn = planner.dataset.loggedIn === '1';
  const RESUME_KEY = 'plannerResume';
  let round = 0;
  let customOccasion = '';
  let budgetType = 'person';
  // The choices the results on screen were made for; adding to the quote uses the same number of people
  let current = null;
  // Bundles appear only after the customer asks for them with Show Recommendations
  let shown = false;

  // These buttons need an account. The visitor's choices wait here, so they come back to the same page
  function askToLogin(action) {
    try {
      sessionStorage.setItem(RESUME_KEY, JSON.stringify({
        action: action,
        occasion: document.querySelector('.bp-occasion.active').dataset.occasion,
        customOccasion: customOccasion,
        people: peopleInput.value,
        budget: budgetInput.value,
        budgetType: budgetType,
        preferences: Array.from(document.querySelectorAll('.bp-chip.active')).map(function(chip) { return chip.dataset.pref; })
      }));
    } catch (e) {
      // Private browsing can refuse this: they just choose again after logging in
    }
    window.location.href = planner.dataset.loginUrl;
  }

  function postJson(url, body) {
    return fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify(body)
    }).then(function(response) {
      if (!response.ok) { throw new Error('Request failed'); }
      return response.json();
    });
  }

  function el(tag, className, text) {
    const node = document.createElement(tag);
    if (className) { node.className = className; }
    if (text !== undefined) { node.textContent = text; }
    return node;
  }

  function amount(value) {
    return value.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
  }

  function money(value) {
    return 'AED ' + value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  // The typed choices, or null with a message when one is missing
  function choices() {
    const people = parseInt(peopleInput.value, 10);
    const budget = parseFloat(budgetInput.value);
    if (!(people >= 1) || String(people) !== peopleInput.value.trim()) {
      return { error: 'Please enter how many people the gifts are for.', field: peopleInput };
    }
    if (!(budget > 0)) {
      return { error: 'Please enter your budget in AED.', field: budgetInput };
    }
    const perPerson = budgetType === 'total' ? budget / people : budget;
    if (perPerson < 1) {
      return { error: 'That is under AED 1 per person. Please raise the budget.', field: budgetInput };
    }
    return { people: people, budget: budget, budget_type: budgetType, perPerson: perPerson };
  }

  // Under the budget: what it comes to per person, or for everyone
  function updateHint() {
    const c = choices();
    if (c.error) {
      budgetHint.textContent = budgetType === 'total' ? 'Your whole budget, shared by everyone.' : 'What you want to spend on each person.';
      return;
    }
    const who = c.people + (c.people === 1 ? ' person' : ' people');
    budgetHint.textContent = budgetType === 'total'
      ? 'About ' + money(Math.floor(c.perPerson * 100) / 100) + ' per person for ' + who + '.'
      : money(c.budget * c.people) + ' in total for ' + who + '.';
  }

  // A missing choice says what to fix; anything else is a general message
  function errorText(e) {
    return e && e.message && e.message !== 'Request failed' && e.message !== 'Failed to fetch' ? e.message : 'Something went wrong. Please try again.';
  }

  function showError(message) {
    error.textContent = message;
    error.hidden = !message;
  }

  function photo(item) {
    if (!item.image) {
      const blank = el('div', 'bp-photo-blank');
      blank.appendChild(el('i', 'bi bi-gift'));
      return blank;
    }
    const image = el('img');
    image.src = item.image;
    image.alt = item.name;
    image.loading = 'lazy';
    return image;
  }

  function bundlePicture(url, name) {
    const frame = el('figure', 'bp-picture');
    const image = el('img');
    image.src = url;
    image.alt = name;
    frame.appendChild(image);
    frame.appendChild(el('figcaption', '', 'Illustrative image'));
    return frame;
  }

  // Creating a picture takes a while, so the card shows first and the picture follows
  // Loading scene while the picture is made: gifts drop into a box, with steps that name the occasion
  function creatingScene() {
    const scene = el('div', 'bp-gen');
    scene.setAttribute('role', 'status');
    const stage = el('div', 'bp-gen-stage');
    stage.setAttribute('aria-hidden', 'true');
    ['bi-cup-hot', 'bi-journal-bookmark', 'bi-gift'].forEach(function(icon) {
      const item = el('span', 'bp-gen-item');
      item.appendChild(el('i', 'bi ' + icon));
      stage.appendChild(item);
    });
    stage.appendChild(el('span', 'bp-gen-box'));
    stage.appendChild(el('span', 'bp-gen-lid'));
    ['s1', 's2', 's3'].forEach(function(s) { stage.appendChild(el('span', 'bp-gen-spark ' + s)); });
    scene.appendChild(stage);

    const occasion = document.getElementById('selectedOccasion').textContent;
    const steps = ['Unpacking your products…', 'Arranging the bundle…', 'Setting the ' + occasion + ' scene…'];
    if (brand.logo) { steps.push('Adding your logo…'); }
    steps.push('Adding the final touches…');
    const text = el('div', 'bp-gen-text');
    const line = el('strong', '', steps[0]);
    text.appendChild(line);
    text.appendChild(el('small', '', 'Creating your bundle picture'));
    scene.appendChild(text);

    const progress = el('div', 'bp-gen-progress');
    const fill = el('span');
    progress.appendChild(fill);
    scene.appendChild(progress);

    let step = 0;
    const timer = setInterval(function() {
      if (step >= steps.length - 1) { return; }
      line.classList.add('out');
      setTimeout(function() { line.textContent = steps[++step]; line.classList.remove('out'); }, 300);
    }, 3200);
    requestAnimationFrame(function() { requestAnimationFrame(function() { fill.style.width = '92%'; }); });

    return {
      node: scene,
      finish: function() {
        clearInterval(timer);
        fill.style.transition = 'width .3s';
        fill.style.width = '100%';
      }
    };
  }

  function requestPicture(bundle, media) {
    const scene = creatingScene();
    media.appendChild(scene.node);
    media.classList.add('creating');
    media.setAttribute('aria-busy', 'true');
    postJson(planner.dataset.imageUrl, { token: bundle.image_token }).then(function(result) {
      if (!result.url) { return; }
      // Swap only once the new picture has loaded, so the card never goes blank
      const picture = bundlePicture(result.url, bundle.name);
      return new Promise(function(resolve) {
        const image = picture.querySelector('img');
        image.onload = function() { media.replaceChildren(picture); resolve(); };
        image.onerror = resolve;
      });
    }).catch(function() {
      // keep the picture already showing
    }).finally(function() {
      scene.finish();
      scene.node.remove();
      media.classList.remove('creating');
      media.removeAttribute('aria-busy');
    });
  }

  function bundleCard(bundle) {
    const card = el('div', 'bp-card' + (bundle.featured ? ' featured' : ''));

    const text = el('div');
    text.appendChild(el('span', 'bp-badge ' + bundle.badge, bundle.label));
    text.appendChild(el('h3', '', bundle.name));
    text.appendChild(el('p', 'bp-card-desc', bundle.description));
    const price = el('p', 'bp-card-price', 'AED ');
    price.appendChild(el('strong', '', amount(bundle.price)));
    price.appendChild(document.createTextNode(' / person'));
    price.appendChild(el('small', 'bp-card-total', money(bundle.total) + ' for ' + current.people + (current.people === 1 ? ' person' : ' people')));
    text.appendChild(price);

    const list = el('ul', 'bp-items');
    bundle.items.forEach(function(item) {
      const row = el('li');
      row.appendChild(el('i', 'bi bi-check-lg'));
      const body = el('span');
      const link = el('a', '', item.name);
      link.href = item.url;
      body.appendChild(link);
      if (item.quantity > current.people) {
        body.appendChild(el('small', '', 'Minimum order ' + item.moq + ' pcs'));
      }
      row.appendChild(body);
      list.appendChild(row);
    });
    text.appendChild(list);
    card.appendChild(text);

    const media = el('div', 'bp-card-image');
    if (bundle.image) {
      media.appendChild(bundlePicture(bundle.image, bundle.name));
    } else {
      const photos = el('div', 'bp-photos n' + Math.min(bundle.items.length, 4));
      bundle.items.slice(0, 4).forEach(function(item) { photos.appendChild(photo(item)); });
      media.appendChild(photos);
    }
    card.appendChild(media);
    if (bundle.image_token) {
      requestPicture(bundle, media);
    }

    const select = el('button', 'bp-btn bp-select-bundle', 'Select This Bundle');
    select.type = 'button';
    select.addEventListener('click', function() {
      if (!loggedIn) {
        askToLogin('select');
        return;
      }
      select.disabled = true;
      select.textContent = 'Adding to your quote...';
      postJson(planner.dataset.cartUrl, { people: current.people, product_ids: bundle.items.map(function(item) { return item.id; }) })
        .then(function(result) { window.location.href = result.redirect; })
        .catch(function() {
          select.disabled = false;
          select.textContent = 'Could not add. Try again';
        });
    });
    card.appendChild(select);
    return card;
  }

  // A single gift, priced for the number of people
  function productCard(item) {
    const card = el('article', 'bp-product');
    const link = el('a', 'bp-product-image');
    link.href = item.url;
    link.appendChild(photo(item));
    card.appendChild(link);

    const body = el('div', 'bp-product-body');
    const title = el('h3');
    const name = el('a', '', item.name);
    name.href = item.url;
    title.appendChild(name);
    body.appendChild(title);
    if (item.preferences.length) {
      const tags = el('div', 'bp-product-tags');
      item.preferences.forEach(function(label) { tags.appendChild(el('span', '', label)); });
      body.appendChild(tags);
    }
    const price = el('p', 'bp-product-price', 'AED ');
    price.appendChild(el('strong', '', amount(item.cost)));
    price.appendChild(document.createTextNode(' / person'));
    body.appendChild(price);
    // A minimum order above the number of people is paid in full, which is why the cost per person is higher
    body.appendChild(el('p', 'bp-product-total', money(item.total) + ' for ' + item.quantity + ' pcs' + (item.quantity > current.people ? ' (minimum order, ' + money(item.price) + ' each)' : '')));

    const add = el('button', 'bp-btn', 'Add to Quote');
    add.type = 'button';
    add.addEventListener('click', function() {
      if (!loggedIn) {
        askToLogin('select');
        return;
      }
      add.disabled = true;
      add.textContent = 'Adding...';
      postJson(planner.dataset.cartUrl, { people: current.people, product_ids: [item.id] }).then(function(result) {
        add.textContent = 'Added to your quote';
        add.classList.add('added');
        const note = el('p', 'bp-product-added');
        const view = el('a', '', 'View quote');
        view.href = result.redirect;
        note.appendChild(view);
        body.appendChild(note);
        const badge = document.getElementById('cartCount');
        if (badge && result.count !== undefined) { badge.textContent = result.count; }
      }).catch(function() {
        add.disabled = false;
        add.textContent = 'Could not add. Try again';
      });
    });
    body.appendChild(add);
    card.appendChild(body);
    return card;
  }

  function subtitleText(result, occasionLabel) {
    const who = current.people + (current.people === 1 ? ' person' : ' people');
    const parts = ['For ' + who + ', up to ' + money(result.perPerson) + ' per person.'];
    if (result.guest) {
      parts.push('Log in to get bundles named for your occasion and pictured with your logo.');
    }
    if (result.broadened) {
      parts.push('We don\'t have gifts made just for ' + occasionLabel + ' yet, so these are popular corporate gifts.');
    }
    if (result.unmatchedPreferences.length) {
      parts.push('Nothing matched ' + result.unmatchedPreferences.join(', ') + ', so these are the closest options.');
    }
    return parts.join(' ');
  }

  // Bundles or Individual Products
  const tabs = document.querySelectorAll('.bp-tab');

  function showTab(name) {
    tabs.forEach(function(tab) {
      const active = tab.dataset.tab === name;
      tab.classList.toggle('active', active);
      tab.setAttribute('aria-selected', active ? 'true' : 'false');
      tab.tabIndex = active ? 0 : -1;
    });
    document.getElementById('panelBundles').hidden = name !== 'bundles';
    document.getElementById('panelProducts').hidden = name !== 'products';
    moreBtn.hidden = name !== 'bundles';
  }

  tabs.forEach(function(tab, i) {
    tab.addEventListener('click', function() { showTab(this.dataset.tab); });
    tab.addEventListener('keydown', function(event) {
      if (event.key !== 'ArrowRight' && event.key !== 'ArrowLeft') { return; }
      const next = tabs[(i + (event.key === 'ArrowRight' ? 1 : tabs.length - 1)) % tabs.length];
      showTab(next.dataset.tab);
      next.focus();
    });
  });

  function load(nextRound) {
    const occasion = document.querySelector('.bp-occasion.active');
    const occasionLabel = occasion.querySelector('.bp-occasion-label').textContent;
    const c = choices();
    if (c.error) {
      return Promise.reject(new Error(c.error));
    }
    return postJson(planner.dataset.url, {
      occasion: occasion.dataset.occasion,
      people: c.people,
      budget_type: c.budget_type,
      budget: c.budget,
      preferences: Array.from(document.querySelectorAll('.bp-chip.active')).map(function(chip) { return chip.dataset.pref; }),
      round: nextRound,
      custom_occasion: occasion.dataset.occasion === 'other' ? customOccasion : ''
    }).then(function(result) {
      if (nextRound > 0 && !result.bundles.length) {
        return false; // nothing further: keep what's on screen
      }
      round = nextRound;
      shown = true;
      current = c;
      document.getElementById('plannerWelcome').hidden = true;
      document.getElementById('resultsHead').hidden = false;
      document.getElementById('resultsTabs').hidden = false;
      document.getElementById('selectedOccasion').textContent = occasionLabel;
      grid.replaceChildren();
      result.bundles.forEach(function(bundle) { grid.appendChild(bundleCard(bundle)); });
      empty.hidden = result.bundles.length > 0;
      productsGrid.replaceChildren();
      result.products.forEach(function(item) { productsGrid.appendChild(productCard(item)); });
      document.getElementById('productsEmpty').hidden = result.products.length > 0;
      document.getElementById('countBundles').textContent = result.bundles.length;
      document.getElementById('countProducts').textContent = result.products.length;
      // A first look opens the tab that has something to show
      if (nextRound === 0) {
        showTab(!result.bundles.length && result.products.length ? 'products' : 'bundles');
      }
      subtitle.textContent = subtitleText(result, occasionLabel);
      return true;
    });
  }

  function resetMore() {
    moreBtn.disabled = false;
    moreBtn.innerHTML = moreLabel;
  }

  // Occasion selection
  const occasionCards = document.querySelectorAll('.bp-occasion');

  function markOccasion(card) {
    occasionCards.forEach(function(c) {
      c.classList.remove('active');
      c.setAttribute('aria-pressed', 'false');
    });
    card.classList.add('active');
    card.setAttribute('aria-pressed', 'true');
  }

  // Before the first Show Recommendations, choosing an occasion only marks it
  function selectOccasion(card) {
    markOccasion(card);
    if (!shown) {
      return Promise.resolve(false);
    }
    resetMore();
    return load(0);
  }

  // Example bundles: "Plan for …" picks that occasion and takes the customer to the choices
  document.querySelectorAll('.bp-example-try').forEach(function(button) {
    button.addEventListener('click', function() {
      const card = document.querySelector('.bp-occasion[data-occasion="' + this.dataset.occasion + '"]');
      if (!card) { return; }
      selectOccasion(card).catch(function(e) { showError(errorText(e)); });
      document.getElementById('plannerStart').scrollIntoView({ behavior: 'smooth' });
      card.focus({ preventScroll: true });
    });
  });

  occasionCards.forEach(function(card) {
    card.addEventListener('click', function() {
      if (this.dataset.occasion === 'other') {
        openOtherModal();
        return;
      }
      selectOccasion(this).catch(function(e) { showError(errorText(e)); });
    });
  });

  // Other: the customer says what the occasion is
  const otherCard = document.querySelector('.bp-occasion[data-occasion="other"]');
  const otherLabel = otherCard.querySelector('.bp-occasion-label');
  const otherDefaultLabel = otherLabel.textContent;
  const otherModal = document.getElementById('otherModal');
  const otherInput = document.getElementById('otherInput');
  const otherError = document.getElementById('otherError');
  const otherContinue = document.getElementById('otherContinue');

  function refreshOtherForm() {
    document.getElementById('otherCount').textContent = otherInput.value.length;
    otherContinue.disabled = otherInput.value.trim() === '';
    otherError.hidden = true;
  }

  function openOtherModal() {
    otherInput.value = customOccasion;
    refreshOtherForm();
    otherModal.hidden = false;
    document.body.style.overflow = 'hidden';
    otherInput.focus();
  }

  function closeOtherModal() {
    otherModal.hidden = true;
    document.body.style.overflow = '';
    otherCard.focus();
  }

  function applyOtherOccasion(value) {
    const text = value.trim().replace(/\s+/g, ' ');
    // Same rule as GiftPlannerController::recommend()
    if (!/^[\p{L}\p{M}\p{N}\s&'’.,\/-]+$/u.test(text)) {
      otherError.textContent = 'Please use only letters and numbers for the occasion.';
      otherError.hidden = false;
      return;
    }
    const previous = [customOccasion, otherLabel.textContent];
    const previousCard = document.querySelector('.bp-occasion.active');
    customOccasion = text;
    otherLabel.textContent = text;
    otherContinue.disabled = true;
    selectOccasion(otherCard).then(function() {
      document.getElementById('otherEdit').hidden = false;
      closeOtherModal();
    }).catch(function() {
      customOccasion = previous[0];
      otherLabel.textContent = previous[1] || otherDefaultLabel;
      markOccasion(previousCard);
      otherContinue.disabled = false;
      otherError.textContent = 'Something went wrong. Please try again.';
      otherError.hidden = false;
    });
  }

  otherInput.addEventListener('input', refreshOtherForm);
  document.getElementById('otherForm').addEventListener('submit', function(event) {
    event.preventDefault();
    if (otherInput.value.trim()) { applyOtherOccasion(otherInput.value); }
  });
  document.querySelectorAll('.bp-modal-pick').forEach(function(pick) {
    pick.addEventListener('click', function() { applyOtherOccasion(this.dataset.pick); });
  });
  document.getElementById('otherCancel').addEventListener('click', closeOtherModal);
  document.getElementById('otherClose').addEventListener('click', closeOtherModal);
  otherModal.addEventListener('click', function(event) {
    if (event.target === otherModal) { closeOtherModal(); }
  });
  document.addEventListener('keydown', function(event) {
    if (otherModal.hidden) { return; }
    if (event.key === 'Escape') {
      closeOtherModal();
    } else if (event.key === 'Tab') {
      const focusable = Array.from(otherModal.querySelectorAll('button:not(:disabled), input'));
      const first = focusable[0];
      const last = focusable[focusable.length - 1];
      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    }
  });

  // Budget per person or in total
  const budgetTypeButtons = document.querySelectorAll('[data-budget-type]');

  function setBudgetType(type) {
    budgetType = type === 'total' ? 'total' : 'person';
    budgetTypeButtons.forEach(function(button) {
      const active = button.dataset.budgetType === budgetType;
      button.classList.toggle('active', active);
      button.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
    budgetInput.placeholder = budgetType === 'total' ? 'e.g. 7500' : 'e.g. 150';
    updateHint();
  }

  budgetTypeButtons.forEach(function(button) {
    button.addEventListener('click', function() { setBudgetType(this.dataset.budgetType); });
  });
  [peopleInput, budgetInput].forEach(function(input) {
    input.addEventListener('input', function() { updateHint(); showError(''); });
    input.addEventListener('keydown', function(event) {
      if (event.key === 'Enter') { showBtn.click(); }
    });
  });

  // Preference chips
  document.querySelectorAll('.bp-chip').forEach(function(chip) {
    chip.addEventListener('click', function() {
      const active = this.classList.toggle('active');
      this.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
  });

  // Logo and brand assets pop-up: opens on Show Recommendations; uploads are optional
  const brandModal = document.getElementById('brandModal');
  const brandError = document.getElementById('brandError');
  const maxAssets = parseInt(brandModal.dataset.maxAssets, 10);
  let brand = JSON.parse(brandModal.dataset.brand || '{"logo":null,"assets":[]}');

  function showBrandError(message) {
    brandError.textContent = message;
    brandError.hidden = !message;
  }

  function brandTile(file, label) {
    const tile = el('div', 'bp-brand-file');
    const image = el('img');
    image.src = file.url + '?v=' + Date.now();
    image.alt = label;
    tile.appendChild(image);
    const remove = el('button');
    remove.type = 'button';
    remove.setAttribute('aria-label', 'Remove ' + label.toLowerCase());
    remove.appendChild(el('i', 'bi bi-x-lg'));
    remove.addEventListener('click', function() {
      remove.disabled = true;
      postJson(brandModal.dataset.removeUrl, { id: file.id }).then(renderBrand).catch(function() {
        remove.disabled = false;
        showBrandError('Could not remove it. Please try again.');
      });
    });
    tile.appendChild(remove);
    return tile;
  }

  function renderBrand(list) {
    brand = list;
    showBrandError('');
    const logoRow = document.getElementById('brandLogo');
    const assetRow = document.getElementById('brandAssets');
    logoRow.replaceChildren();
    assetRow.replaceChildren();
    if (brand.logo) { logoRow.appendChild(brandTile(brand.logo, 'Logo')); }
    brand.assets.forEach(function(file, i) { assetRow.appendChild(brandTile(file, 'Brand asset ' + (i + 1))); });
    document.querySelector('#brandLogoAdd span').textContent = brand.logo ? 'Replace logo' : 'Upload logo';
    document.getElementById('brandAssetAdd').hidden = brand.assets.length >= maxAssets;
  }

  function uploadBrand(kind, files, button) {
    files = Array.from(files);
    if (kind === 'asset' && brand.assets.length + files.length > maxAssets) {
      showBrandError('You can add up to ' + maxAssets + ' brand assets.');
      return;
    }
    button.classList.add('busy');
    showBrandError('');
    // One at a time, so each gets its own message if it fails
    files.reduce(function(chain, file) {
      return chain.then(function() {
        const form = new FormData();
        form.append('kind', kind);
        form.append('file', file);
        return fetch(brandModal.dataset.storeUrl, {
          method: 'POST',
          headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
          body: form
        }).then(function(response) {
          return response.json().catch(function() { return {}; }).then(function(result) {
            if (!response.ok) {
              const errors = result.errors ? Object.values(result.errors)[0] : null;
              throw new Error((errors && errors[0]) || result.message || 'Could not upload ' + file.name + '. Please try again.');
            }
            renderBrand(result);
          });
        });
      });
    }, Promise.resolve()).catch(function(e) {
      showBrandError(e.message);
    }).finally(function() {
      button.classList.remove('busy');
    });
  }

  function openBrandModal() {
    renderBrand(brand);
    brandModal.hidden = false;
    document.body.style.overflow = 'hidden';
    document.getElementById('brandContinue').focus();
  }

  function closeBrandModal() {
    brandModal.hidden = true;
    document.body.style.overflow = '';
  }

  document.getElementById('brandLogoInput').addEventListener('change', function() {
    if (this.files.length) { uploadBrand('logo', this.files, document.getElementById('brandLogoAdd')); }
    this.value = '';
  });
  document.getElementById('brandAssetInput').addEventListener('change', function() {
    if (this.files.length) { uploadBrand('asset', this.files, document.getElementById('brandAssetAdd')); }
    this.value = '';
  });
  ['brandSkip', 'brandContinue'].forEach(function(id) {
    document.getElementById(id).addEventListener('click', function() {
      closeBrandModal();
      showRecommendations();
    });
  });
  document.getElementById('brandClose').addEventListener('click', function() {
    closeBrandModal();
    showBtn.focus();
  });
  brandModal.addEventListener('click', function(event) {
    if (event.target === brandModal) { closeBrandModal(); }
  });
  document.addEventListener('keydown', function(event) {
    if (!brandModal.hidden && event.key === 'Escape') { closeBrandModal(); }
  });

  // Show Recommendations button: checks the choices, then asks logged-in customers for the logo and assets
  showBtn.addEventListener('click', function() {
    const c = choices();
    if (c.error) {
      showError(c.error);
      c.field.focus();
      return;
    }
    showError('');
    if (loggedIn) {
      openBrandModal();
    } else {
      showRecommendations();
    }
  });

  function showRecommendations() {
    resetMore();
    showBtn.disabled = true;
    showBtn.textContent = 'Finding gifts...';
    load(0).then(function() {
      document.getElementById('recommendations').scrollIntoView({ behavior: 'smooth' });
    }).catch(function(e) {
      showError(errorText(e));
    }).finally(function() {
      showBtn.disabled = false;
      showBtn.innerHTML = showLabel;
    });
  }

  // View More Options: the next bundles for the same choices
  moreBtn.addEventListener('click', function() {
    moreBtn.disabled = true;
    (round < 4 ? load(round + 1) : Promise.resolve(false)).then(function(found) {
      if (found) {
        moreBtn.disabled = false;
      } else {
        moreBtn.textContent = 'No more options';
      }
    }).catch(function() {
      moreBtn.disabled = false;
    });
  });

  // Choices made before logging in come back, whether or not they went through with it
  function restoreChoices() {
    let saved = null;
    try {
      saved = JSON.parse(sessionStorage.getItem(RESUME_KEY));
      if (loggedIn) { sessionStorage.removeItem(RESUME_KEY); }
    } catch (e) {
      return null;
    }
    if (!saved) { return null; }
    const card = document.querySelector('.bp-occasion[data-occasion="' + CSS.escape(saved.occasion || '') + '"]');
    if (card && (saved.occasion !== 'other' || saved.customOccasion)) {
      if (saved.occasion === 'other') {
        customOccasion = saved.customOccasion;
        otherLabel.textContent = saved.customOccasion;
        document.getElementById('otherEdit').hidden = false;
      }
      markOccasion(card);
    }
    peopleInput.value = saved.people || '';
    budgetInput.value = saved.budget || '';
    setBudgetType(saved.budgetType);
    document.querySelectorAll('.bp-chip').forEach(function(chip) {
      const active = (saved.preferences || []).indexOf(chip.dataset.pref) !== -1;
      chip.classList.toggle('active', active);
      chip.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
    return saved;
  }

  updateHint();
  const resume = restoreChoices();
  if (resume && loggedIn && resume.action === 'show') {
    // Carry on with what they clicked before logging in
    showBtn.click();
  }
});
