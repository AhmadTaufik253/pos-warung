<x-modal id="modal-hold-confirm" size="max-w-sm">
  <div class="p-4 bg-amber-50 border-b border-amber-100 flex items-center justify-between">
    <div class="flex items-center gap-2">
      <i data-lucide="pause-circle" class="w-5 h-5 text-amber-600"></i>
      <h3 class="font-bold text-sm text-slate-800">Tahan / Parkir Belanjaan</h3>
    </div>
    <button onclick="closeHoldConfirmModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-4 h-4"></i></button>
  </div>

  <div class="p-5 space-y-4">
    <div>
      <label for="hold-label-input" class="block text-xs font-semibold text-slate-700 mb-1">Nama / Keterangan Antrean:</label>
      <input type="text" id="hold-label-input" placeholder="Contoh: Ibu Rina (ambil dompet di motor)" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold outline-none focus:border-amber-500 focus:bg-white">
      <p class="text-[10px] text-slate-400 mt-1">Antrean disimpan ke database dan stok belum dikurangi.</p>
    </div>

    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs space-y-1">
      <div class="flex justify-between">
        <span class="text-slate-500">Jumlah Barang:</span>
        <span id="hold-summary-items" class="font-bold text-slate-700">0 Item</span>
      </div>
      <div class="flex justify-between">
        <span class="text-slate-500">Total Sementara:</span>
        <span id="hold-summary-total" class="font-mono font-bold text-brand-700">Rp 0</span>
      </div>
    </div>

    <div class="flex gap-2">
      <button type="button" onclick="closeHoldConfirmModal()" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
      <button type="button" onclick="executeHoldCart()" class="flex-1 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs shadow-md">Simpan ke Antrean</button>
    </div>
  </div>
</x-modal>
