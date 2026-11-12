<x-modal id="modal-restock" size="max-w-xs" class="p-5 space-y-4">
  <div class="flex items-center justify-between border-b border-slate-100 pb-2">
    <h3 class="font-bold text-xs text-slate-800 flex items-center gap-1.5">
      <i data-lucide="sliders" class="w-4 h-4 text-emerald-600"></i>
      <span>Restock Stok Kilat</span>
    </h3>
    <button onclick="closeRestockModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-4 h-4"></i></button>
  </div>

  <input type="hidden" id="restock-prod-id">
  <p id="restock-prod-name" class="font-bold text-xs text-brand-800">Nama Barang</p>

  <div>
    <label for="restock-qty-input" class="block text-[11px] font-semibold text-slate-600 mb-1">Jumlah Tambahan Stok (Satuan Dasar):</label>
    <input type="number" id="restock-qty-input" min="0.1" step="any" placeholder="Contoh: 50" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-base font-bold font-mono outline-none focus:border-brand-500">
  </div>

  <div class="flex gap-2">
    <button onclick="closeRestockModal()" class="flex-1 py-2 bg-slate-100 text-slate-600 font-semibold rounded-xl text-xs">Batal</button>
    <button onclick="executeRestock()" class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md">Simpan Stok</button>
  </div>
</x-modal>
