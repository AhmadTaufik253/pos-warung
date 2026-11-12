{{-- TAB: BUKU KASBON / PIUTANG (F4) --}}
<section id="tab-content-kasbon" class="hidden space-y-5">
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Piutang Belum Lunas</p>
        <h3 id="stat-kasbon-unpaid" class="text-xl sm:text-2xl font-black font-mono text-red-600 mt-1">Rp 0</h3>
      </div>
      <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
        <i data-lucide="clock" class="w-6 h-6"></i>
      </div>
    </div>

    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Pelanggan Kasbon</p>
        <h3 id="stat-kasbon-customer-count" class="text-2xl font-black font-mono text-slate-800 mt-1">0 Orang</h3>
      </div>
      <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
        <i data-lucide="users" class="w-6 h-6"></i>
      </div>
    </div>

    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Piutang Berhasil Diterima</p>
        <h3 id="stat-kasbon-paid" class="text-xl sm:text-2xl font-black font-mono text-emerald-600 mt-1">Rp 0</h3>
      </div>
      <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
        <i data-lucide="check-circle-2" class="w-6 h-6"></i>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 bg-slate-50/70">
      <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
        <i data-lucide="book-marked" class="w-4 h-4 text-amber-600"></i>
        <span>Catatan Hutang / Kasbon Langganan Warung</span>
      </h3>
      <div class="flex items-center gap-2">
        <button onclick="fetchDebts()" class="p-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 text-slate-600" title="Refresh Kasbon">
          <i data-lucide="refresh-cw" class="w-4 h-4"></i>
        </button>
        <input type="text" id="kasbon-search-input" oninput="renderKasbonTable()" placeholder="Cari nama langganan..." class="px-3 py-1.5 bg-white border border-slate-300 rounded-xl text-xs font-medium outline-none focus:border-brand-500">
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs text-slate-700">
        <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
          <tr>
            <th class="py-3 px-4">Nama Pelanggan</th>
            <th class="py-3 px-4">Kontak / No. HP</th>
            <th class="py-3 px-4">Faktur / Tanggal</th>
            <th class="py-3 px-4 text-right">Total Kasbon Awal</th>
            <th class="py-3 px-4 text-right text-red-600">Sisa Hutang Belum Dibayar</th>
            <th class="py-3 px-4 text-center">Status</th>
            <th class="py-3 px-4 text-center">Aksi Pelunasan</th>
          </tr>
        </thead>
        <tbody id="kasbon-table-body" class="divide-y divide-slate-100"></tbody>
      </table>
    </div>
  </div>
</section>
