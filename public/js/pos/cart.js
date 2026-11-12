// ==========================================
// KERANJANG BELANJA
// ==========================================
function addToCart(productId) {
  const product = products.find(p => p.id === productId);
  if (!product) return;

  const sel = document.getElementById(`unit-sel-${productId}`);
  const unitId = sel ? parseInt(sel.value, 10) : null;
  const unit = product.units.find(u => u.id === unitId) || product.units[0];

  const currentCartDeduction = cart
    .filter(c => c.product.id === product.id)
    .reduce((sum, c) => sum + (c.conversion_factor * c.qty), 0);

  const requiredDeduction = Number(unit.conversion_factor);
  if (currentCartDeduction + requiredDeduction > Number(product.current_stock)) {
    showToast(`Stok ${product.name} tidak mencukupi!`);
    return;
  }

  const existing = cart.find(c => c.product.id === product.id && c.product_unit_id === unit.id && !c.isNominal);
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({
      cartId: 'item-' + Date.now() + Math.random(),
      product: product,
      product_unit_id: unit.id,
      unit_name: unit.unit_name,
      conversion_factor: Number(unit.conversion_factor),
      price: Number(unit.sell_price),
      qty: 1,
      isNominal: false
    });
  }

  playBeep(700, 0.08);
  renderCart();
  showToast(`${product.name} (${unit.unit_name}) masuk keranjang.`);
}

function renderCart() {
  const container = document.getElementById('cart-items');
  document.getElementById('cart-item-count').textContent = `${cart.reduce((s, c) => s + c.qty, 0)} item`;

  if (cart.length === 0) {
    container.innerHTML = `
      <div class="h-44 flex flex-col items-center justify-center text-slate-400 text-center">
        <i data-lucide="shopping-bag" class="w-10 h-10 mb-1.5 text-slate-300"></i>
        <p class="text-xs font-semibold text-slate-600">Keranjang masih kosong</p>
        <p class="text-[11px] text-slate-400">Pilih sembako di sebelah kiri untuk memulai.</p>
      </div>
    `;
    document.getElementById('cart-subtotal').textContent = 'Rp 0';
    document.getElementById('cart-grandtotal').textContent = 'Rp 0';
    document.getElementById('cart-discount-row').style.display = 'none';
    lucide.createIcons();
    return;
  }

  container.innerHTML = cart.map(item => `
    <div class="py-2.5 flex items-center justify-between gap-2">
      <div class="flex-1 min-w-0">
        <h4 class="font-bold text-xs text-slate-800 truncate">${esc(item.product.name)}</h4>
        <div class="flex items-center gap-1.5 mt-0.5">
          <span class="bg-brand-50 text-brand-800 text-[10px] font-semibold px-1.5 py-0.2 rounded font-mono">${esc(item.unit_name)}</span>
          <span class="text-[11px] text-slate-400 font-mono">${formatRp(item.price)}</span>
        </div>
      </div>

      <div class="flex items-center gap-1.5">
        ${item.isNominal ? `
          <span class="font-mono text-xs font-bold text-amber-800 px-2 bg-amber-50 rounded border border-amber-200">${item.qty} pack</span>
        ` : `
          <button onclick="updateCartQty('${item.cartId}', -1)" class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-700 font-bold active:scale-95 transition-all">-</button>
          <span class="font-mono text-xs font-bold text-slate-800 w-6 text-center">${item.qty}</span>
          <button onclick="updateCartQty('${item.cartId}', 1)" class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-700 font-bold active:scale-95 transition-all">+</button>
        `}

        <div class="text-right ml-1 w-20">
          <p class="font-mono font-bold text-xs text-slate-900">${formatRp(item.price * item.qty)}</p>
        </div>

        <button onclick="removeCartItem('${item.cartId}')" class="p-1 text-slate-400 hover:text-red-600 transition-colors">
          <i data-lucide="x" class="w-3.5 h-3.5"></i>
        </button>
      </div>
    </div>
  `).join('');

  document.getElementById('cart-subtotal').textContent = formatRp(cartSubtotal());
  document.getElementById('cart-grandtotal').textContent = formatRp(cartGrandTotal());

  const discRow = document.getElementById('cart-discount-row');
  if (discountAmount > 0) {
    discRow.style.display = 'flex';
    document.getElementById('cart-discount').textContent = `- ${formatRp(discountAmount)}`;
  } else {
    discRow.style.display = 'none';
  }

  lucide.createIcons();
}

function updateCartQty(cartId, change) {
  const item = cart.find(c => c.cartId === cartId);
  if (!item) return;

  if (change > 0) {
    const currentDeduction = cart
      .filter(c => c.product.id === item.product.id)
      .reduce((sum, c) => sum + (c.conversion_factor * c.qty), 0);

    if (currentDeduction + item.conversion_factor > Number(item.product.current_stock)) {
      showToast(`Stok ${item.product.name} telah mencapai batas!`);
      return;
    }
    item.qty += 1;
  } else {
    item.qty -= 1;
    if (item.qty <= 0) cart = cart.filter(c => c.cartId !== cartId);
  }
  renderCart();
}

function removeCartItem(cartId) {
  cart = cart.filter(c => c.cartId !== cartId);
  renderCart();
}

function clearCart() {
  if (cart.length === 0) return;
  resetCartState();
  renderCart();
  showToast('Keranjang belanja telah dikosongkan.');
}

function handleDiscountChange(val) {
  discountAmount = Math.max(0, parseFloat(val) || 0);
  renderCart();
}

// Dipakai oleh hold.js dan checkout.js
function buildOrderItems() {
  return cart.map(item => ({
    product_id: item.product.id,
    product_unit_id: item.product_unit_id,
    unit_name: item.unit_name,
    quantity: item.qty,
    conversion_factor: item.conversion_factor,
    selling_price: item.price,
    subtotal: item.price * item.qty
  }));
}
