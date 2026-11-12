// ==========================================
// RIWAYAT TRANSAKSI (F3)
// ==========================================
async function fetchOrders() {
  try {
    const res = await fetch(`${API_BASE}/orders`);
    const json = await res.json();
    if (json.success) {
      transactions = json.data.data;
      renderHistoryTable();
    }
  } catch (err) {
    console.error('Gagal mengambil pesanan:', err);
  }
}

function renderHistoryTable() {
  const tbody = document.getElementById('history-table-body');
  const methodFilter = document.getElementById('history-filter-method').value;

  const filtered = transactions.filter(t => methodFilter === 'ALL' || t.payment_method === methodFilter);

  const totalSales = transactions.reduce((sum, t) => sum + Number(t.grand_total), 0);
  const totalProfit = transactions.reduce((sum, t) => sum + (Number(t.grand_total) - Number(t.total_cogs)), 0);
  const totalItems = transactions.reduce((sum, t) => sum + (t.items ? t.items.reduce((s, i) => s + Number(i.quantity), 0) : 0), 0);

  document.getElementById('stat-total-sales').textContent = formatRp(totalSales);
  document.getElementById('stat-total-profit').textContent = formatRp(totalProfit);
  document.getElementById('stat-total-invoices').textContent = transactions.length;
  document.getElementById('stat-total-items-sold').textContent = totalItems.toFixed(0);

  if (filtered.length === 0) {
    tbody.innerHTML = `<tr><td colspan="9" class="text-center py-8 text-slate-400">Belum ada riwayat transaksi di database.</td></tr>`;
    return;
  }

  const methodBadge = {
    cash: 'bg-emerald-100 text-emerald-800',
    qris: 'bg-blue-100 text-blue-800',
    transfer: 'bg-purple-100 text-purple-800',
  };

  tbody.innerHTML = filtered.map(t => {
    const profit = Number(t.grand_total) - Number(t.total_cogs);
    return `
      <tr class="hover:bg-slate-50 transition-colors">
        <td class="py-3 px-4 font-mono font-bold text-slate-800">${esc(t.invoice_number)}</td>
        <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">${new Date(t.transaction_date).toLocaleString('id-ID')}</td>
        <td class="py-3 px-4 font-semibold text-slate-700">${esc(t.customer ? t.customer.name : 'Pelanggan Umum')}</td>
        <td class="py-3 px-4 text-center">
          <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${methodBadge[t.payment_method] || 'bg-amber-100 text-amber-800'}">${esc(t.payment_method.toUpperCase())}</span>
        </td>
        <td class="py-3 px-4 text-right font-mono">${formatRp(t.total_amount)}</td>
        <td class="py-3 px-4 text-right font-mono text-slate-400">${Number(t.discount_amount) > 0 ? '-' + formatRp(t.discount_amount) : '-'}</td>
        <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">${formatRp(t.grand_total)}</td>
        <td class="py-3 px-4 text-right font-mono font-bold text-emerald-700">+${formatRp(profit)}</td>
        <td class="py-3 px-4 text-center">
          <button onclick="reprintReceipt(${t.id})" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold flex items-center gap-1 mx-auto">
            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
            <span>Struk</span>
          </button>
        </td>
      </tr>
    `;
  }).join('');

  lucide.createIcons();
}

async function reprintReceipt(orderId) {
  try {
    const res = await fetch(`${API_BASE}/orders/${orderId}`);
    const json = await res.json();
    if (json.success) showReceiptModal(json.data);
  } catch (err) {
    showToast('Gagal memuat struk pesanan.');
  }
}
