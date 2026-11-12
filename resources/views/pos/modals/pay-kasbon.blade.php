<x-modal id="modal-pay-kasbon" size="max-w-sm">
  <div class="p-4 bg-amber-50 border-b border-amber-100 flex items-center justify-between">
    <div class="flex items-center gap-2">
      <i data-lucide="hand-coins" class="w-5 h-5 text-amber-600"></i>
      <h3 class="font-bold text-sm text-slate-800">Pembayaran Kasbon / Piutang</h3>
    </div>
    <button onclick="closePayKasbonModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-4 h-4"></i></button>
  </div>

  <div class="p-5 space-y-4">
    <input type="hidden" id="pk-kasbon-id">

    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs space-y-1">
      <div class="flex justify-between">
        <span class="text-slate-500">Nama Pelanggan:</span>
        <span id="pk-cust-name" class="font-bold text-slate-800">-</span>
      </div>
      <div class="flex justify-between items-baseline pt-1 border-t border-slate-200/60">
        <span class="text-slate-500">Sisa Hutang:</span>
        <span id="pk-cust-balance" class="text-base font-black font-mono text-red-600">Rp 0</span>
      </div>
    </div>

    <div>
      <label for="pk-amount-input" class="block text-xs font-semibold text-slate-700 mb-1">Nominal Cicil / Pelunasan (Rp):</label>
      <div class="relative">
        <span class="absolute left-3 top-2.5 text-slate-400 font-bold font-mono text-sm">Rp</span>
        <input type="number" id="pk-amount-input" min="1000" step="1000" placeholder="0" class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-bold font-mono outline-none focus:border-amber-500 focus:bg-white">
      </div>
      <button type="button" onclick="setPayKasbonFull()" class="text-[11px] font-bold text-amber-700 hover:underline mt-1 block">Bayar Lunas Semua</button>
    </div>

    <div class="flex gap-2">
      <button type="button" onclick="closePayKasbonModal()" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
      <button type="button" onclick="confirmPayKasbon()" class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md">Simpan Pembayaran</button>
    </div>
  </div>
</x-modal>
