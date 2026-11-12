// ==========================================
// TAHAN & ANTREAN (HOLD & RECALL)
// ==========================================
function openHoldConfirmModal() {
  if (cart.length === 0) {
    showToast('Keranjang kosong! Tidak ada transaksi yang bisa ditahan.');
    return;
  }

  const totalItems = cart.reduce((s, c) => s + c.qty, 0);
  const timeStr = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

  document.getElementById('hold-label-input').value = `Antrean #${holdQueue.length + 1} (${timeStr})`;
  document.getElementById('hold-summary-items').textContent = `${totalItems} Item`;
  document.getElementById('hold-summary-total').textContent = formatRp(cartGrandTotal());

  document.getElementById('modal-hold-confirm').classList.remove('hidden');
  document.getElementById('hold-label-input').focus();
}

function closeHoldConfirmModal() {
  document.getElementById('modal-hold-confirm').classList.add('hidden');
}

async function executeHoldCart() {
  if (cart.length === 0) return;

  const label = document.getElementById('hold-label-input').value.trim() || `Antrean #${holdQueue.length + 1}`;
  const payload = { hold_label: label, items: buildOrderItems() };

  try {
    const res = await fetch(`${API_BASE}/orders/hold`, {
      method: 'POST',
      headers: getApiHeaders(),
      body: JSON.stringify(payload)
    });

    const json = await res.json();
    if (!res.ok) throw new Error(json.message || 'Gagal menahan antrean');

    resetCartState();
    closeHoldConfirmModal();
    renderCart();
    fetchHoldQueue();
    showToast(`Antrean "${label}" berhasil diparkir di database!`);
  } catch (err) {
    showToast('Gagal: ' + err.message);
  }
}

async function fetchHoldQueue() {
  try {
    const res = await fetch(`${API_BASE}/orders/hold/list`);
    const json = await res.json();
    if (json.success) {
      holdQueue = json.data;
      updateHoldBadge();
    }
  } catch (err) {
    console.error('Gagal mengambil daftar hold:', err);
  }
}

function updateHoldBadge() {
  const badge = document.getElementById('hold-count-badge');
  if (holdQueue.length > 0) {
    badge.textContent = holdQueue.length;
    badge.classList.remove('hidden');
  } else {
    badge.classList.add('hidden');
  }
}

function openHoldQueueModal() {
  fetchHoldQueue().then(() => {
    renderHoldQueueList();
    document.getElementById('modal-hold-queue').classList.remove('hidden');
  });
}

function closeHoldQueueModal() {
  document.getElementById('modal-hold-queue').classList.add('hidden');
}

function renderHoldQueueList() {
  const container = document.getElementById('hold-queue-items-container');
  document.getElementById('hold-queue-badge-count').textContent = `${holdQueue.length} Antrean`;

  if (holdQueue.length === 0) {
    container.innerHTML = `
      <div class="py-12 text-center text-slate-400">
        <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-1 text-slate-300"></i>
        <p class="text-xs font-semibold text-slate-600">Tidak ada antrean yang ditahan.</p>
      </div>
    `;
    lucide.createIcons();
    return;
  }

  container.innerHTML = holdQueue.map(item => {
    const itemNames = (item.items || []).map(c => `${c.product ? c.product.name : c.unit_name} (${c.quantity})`).join(', ');
    const timeStr = new Date(item.transaction_date).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    return `
      <div class="p-3 bg-amber-50/70 border border-amber-200 rounded-xl space-y-2">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-1.5">
            <span class="font-bold text-xs text-amber-950">${esc(item.hold_label)}</span>
            <span class="text-[10px] font-mono text-amber-700 bg-amber-100 px-1.5 py-0.2 rounded">${timeStr}</span>
          </div>
          <span class="font-mono font-bold text-xs text-brand-800">${formatRp(item.grand_total)}</span>
        </div>

        <p class="text-[11px] text-slate-500 truncate italic">${esc(itemNames)}</p>

        <div class="flex items-center justify-between pt-1 border-t border-amber-100">
          <span class="text-[10px] text-slate-400">${(item.items || []).length} jenis item</span>
          <div class="flex items-center gap-2">
            <button onclick="deleteHoldItem(${item.id})" class="text-xs font-semibold text-red-600 hover:text-red-800">Hapus</button>
            <button onclick="recallHoldItem(${item.id})" class="px-2.5 py-1 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-lg flex items-center gap-1">
              <i data-lucide="play" class="w-3.5 h-3.5"></i>
              <span>Panggil ke Kasir</span>
            </button>
          </div>
        </div>
      </div>
    `;
  }).join('');

  lucide.createIcons();
}

async function recallHoldItem(orderId) {
  const selected = holdQueue.find(h => h.id === orderId);
  if (!selected) return;

  if (cart.length > 0 && !confirm('Keranjang aktif saat ini masih ada barang. Apakah Anda ingin menimpanya?')) return;

  cart = selected.items.map(it => ({
    cartId: 'item-' + Date.now() + Math.random(),
    product: it.product || { id: it.product_id, name: it.unit_name, current_stock: 999 },
    product_unit_id: it.product_unit_id,
    unit_name: it.unit_name,
    conversion_factor: Number(it.conversion_factor),
    price: Number(it.selling_price),
    qty: Number(it.quantity),
    isNominal: !it.product_unit_id && it.unit_name.includes('Nominal')
  }));

  discountAmount = Number(selected.discount_amount) || 0;
  document.getElementById('input-discount-val').value = discountAmount || '';

  await fetch(`${API_BASE}/orders/hold/${orderId}`, {
    method: 'DELETE',
    headers: getApiHeaders()
  });

  closeHoldQueueModal();
  fetchHoldQueue();
  renderCart();
  playBeep(850, 0.12);
  showToast(`Antrean "${selected.hold_label}" berhasil dimuat ke kasir!`);
}

async function deleteHoldItem(orderId) {
  if (!confirm('Hapus antrean belanja ini dari database?')) return;

  await fetch(`${API_BASE}/orders/hold/${orderId}`, {
    method: 'DELETE',
    headers: getApiHeaders()
  });
  await fetchHoldQueue();
  renderHoldQueueList();
}
