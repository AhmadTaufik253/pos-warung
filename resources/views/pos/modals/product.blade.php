<x-modal id="modal-product" size="max-w-lg" class="max-h-[90vh] flex flex-col">
  <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
    <h3 id="product-modal-title" class="font-bold text-sm text-slate-800 flex items-center gap-2">
      <i data-lucide="package-plus" class="w-4 h-4 text-brand-600"></i>
      <span>Tambah Produk Sembako Baru</span>
    </h3>
    <button onclick="closeProductModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-4 h-4"></i></button>
  </div>

  <form id="product-form" onsubmit="handleSaveProduct(event)" class="p-4 overflow-y-auto space-y-3.5 flex-1 text-xs">
    <input type="hidden" id="p-id">

    <div class="grid grid-cols-2 gap-3">
      <div>
        <label for="p-barcode" class="block font-semibold text-slate-700 mb-1">Barcode / SKU</label>
        <input type="text" id="p-barcode" placeholder="GLP-002" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500 font-mono">
      </div>
      <div>
        <div class="flex justify-between items-center mb-1">
          <label for="p-category-id" class="font-semibold text-slate-700">Kategori Sembako</label>
          <button type="button" onclick="quickAddCategory()" class="text-[10px] text-brand-700 hover:underline font-bold">+ Baru</button>
        </div>
        <select id="p-category-id" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500">
          <option value="">Umum (Tanpa Kategori)</option>
        </select>
      </div>
    </div>

    <div>
      <label for="p-name" class="block font-semibold text-slate-700 mb-1">Nama Barang Lengkap</label>
      <input type="text" id="p-name" required placeholder="Gula Pasir Gulaku Kuning" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500">
    </div>

    <div class="grid grid-cols-3 gap-2.5">
      <div>
        <label for="p-base-unit" class="block font-semibold text-slate-700 mb-1">Satuan Dasar</label>
        <input type="text" id="p-base-unit" required placeholder="kg / liter / pcs" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500">
      </div>
      <div>
        <label for="p-buy-price" class="block font-semibold text-slate-700 mb-1">Modal Dasar (Rp)</label>
        <input type="number" id="p-buy-price" required min="0" step="100" placeholder="14000" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500 font-mono">
      </div>
      <div>
        <label for="p-sell-price" class="block font-semibold text-slate-700 mb-1">Harga Jual Standar (Rp)</label>
        <input type="number" id="p-sell-price" required min="0" step="100" placeholder="17000" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500 font-mono">
      </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
      <div>
        <label for="p-stock" class="block font-semibold text-slate-700 mb-1">Stok Fisik Gudang</label>
        <input type="number" id="p-stock" required min="0" step="any" placeholder="50" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500 font-mono">
      </div>
      <div>
        <label for="p-min-stock" class="block font-semibold text-slate-700 mb-1">Batas Peringatan Stok Menipis</label>
        <input type="number" id="p-min-stock" min="0" step="any" value="5" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500 font-mono">
      </div>
    </div>

    <div class="p-3 bg-amber-50/80 border border-amber-200 rounded-xl flex items-center justify-between">
      <div>
        <span class="block font-bold text-amber-950 text-xs">Izinkan Fitur Beli Nominal (Rp)?</span>
        <span class="text-[10px] text-amber-700">Untuk barang curah timbangan/literan (cth: "Beli Minyak Rp 5.000").</span>
      </div>
      <label for="p-allow-nominal" class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" id="p-allow-nominal" class="sr-only peer">
        <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-600"></div>
      </label>
    </div>

    <div class="pt-2 border-t border-slate-200 space-y-2">
      <div class="flex items-center justify-between">
        <div>
          <span class="font-bold text-slate-800">Daftar Satuan Jual & Eceran (Multi-Satuan)</span>
          <p class="text-[10px] text-slate-400">Tentukan harga jual per sak, kilogram, setengah kilo, dsb.</p>
        </div>
        <button type="button" onclick="addUnitRow()" class="px-2.5 py-1 bg-brand-50 text-brand-700 hover:bg-brand-100 rounded-lg font-bold text-[11px] flex items-center gap-1">
          <i data-lucide="plus" class="w-3.5 h-3.5"></i>
          <span>Tambah Satuan</span>
        </button>
      </div>

      <div id="unit-rows-list" class="space-y-2"></div>
    </div>

    <div class="pt-3 border-t border-slate-200 flex justify-end gap-2">
      <button type="button" onclick="closeProductModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl">Batal</button>
      <button type="submit" id="btn-save-product" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl shadow-md">Simpan ke Database</button>
    </div>
  </form>
</x-modal>
