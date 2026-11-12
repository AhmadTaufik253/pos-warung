<x-modal id="modal-hold-queue" size="max-w-md" class="flex flex-col max-h-[85vh]">
  <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
    <div class="flex items-center gap-2">
      <i data-lucide="list" class="w-5 h-5 text-amber-600"></i>
      <h3 class="font-bold text-sm text-slate-800">Antrean Keranjang Ditahan (Database)</h3>
      <span id="hold-queue-badge-count" class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full">0 Antrean</span>
    </div>
    <button onclick="closeHoldQueueModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-4 h-4"></i></button>
  </div>

  <div class="p-4 overflow-y-auto space-y-2.5 flex-1" id="hold-queue-items-container"></div>

  <div class="p-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs">
    <span class="text-slate-400 text-[11px]">Panggil antrean untuk memuat ulang ke kasir.</span>
    <button onclick="closeHoldQueueModal()" class="px-4 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Tutup</button>
  </div>
</x-modal>
