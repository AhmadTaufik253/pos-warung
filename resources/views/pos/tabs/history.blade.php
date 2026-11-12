{{-- TAB: RIWAYAT TRANSAKSI (F3) --}}
<section id="tab-content-history" class="hidden space-y-5">
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
      <p class="text-xs font-semibold text-slate-500">Total Omset Selesai</p>
      <h3 id="stat-total-sales" class="text-lg sm:text-2xl font-black font-mono text-brand-700 mt-1">Rp 0</h3>
    </div>
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
      <p class="text-xs font-semibold text-slate-500">Estimasi Keuntungan (Laba)</p>
      <h3 id="stat-total-profit" class="text-lg sm:text-2xl font-black font-mono text-emerald-600 mt-1">Rp 0</h3>
    </div>
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
      <p class="text-xs font-semibold text-slate-500">Total Transaksi</p>
      <h3 id="stat-total-invoices" class="text-lg sm:text-2xl font-black font-mono text-slate-800 mt-1">0</h3>
    </div>
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
      <p class="text-xs font-semibold text-slate-500">Total Item Terjual</p>
      <h3 id="stat-total-items-sold" class="text-lg sm:text-2xl font-black font-mono text-blue-600 mt-1">0</h3>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 bg-slate-50/70">
      <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
        <i data-lucide="file-text" class="w-4 h-4 text-brand-600"></i>
        <span>Daftar Transaksi Kasir (Database)</span>
      </h3>
      <div class="flex items-center gap-2">
        <button onclick="fetchOrders()" class="p-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 text-slate-600" title="Refresh Riwayat">
          <i data-lucide="refresh-cw" class="w-4 h-4"></i>
        </button>
        <select id="history-filter-method" onchange="renderHistoryTable()" class="px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-xs font-semibold outline-none">
          <option value="ALL">Semua Pembayaran</option>
          <option value="cash">Tunai</option>
          <option value="qris">QRIS</option>
          <option value="transfer">Transfer</option>
          <option value="debt">Kasbon / Tempo</option>
        </select>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs text-slate-700">
        <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
          <tr>
            <th class="py-3 px-4">No. Invoice</th>
            <th class="py-3 px-4">Waktu</th>
            <th class="py-3 px-4">Pelanggan</th>
            <th class="py-3 px-4 text-center">Metode</th>
            <th class="py-3 px-4 text-right">Subtotal</th>
            <th class="py-3 px-4 text-right">Potongan</th>
            <th class="py-3 px-4 text-right">Total Akhir</th>
            <th class="py-3 px-4 text-right text-emerald-700">Estimasi Laba</th>
            <th class="py-3 px-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody id="history-table-body" class="divide-y divide-slate-100"></tbody>
      </table>
    </div>
  </div>
</section>
