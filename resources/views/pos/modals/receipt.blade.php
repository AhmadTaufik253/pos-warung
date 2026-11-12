<x-modal id="modal-receipt" size="max-w-sm">
  <div class="p-3 bg-slate-800 text-white flex items-center justify-between no-print">
    <span class="text-xs font-bold flex items-center gap-1.5">
      <i data-lucide="receipt" class="w-4 h-4 text-brand-300"></i>
      Struk Belanja Thermal
    </span>
    <button onclick="closeReceiptModal()" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
  </div>

  <div class="p-4 bg-white font-mono text-[11px] text-slate-800 leading-tight space-y-2" id="printable-receipt">
    <div class="text-center border-b border-dashed border-slate-300 pb-2">
      <h2 class="font-bold text-sm tracking-tight uppercase">Warung Paing</h2>
      <p class="text-[10px] text-slate-500">Jl. Mujahidin, No. 83, Parigi Baru, Pondok Aren</p>
      <p class="text-[10px] text-slate-500">WA: 0812-3456-7890</p>
    </div>

    <div class="text-[10px] border-b border-dashed border-slate-300 pb-1.5 space-y-0.5">
      <div class="flex justify-between"><span>Invoice:</span><span id="rcpt-invoice" class="font-bold">INV-0000</span></div>
      <div class="flex justify-between"><span>Tanggal:</span><span id="rcpt-date">-</span></div>
      <div class="flex justify-between"><span>Kasir:</span><span>Kasir Sembako</span></div>
      <div class="flex justify-between" id="rcpt-customer-row"><span>Pelanggan:</span><span id="rcpt-customer" class="font-bold">Umum</span></div>
    </div>

    <div id="rcpt-items" class="space-y-1.5 border-b border-dashed border-slate-300 py-1.5"></div>

    <div class="space-y-1 pt-1 text-[11px]">
      <div class="flex justify-between"><span>Subtotal:</span><span id="rcpt-subtotal">Rp 0</span></div>
      <div class="flex justify-between" id="rcpt-discount-row"><span>Diskon:</span><span id="rcpt-discount">- Rp 0</span></div>
      <div class="flex justify-between font-bold text-xs pt-1 border-t border-slate-200"><span>TOTAL:</span><span id="rcpt-total">Rp 0</span></div>
      <div class="flex justify-between"><span id="rcpt-pay-label">Bayar:</span><span id="rcpt-paid">Rp 0</span></div>
      <div class="flex justify-between" id="rcpt-change-row"><span>Kembalian:</span><span id="rcpt-change">Rp 0</span></div>
    </div>

    <div class="text-center pt-3 text-[10px] text-slate-500 space-y-0.5 border-t border-dashed border-slate-300">
      <p class="font-bold text-slate-700">*** TERIMA KASIH ***</p>
      <p>Barang yang sudah dibeli tidak dapat ditukar kecuali perjanjian sebelumnya.</p>
    </div>
  </div>

  <div class="p-3 bg-slate-50 border-t border-slate-200 flex gap-2 no-print">
    <button onclick="window.print()" class="flex-1 py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-sm">
      <i data-lucide="printer" class="w-4 h-4"></i>
      <span>Cetak Struk (Print)</span>
    </button>
    <button onclick="closeReceiptModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Tutup</button>
  </div>
</x-modal>
