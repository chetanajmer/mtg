(function () {
  'use strict';

  var routes = window.MTG_ADDRESS_ROUTES || {};
  var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  var list = document.getElementById('addressList');
  var formCard = document.getElementById('addressFormCard');
  var form = document.getElementById('addressForm');
  var idField = document.getElementById('address_id');
  var formTitle = document.getElementById('formTitle');
  var formSubtitle = document.getElementById('formSubtitle');
  var saveText = document.getElementById('btnSaveText');
  var btnSave = document.getElementById('btnSave');
  var btnCancel = document.getElementById('btnCancel');
  var btnAddNew = document.getElementById('btnAddNew');
  var defaultCheck = document.getElementById('f_is_default');
  var deleteModal = document.getElementById('deleteModal');
  var deleteConfirm = document.getElementById('deleteConfirm');
  var deleteCancel = document.getElementById('deleteCancel');
  var toastWrap = document.getElementById('toastWrap');

  var pendingDeleteId = null;
  var fields = ['name', 'mobile', 'label', 'address', 'city', 'state', 'pincode'];
  var selectedAddressKey = 'mtg_selected_address_id';

  /* ---------------- helpers ---------------- */
  function esc(v) {
    return String(v == null ? '' : v)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
  }

  function url(key, id) {
    return (routes[key] || '').replace('__ID__', id);
  }

  function toast(message, type) {
    if (!toastWrap || !message) return;
    var el = document.createElement('div');
    el.className = 'mtg-toast ' + (type || 'success');
    var icon = type === 'error' ? 'bi-exclamation-circle-fill' : 'bi-check-circle-fill';
    el.innerHTML = '<i class="bi ' + icon + '"></i><span>' + esc(message) + '</span>';
    toastWrap.appendChild(el);
    setTimeout(function () {
      el.classList.add('out');
      setTimeout(function () { el.remove(); }, 260);
    }, 3200);
  }

  function request(method, action, body) {
    var opts = {
      method: method,
      headers: {
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    };
    if (body) opts.body = body;
    return fetch(action, opts).then(function (res) {
      return res.json().catch(function () { return {}; }).then(function (data) {
        if (!res.ok) {
          var err = new Error(data.message || 'Something went wrong. Please try again.');
          err.status = res.status;
          err.errors = data.errors || {};
          throw err;
        }
        return data;
      });
    });
  }

  /* ---------------- rendering ---------------- */
  function typeIcon(label) {
    return label === 'Office' ? 'bi-building' : (label === 'Other' ? 'bi-geo' : 'bi-house-door');
  }

  function cardHtml(a) {
    var line2 = [a.city, a.state].filter(Boolean).join(', ');
    if (a.pincode) line2 += (line2 ? ' - ' : '') + a.pincode;
    var primary = a.is_default === true || a.is_default === 1 || a.is_default === '1';

    return '' +
      '<article class="address-card ' + (primary ? 'is-primary' : '') + '" data-id="' + a.id + '" data-address=\'' + esc(JSON.stringify(a)) + '\'>' +
        '<div class="address-card-top">' +
          '<div class="address-card-tags">' +
            (primary ? '<span class="tag-primary"><i class="bi bi-star-fill"></i> Primary Address</span>' : '') +
            '<span class="tag-type"><i class="bi ' + typeIcon(a.label) + '"></i> ' + esc(a.label || 'Home') + '</span>' +
          '</div>' +
          '<div class="address-card-actions">' +
            '<button type="button" class="btn-edit" data-action="edit"><i class="bi bi-pencil"></i> Edit</button>' +
            '<button type="button" class="btn-delete" data-action="delete"><i class="bi bi-trash"></i> Delete</button>' +
          '</div>' +
        '</div>' +
        '<div class="address-card-body">' +
          '<div class="address-card-main">' +
            '<div class="address-checkbox">' +
              '<input type="checkbox" class="address-select-checkbox" data-id="' + a.id + '">' +
            '</div>' +
            '<div class="address-pin"><i class="bi bi-geo-alt-fill"></i></div>' +
            '<div class="address-details">' +
              '<h3>' + esc(a.name) + '</h3>' +
              '<p><i class="bi bi-telephone"></i><span>' + esc(a.mobile) + '</span></p>' +
              '<p><i class="bi bi-geo-alt"></i><span>' + esc(a.address) + (line2 ? ',' : '') + '<br>' + esc(line2) + '</span></p>' +
            '</div>' +
          '</div>' +
          '<div class="address-card-side">' +
            (primary
              ? '<span class="pill-primary">Primary Address</span><p>This address will be used by default for placing orders.</p>'
              : '<button type="button" class="btn-set-primary" data-action="primary"><i class="bi bi-star"></i> Set as Primary</button><p>Select this address to use it by default for placing orders.</p>') +
          '</div>' +
        '</div>' +
      '</article>';
  }

  function emptyHtml() {
    return '' +
      '<div class="address-empty" id="addressEmpty">' +
        '<div class="address-empty-icon"><i class="bi bi-geo-alt"></i></div>' +
        '<h3>No saved addresses yet</h3>' +
        '<p>Add your first delivery address using the form below to speed up checkout.</p>' +
      '</div>';
  }

  function renderList(addresses) {
    if (!addresses || !addresses.length) {
      list.innerHTML = emptyHtml();
      if (defaultCheck) { defaultCheck.checked = true; }
      return;
    }
    list.innerHTML = addresses.map(cardHtml).join('');
    
    // Restore selected address from localStorage
    var selectedId = localStorage.getItem(selectedAddressKey);
    if (selectedId) {
      var checkbox = list.querySelector('.address-select-checkbox[data-id="' + selectedId + '"]');
      if (checkbox) {
        checkbox.checked = true;
      }
    }
  }

  /* ---------------- form state ---------------- */
  function clearErrors() {
    form.querySelectorAll('.form-field.has-error').forEach(function (f) { f.classList.remove('has-error'); });
    form.querySelectorAll('.field-error').forEach(function (e) { e.textContent = ''; });
  }

  function showErrors(errors) {
    clearErrors();
    var first = null;
    Object.keys(errors || {}).forEach(function (name) {
      var input = form.querySelector('[name="' + name + '"]');
      if (!input) return;
      var field = input.closest('.form-field');
      var msg = field && field.querySelector('.field-error');
      if (field) field.classList.add('has-error');
      if (msg) msg.textContent = Array.isArray(errors[name]) ? errors[name][0] : errors[name];
      if (!first) first = input;
    });
    if (first) first.focus();
  }

  function validateClient() {
    var errors = {};
    fields.forEach(function (name) {
      var input = form.querySelector('[name="' + name + '"]');
      if (input && !input.value.trim()) errors[name] = 'This field is required.';
    });
    var mobile = form.querySelector('[name="mobile"]').value.trim();
    if (mobile && !/^[0-9+\-\s()]{7,20}$/.test(mobile)) errors.mobile = 'Please enter a valid mobile number.';
    var pin = form.querySelector('[name="pincode"]').value.trim();
    if (pin && !/^[A-Za-z0-9\-\s]{3,20}$/.test(pin)) errors.pincode = 'Please enter a valid pincode.';
    return errors;
  }

  function setMode(mode, data) {
    clearErrors();
    form.reset();
    formCard.classList.toggle('is-editing', mode === 'edit');

    if (mode === 'edit' && data) {
      idField.value = data.id;
      fields.forEach(function (name) {
        var input = form.querySelector('[name="' + name + '"]');
        if (input) input.value = data[name] == null ? '' : data[name];
      });
      if (!form.querySelector('[name="label"]').value) form.querySelector('[name="label"]').value = 'Home';
      defaultCheck.checked = !!Number(data.is_default);
      defaultCheck.disabled = !!Number(data.is_default);
      formTitle.textContent = 'Edit Address';
      formSubtitle.textContent = 'Update the details of this delivery address.';
      saveText.textContent = 'Update Address';
    } else {
      idField.value = '';
      defaultCheck.disabled = false;
      defaultCheck.checked = !list.querySelector('.address-card');
      formTitle.textContent = 'Add New Address';
      formSubtitle.textContent = 'Enter your address details to add a new delivery address.';
      saveText.textContent = 'Save Address';
    }
  }

  function focusForm() {
    formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
    setTimeout(function () { form.querySelector('[name="name"]').focus({ preventScroll: true }); }, 350);
  }

  /* ---------------- events ---------------- */
  btnAddNew.addEventListener('click', function () {
    setMode('add');
    focusForm();
  });

  btnCancel.addEventListener('click', function () {
    setMode('add');
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var errors = validateClient();
    if (Object.keys(errors).length) { showErrors(errors); return; }

    var id = idField.value;
    var fd = new FormData(form);
    if (!defaultCheck.checked) fd.delete('is_default');
    if (defaultCheck.disabled) fd.set('is_default', '1');
    fd.delete('address_id');
    if (id) fd.set('_method', 'PUT');

    btnSave.disabled = true;
    request('POST', id ? url('update', id) : routes.store, fd)
      .then(function (data) {
        // Clear selected address if the edited/deleted address was selected
        if (id && localStorage.getItem(selectedAddressKey) === String(id)) {
          localStorage.removeItem(selectedAddressKey);
        }
        renderList(data.addresses);
        setMode('add');
        toast(data.message || 'Saved successfully!', 'success');
        if (id) {
          var card = list.querySelector('.address-card[data-id="' + id + '"]');
          if (card) card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      })
      .catch(function (err) {
        if (err.status === 422) { showErrors(err.errors); toast('Please correct the highlighted fields.', 'error'); }
        else if (err.status === 401) { window.location.reload(); }
        else { toast(err.message, 'error'); }
      })
      .then(function () { btnSave.disabled = false; });
  });

  list.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-action]');
    var checkbox = e.target.closest('.address-select-checkbox');

    if (checkbox) {
      var card = checkbox.closest('.address-card');
      var id = card.getAttribute('data-id');
      var isChecked = checkbox.checked;

      // Uncheck all other checkboxes (single selection)
      list.querySelectorAll('.address-select-checkbox').forEach(function(cb) {
        if (cb !== checkbox) cb.checked = false;
      });

      // Save selected address to localStorage
      if (isChecked) {
        localStorage.setItem(selectedAddressKey, id);
        toast('Address selected successfully!', 'success');
      } else {
        localStorage.removeItem(selectedAddressKey);
      }

      // Checkbox is just for visual selection, not primary address
      // Don't make it primary automatically
      return;
    }

    if (!btn) return;
    var card = btn.closest('.address-card');
    var id = card.getAttribute('data-id');
    var action = btn.getAttribute('data-action');

    if (action === 'edit') {
      var data = {};
      try { data = JSON.parse(card.getAttribute('data-address')); } catch (err) {}
      setMode('edit', data);
      focusForm();
    } else if (action === 'delete') {
      pendingDeleteId = id;
      deleteModal.classList.add('open');
      deleteModal.setAttribute('aria-hidden', 'false');
    } else if (action === 'primary') {
      btn.disabled = true;
      request('POST', url('select', id))
        .then(function (data) {
          console.log('Primary address response:', data);
          renderList(data.addresses);
          toast(data.message || 'Primary address updated!', 'success');
        })
        .catch(function (err) { btn.disabled = false; toast(err.message, 'error'); });
    }
  });

  function closeDeleteModal() {
    pendingDeleteId = null;
    deleteModal.classList.remove('open');
    deleteModal.setAttribute('aria-hidden', 'true');
  }

  deleteCancel.addEventListener('click', closeDeleteModal);
  deleteModal.addEventListener('click', function (e) { if (e.target === deleteModal) closeDeleteModal(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && deleteModal.classList.contains('open')) closeDeleteModal(); });

  deleteConfirm.addEventListener('click', function () {
    if (!pendingDeleteId) return;
    var id = pendingDeleteId;
    deleteConfirm.disabled = true;
    request('DELETE', url('delete', id))
      .then(function (data) {
        // Clear selected address if the deleted address was selected
        if (localStorage.getItem(selectedAddressKey) === String(id)) {
          localStorage.removeItem(selectedAddressKey);
        }
        renderList(data.addresses);
        if (idField.value === String(id)) setMode('add');
        toast(data.message || 'Address deleted successfully!', 'success');
      })
      .catch(function (err) { toast(err.message, 'error'); })
      .then(function () { deleteConfirm.disabled = false; closeDeleteModal(); });
  });

  // Show any server-side flash message (non-AJAX fallbacks)
  if (window.MTG_FLASH) toast(window.MTG_FLASH, 'success');
})();
