document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('saveCollectionModal');
  if (!modal) { return; }
  const list = document.getElementById('saveCollectionList');
  const form = document.getElementById('saveCollectionForm');
  const nameInput = document.getElementById('saveCollectionName');
  const createBtn = document.getElementById('saveCollectionCreate');
  const errorBox = document.getElementById('saveCollectionError');
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  let current = null;

  function request(method, url, body) {
    return fetch(url, {
      method: method,
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: body ? JSON.stringify(body) : undefined
    }).then(function(response) {
      return response.json().catch(function() { return {}; }).then(function(data) {
        if (!response.ok || data.success === false) {
          const firstError = data.errors ? Object.values(data.errors)[0][0] : null;
          throw new Error(firstError || data.message || 'Something went wrong. Please try again.');
        }
        return data;
      });
    });
  }

  function showError(message) {
    errorBox.textContent = message || '';
    errorBox.hidden = !message;
  }

  function markTriggerSaved() {
    current.trigger.classList.add('saved');
    const icon = current.trigger.querySelector('i');
    if (icon) { icon.className = current.trigger.dataset.savedIcon || 'bi bi-bookmark-fill'; }
  }

  function rowSaved(row, collection) {
    row.classList.add('saved');
    row.disabled = true;
    row.querySelector('.mh-save-state').textContent = 'Saved';
    const link = document.createElement('a');
    link.href = collection.url;
    link.textContent = 'View';
    row.parentNode.appendChild(link);
  }

  function addToCollection(collectionId) {
    const url = modal.dataset.addUrl.replace(/\/0\/items$/, '/' + collectionId + '/items');
    return request('POST', url, { product_id: current.id, quantity: current.quantity }).then(markTriggerSaved);
  }

  function renderRow(collection) {
    const wrap = document.createElement('div');
    wrap.className = 'mh-save-row';
    const row = document.createElement('button');
    row.type = 'button';
    const name = document.createElement('span');
    name.className = 'mh-save-name';
    name.textContent = collection.name;
    const count = document.createElement('small');
    count.textContent = collection.count + (collection.count === 1 ? ' product' : ' products');
    name.appendChild(count);
    const state = document.createElement('span');
    state.className = 'mh-save-state';
    state.textContent = 'Save';
    row.appendChild(name);
    row.appendChild(state);
    wrap.appendChild(row);
    list.appendChild(wrap);
    if (collection.saved) {
      rowSaved(row, collection);
      return wrap;
    }
    row.addEventListener('click', function() {
      showError('');
      row.disabled = true;
      addToCollection(collection.id).then(function() {
        rowSaved(row, collection);
        count.textContent = (collection.count + 1) + (collection.count === 0 ? ' product' : ' products');
      }).catch(function(error) {
        row.disabled = false;
        showError(error.message);
      });
    });
    return wrap;
  }

  function loadList() {
    list.replaceChildren();
    const loading = document.createElement('p');
    loading.className = 'mh-save-note';
    loading.textContent = 'Loading your collections...';
    list.appendChild(loading);
    request('GET', modal.dataset.listUrl + '?product_id=' + encodeURIComponent(current.id)).then(function(data) {
      list.replaceChildren();
      if (!data.collections.length) {
        const note = document.createElement('p');
        note.className = 'mh-save-note';
        note.textContent = 'You have no collections yet. Create your first one below.';
        list.appendChild(note);
      }
      data.collections.forEach(renderRow);
    }).catch(function(error) {
      list.replaceChildren();
      showError(error.message);
    });
  }

  function open(trigger) {
    if (modal.dataset.loggedIn !== '1') {
      window.location.href = modal.dataset.loginUrl;
      return;
    }
    current = { id: trigger.dataset.saveCollection, quantity: trigger.dataset.quantity || 1, trigger: trigger };
    document.getElementById('saveCollectionProduct').textContent = trigger.dataset.name || '';
    nameInput.value = '';
    createBtn.disabled = true;
    showError('');
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
    document.getElementById('saveCollectionClose').focus();
    loadList();
  }

  function close() {
    modal.hidden = true;
    document.body.style.overflow = '';
    if (current) { current.trigger.focus(); }
  }

  document.addEventListener('click', function(event) {
    const trigger = event.target.closest('[data-save-collection]');
    if (trigger) { open(trigger); }
  });

  document.getElementById('saveCollectionClose').addEventListener('click', close);
  modal.addEventListener('click', function(event) {
    if (event.target === modal) { close(); }
  });
  document.addEventListener('keydown', function(event) {
    if (modal.hidden) { return; }
    if (event.key === 'Escape') {
      close();
    } else if (event.key === 'Tab') {
      const focusable = Array.from(modal.querySelectorAll('a[href], button:not(:disabled), input'));
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

  nameInput.addEventListener('input', function() {
    createBtn.disabled = nameInput.value.trim() === '';
  });

  form.addEventListener('submit', function(event) {
    event.preventDefault();
    const name = nameInput.value.trim();
    if (!name) { return; }
    showError('');
    createBtn.disabled = true;
    request('POST', modal.dataset.createUrl, { name: name }).then(function(data) {
      return addToCollection(data.collection.id).then(function() {
        const note = list.querySelector('.mh-save-note');
        if (note) { note.remove(); }
        const row = renderRow({ id: data.collection.id, name: data.collection.name, count: 1, saved: true, url: data.url });
        list.prepend(row);
        nameInput.value = '';
      });
    }).catch(function(error) {
      createBtn.disabled = false;
      showError(error.message);
    });
  });
});
