// ==========================================
// BUKU KASBON / PIUTANG (F4)
// ==========================================
async function fetchDebts() {
  try {
    const res = await fetch(`${API_BASE}/debts`);
    const json = await res.json();
    if (json.success) {
      kasbonList = json.data;
      renderKasbonTable();
    }
  } catch (err) {
    console.error('Gagal mengambil kasbon:', err);
  }
}

function renderKasbonTable() {
  const tbody = document.getElementById('kasbon-table-body');
  const query = (document.getElementById('kasbon-search-input').value || '').toLowerCase().trim();

  const filtered = kasbonList.filter(k =>
    (k.customer && k.customer.name.toLowerCase().includes(query)) ||
    (k.customer && k.customer.phone && k.customer.phone.includes(query))
  );

  const unpaidTotal = kasbonList.reduce((sum, k) => sum + Number(k.remaining_balance), 0);
  const paidTotal = kasbonList.reduce((sum, k) => sum + (Number(k.original_amount) - Number(k.remaining_balance)), 0);
  const unpaidCount = kasbonList.filter(k => Number(k.remaining_balance) > 0).length;

  document.getElementById('stat-kasbon-unpaid').textContent = formatRp(unpaidTotal);
  document.getElementById('stat-kasbon-customer-count').textContent = `${unpaidCount} Transaksi`;
  document.getElementById('stat-kasbon-paid').textContent = formatRp(paidTotal);
  document.getElementById('kasbon-nav-badge').classList.toggle('hidden', unpaidTotal <= 0);

  if (filtered.length === 0) {
    tbody.innerHTML = `<tr><td colspan="7" class="text-center py-8 text-slate-400">Tidak ada catatan kasbon di database.</td></tr>`;
    return;
  }

  tbody.innerHTML = filtered.map(k => {
    const isLunas = Number(k.remaining_balance) <= 0;
    return `
      <tr class="hover:bg-slate-50 transition-colors">
        <td class="py-3 px-4 font-bold text-slate-800">${esc(k.customer ? k.customer.name : '-')}</td>
        <td class="py-3 px-4 font-mono text-slate-500">${esc(k.customer && k.customer.phone ? k.customer.phone : '-')}</td>
        <td class="py-3 px-4 text-slate-600">
          <span class="font-mono text-xs font-semibold text-slate-700">${esc(k.order ? k.order.invoice_number : '-')}</span>
          <div class="text-[10px] text-slate-400 font-mono mt-0.5">${new Date(k.created_at).toLocaleDateString('id-ID')}</div>
        </td>
        <td class="py-3 px-4 text-right font-mono">${formatRp(k.original_amount)}</td>
        <td class="py-3 px-4 text-right font-mono font-bold text-red-600">${formatRp(k.remaining_balance)}</td>
        <td class="py-3 px-4 text-center">
          <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${isLunas ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'}">${esc(k.status.toUpperCase())}</span>
        </td>
        <td class="py-3 px-4 text-center">
          ${!isLunas ? `
            <button onclick="openPayKasbonModal(${k.id})" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold flex items-center gap-1 mx-auto shadow-xs">
              <i data-lucide="hand-coins" class="w-3.5 h-3.5"></i>
              <span>Bayar / Cicil</span>
            </button>
          ` : `<span class="text-[11px] text-slate-400 font-semibold italic">Telah Lunas</span>`}
        </td>
      </tr>
    `;
  }).join('');

  lucide.createIcons();
}

function openPayKasbonModal(debtId) {
  const k = kasbonList.find(item => item.id === debtId);
  if (!k) return;

  document.getElementById('pk-kasbon-id').value = k.id;
  document.getElementById('pk-cust-name').textContent = k.customer ? k.customer.name : '-';
  document.getElementById('pk-cust-balance').textContent = formatRp(k.remaining_balance);
  document.getElementById('pk-amount-input').value = k.remaining_balance;

  document.getElementById('modal-pay-kasbon').classList.remove('hidden');
  document.getElementById('pk-amount-input').focus();
}

function closePayKasbonModal() {
  document.getElementById('modal-pay-kasbon').classList.add('hidden');
}

function setPayKasbonFull() {
  const id = parseInt(document.getElementById('pk-kasbon-id').value, 10);
  const k = kasbonList.find(item => item.id === id);
  if (k) document.getElementById('pk-amount-input').value = k.remaining_balance;
}

async function confirmPayKasbon() {
  const id = parseInt(document.getElementById('pk-kasbon-id').value, 10);
  const amount = parseFloat(document.getElementById('pk-amount-input').value) || 0;

  if (amount <= 0) {
    showToast('Masukkan nominal pelunasan kasbon yang valid!');
    return;
  }

  const k = kasbonList.find(item => item.id === id);
  if (!k) return;

  if (amount > Number(k.remaining_balance)) {
    showToast('Nominal pembayaran melebihi sisa hutang!');
    return;
  }

  try {
    const res = await fetch(`${API_BASE}/debts/${id}/pay`, {
      method: 'POST',
      headers: getApiHeaders(),
      body: JSON.stringify({ amount: amount, payment_method: 'cash', notes: 'Pelunasan Kasbon Kasir' })
    });

    const json = await res.json();
    if (!res.ok) throw new Error(json.message || 'Gagal memproses pembayaran kasbon');

    closePayKasbonModal();
    fetchDebts();
    playBeep(900, 0.15);
    showToast(`Pembayaran kasbon sebesar ${formatRp(amount)} berhasil dicatat.`);
  } catch (err) {
    showToast('Gagal: ' + err.message);
  }
}
