{{-- TAB: KASIR (POS) --}}
<section id="tab-content-pos" class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
  <div class="lg:col-span-7 xl:col-span-8 flex flex-col gap-4">
    <div class="bg-white p-3.5 sm:p-4 rounded-2xl shadow-sm border border-slate-200 space-y-3">
      <div class="relative flex items-center">
        <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3.5 pointer-events-none"></i>
        <input type="text" id="search-input" placeholder="Cari beras, minyak curah, gula, atau scan barcode..." autocomplete="off" class="w-full pl-11 pr-24 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 outline-none focus:border-brand-600 focus:bg-white transition-all shadow-inner">
        <span class="absolute right-3 text-[10px] font-mono font-semibold text-slate-400 bg-slate-200 px-2 py-1 rounded-md">ESC = Reset</span>
      </div>

      {{-- Pil kategori (dinamis dari API) --}}
      <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs" id="category-pills">
        <button onclick="setCategoryFilter('all')" class="cat-pill active px-3 py-1.5 rounded-lg font-bold bg-brand-700 text-white shadow-xs whitespace-nowrap">Semua Sembako</button>
      </div>
    </div>

    <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3.5">
      {{-- Diisi oleh products.js --}}
    </div>
  </div>

  {{-- Panel keranjang & checkout --}}
  <div class="lg:col-span-5 xl:col-span-4 bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col sticky top-20 max-h-[calc(100vh-6rem)] overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <i data-lucide="shopping-cart" class="w-5 h-5 text-brand-600"></i>
        <h2 class="font-bold text-sm text-slate-800">Keranjang Belanja</h2>
        <span id="cart-item-count" class="bg-brand-600 text-white text-[11px] font-bold px-2 py-0.5 rounded-full">0 item</span>
      </div>

      <div class="flex items-center gap-1.5">
        <button type="button" onclick="openHoldConfirmModal()" class="px-2.5 py-1 text-xs font-semibold rounded-lg border border-amber-300 bg-amber-50 text-amber-900 hover:bg-amber-100 flex items-center gap-1 transition-all active:scale-95 shadow-xs" title="Parkirkan / tahan belanjaan pelanggan ini">
          <i data-lucide="pause-circle" class="w-3.5 h-3.5 text-amber-600"></i>
          <span>Tahan</span>
        </button>

        <button type="button" onclick="openHoldQueueModal()" class="px-2.5 py-1 text-xs font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 flex items-center gap-1.5 transition-all active:scale-95 shadow-xs relative" title="Buka daftar antrean yang ditahan">
          <i data-lucide="list" class="w-3.5 h-3.5 text-slate-500"></i>
          <span>Antrean</span>
          <span id="hold-count-badge" class="hidden bg-amber-500 text-white text-[10px] font-mono font-bold px-1.5 py-0.2 rounded-full">0</span>
        </button>

        <button type="button" onclick="clearCart()" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Kosongkan Keranjang">
          <i data-lucide="trash-2" class="w-4 h-4"></i>
        </button>
      </div>
    </div>

    <div id="cart-items" class="p-3 overflow-y-auto flex-1 divide-y divide-slate-100 min-h-[160px] max-h-[38vh]">
      {{-- Diisi oleh cart.js --}}
    </div>

    <div class="p-4 bg-slate-50 border-t border-slate-200 space-y-3">
      <div class="flex items-center justify-between gap-2 text-xs">
        <span class="text-slate-500 font-medium">Diskon (Rp):</span>
        <div class="relative w-36">
          <span class="absolute left-2.5 top-1.5 text-slate-400 font-mono text-xs">Rp</span>
          <input type="number" id="input-discount-val" min="0" step="500" placeholder="0" oninput="handleDiscountChange(this.value)" class="w-full pl-8 pr-2 py-1 text-right bg-white border border-slate-300 rounded-lg font-mono text-xs font-bold outline-none focus:border-brand-500">
        </div>
      </div>

      <div class="space-y-1 pt-1 border-t border-slate-200/80">
        <div class="flex justify-between text-xs text-slate-500">
          <span>Subtotal</span>
          <span id="cart-subtotal" class="font-mono font-medium">Rp 0</span>
        </div>
        <div class="flex justify-between text-xs text-emerald-600" id="cart-discount-row" style="display: none;">
          <span>Potongan Diskon</span>
          <span id="cart-discount" class="font-mono font-semibold">- Rp 0</span>
        </div>
        <div class="flex justify-between items-baseline pt-1">
          <span class="text-sm font-bold text-slate-800">Total Tagihan:</span>
          <span id="cart-grandtotal" class="text-xl sm:text-2xl font-black font-mono text-brand-700">Rp 0</span>
        </div>
      </div>

      <button type="button" id="btn-checkout" onclick="openCheckoutModal()" class="w-full py-3.5 px-4 bg-brand-600 hover:bg-brand-700 active:scale-[0.99] text-white rounded-xl font-bold text-sm sm:text-base flex items-center justify-center gap-2 shadow-lg shadow-brand-600/30 transition-all cursor-pointer">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span>Bayar Sekarang</span>
        <kbd class="bg-brand-800/60 text-white/90 text-xs font-mono px-2 py-0.5 rounded ml-1">F8</kbd>
      </button>
    </div>
  </div>
</section>
