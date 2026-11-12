<x-modal id="modal-checkout" size="max-w-md">
  <div class="p-4 bg-brand-800 text-white flex items-center justify-between">
    <div class="flex items-center gap-2">
      <i data-lucide="credit-card" class="w-5 h-5 text-brand-300"></i>
      <h3 class="font-bold text-sm">Pembayaran Kasir</h3>
    </div>
    <button onclick="closeCheckoutModal()" class="text-white/80 hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
  </div>

  <div class="p-5 space-y-4">
    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-center">
      <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">Total Tagihan Belanja</span>
      <span id="checkout-total-bill" class="text-2xl sm:text-3xl font-black font-mono text-brand-700">Rp 0</span>
    </div>

    <div>
      <span class="block text-xs font-semibold text-slate-600 mb-1.5">Pilih Metode Pembayaran:</span>
      <div class="grid grid-cols-4 gap-1.5 bg-slate-100 p-1 rounded-xl text-xs font-bold text-center">
        <button type="button" onclick="setPaymentMethod('cash')" id="pm-cash" class="pm-tab py-2 rounded-lg bg-white text-brand-900 shadow-xs">Tunai</button>
        <button type="button" onclick="setPaymentMethod('qris')" id="pm-qris" class="pm-tab py-2 rounded-lg text-slate-600 hover:text-slate-900">QRIS</button>
        <button type="button" onclick="setPaymentMethod('transfer')" id="pm-transfer" class="pm-tab py-2 rounded-lg text-slate-600 hover:text-slate-900">Transfer</button>
        <button type="button" onclick="setPaymentMethod('debt')" id="pm-debt" class="pm-tab py-2 rounded-lg text-amber-700 hover:bg-amber-100">Kasbon</button>
      </div>
    </div>

    {{-- TUNAI --}}
    <div id="section-pay-tunai" class="space-y-3">
      <div>
        <span class="block text-xs font-semibold text-slate-600 mb-1">Pecahan Uang Tunai Cepat:</span>
        <div class="grid grid-cols-3 gap-1.5 text-xs font-mono font-bold" id="quick-cash-buttons"></div>
      </div>

      <div>
        <label for="cash-received-input" class="block text-xs font-semibold text-slate-700 mb-1">Nominal Uang Diterima (Rp):</label>
        <input type="number" id="cash-received-input" min="0" step="500" oninput="calcCashChange()" placeholder="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-lg font-black font-mono outline-none focus:border-brand-600 focus:bg-white">
      </div>

      <div class="bg-emerald-50 p-3 rounded-xl border border-emerald-200 flex justify-between items-center text-xs">
        <span class="font-bold text-emerald-900">Uang Kembalian:</span>
        <span id="cash-change-display" class="font-mono text-base font-black text-emerald-700">Rp 0</span>
      </div>
    </div>

    {{-- QRIS / TRANSFER --}}
    <div id="section-pay-digital" class="hidden text-center p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
      <div class="w-12 h-12 bg-white rounded-xl mx-auto flex items-center justify-center border border-slate-200 shadow-xs">
        <i data-lucide="qr-code" class="w-6 h-6 text-brand-700"></i>
      </div>
      <p class="text-xs font-bold text-slate-700" id="digital-pay-title">Scan QRIS Warung</p>
      <p class="text-[11px] text-slate-500">Pastikan notifikasi dana masuk telah diterima sebelum menyelesaikan transaksi.</p>
    </div>

    {{-- KASBON --}}
    <div id="section-pay-kasbon" class="hidden space-y-2.5 bg-amber-50/70 p-3.5 rounded-xl border border-amber-200">
      <div>
        <label for="kasbon-cust-name" class="block text-[11px] font-bold text-amber-900 mb-0.5">Nama Pelanggan (Wajib):</label>
        <input type="text" id="kasbon-cust-name" placeholder="Contoh: Bu Hj. Maryam / Pak Joko" class="w-full px-3 py-1.5 bg-white border border-amber-300 rounded-lg text-xs font-semibold outline-none focus:border-amber-600">
      </div>
      <div>
        <label for="kasbon-notes" class="block text-[11px] font-bold text-amber-900 mb-0.5">Catatan / Rencana Bayar:</label>
        <input type="text" id="kasbon-notes" placeholder="Janji bayar saat gajian" class="w-full px-3 py-1.5 bg-white border border-amber-300 rounded-lg text-xs outline-none focus:border-amber-600">
      </div>
    </div>

    <button type="button" onclick="finalizeTransaction()" id="btn-submit-pay" class="w-full py-3 bg-brand-600 hover:bg-brand-700 active:scale-[0.99] text-white font-bold rounded-xl text-sm flex items-center justify-center gap-2 shadow-lg shadow-brand-600/30 transition-all cursor-pointer">
      <i data-lucide="printer" class="w-4 h-4"></i>
      <span>Selesaikan Transaksi</span>
    </button>
  </div>
</x-modal>
