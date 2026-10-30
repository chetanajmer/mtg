document.addEventListener("DOMContentLoaded", () => {
  // Quantity control
  const quantity = document.getElementById("quantity");
  const minus = document.getElementById("qtyMinus");
  const plus = document.getElementById("qtyPlus");
  const minimum = 50;

  minus.addEventListener("click", () => {
    const current = parseInt(quantity.value || minimum, 10);
    quantity.value = Math.max(minimum, current - 1);
  });

  plus.addEventListener("click", () => {
    const current = parseInt(quantity.value || minimum, 10);
    quantity.value = current + 1;
  });

  quantity.addEventListener("change", () => {
    const current = parseInt(quantity.value || minimum, 10);
    quantity.value = Math.max(minimum, current);
  });

  // Color selection
  const colorLabel = document.getElementById("selectedColor");
  document.querySelectorAll(".color-swatch").forEach((swatch) => {
    swatch.addEventListener("click", () => {
      document.querySelectorAll(".color-swatch").forEach((item) => item.classList.remove("selected"));
      swatch.classList.add("selected");
      colorLabel.textContent = swatch.dataset.color;
    });
  });

  // Branding selection
  document.querySelectorAll(".branding-option").forEach((option) => {
    option.addEventListener("click", () => {
      document.querySelectorAll(".branding-option").forEach((item) => item.classList.remove("selected"));
      option.classList.add("selected");
    });
  });

  // Product gallery
  const galleryImages = [
    "assets/product-main.jpg",
    "assets/thumb-2.jpg",
    "assets/thumb-3.jpg",
    "assets/thumb-4.jpg",
    "assets/thumb-5.jpg"
  ];

  let galleryIndex = 0;
  const mainImage = document.getElementById("mainProductImage");
  const thumbs = document.querySelectorAll(".thumb");

  function setGalleryImage(index) {
    galleryIndex = (index + galleryImages.length) % galleryImages.length;
    mainImage.src = galleryImages[galleryIndex];

    thumbs.forEach((thumb, i) => {
      thumb.classList.toggle("active", i === galleryIndex);
    });
  }

  thumbs.forEach((thumb, index) => {
    thumb.addEventListener("click", () => setGalleryImage(index));
  });

  document.querySelectorAll("[data-gallery]").forEach((button) => {
    button.addEventListener("click", () => {
      const direction = button.dataset.gallery;
      setGalleryImage(direction === "next" ? galleryIndex + 1 : galleryIndex - 1);
    });
  });

  // Product details tabs
  const tabs = document.querySelectorAll(".detail-tab");
  const panels = document.querySelectorAll(".detail-panel");

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const target = tab.dataset.tab;

      tabs.forEach((item) => item.classList.remove("active"));
      panels.forEach((panel) => panel.classList.remove("active"));

      tab.classList.add("active");
      document.getElementById(target).classList.add("active");
    });
  });

  // Demo quote buttons
  document.querySelector(".quote-button").addEventListener("click", () => {
    const qty = parseInt(quantity.value || minimum, 10);
    alert(`Added ${qty} pcs of Premium Vacuum Bottle to your quote.`);
  });

  document.querySelector(".sample-button").addEventListener("click", () => {
    alert("Sample request selected.");
  });

  document.querySelector(".more-colors").addEventListener("click", () => {
    alert("More color options can be connected to your product API here.");
  });

  document.querySelector(".custom-quantity button").addEventListener("click", () => {
    alert("Custom quote request selected.");
  });
});

// Corporate Portal Dashboard Scripts
document.addEventListener('DOMContentLoaded',()=>{const toast=document.getElementById('toast');let timer;function notify(msg){toast.textContent=msg;toast.classList.add('show');clearTimeout(timer);timer=setTimeout(()=>toast.classList.remove('show'),2600)}
document.querySelectorAll('[data-toast]').forEach(el=>el.addEventListener('click',()=>notify(el.dataset.toast)));
document.getElementById('notifications').addEventListener('click',()=>notify('You have 3 notifications.'));
document.getElementById('searchInput').addEventListener('keydown',e=>{if(e.key==='Enter')notify(e.target.value.trim()?`Searching for "${e.target.value.trim()}"…`:'Enter a search term.')});
document.getElementById('createCollection').addEventListener('click',()=>{const name=prompt('Enter a name for your new collection:');if(name&&name.trim())notify(`Collection "${name.trim()}" created in this demo.`)});
document.getElementById('download').addEventListener('click',()=>{const type=document.getElementById('reportType').value;const blob=new Blob([`Report,Value\n"${type}","Sample dashboard report — connect your backend for live data"\n`],{type:'text/csv;charset=utf-8;'});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download=type.toLowerCase().replaceAll(' ','-')+'.csv';a.click();URL.revokeObjectURL(url);notify(`${type} downloaded (demo data).`)});
});

// Cart Functionality
function addToCart(productId, productName, price, image, quantity = 1) {
  const qty = parseInt(quantity) || 1;
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  fetch('/cart/add', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': token || ''
    },
    body: JSON.stringify({ product_id: productId, quantity: qty })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      const cartCount = document.getElementById('cartCount');
      if (cartCount) cartCount.textContent = data.count;
      showNotification('Added to Cart!');
    } else {
      showNotification(data.message || 'Could not add to cart');
    }
  })
  .catch(() => {
    // Fallback to localStorage cart if the request fails
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) {
      existingItem.quantity += qty;
    } else {
      cart.push({ id: productId, name: productName, price: price, image: image, quantity: qty, sku: 'SKU-' + productId });
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showNotification('Added to Cart!');
  });
}

function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
  const cartCount = document.getElementById('cartCount');
  if (cartCount) {
    cartCount.textContent = totalItems;
  }
}

function showNotification(message) {
  const notification = document.createElement('div');
  notification.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #22c55e; color: white; padding: 12px 24px; border-radius: 8px; z-index: 1000; animation: slideIn 0.3s ease;';
  notification.textContent = message;
  document.body.appendChild(notification);

  setTimeout(() => {
    notification.style.animation = 'slideOut 0.3s ease';
    setTimeout(() => notification.remove(), 300);
  }, 2000);
}

// Cart count is rendered server-side in the header; no init needed here

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
  @keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
  }
  @keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
  }
`;
document.head.appendChild(style);
