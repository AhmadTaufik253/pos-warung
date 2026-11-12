<x-modal id="modal-manage-categories" size="max-w-sm">
  <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
    <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
      <i data-lucide="tag" class="w-4 h-4 text-brand-600"></i>
      <span>Kelola Kategori Sembako</span>
    </h3>
    <button onclick="closeCategoriesModal()" class="text-slate-400 hover:text-slate-600">
      <i data-lucide="x" class="w-4 h-4"></i>
    </button>
  </div>

  <div class="p-4 space-y-3">
    <button onclick="quickAddCategory()" class="w-full py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-colors">
      <i data-lucide="plus" class="w-3.5 h-3.5"></i>
      <span>Tambah Kategori Baru</span>
    </button>

    <div id="category-manage-list" class="max-h-60 overflow-y-auto space-y-2 pr-1 text-xs"></div>
  </div>
</x-modal>
