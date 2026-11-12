<header class="bg-brand-800 text-white shadow-md sticky top-0 z-30">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2.5 flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/20 shadow-inner">
        <i data-lucide="store" class="w-6 h-6 text-brand-300"></i>
      </div>
      <div>
        <div class="flex items-center gap-2">
          <h1 class="font-extrabold text-base sm:text-lg tracking-tight">Warung Paing</h1>
          <span class="bg-emerald-500/30 text-emerald-200 border border-emerald-400/30 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">V1.0.0</span>
        </div>
        <p id="live-clock" class="text-xs text-brand-200 font-mono tracking-wide">Memuat waktu...</p>
      </div>
    </div>

    <nav class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto pb-1 sm:pb-0">
      <button id="nav-pos" onclick="switchTab('pos')" class="nav-tab-btn px-3 py-2 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition-all bg-white text-brand-900 shadow-sm">
        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
        <span>Kasir</span>
        <kbd class="hidden sm:inline bg-brand-100 text-brand-800 text-[10px] font-mono px-1.5 py-0.5 rounded">F1</kbd>
      </button>

      <button id="nav-master" onclick="switchTab('master')" class="nav-tab-btn px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 transition-all text-white/80 hover:text-white hover:bg-white/10">
        <i data-lucide="boxes" class="w-4 h-4"></i>
        <span>Master Barang</span>
        <kbd class="hidden sm:inline bg-black/20 text-white/90 text-[10px] font-mono px-1.5 py-0.5 rounded">F2</kbd>
      </button>

      <button id="nav-history" onclick="switchTab('history')" class="nav-tab-btn px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 transition-all text-white/80 hover:text-white hover:bg-white/10">
        <i data-lucide="receipt" class="w-4 h-4"></i>
        <span>Riwayat Transaksi</span>
        <kbd class="hidden sm:inline bg-black/20 text-white/90 text-[10px] font-mono px-1.5 py-0.5 rounded">F3</kbd>
      </button>

      <button id="nav-kasbon" onclick="switchTab('kasbon')" class="nav-tab-btn px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 transition-all text-white/80 hover:text-white hover:bg-white/10 relative">
        <i data-lucide="book-open" class="w-4 h-4"></i>
        <span>Buku Kasbon</span>
        <kbd class="hidden sm:inline bg-black/20 text-white/90 text-[10px] font-mono px-1.5 py-0.5 rounded">F4</kbd>
        <span id="kasbon-nav-badge" class="hidden absolute -top-1 -right-1 bg-amber-400 text-slate-900 text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center">!</span>
      </button>
    </nav>
  </div>
</header>
