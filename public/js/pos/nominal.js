// ==========================================
// BELI NOMINAL (RP)
// ==========================================
function openNominalModal(productId) {
  activeNominalProductId = productId;
  const product = products.find(p => p.id === productId);
  if (!product) return;

  document.getElementById('nominal-product-name').textContent = `${product.name} (${product.barcode || '-'})`;
  document.getElementById('nominal-base-price').textContent = `${formatRp(product.base_sell_price)} / ${product.base_unit}`;
  document.getElementById('nominal-money-input').value = '5000';

  calcNominalEquivalent();
  document.getElementById('modal-nominal').classList.remove('hidden');
  document.getElementById('nominal-money-input').focus();
}

function closeNominalModal() {
  document.getElementById('modal-nominal').classList.add('hidden');
  activeNominalProductId = null;
}

function setNominalInput(val) {
  document.getElementById('nominal-money-input').value = val;
  calcNominalEquivalent();
}

function calcNominalEquivalent() {
  const money = parseFloat(document.getElementById('nominal-money-input').value) || 0;
  const product = products.find(p => p.id === activeNominalProductId);
  if (!product) return;

  const basePrice = Number(product.base_sell_price);
  const qty = basePrice > 0 ? (money / basePrice) : 0;
  document.getElementById('nominal-calc-qty').textContent = `${qty.toFixed(3)} ${product.base_unit}`;
}

function confirmAddNominalToCart() {
  const money = parseFloat(document.getElementById('nominal-money-input').value) || 0;
  if (money <= 0) {
    showToast('Masukkan nominal uang yang valid.');
    return;
  }

  const product = products.find(p => p.id === activeNominalProductId);
  if (!product) return;

  const qtyEquivalent = money / Number(product.base_sell_price);

  if (qtyEquivalent > Number(product.current_stock)) {
    showToast(`Stok ${product.name} hanya tersisa ${product.current_stock} ${product.base_unit}!`);
    return;
  }

  cart.push({
    cartId: 'nom-' + Date.now(),
    product: product,
    product_unit_id: null,
    unit_name: `Nominal Rp ${money.toLocaleString('id-ID')} (~${qtyEquivalent.toFixed(3)} ${product.base_unit})`,
    conversion_factor: 1.0,
    price: money,
    qty: 1,
    isNominal: true
  });

  playBeep(800, 0.1);
  closeNominalModal();
  renderCart();
  showToast(`Beli nominal ${formatRp(money)} ditambahkan.`);
}
