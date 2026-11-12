<x-modal id="modal-nominal" size="max-w-sm">
  <div class="p-4 bg-amber-50 border-b border-amber-100 flex items-center justify-between">
    <div class="flex items-center gap-2">
      <i data-lucide="coins" class="w-5 h-5 text-amber-600"></i>
      <div>
        <h3 class="font-bold text-sm text-slate-800">Beli Sesuai Uang (Rp)</h3>
        <p id="nominal-product-name" class="text-[11px] text-amber-800 font-semibold truncate max-w-[200px]">Nama Barang</p>
      </div>
    </div>
    <button onclick="closeNominalModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-4 h-4"></i></button>
  </div>

  <div class="p-5 space-y-4">
    <div>
      <span class="block text-xs font-semibold text-slate-600 mb-1">Pilih Cepat Uang Belanja:</span>
      <div class="grid grid-cols-4 gap-2">
        @foreach ([2000 => '2k', 3000 => '3k', 5000 => '5k', 10000 => '10k', 15000 => '15k', 20000 => '20k', 25000 => '25k', 50000 => '50k'] as $val => $label)
          <button type="button" onclick="setNominalInput({{ $val }})" class="py-2 bg-slate-100 hover:bg-amber-100 text-slate-800 hover:text-amber-900 rounded-xl font-bold font-mono text-xs border border-slate-200">{{ $label }}</button>
        @endforeach
      </div>
    </div>

    <div>
      <label for="nominal-money-input" class="block text-xs font-semibold text-slate-700 mb-1">Ketik Nominal Uang Pembeli (Rp):</label>
      <div class="relative">
        <span class="absolute left-3 top-2.5 text-slate-400 font-bold font-mono text-sm">Rp</span>
        <input type="number" id="nominal-money-input" min="500" step="500" placeholder="5000" oninput="calcNominalEquivalent()" class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-bold font-mono outline-none focus:border-amber-500 focus:bg-white">
      </div>
    </div>

    <div class="bg-amber-50/80 p-3 rounded-xl border border-amber-200 text-xs space-y-1 font-medium text-amber-900">
      <div class="flex justify-between">
        <span class="text-amber-800">Harga Satuan Standar:</span>
        <span id="nominal-base-price" class="font-mono font-bold">Rp 0 / kg</span>
      </div>
      <div class="flex justify-between items-baseline pt-1 border-t border-amber-200/60">
        <span class="font-bold">Estimasi Takaran Timbangan:</span>
        <span id="nominal-calc-qty" class="text-base font-black font-mono text-amber-900">0.00 kg</span>
      </div>
    </div>

    <button type="button" onclick="confirmAddNominalToCart()" class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-md shadow-amber-600/20">
      <i data-lucide="plus" class="w-4 h-4"></i>
      <span>Masukkan ke Keranjang</span>
    </button>
  </div>
</x-modal>
