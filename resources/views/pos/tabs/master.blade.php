{{-- TAB: MASTER DATA BARANG (F2) --}}
<section id="tab-content-master" class="hidden space-y-5">
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Produk Sembako</p>
        <h3 id="stat-total-products" class="text-2xl font-black font-mono text-slate-800 mt-1">0</h3>
      </div>
      <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
        <i data-lucide="package" class="w-6 h-6"></i>
      </div>
    </div>

    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Nilai Aset Stok (Modal)</p>
        <h3 id="stat-total-asset-value" class="text-xl sm:text-2xl font-black font-mono text-brand-700 mt-1">Rp 0</h3>
      </div>
      <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
        <i data-lucide="wallet" class="w-6 h-6"></i>
      </div>
    </div>

    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Menipis / Kritis</p>
        <h3 id="stat-low-stock-count" class="text-2xl font-black font-mono text-amber-600 mt-1">0</h3>
      </div>
      <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
        <i data-lucide="alert-triangle" class="w-6 h-6"></i>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 bg-slate-50/70">
      <div class="flex items-center gap-2 flex-1 max-w-md">
        <div class="relative w-full">
          <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none"></i>
          <input type="text" id="master-search-input" oninput="renderMasterTable()" placeholder="Cari barcode atau nama barang..." class="w-full pl-9 pr-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-medium outline-none focus:border-brand-500">
        </div>
      </div>

      <div class="flex items-center gap-2">
        <button onclick="manageCategoriesModal()" class="px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold flex items-center gap-1.5 transition-colors shadow-xs">
          <i data-lucide="tag" class="w-4 h-4 text-slate-500"></i>
          <span>Kelola Kategori</span>
        </button>
        <button onclick="exportMasterCSV()" class="px-3 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-xs">
          <i data-lucide="download" class="w-4 h-4 text-slate-500"></i>
          <span>Ekspor CSV</span>
        </button>
        <button onclick="openProductModal()" class="px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold flex items-center gap-1.5 transition-all shadow-md shadow-brand-600/20">
          <i data-lucide="plus-circle" class="w-4 h-4"></i>
          <span>Tambah Produk Baru</span>
        </button>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs text-slate-700">
        <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
          <tr>
            <th class="py-3 px-4">Barcode/SKU</th>
            <th class="py-3 px-4">Nama Barang & Satuan Dasar</th>
            <th class="py-3 px-4">Kategori</th>
            <th class="py-3 px-4 text-right">Modal Pokok (HPP)</th>
            <th class="py-3 px-4">Multi-Satuan Jual (UOM)</th>
            <th class="py-3 px-4 text-center">Stok Fisik</th>
            <th class="py-3 px-4 text-center">Status</th>
            <th class="py-3 px-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody id="master-table-body" class="divide-y divide-slate-100"></tbody>
      </table>
    </div>
  </div>
</section>
