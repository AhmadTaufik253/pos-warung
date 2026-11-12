// ==========================================
// CHECKOUT & PEMBAYARAN (F8)
// ==========================================
function openCheckoutModal() {
  if (cart.length === 0) {
    showToast('Keranjang masih kosong!');
    return;
  }

  const grandTotal = cartGrandTotal();

  document.getElementById('checkout-total-bill').textContent = formatRp(grandTotal);
  setPaymentMethod('cash');
  buildQuickCashButtons(grandTotal);

  document.getElementById('cash-received-input').value = grandTotal;
  calcCashChange();

  document.getElementById('modal-checkout').classList.remove('hidden');
  document.getElementById('cash-received-input').focus();
  document.getElementById('cash-received-input').select();
}

function closeCheckoutModal() {
  document.getElementById('modal-checkout').classList.add('hidden');
}

function setPaymentMethod(method) {
  currentPaymentMethod = method;

  ['cash', 'qris', 'transfer', 'debt'].forEach(m => {
    const btn = document.getElementById(`pm-${m}`);
    if (m === method) {
      btn.classList.add('bg-white', 'text-brand-900', 'shadow-xs');
      btn.classList.remove('text-slate-600', 'text-amber-700');
    } else {
      btn.classList.remove('bg-white', 'text-brand-900', 'shadow-xs');
      btn.classList.add(m === 'debt' ? 'text-amber-700' : 'text-slate-600');
    }
  });

  const tunaiSec = document.getElementById('section-pay-tunai');
  const digitalSec = document.getElementById('section-pay-digital');
  const kasbonSec = document.getElementById('section-pay-kasbon');

  tunaiSec.classList.add('hidden');
  digitalSec.classList.add('hidden');
  kasbonSec.classList.add('hidden');

  if (method === 'cash') {
    tunaiSec.classList.remove('hidden');
    document.getElementById('cash-received-input').focus();
  } else if (method === 'qris' || method === 'transfer') {
    digitalSec.classList.remove('hidden');
    document.getElementById('digital-pay-title').textContent = method === 'qris' ? 'Scan Barcode QRIS Warung' : 'Transfer Rekening Bank Warung';
  } else if (method === 'debt') {
    kasbonSec.classList.remove('hidden');
    document.getElementById('kasbon-cust-name').focus();
  }
}

function buildQuickCashButtons(total) {
  const container = document.getElementById('quick-cash-buttons');
  const nominations = [total, 10000, 20000, 50000, 100000, 200000];
  const unique = Array.from(new Set(nominations)).filter(n => n >= total);

  container.innerHTML = unique.slice(0, 6).map((nom, idx) => `
    <button type="button" onclick="setCashReceived(${nom})" class="py-2 px-1 bg-slate-100 hover:bg-brand-100 text-slate-800 rounded-lg border border-slate-200 transition-colors ${idx === 0 ? 'border-brand-500 bg-brand-50 text-brand-900 font-black' : ''}">
      ${idx === 0 ? 'Uang Pas' : formatRp(nom)}
    </button>
  `).join('');
}

function setCashReceived(val) {
  document.getElementById('cash-received-input').value = val;
  calcCashChange();
}

function calcCashChange() {
  const received = parseFloat(document.getElementById('cash-received-input').value) || 0;
  const change = received - cartGrandTotal();

  const changeEl = document.getElementById('cash-change-display');
  if (change < 0) {
    changeEl.textContent = `Kurang ${formatRp(Math.abs(change))}`;
    changeEl.classList.remove('text-emerald-700');
    changeEl.classList.add('text-red-600');
  } else {
    changeEl.textContent = formatRp(change);
    changeEl.classList.remove('text-red-600');
    changeEl.classList.add('text-emerald-700');
  }
}

async function finalizeTransaction() {
  const grandTotal = cartGrandTotal();
  let cashTendered = grandTotal;

  if (currentPaymentMethod === 'cash') {
    cashTendered = parseFloat(document.getElementById('cash-received-input').value) || 0;
    if (cashTendered < grandTotal) {
      showToast('Uang tunai pembayaran masih kurang!');
      return;
    }
  }

  let customerName = null;
  if (currentPaymentMethod === 'debt') {
    customerName = document.getElementById('kasbon-cust-name').value.trim();
    if (!customerName) {
      showToast('Wajib mengisi nama pelanggan untuk Kasbon!');
      return;
    }
  }

  const payload = {
    payment_method: currentPaymentMethod,
    discount_amount: discountAmount,
    cash_tendered: cashTendered,
    customer_name: customerName,
    cashier_note: currentPaymentMethod === 'debt' ? document.getElementById('kasbon-notes').value.trim() : null,
    items: buildOrderItems()
  };

  const btnPay = document.getElementById('btn-submit-pay');
  btnPay.disabled = true;
  btnPay.innerHTML = 'Memproses Transaksi...';

  try {
    const res = await fetch(`${API_BASE}/orders/checkout`, {
      method: 'POST',
      headers: getApiHeaders(),
      body: JSON.stringify(payload)
    });

    const json = await res.json();
    if (!res.ok) throw new Error(json.message || 'Gagal checkout transaksi');

    closeCheckoutModal();
    showReceiptModal(json.data);

    resetCartState();
    renderCart();

    fetchProducts();
    fetchDebts();
    playBeep(950, 0.2, 'triangle');
    showToast('Transaksi berhasil dicatat ke server!');
  } catch (err) {
    showToast('Kesalahan: ' + err.message);
  } finally {
    btnPay.disabled = false;
    btnPay.innerHTML = `<i data-lucide="printer" class="w-4 h-4"></i><span>Selesaikan Transaksi</span>`;
    lucide.createIcons();
  }
}
