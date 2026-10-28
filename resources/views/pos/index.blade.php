<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>POS Toko Sembako Berkah Jaya</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50: '#ecfdf5',
              100: '#d1fae5',
              500: '#10b981',
              600: '#059669',
              700: '#047857',
              800: '#065f46',
              900: '#064e3b',
            }
          }
        }
      }
    }
  </script>
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .font-mono {
      font-family: 'JetBrains Mono', monospace;
    }
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-track {
      background: #f1f5f9;
    }
    ::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
    @media print {
      body * {
        visibility: hidden !important;
      }
      #printable-receipt, #printable-receipt * {
        visibility: visible !important;
      }
      #printable-receipt {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        max-width: 80mm !important;
        margin: 0 !important;
        padding: 8px !important;
        box-shadow: none !important;
        border: none !important;
        color: #000 !important;
        background: #fff !important;
      }
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col select-none antialiased">

  <!-- ================= TOP NAVBAR ================= -->
  <header class="bg-brand-800 text-white shadow-md sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2.5 flex flex-wrap items-center justify-between gap-3">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/20 shadow-inner">
          <i data-lucide="store" class="w-6 h-6 text-brand-300"></i>
        </div>
        <div>
          <div class="flex items-center gap-2">
            <h1 class="font-extrabold text-base sm:text-lg tracking-tight">Toko Sembako Berkah Jaya</h1>
            <span class="bg-emerald-500/30 text-emerald-200 border border-emerald-400/30 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">API Connected</span>
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

  <!-- ================= MAIN CONTAINER ================= -->
  <main class="max-w-7xl mx-auto p-3 sm:p-5 w-full flex-1">

    <!-- ================= 1. TAB: KASIR (POS) ================= -->
    <section id="tab-content-pos" class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
      <div class="lg:col-span-7 xl:col-span-8 flex flex-col gap-4">
        <div class="bg-white p-3.5 sm:p-4 rounded-2xl shadow-sm border border-slate-200 space-y-3">
          <div class="relative flex items-center">
            <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3.5 pointer-events-none"></i>
            <input 
              type="text" 
              id="search-input" 
              placeholder="Cari beras, minyak curah, gula, atau scan barcode..." 
              autocomplete="off"
              class="w-full pl-11 pr-24 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 outline-none focus:border-brand-600 focus:bg-white transition-all shadow-inner"
            >
            <span class="absolute right-3 text-[10px] font-mono font-semibold text-slate-400 bg-slate-200 px-2 py-1 rounded-md">ESC = Reset</span>
          </div>

          <!-- Pil Kategori (Dinamis dari API Categories) -->
          <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs" id="category-pills">
            <button onclick="setCategoryFilter('all')" class="cat-pill active px-3 py-1.5 rounded-lg font-bold bg-brand-700 text-white shadow-xs whitespace-nowrap">Semua Sembako</button>
          </div>
        </div>

        <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3.5">
          <!-- Populated by API -->
        </div>
      </div>

      <!-- Checkout & Cart Panel -->
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
          <!-- Cart items populated by JS -->
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200 space-y-3">
          <div class="flex items-center justify-between gap-2 text-xs">
            <span class="text-slate-500 font-medium">Diskon Toko (Rp):</span>
            <div class="relative w-36">
              <span class="absolute left-2.5 top-1.5 text-slate-400 font-mono text-xs">Rp</span>
              <input 
                type="number" 
                id="input-discount-val" 
                min="0" 
                step="500" 
                placeholder="0" 
                oninput="handleDiscountChange(this.value)"
                class="w-full pl-8 pr-2 py-1 text-right bg-white border border-slate-300 rounded-lg font-mono text-xs font-bold outline-none focus:border-brand-500"
              >
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

          <button 
            type="button" 
            id="btn-checkout" 
            onclick="openCheckoutModal()" 
            class="w-full py-3.5 px-4 bg-brand-600 hover:bg-brand-700 active:scale-[0.99] text-white rounded-xl font-bold text-sm sm:text-base flex items-center justify-center gap-2 shadow-lg shadow-brand-600/30 transition-all cursor-pointer"
          >
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <span>Bayar Sekarang</span>
            <kbd class="bg-brand-800/60 text-white/90 text-xs font-mono px-2 py-0.5 rounded ml-1">F8</kbd>
          </button>
        </div>
      </div>
    </section>

    <!-- ================= 2. TAB: MASTER DATA BARANG (F2) ================= -->
    <section id="tab-content-master" class="hidden space-y-5">
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Produk Sembako</p>
            <h3 id="stat-total-products" class="text-2xl font-black font-mono text-slate-800 mt-1">0</h3>
          </div>
          <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <i data-lucide="package" class="w-6 h-6"></i>
          </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Nilai Aset Stok (Modal)</p>
            <h3 id="stat-total-asset-value" class="text-xl sm:text-2xl font-black font-mono text-brand-700 mt-1">Rp 0</h3>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <i data-lucide="wallet" class="w-6 h-6"></i>
          </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Menipis / Kritis</p>
            <h3 id="stat-low-stock-count" class="text-2xl font-black font-mono text-amber-600 mt-1">0</h3>
          </div>
          <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 bg-slate-50/70">
          <div class="flex items-center gap-2 flex-1 max-w-md">
            <div class="relative w-full">
              <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none"></i>
              <input 
                type="text" 
                id="master-search-input" 
                oninput="renderMasterTable()"
                placeholder="Cari barcode atau nama barang..." 
                class="w-full pl-9 pr-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-medium outline-none focus:border-brand-500"
              >
            </div>
          </div>

          <div class="flex items-center gap-2">
            <button onclick="manageCategoriesModal()" class="px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold flex items-center gap-1.5 transition-colors shadow-xs">
              <i data-lucide="tag" class="w-4 h-4 text-slate-500"></i>
              <span>Kelola Kategori</span>
            </button>
            <button onclick="exportMasterCSV()" class="px-3 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-xs">
              <i data-lucide="download" class="w-4 h-4 text-slate-500"></i>
              <span>Ekspor CSV</span>
            </button>
            <button onclick="openProductModal()" class="px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold flex items-center gap-1.5 transition-all shadow-md shadow-brand-600/20">
              <i data-lucide="plus-circle" class="w-4 h-4"></i>
              <span>Tambah Produk Baru</span>
            </button>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs text-slate-700">
            <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
              <tr>
                <th class="py-3 px-4">Barcode/SKU</th>
                <th class="py-3 px-4">Nama Barang & Satuan Dasar</th>
                <th class="py-3 px-4">Kategori</th>
                <th class="py-3 px-4 text-right">Modal Pokok (HPP)</th>
                <th class="py-3 px-4">Multi-Satuan Jual (UOM)</th>
                <th class="py-3 px-4 text-center">Stok Fisik</th>
                <th class="py-3 px-4 text-center">Status</th>
                <th class="py-3 px-4 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody id="master-table-body" class="divide-y divide-slate-100">
              <!-- Populated by API -->
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ================= 3. TAB: RIWAYAT TRANSAKSI (F3) ================= -->
    <section id="tab-content-history" class="hidden space-y-5">
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
          <p class="text-xs font-semibold text-slate-500">Total Omset Selesai</p>
          <h3 id="stat-total-sales" class="text-lg sm:text-2xl font-black font-mono text-brand-700 mt-1">Rp 0</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
          <p class="text-xs font-semibold text-slate-500">Estimasi Keuntungan (Laba)</p>
          <h3 id="stat-total-profit" class="text-lg sm:text-2xl font-black font-mono text-emerald-600 mt-1">Rp 0</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
          <p class="text-xs font-semibold text-slate-500">Total Transaksi</p>
          <h3 id="stat-total-invoices" class="text-lg sm:text-2xl font-black font-mono text-slate-800 mt-1">0</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
          <p class="text-xs font-semibold text-slate-500">Total Item Terjual</p>
          <h3 id="stat-total-items-sold" class="text-lg sm:text-2xl font-black font-mono text-blue-600 mt-1">0</h3>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 bg-slate-50/70">
          <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
            <i data-lucide="file-text" class="w-4 h-4 text-brand-600"></i>
            <span>Daftar Transaksi Kasir (Database)</span>
          </h3>
          <div class="flex items-center gap-2">
            <button onclick="fetchOrders()" class="p-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 text-slate-600" title="Refresh Riwayat">
              <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </button>
            <select id="history-filter-method" onchange="renderHistoryTable()" class="px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-xs font-semibold outline-none">
              <option value="ALL">Semua Pembayaran</option>
              <option value="cash">Tunai</option>
              <option value="qris">QRIS</option>
              <option value="transfer">Transfer</option>
              <option value="debt">Kasbon / Tempo</option>
            </select>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs text-slate-700">
            <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
              <tr>
                <th class="py-3 px-4">No. Invoice</th>
                <th class="py-3 px-4">Waktu</th>
                <th class="py-3 px-4">Pelanggan</th>
                <th class="py-3 px-4 text-center">Metode</th>
                <th class="py-3 px-4 text-right">Subtotal</th>
                <th class="py-3 px-4 text-right">Potongan</th>
                <th class="py-3 px-4 text-right">Total Akhir</th>
                <th class="py-3 px-4 text-right text-emerald-700">Estimasi Laba</th>
                <th class="py-3 px-4 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody id="history-table-body" class="divide-y divide-slate-100">
              <!-- Populated by API -->
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ================= 4. TAB: BUKU KASBON / PIUTANG (F4) ================= -->
    <section id="tab-content-kasbon" class="hidden space-y-5">
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Piutang Belum Lunas</p>
            <h3 id="stat-kasbon-unpaid" class="text-xl sm:text-2xl font-black font-mono text-red-600 mt-1">Rp 0</h3>
          </div>
          <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
            <i data-lucide="clock" class="w-6 h-6"></i>
          </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Pelanggan Kasbon</p>
            <h3 id="stat-kasbon-customer-count" class="text-2xl font-black font-mono text-slate-800 mt-1">0 Orang</h3>
          </div>
          <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
            <i data-lucide="users" class="w-6 h-6"></i>
          </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Piutang Berhasil Diterima</p>
            <h3 id="stat-kasbon-paid" class="text-xl sm:text-2xl font-black font-mono text-emerald-600 mt-1">Rp 0</h3>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <i data-lucide="check-circle-2" class="w-6 h-6"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 bg-slate-50/70">
          <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
            <i data-lucide="book-marked" class="w-4 h-4 text-amber-600"></i>
            <span>Catatan Hutang / Kasbon Langganan Warung</span>
          </h3>
          <div class="flex items-center gap-2">
            <button onclick="fetchDebts()" class="p-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 text-slate-600" title="Refresh Kasbon">
              <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </button>
            <input 
              type="text" 
              id="kasbon-search-input" 
              oninput="renderKasbonTable()" 
              placeholder="Cari nama langganan..." 
              class="px-3 py-1.5 bg-white border border-slate-300 rounded-xl text-xs font-medium outline-none focus:border-brand-500"
            >
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs text-slate-700">
            <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-200">
              <tr>
                <th class="py-3 px-4">Nama Pelanggan</th>
                <th class="py-3 px-4">Kontak / No. HP</th>
                <th class="py-3 px-4">Faktur / Tanggal</th>
                <th class="py-3 px-4 text-right">Total Kasbon Awal</th>
                <th class="py-3 px-4 text-right text-red-600">Sisa Hutang Belum Dibayar</th>
                <th class="py-3 px-4 text-center">Status</th>
                <th class="py-3 px-4 text-center">Aksi Pelunasan</th>
              </tr>
            </thead>
            <tbody id="kasbon-table-body" class="divide-y divide-slate-100">
              <!-- Populated by API -->
            </tbody>
          </table>
        </div>
      </div>
    </section>

  </main>

  <!-- ================= MODAL: KELOLA KATEGORI (CRUD) ================= -->
  <div id="modal-manage-categories" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden animate-in fade-in duration-150">
      <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
        <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
          <i data-lucide="tag" class="w-4 h-4 text-brand-600"></i>
          <span>Kelola Kategori Sembako</span>
        </h3>
        <button onclick="document.getElementById('modal-manage-categories').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>

      <div class="p-4 space-y-3">
        <button onclick="quickAddCategory()" class="w-full py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-colors">
          <i data-lucide="plus" class="w-3.5 h-3.5"></i>
          <span>Tambah Kategori Baru</span>
        </button>

        <div id="category-manage-list" class="max-h-60 overflow-y-auto space-y-2 pr-1 custom-scroll text-xs">
          <!-- Rendered by API -->
        </div>
      </div>
    </div>
  </div>

  <!-- ================= MODAL: BELI NOMINAL (RP) ================= -->
  <div id="modal-nominal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden animate-in fade-in duration-150">
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
          <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Cepat Uang Belanja:</label>
          <div class="grid grid-cols-4 gap-2">
            <button type="button" onclick="setNominalInput(2000)" class="py-2 bg-slate-100 hover:bg-amber-100 text-slate-800 hover:text-amber-900 rounded-xl font-bold font-mono text-xs border border-slate-200">2k</button>
            <button type="button" onclick="setNominalInput(3000)" class="py-2 bg-slate-100 hover:bg-amber-100 text-slate-800 hover:text-amber-900 rounded-xl font-bold font-mono text-xs border border-slate-200">3k</button>
            <button type="button" onclick="setNominalInput(5000)" class="py-2 bg-slate-100 hover:bg-amber-100 text-slate-800 hover:text-amber-900 rounded-xl font-bold font-mono text-xs border border-slate-200">5k</button>
            <button type="button" onclick="setNominalInput(10000)" class="py-2 bg-slate-100 hover:bg-amber-100 text-slate-800 hover:text-amber-900 rounded-xl font-bold font-mono text-xs border border-slate-200">10k</button>
            <button type="button" onclick="setNominalInput(15000)" class="py-2 bg-slate-100 hover:bg-amber-100 text-slate-800 hover:text-amber-900 rounded-xl font-bold font-mono text-xs border border-slate-200">15k</button>
            <button type="button" onclick="setNominalInput(20000)" class="py-2 bg-slate-100 hover:bg-amber-100 text-slate-800 hover:text-amber-900 rounded-xl font-bold font-mono text-xs border border-slate-200">20k</button>
            <button type="button" onclick="setNominalInput(25000)" class="py-2 bg-slate-100 hover:bg-amber-100 text-slate-800 hover:text-amber-900 rounded-xl font-bold font-mono text-xs border border-slate-200">25k</button>
            <button type="button" onclick="setNominalInput(50000)" class="py-2 bg-slate-100 hover:bg-amber-100 text-slate-800 hover:text-amber-900 rounded-xl font-bold font-mono text-xs border border-slate-200">50k</button>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Ketik Nominal Uang Pembeli (Rp):</label>
          <div class="relative">
            <span class="absolute left-3 top-2.5 text-slate-400 font-bold font-mono text-sm">Rp</span>
            <input 
              type="number" 
              id="nominal-money-input" 
              min="500" 
              step="500" 
              placeholder="5000" 
              oninput="calcNominalEquivalent()" 
              class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-bold font-mono outline-none focus:border-amber-500 focus:bg-white"
            >
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

        <button 
          type="button" 
          onclick="confirmAddNominalToCart()" 
          class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-md shadow-amber-600/20"
        >
          <i data-lucide="plus" class="w-4 h-4"></i>
          <span>Masukkan ke Keranjang</span>
        </button>
      </div>
    </div>
  </div>

  <!-- ================= MODAL: TAHAN TRANSAKSI (HOLD) ================= -->
  <div id="modal-hold-confirm" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden animate-in fade-in duration-150">
      <div class="p-4 bg-amber-50 border-b border-amber-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i data-lucide="pause-circle" class="w-5 h-5 text-amber-600"></i>
          <h3 class="font-bold text-sm text-slate-800">Tahan / Parkir Belanjaan</h3>
        </div>
        <button onclick="closeHoldConfirmModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-4 h-4"></i></button>
      </div>

      <div class="p-5 space-y-4">
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Nama / Keterangan Antrean:</label>
          <input 
            type="text" 
            id="hold-label-input" 
            placeholder="Contoh: Ibu Rina (ambil dompet di motor)" 
            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold outline-none focus:border-amber-500 focus:bg-white"
          >
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
    </div>
  </div>

  <!-- ================= MODAL: DAFTAR ANTREAN PARKIR (RECALL) ================= -->
  <div id="modal-hold-queue" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden animate-in fade-in duration-150 flex flex-col max-h-[85vh]">
      <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i data-lucide="list" class="w-5 h-5 text-amber-600"></i>
          <h3 class="font-bold text-sm text-slate-800">Antrean Keranjang Ditahan (Database)</h3>
          <span id="hold-queue-badge-count" class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full">0 Antrean</span>
        </div>
        <button onclick="closeHoldQueueModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-4 h-4"></i></button>
      </div>

      <div class="p-4 overflow-y-auto space-y-2.5 flex-1" id="hold-queue-items-container">
        <!-- Rendered by API -->
      </div>

      <div class="p-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs">
        <span class="text-slate-400 text-[11px]">Panggil antrean untuk memuat ulang ke kasir.</span>
        <button onclick="closeHoldQueueModal()" class="px-4 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Tutup</button>
      </div>
    </div>
  </div>

  <!-- ================= MODAL: CHECKOUT & PEMBAYARAN (F8) ================= -->
  <div id="modal-checkout" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden animate-in fade-in duration-150">
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
          <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pilih Metode Pembayaran:</label>
          <div class="grid grid-cols-4 gap-1.5 bg-slate-100 p-1 rounded-xl text-xs font-bold text-center">
            <button type="button" onclick="setPaymentMethod('cash')" id="pm-cash" class="pm-tab py-2 rounded-lg bg-white text-brand-900 shadow-xs">Tunai</button>
            <button type="button" onclick="setPaymentMethod('qris')" id="pm-qris" class="pm-tab py-2 rounded-lg text-slate-600 hover:text-slate-900">QRIS</button>
            <button type="button" onclick="setPaymentMethod('transfer')" id="pm-transfer" class="pm-tab py-2 rounded-lg text-slate-600 hover:text-slate-900">Transfer</button>
            <button type="button" onclick="setPaymentMethod('debt')" id="pm-debt" class="pm-tab py-2 rounded-lg text-amber-700 hover:bg-amber-100">Kasbon</button>
          </div>
        </div>

        <!-- TUNAI SECTION -->
        <div id="section-pay-tunai" class="space-y-3">
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Pecahan Uang Tunai Cepat:</label>
            <div class="grid grid-cols-3 gap-1.5 text-xs font-mono font-bold" id="quick-cash-buttons">
              <!-- Rendered dynamically -->
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal Uang Diterima (Rp):</label>
            <input 
              type="number" 
              id="cash-received-input" 
              min="0" 
              step="500" 
              oninput="calcCashChange()" 
              placeholder="0" 
              class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-lg font-black font-mono outline-none focus:border-brand-600 focus:bg-white"
            >
          </div>

          <div class="bg-emerald-50 p-3 rounded-xl border border-emerald-200 flex justify-between items-center text-xs">
            <span class="font-bold text-emerald-900">Uang Kembalian:</span>
            <span id="cash-change-display" class="font-mono text-base font-black text-emerald-700">Rp 0</span>
          </div>
        </div>

        <!-- QRIS / TRANSFER SECTION -->
        <div id="section-pay-digital" class="hidden text-center p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
          <div class="w-12 h-12 bg-white rounded-xl mx-auto flex items-center justify-center border border-slate-200 shadow-xs">
            <i data-lucide="qr-code" class="w-6 h-6 text-brand-700"></i>
          </div>
          <p class="text-xs font-bold text-slate-700" id="digital-pay-title">Scan QRIS Toko Sembako</p>
          <p class="text-[11px] text-slate-500">Pastikan notifikasi dana masuk telah diterima sebelum menyelesaikan transaksi.</p>
        </div>

        <!-- KASBON SECTION -->
        <div id="section-pay-kasbon" class="hidden space-y-2.5 bg-amber-50/70 p-3.5 rounded-xl border border-amber-200">
          <div>
            <label class="block text-[11px] font-bold text-amber-900 mb-0.5">Nama Pelanggan (Wajib):</label>
            <input type="text" id="kasbon-cust-name" placeholder="Contoh: Bu Hj. Maryam / Pak Joko" class="w-full px-3 py-1.5 bg-white border border-amber-300 rounded-lg text-xs font-semibold outline-none focus:border-amber-600">
          </div>
          <div>
            <label class="block text-[11px] font-bold text-amber-900 mb-0.5">Catatan / Rencana Bayar:</label>
            <input type="text" id="kasbon-notes" placeholder="Janji bayar saat gajian" class="w-full px-3 py-1.5 bg-white border border-amber-300 rounded-lg text-xs outline-none focus:border-amber-600">
          </div>
        </div>

        <button 
          type="button" 
          onclick="finalizeTransaction()" 
          id="btn-submit-pay"
          class="w-full py-3 bg-brand-600 hover:bg-brand-700 active:scale-[0.99] text-white font-bold rounded-xl text-sm flex items-center justify-center gap-2 shadow-lg shadow-brand-600/30 transition-all cursor-pointer"
        >
          <i data-lucide="printer" class="w-4 h-4"></i>
          <span>Selesaikan Transaksi (API)</span>
        </button>
      </div>
    </div>
  </div>

  <!-- ================= MODAL: STRUK THERMAL CETAK ================= -->
  <div id="modal-receipt" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden animate-in fade-in duration-150">
      <div class="p-3 bg-slate-800 text-white flex items-center justify-between no-print">
        <span class="text-xs font-bold flex items-center gap-1.5">
          <i data-lucide="receipt" class="w-4 h-4 text-brand-300"></i>
          Struk Belanja Thermal
        </span>
        <button onclick="closeReceiptModal()" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
      </div>

      <div class="p-4 bg-white font-mono text-[11px] text-slate-800 leading-tight space-y-2" id="printable-receipt">
        <div class="text-center border-b border-dashed border-slate-300 pb-2">
          <h2 class="font-bold text-sm tracking-tight uppercase">Toko Sembako Berkah Jaya</h2>
          <p class="text-[10px] text-slate-500">Jl. Pasar Senen No. 45, Jakarta</p>
          <p class="text-[10px] text-slate-500">WA: 0812-3456-7890</p>
        </div>

        <div class="text-[10px] border-b border-dashed border-slate-300 pb-1.5 space-y-0.5">
          <div class="flex justify-between">
            <span>Invoice:</span>
            <span id="rcpt-invoice" class="font-bold">INV-0000</span>
          </div>
          <div class="flex justify-between">
            <span>Tanggal:</span>
            <span id="rcpt-date">-</span>
          </div>
          <div class="flex justify-between">
            <span>Kasir:</span>
            <span>Kasir Sembako</span>
          </div>
          <div class="flex justify-between" id="rcpt-customer-row">
            <span>Pelanggan:</span>
            <span id="rcpt-customer" class="font-bold">Umum</span>
          </div>
        </div>

        <div id="rcpt-items" class="space-y-1.5 border-b border-dashed border-slate-300 py-1.5">
          <!-- Rendered by API response -->
        </div>

        <div class="space-y-1 pt-1 text-[11px]">
          <div class="flex justify-between">
            <span>Subtotal:</span>
            <span id="rcpt-subtotal">Rp 0</span>
          </div>
          <div class="flex justify-between" id="rcpt-discount-row">
            <span>Diskon:</span>
            <span id="rcpt-discount">- Rp 0</span>
          </div>
          <div class="flex justify-between font-bold text-xs pt-1 border-t border-slate-200">
            <span>TOTAL:</span>
            <span id="rcpt-total">Rp 0</span>
          </div>
          <div class="flex justify-between">
            <span id="rcpt-pay-label">Bayar:</span>
            <span id="rcpt-paid">Rp 0</span>
          </div>
          <div class="flex justify-between" id="rcpt-change-row">
            <span>Kembalian:</span>
            <span id="rcpt-change">Rp 0</span>
          </div>
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
        <button onclick="closeReceiptModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">
          Tutup
        </button>
      </div>
    </div>
  </div>

  <!-- ================= MODAL: TAMBAH / EDIT PRODUK ================= -->
  <div id="modal-product" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden animate-in fade-in duration-150 max-h-[90vh] flex flex-col">
      <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
        <h3 id="product-modal-title" class="font-bold text-sm text-slate-800 flex items-center gap-2">
          <i data-lucide="package-plus" class="w-4 h-4 text-brand-600"></i>
          <span>Tambah Produk Sembako Baru</span>
        </h3>
        <button onclick="closeProductModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-4 h-4"></i></button>
      </div>

      <form id="product-form" onsubmit="handleSaveProduct(event)" class="p-4 overflow-y-auto space-y-3.5 flex-1 text-xs">
        <input type="hidden" id="p-id">

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Barcode / SKU</label>
            <input type="text" id="p-barcode" placeholder="GLP-002" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500 font-mono">
          </div>
          <div>
            <div class="flex justify-between items-center mb-1">
              <label class="font-semibold text-slate-700">Kategori Sembako</label>
              <button type="button" onclick="quickAddCategory()" class="text-[10px] text-brand-700 hover:underline font-bold">+ Baru</button>
            </div>
            <select id="p-category-id" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500">
              <option value="">Umum (Tanpa Kategori)</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block font-semibold text-slate-700 mb-1">Nama Barang Lengkap</label>
          <input type="text" id="p-name" required placeholder="Gula Pasir Gulaku Kuning" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500">
        </div>

        <div class="grid grid-cols-3 gap-2.5">
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Satuan Dasar</label>
            <input type="text" id="p-base-unit" required placeholder="kg / liter / pcs" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500">
          </div>
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Modal Dasar (Rp)</label>
            <input type="number" id="p-buy-price" required min="0" step="100" placeholder="14000" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500 font-mono">
          </div>
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Harga Jual Standar (Rp)</label>
            <input type="number" id="p-sell-price" required min="0" step="100" placeholder="17000" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500 font-mono">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Stok Fisik Gudang</label>
            <input type="number" id="p-stock" required min="0" step="any" placeholder="50" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500 font-mono">
          </div>
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Batas Peringatan Stok Menipis</label>
            <input type="number" id="p-min-stock" min="0" step="any" value="5" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:border-brand-500 font-mono">
          </div>
        </div>

        <div class="p-3 bg-amber-50/80 border border-amber-200 rounded-xl flex items-center justify-between">
          <div>
            <span class="block font-bold text-amber-950 text-xs">Izinkan Fitur Beli Nominal (Rp)?</span>
            <span class="text-[10px] text-amber-700">Untuk barang curah timbangan/literan (cth: "Beli Minyak Rp 5.000").</span>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" id="p-allow-nominal" class="sr-only peer">
            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-600"></div>
          </label>
        </div>

        <div class="pt-2 border-t border-slate-200 space-y-2">
          <div class="flex items-center justify-between">
            <div>
              <span class="font-bold text-slate-800">Daftar Satuan Jual & Eceran (Multi-Satuan)</span>
              <p class="text-[10px] text-slate-400">Tentukan harga jual per sak, kilogram, setengah kilo, dsb.</p>
            </div>
            <button type="button" onclick="addUnitRow()" class="px-2.5 py-1 bg-brand-50 text-brand-700 hover:bg-brand-100 rounded-lg font-bold text-[11px] flex items-center gap-1">
              <i data-lucide="plus" class="w-3.5 h-3.5"></i>
              <span>Tambah Satuan</span>
            </button>
          </div>

          <div id="unit-rows-list" class="space-y-2">
            <!-- Unit rows -->
          </div>
        </div>

        <div class="pt-3 border-t border-slate-200 flex justify-end gap-2">
          <button type="button" onclick="closeProductModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl">Batal</button>
          <button type="submit" id="btn-save-product" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl shadow-md">Simpan ke Database</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ================= MODAL: RESTOCK CEPAT ================= -->
  <div id="modal-restock" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-xs w-full shadow-2xl p-5 space-y-4 animate-in fade-in duration-150">
      <div class="flex items-center justify-between border-b border-slate-100 pb-2">
        <h3 class="font-bold text-xs text-slate-800 flex items-center gap-1.5">
          <i data-lucide="sliders" class="w-4 h-4 text-emerald-600"></i>
          <span>Restock Stok Kilat (API)</span>
        </h3>
        <button onclick="closeRestockModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-4 h-4"></i></button>
      </div>

      <input type="hidden" id="restock-prod-id">
      <p id="restock-prod-name" class="font-bold text-xs text-brand-800">Nama Barang</p>

      <div>
        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Jumlah Tambahan Stok (Satuan Dasar):</label>
        <input type="number" id="restock-qty-input" min="0.1" step="any" placeholder="Contoh: 50" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-base font-bold font-mono outline-none focus:border-brand-500">
      </div>

      <div class="flex gap-2">
        <button onclick="closeRestockModal()" class="flex-1 py-2 bg-slate-100 text-slate-600 font-semibold rounded-xl text-xs">Batal</button>
        <button onclick="executeRestock()" class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md">Simpan Stok</button>
      </div>
    </div>
  </div>

  <!-- ================= MODAL: CICIL / BAYAR KASBON ================= -->
  <div id="modal-pay-kasbon" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden animate-in fade-in duration-150">
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
          <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal Cicil / Pelunasan (Rp):</label>
          <div class="relative">
            <span class="absolute left-3 top-2.5 text-slate-400 font-bold font-mono text-sm">Rp</span>
            <input 
              type="number" 
              id="pk-amount-input" 
              min="1000" 
              step="1000" 
              placeholder="0" 
              class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-base font-bold font-mono outline-none focus:border-amber-500 focus:bg-white"
            >
          </div>
          <button type="button" onclick="setPayKasbonFull()" class="text-[11px] font-bold text-amber-700 hover:underline mt-1 block">Bayar Lunas Semua</button>
        </div>

        <div class="flex gap-2">
          <button type="button" onclick="closePayKasbonModal()" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
          <button type="button" onclick="confirmPayKasbon()" class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md">Simpan Pembayaran</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast Notification Box -->
  <div id="toast" class="fixed bottom-5 right-5 z-50 bg-slate-900 text-white px-4 py-2.5 rounded-xl shadow-2xl text-xs font-medium flex items-center gap-2 transform translate-y-20 opacity-0 transition-all duration-200">
    <i data-lucide="info" class="w-4 h-4 text-brand-400"></i>
    <span id="toast-text">Pesan notifikasi</span>
  </div>

  <!-- ================= JAVASCRIPT API CLIENT LOGIC ================= -->
  <script>
    const API_BASE = '/api/v1';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let categories = [];
    let products = [];
    let transactions = [];
    let kasbonList = [];
    let holdQueue = [];
    let cart = [];
    let currentCategory = 'all';
    let currentPaymentMethod = 'cash';
    let discountAmount = 0;
    let activeNominalProductId = null;

    function getApiHeaders() {
      return {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      };
    }

    function formatRp(val) {
      return 'Rp ' + Math.round(Number(val) || 0).toLocaleString('id-ID');
    }

    function formatStock(val) {
      if (val === undefined || val === null) return '0';
      return Number(val).toLocaleString('id-ID', { maximumFractionDigits: 3 });
    }

    function playBeep(freq = 600, duration = 0.08, type = 'sine') {
      try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = type;
        osc.frequency.value = freq;
        gain.gain.setValueAtTime(0.08, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + duration);
      } catch (e) {}
    }

    function showToast(text) {
      const toast = document.getElementById('toast');
      const toastText = document.getElementById('toast-text');
      toastText.textContent = text;
      toast.classList.remove('translate-y-20', 'opacity-0');
      setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
      }, 2500);
    }

    function startClock() {
      const clockEl = document.getElementById('live-clock');
      function update() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
        clockEl.textContent = now.toLocaleDateString('id-ID', options).replace('.', ':');
      }
      update();
      setInterval(update, 1000);
    }

    function switchTab(tabId) {
      const tabs = ['pos', 'master', 'history', 'kasbon'];
      tabs.forEach(t => {
        const content = document.getElementById(`tab-content-${t}`);
        const navBtn = document.getElementById(`nav-${t}`);
        if (t === tabId) {
          content.classList.remove('hidden');
          navBtn.classList.remove('text-white/80', 'hover:bg-white/10');
          navBtn.classList.add('bg-white', 'text-brand-900', 'shadow-sm');
        } else {
          content.classList.add('hidden');
          navBtn.classList.remove('bg-white', 'text-brand-900', 'shadow-sm');
          navBtn.classList.add('text-white/80', 'hover:bg-white/10');
        }
      });

      if (tabId === 'pos') {
        renderProducts();
        document.getElementById('search-input').focus();
      } else if (tabId === 'master') {
        renderMasterTable();
      } else if (tabId === 'history') {
        fetchOrders();
      } else if (tabId === 'kasbon') {
        fetchDebts();
      }
      lucide.createIcons();
    }

    // ==========================================
    // 1. API: CATEGORIES (CRUD LENGKAP)
    // ==========================================
    async function fetchCategories() {
      try {
        const res = await fetch(`${API_BASE}/categories`);
        const json = await res.json();
        if (json.success) {
          categories = json.data;
          renderCategoryPillsAndSelect();
        }
      } catch (err) {
        console.error('Gagal mengambil kategori:', err);
      }
    }

    function renderCategoryPillsAndSelect() {
      const pillsContainer = document.getElementById('category-pills');
      const catSelect = document.getElementById('p-category-id');

      pillsContainer.innerHTML = `
        <button onclick="setCategoryFilter('all')" class="cat-pill px-3 py-1.5 rounded-lg font-bold ${currentCategory === 'all' ? 'bg-brand-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'} shadow-xs whitespace-nowrap">
          Semua Sembako
        </button>
      `;

      catSelect.innerHTML = `<option value="">Umum (Tanpa Kategori)</option>`;

      categories.forEach(cat => {
        pillsContainer.innerHTML += `
          <button onclick="setCategoryFilter('${cat.id}')" class="cat-pill px-3 py-1.5 rounded-lg font-semibold ${currentCategory == cat.id ? 'bg-brand-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'} whitespace-nowrap">
            ${cat.name}
          </button>
        `;

        catSelect.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
      });
    }

    function setCategoryFilter(catId) {
      currentCategory = catId;
      renderCategoryPillsAndSelect();
      renderProducts();
    }

    async function quickAddCategory() {
      const name = prompt('Masukkan Nama Kategori Baru (misal: "Rokok & Tembakau"):');
      if (!name || !name.trim()) return;

      try {
        const res = await fetch(`${API_BASE}/categories`, {
          method: 'POST',
          headers: getApiHeaders(),
          body: JSON.stringify({ name: name.trim() })
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Gagal menambah kategori');

        showToast('Kategori baru berhasil ditambahkan!');
        await fetchCategories();
      } catch (err) {
        showToast('Gagal: ' + err.message);
      }
    }

    function manageCategoriesModal() {
      const listContainer = document.getElementById('category-manage-list');
      listContainer.innerHTML = '';

      if (categories.length === 0) {
        listContainer.innerHTML = `<p class="text-slate-400 text-center py-4">Belum ada kategori.</p>`;
      } else {
        categories.forEach(cat => {
          listContainer.innerHTML += `
            <div class="flex items-center justify-between p-2.5 bg-slate-50 border rounded-xl">
              <div>
                <span class="font-bold text-xs text-slate-800">${cat.name}</span>
                <span class="text-[10px] text-slate-400 ml-1">(${cat.products_count || 0} produk)</span>
              </div>
              <div class="flex items-center gap-1.5">
                <button onclick="editCategoryName(${cat.id}, '${cat.name}')" class="p-1 text-blue-600 hover:bg-blue-50 rounded" title="Ubah Nama">
                  <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                </button>
                <button onclick="deleteCategoryAction(${cat.id})" class="p-1 text-red-600 hover:bg-red-50 rounded" title="Hapus Kategori">
                  <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                </button>
              </div>
            </div>
          `;
        });
      }
      document.getElementById('modal-manage-categories').classList.remove('hidden');
      lucide.createIcons();
    }

    async function editCategoryName(id, currentName) {
      const newName = prompt('Ubah Nama Kategori:', currentName);
      if (!newName || !newName.trim() || newName.trim() === currentName) return;

      try {
        const res = await fetch(`${API_BASE}/categories/${id}`, {
          method: 'PUT',
          headers: getApiHeaders(),
          body: JSON.stringify({ name: newName.trim() })
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Gagal mengubah kategori');

        showToast('Kategori berhasil diperbarui!');
        await fetchCategories();
        manageCategoriesModal();
      } catch (err) {
        showToast('Gagal: ' + err.message);
      }
    }

    async function deleteCategoryAction(id) {
      if (!confirm('Hapus kategori ini? Kategori yang masih berisi produk tidak dapat dihapus.')) return;

      try {
        const res = await fetch(`${API_BASE}/categories/${id}`, {
          method: 'DELETE',
          headers: getApiHeaders()
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Gagal menghapus kategori');

        showToast(json.message || 'Kategori berhasil dihapus');
        await fetchCategories();
        manageCategoriesModal();
      } catch (err) {
        showToast('Gagal: ' + err.message);
      }
    }

    // ==========================================
    // 2. API: PRODUK & KATALOG
    // ==========================================
    async function fetchProducts() {
      try {
        const res = await fetch(`${API_BASE}/products`);
        const json = await res.json();
        if (json.success) {
          products = json.data;
          renderProducts();
          renderMasterTable();
        }
      } catch (err) {
        showToast('Gagal memuat produk dari server.');
      }
    }

    function renderProducts() {
      const grid = document.getElementById('product-grid');
      const query = (document.getElementById('search-input').value || '').toLowerCase().trim();

      const filtered = products.filter(p => {
        const matchCat = (currentCategory === 'all') || (p.category_id == currentCategory);
        const matchQuery = p.name.toLowerCase().includes(query) || (p.barcode && p.barcode.toLowerCase().includes(query));
        return matchCat && matchQuery;
      });

      if (filtered.length === 0) {
        grid.innerHTML = `
          <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
            <i data-lucide="package-x" class="w-12 h-12 mx-auto mb-2 text-slate-300"></i>
            <p class="font-bold text-sm text-slate-600">Barang sembako tidak ditemukan</p>
            <p class="text-xs text-slate-400 mt-1">Coba kata kunci lain atau tambahkan di Master Data.</p>
          </div>
        `;
        lucide.createIcons();
        return;
      }

      grid.innerHTML = filtered.map(item => {
        const isOutOfStock = Number(item.current_stock) <= 0;
        const isLowStock = Number(item.current_stock) > 0 && Number(item.current_stock) <= Number(item.min_stock_alert);

        return `
          <div class="bg-white rounded-2xl p-3.5 border border-slate-200 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group ${isOutOfStock ? 'opacity-60' : 'hover:border-brand-300'}">
            <div>
              <div class="flex items-center justify-between gap-1 mb-1.5">
                <span class="text-[10px] font-mono text-slate-400 font-bold">${item.barcode || '-'}</span>
                <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold ${
                  isOutOfStock ? 'bg-red-100 text-red-700' : isLowStock ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600'
                }">
                  ${isOutOfStock ? 'Habis' : `Stok: ${formatStock(item.current_stock)} ${item.base_unit}`}
                </span>
              </div>

              <h3 class="font-bold text-slate-800 text-xs sm:text-sm line-clamp-1 group-hover:text-brand-700 transition-colors">
                ${item.name}
              </h3>

              <div class="mt-2.5">
                <label class="text-[10px] font-semibold text-slate-400 block mb-1">Pilih Satuan Jual:</label>
                <select 
                  id="unit-sel-${item.id}"
                  class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-slate-700 outline-none focus:border-brand-500 focus:bg-white cursor-pointer"
                >
                  ${item.units.map(u => `
                    <option value="${u.id}">
                      ${u.unit_name} — ${formatRp(u.sell_price)}
                    </option>
                  `).join('')}
                </select>
              </div>

              ${item.allow_nominal_buy && !isOutOfStock ? `
                <button 
                  type="button" 
                  onclick="openNominalModal(${item.id})"
                  class="mt-2 w-full py-1.5 px-2 bg-amber-50 hover:bg-amber-100 border border-amber-300 text-amber-900 text-[11px] font-bold rounded-lg flex items-center justify-center gap-1.5 transition-all active:scale-95 shadow-xs"
                >
                  <i data-lucide="coins" class="w-3.5 h-3.5 text-amber-600"></i>
                  <span>Beli Nominal (Rp)</span>
                </button>
              ` : ''}
            </div>

            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between">
              <span class="text-[10px] text-slate-400 font-medium">${item.category ? item.category.name : 'Umum'}</span>
              <button 
                type="button" 
                onclick="addToCart(${item.id})"
                ${isOutOfStock ? 'disabled' : ''}
                class="px-3.5 py-1.5 bg-brand-600 hover:bg-brand-700 disabled:bg-slate-300 text-white text-xs font-bold rounded-lg flex items-center gap-1 transition-all active:scale-95 shadow-sm"
              >
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah</span>
              </button>
            </div>
          </div>
        `;
      }).join('');

      lucide.createIcons();
    }

    // ==========================================
    // 3. KERANJANG BELANJA (CART)
    // ==========================================
    function addToCart(productId) {
      const product = products.find(p => p.id === productId);
      if (!product) return;

      const sel = document.getElementById(`unit-sel-${productId}`);
      const unitId = sel ? parseInt(sel.value, 10) : null;
      const unit = product.units.find(u => u.id === unitId) || product.units[0];

      const currentCartDeduction = cart
        .filter(c => c.product.id === product.id)
        .reduce((sum, c) => sum + (c.conversion_factor * c.qty), 0);

      const requiredDeduction = Number(unit.conversion_factor);
      if (currentCartDeduction + requiredDeduction > Number(product.current_stock)) {
        showToast(`Stok ${product.name} tidak mencukupi!`);
        return;
      }

      const existing = cart.find(c => c.product.id === product.id && c.product_unit_id === unit.id && !c.isNominal);
      if (existing) {
        existing.qty += 1;
      } else {
        cart.push({
          cartId: 'item-' + Date.now() + Math.random(),
          product: product,
          product_unit_id: unit.id,
          unit_name: unit.unit_name,
          conversion_factor: Number(unit.conversion_factor),
          price: Number(unit.sell_price),
          qty: 1,
          isNominal: false
        });
      }

      playBeep(700, 0.08);
      renderCart();
      showToast(`${product.name} (${unit.unit_name}) masuk keranjang.`);
    }

    function renderCart() {
      const container = document.getElementById('cart-items');
      const countBadge = document.getElementById('cart-item-count');

      const totalItems = cart.reduce((s, c) => s + c.qty, 0);
      countBadge.textContent = `${totalItems} item`;

      if (cart.length === 0) {
        container.innerHTML = `
          <div class="h-44 flex flex-col items-center justify-center text-slate-400 text-center">
            <i data-lucide="shopping-bag" class="w-10 h-10 mb-1.5 text-slate-300"></i>
            <p class="text-xs font-semibold text-slate-600">Keranjang masih kosong</p>
            <p class="text-[11px] text-slate-400">Pilih sembako di sebelah kiri untuk memulai.</p>
          </div>
        `;
        document.getElementById('cart-subtotal').textContent = 'Rp 0';
        document.getElementById('cart-grandtotal').textContent = 'Rp 0';
        document.getElementById('cart-discount-row').style.display = 'none';
        lucide.createIcons();
        return;
      }

      container.innerHTML = cart.map(item => {
        const lineTotal = item.price * item.qty;
        return `
          <div class="py-2.5 flex items-center justify-between gap-2">
            <div class="flex-1 min-w-0">
              <h4 class="font-bold text-xs text-slate-800 truncate">${item.product.name}</h4>
              <div class="flex items-center gap-1.5 mt-0.5">
                <span class="bg-brand-50 text-brand-800 text-[10px] font-semibold px-1.5 py-0.2 rounded font-mono">
                  ${item.unit_name}
                </span>
                <span class="text-[11px] text-slate-400 font-mono">${formatRp(item.price)}</span>
              </div>
            </div>

            <div class="flex items-center gap-1.5">
              ${item.isNominal ? `
                <span class="font-mono text-xs font-bold text-amber-800 px-2 bg-amber-50 rounded border border-amber-200">
                  ${item.qty} pack
                </span>
              ` : `
                <button onclick="updateCartQty('${item.cartId}', -1)" class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-700 font-bold active:scale-95 transition-all">-</button>
                <span class="font-mono text-xs font-bold text-slate-800 w-6 text-center">${item.qty}</span>
                <button onclick="updateCartQty('${item.cartId}', 1)" class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-700 font-bold active:scale-95 transition-all">+</button>
              `}

              <div class="text-right ml-1 w-20">
                <p class="font-mono font-bold text-xs text-slate-900">${formatRp(lineTotal)}</p>
              </div>

              <button onclick="removeCartItem('${item.cartId}')" class="p-1 text-slate-400 hover:text-red-600 transition-colors">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
              </button>
            </div>
          </div>
        `;
      }).join('');

      const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
      const grandTotal = Math.max(0, subtotal - discountAmount);

      document.getElementById('cart-subtotal').textContent = formatRp(subtotal);
      document.getElementById('cart-grandtotal').textContent = formatRp(grandTotal);

      const discRow = document.getElementById('cart-discount-row');
      if (discountAmount > 0) {
        discRow.style.display = 'flex';
        document.getElementById('cart-discount').textContent = `- ${formatRp(discountAmount)}`;
      } else {
        discRow.style.display = 'none';
      }

      lucide.createIcons();
    }

    function updateCartQty(cartId, change) {
      const item = cart.find(c => c.cartId === cartId);
      if (!item) return;

      if (change > 0) {
        const currentDeduction = cart
          .filter(c => c.product.id === item.product.id)
          .reduce((sum, c) => sum + (c.conversion_factor * c.qty), 0);

        if (currentDeduction + item.conversion_factor > Number(item.product.current_stock)) {
          showToast(`Stok ${item.product.name} telah mencapai batas!`);
          return;
        }
        item.qty += 1;
      } else {
        item.qty -= 1;
        if (item.qty <= 0) {
          cart = cart.filter(c => c.cartId !== cartId);
        }
      }
      renderCart();
    }

    function removeCartItem(cartId) {
      cart = cart.filter(c => c.cartId !== cartId);
      renderCart();
    }

    function clearCart() {
      if (cart.length === 0) return;
      cart = [];
      discountAmount = 0;
      document.getElementById('input-discount-val').value = '';
      renderCart();
      showToast('Keranjang belanja telah dikosongkan.');
    }

    function handleDiscountChange(val) {
      discountAmount = Math.max(0, parseFloat(val) || 0);
      renderCart();
    }

    // ==========================================
    // 4. BELI NOMINAL (RP)
    // ==========================================
    function openNominalModal(productId) {
      activeNominalProductId = productId;
      const product = products.find(p => p.id === productId);
      if (!product) return;

      document.getElementById('nominal-product-name').textContent = `${product.name} (${product.barcode || '-'})`;
      document.getElementById('nominal-base-price').textContent = `${formatRp(product.base_sell_price)} / ${product.base_unit}`;
      document.getElementById('nominal-money-input').value = '5000';

      calcNominalEquivalent();
      document.getElementById('modal-nominal').classList.remove('hidden');
      document.getElementById('nominal-money-input').focus();
    }

    function closeNominalModal() {
      document.getElementById('modal-nominal').classList.add('hidden');
      activeNominalProductId = null;
    }

    function setNominalInput(val) {
      document.getElementById('nominal-money-input').value = val;
      calcNominalEquivalent();
    }

    function calcNominalEquivalent() {
      const money = parseFloat(document.getElementById('nominal-money-input').value) || 0;
      const product = products.find(p => p.id === activeNominalProductId);
      if (!product) return;

      const basePrice = Number(product.base_sell_price);
      const qty = basePrice > 0 ? (money / basePrice) : 0;
      document.getElementById('nominal-calc-qty').textContent = `${qty.toFixed(3)} ${product.base_unit}`;
    }

    function confirmAddNominalToCart() {
      const money = parseFloat(document.getElementById('nominal-money-input').value) || 0;
      if (money <= 0) {
        showToast('Masukkan nominal uang yang valid.');
        return;
      }

      const product = products.find(p => p.id === activeNominalProductId);
      if (!product) return;

      const basePrice = Number(product.base_sell_price);
      const qtyEquivalent = money / basePrice;

      if (qtyEquivalent > Number(product.current_stock)) {
        showToast(`Stok ${product.name} hanya tersisa ${product.current_stock} ${product.base_unit}!`);
        return;
      }

      cart.push({
        cartId: 'nom-' + Date.now(),
        product: product,
        product_unit_id: null,
        unit_name: `Nominal Rp ${money.toLocaleString('id-ID')} (~${qtyEquivalent.toFixed(3)} ${product.base_unit})`,
        conversion_factor: 1.0,
        price: money,
        qty: 1,
        isNominal: true
      });

      playBeep(800, 0.1);
      closeNominalModal();
      renderCart();
      showToast(`Beli nominal ${formatRp(money)} ditambahkan.`);
    }

    // ==========================================
    // 5. TAHAN & ANTREAN API (HOLD & RECALL)
    // ==========================================
    function openHoldConfirmModal() {
      if (cart.length === 0) {
        showToast('Keranjang kosong! Tidak ada transaksi yang bisa ditahan.');
        return;
      }

      const totalItems = cart.reduce((s, c) => s + c.qty, 0);
      const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
      const grandTotal = Math.max(0, subtotal - discountAmount);
      const timeStr = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

      document.getElementById('hold-label-input').value = `Antrean #${holdQueue.length + 1} (${timeStr})`;
      document.getElementById('hold-summary-items').textContent = `${totalItems} Item`;
      document.getElementById('hold-summary-total').textContent = formatRp(grandTotal);

      document.getElementById('modal-hold-confirm').classList.remove('hidden');
      document.getElementById('hold-label-input').focus();
    }

    function closeHoldConfirmModal() {
      document.getElementById('modal-hold-confirm').classList.add('hidden');
    }

    async function executeHoldCart() {
      if (cart.length === 0) return;

      const label = document.getElementById('hold-label-input').value.trim() || `Antrean #${holdQueue.length + 1}`;
      const payload = {
        hold_label: label,
        items: cart.map(item => ({
          product_id: item.product.id,
          product_unit_id: item.product_unit_id,
          unit_name: item.unit_name,
          quantity: item.qty,
          conversion_factor: item.conversion_factor,
          selling_price: item.price,
          subtotal: item.price * item.qty
        }))
      };

      try {
        const res = await fetch(`${API_BASE}/orders/hold`, {
          method: 'POST',
          headers: getApiHeaders(),
          body: JSON.stringify(payload)
        });

        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Gagal menahan antrean');

        cart = [];
        discountAmount = 0;
        document.getElementById('input-discount-val').value = '';

        closeHoldConfirmModal();
        renderCart();
        fetchHoldQueue();
        showToast(`Antrean "${label}" berhasil diparkir di database!`);
      } catch (err) {
        showToast('Gagal: ' + err.message);
      }
    }

    async function fetchHoldQueue() {
      try {
        const res = await fetch(`${API_BASE}/orders/hold/list`);
        const json = await res.json();
        if (json.success) {
          holdQueue = json.data;
          updateHoldBadge();
        }
      } catch (err) {
        console.error('Gagal mengambil daftar hold:', err);
      }
    }

    function updateHoldBadge() {
      const badge = document.getElementById('hold-count-badge');
      if (holdQueue.length > 0) {
        badge.textContent = holdQueue.length;
        badge.classList.remove('hidden');
      } else {
        badge.classList.add('hidden');
      }
    }

    function openHoldQueueModal() {
      fetchHoldQueue().then(() => {
        renderHoldQueueList();
        document.getElementById('modal-hold-queue').classList.remove('hidden');
      });
    }

    function closeHoldQueueModal() {
      document.getElementById('modal-hold-queue').classList.add('hidden');
    }

    function renderHoldQueueList() {
      const container = document.getElementById('hold-queue-items-container');
      const badgeHeader = document.getElementById('hold-queue-badge-count');
      badgeHeader.textContent = `${holdQueue.length} Antrean`;

      if (holdQueue.length === 0) {
        container.innerHTML = `
          <div class="py-12 text-center text-slate-400">
            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-1 text-slate-300"></i>
            <p class="text-xs font-semibold text-slate-600">Tidak ada antrean yang ditahan.</p>
          </div>
        `;
        lucide.createIcons();
        return;
      }

      container.innerHTML = holdQueue.map(item => {
        const itemNames = (item.items || []).map(c => `${c.product ? c.product.name : c.unit_name} (${c.quantity})`).join(', ');
        return `
          <div class="p-3 bg-amber-50/70 border border-amber-200 rounded-xl space-y-2">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-1.5">
                <span class="font-bold text-xs text-amber-950">${item.hold_label}</span>
                <span class="text-[10px] font-mono text-amber-700 bg-amber-100 px-1.5 py-0.2 rounded">${new Date(item.transaction_date).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
              </div>
              <span class="font-mono font-bold text-xs text-brand-800">${formatRp(item.grand_total)}</span>
            </div>

            <p class="text-[11px] text-slate-500 truncate italic">${itemNames}</p>

            <div class="flex items-center justify-between pt-1 border-t border-amber-100">
              <span class="text-[10px] text-slate-400">${item.items.length} jenis item</span>
              <div class="flex items-center gap-2">
                <button onclick="deleteHoldItem(${item.id})" class="text-xs font-semibold text-red-600 hover:text-red-800">Hapus</button>
                <button onclick="recallHoldItem(${item.id})" class="px-2.5 py-1 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-lg flex items-center gap-1">
                  <i data-lucide="play" class="w-3.5 h-3.5"></i>
                  <span>Panggil ke Kasir</span>
                </button>
              </div>
            </div>
          </div>
        `;
      }).join('');

      lucide.createIcons();
    }

    async function recallHoldItem(orderId) {
      const selected = holdQueue.find(h => h.id === orderId);
      if (!selected) return;

      if (cart.length > 0) {
        if (!confirm('Keranjang aktif saat ini masih ada barang. Apakah Anda ingin menimpanya?')) return;
      }

      cart = selected.items.map(it => ({
        cartId: 'item-' + Date.now() + Math.random(),
        product: it.product || { id: it.product_id, name: it.unit_name, current_stock: 999 },
        product_unit_id: it.product_unit_id,
        unit_name: it.unit_name,
        conversion_factor: Number(it.conversion_factor),
        price: Number(it.selling_price),
        qty: Number(it.quantity),
        isNominal: !it.product_unit_id && it.unit_name.includes('Nominal')
      }));

      discountAmount = Number(selected.discount_amount) || 0;
      document.getElementById('input-discount-val').value = discountAmount || '';

      await fetch(`${API_BASE}/orders/hold/${orderId}`, {
        method: 'DELETE',
        headers: getApiHeaders()
      });

      closeHoldQueueModal();
      fetchHoldQueue();
      renderCart();
      playBeep(850, 0.12);
      showToast(`Antrean "${selected.hold_label}" berhasil dimuat ke kasir!`);
    }

    async function deleteHoldItem(orderId) {
      if (confirm('Hapus antrean belanja ini dari database?')) {
        await fetch(`${API_BASE}/orders/hold/${orderId}`, {
          method: 'DELETE',
          headers: getApiHeaders()
        });
        fetchHoldQueue();
        renderHoldQueueList();
      }
    }

    // ==========================================
    // 6. CHECKOUT & PEMBAYARAN API (F8)
    // ==========================================
    function openCheckoutModal() {
      if (cart.length === 0) {
        showToast('Keranjang masih kosong!');
        return;
      }

      const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
      const grandTotal = Math.max(0, subtotal - discountAmount);

      document.getElementById('checkout-total-bill').textContent = formatRp(grandTotal);
      setPaymentMethod('cash');
      buildQuickCashButtons(grandTotal);

      document.getElementById('cash-received-input').value = grandTotal;
      calcCashChange();

      document.getElementById('modal-checkout').classList.remove('hidden');
      document.getElementById('cash-received-input').focus();
      document.getElementById('cash-received-input').select();
    }

    function closeCheckoutModal() {
      document.getElementById('modal-checkout').classList.add('hidden');
    }

    function setPaymentMethod(method) {
      currentPaymentMethod = method;
      const methods = ['cash', 'qris', 'transfer', 'debt'];

      methods.forEach(m => {
        const btn = document.getElementById(`pm-${m}`);
        if (m === method) {
          btn.classList.add('bg-white', 'text-brand-900', 'shadow-xs');
          btn.classList.remove('text-slate-600', 'text-amber-700');
        } else {
          btn.classList.remove('bg-white', 'text-brand-900', 'shadow-xs');
          btn.classList.add(m === 'debt' ? 'text-amber-700' : 'text-slate-600');
        }
      });

      const tunaiSec = document.getElementById('section-pay-tunai');
      const digitalSec = document.getElementById('section-pay-digital');
      const kasbonSec = document.getElementById('section-pay-kasbon');

      tunaiSec.classList.add('hidden');
      digitalSec.classList.add('hidden');
      kasbonSec.classList.add('hidden');

      if (method === 'cash') {
        tunaiSec.classList.remove('hidden');
        document.getElementById('cash-received-input').focus();
      } else if (method === 'qris' || method === 'transfer') {
        digitalSec.classList.remove('hidden');
        document.getElementById('digital-pay-title').textContent = method === 'qris' ? 'Scan Barcode QRIS Toko' : 'Transfer Rekening Bank Toko';
      } else if (method === 'debt') {
        kasbonSec.classList.remove('hidden');
        document.getElementById('kasbon-cust-name').focus();
      }
    }

    function buildQuickCashButtons(total) {
      const container = document.getElementById('quick-cash-buttons');
      const nominations = [total, 10000, 20000, 50000, 100000, 200000];
      const unique = Array.from(new Set(nominations)).filter(n => n >= total || n === total);

      container.innerHTML = unique.slice(0, 6).map((nom, idx) => `
        <button 
          type="button" 
          onclick="setCashReceived(${nom})"
          class="py-2 px-1 bg-slate-100 hover:bg-brand-100 text-slate-800 rounded-lg border border-slate-200 transition-colors ${idx === 0 ? 'border-brand-500 bg-brand-50 text-brand-900 font-black' : ''}"
        >
          ${idx === 0 ? 'Uang Pas' : formatRp(nom)}
        </button>
      `).join('');
    }

    function setCashReceived(val) {
      document.getElementById('cash-received-input').value = val;
      calcCashChange();
    }

    function calcCashChange() {
      const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
      const grandTotal = Math.max(0, subtotal - discountAmount);
      const received = parseFloat(document.getElementById('cash-received-input').value) || 0;
      const change = received - grandTotal;

      const changeEl = document.getElementById('cash-change-display');
      if (change < 0) {
        changeEl.textContent = `Kurang ${formatRp(Math.abs(change))}`;
        changeEl.classList.remove('text-emerald-700');
        changeEl.classList.add('text-red-600');
      } else {
        changeEl.textContent = formatRp(change);
        changeEl.classList.remove('text-red-600');
        changeEl.classList.add('text-emerald-700');
      }
    }

    async function finalizeTransaction() {
      const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
      const grandTotal = Math.max(0, subtotal - discountAmount);
      let cashTendered = grandTotal;

      if (currentPaymentMethod === 'cash') {
        cashTendered = parseFloat(document.getElementById('cash-received-input').value) || 0;
        if (cashTendered < grandTotal) {
          showToast('Uang tunai pembayaran masih kurang!');
          return;
        }
      }

      let customerName = null;
      if (currentPaymentMethod === 'debt') {
        customerName = document.getElementById('kasbon-cust-name').value.trim();
        if (!customerName) {
          showToast('Wajib mengisi nama pelanggan untuk Kasbon!');
          return;
        }
      }

      const payload = {
        payment_method: currentPaymentMethod,
        discount_amount: discountAmount,
        cash_tendered: currentPaymentMethod === 'cash' ? cashTendered : grandTotal,
        customer_name: customerName,
        cashier_note: currentPaymentMethod === 'debt' ? document.getElementById('kasbon-notes').value.trim() : null,
        items: cart.map(item => ({
          product_id: item.product.id,
          product_unit_id: item.product_unit_id,
          unit_name: item.unit_name,
          quantity: item.qty,
          conversion_factor: item.conversion_factor,
          selling_price: item.price,
          subtotal: item.price * item.qty
        }))
      };

      const btnPay = document.getElementById('btn-submit-pay');
      btnPay.disabled = true;
      btnPay.innerHTML = 'Memproses Transaksi...';

      try {
        const res = await fetch(`${API_BASE}/orders/checkout`, {
          method: 'POST',
          headers: getApiHeaders(),
          body: JSON.stringify(payload)
        });

        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Gagal checkout transaksi');

        closeCheckoutModal();
        showReceiptModal(json.data);

        cart = [];
        discountAmount = 0;
        document.getElementById('input-discount-val').value = '';
        renderCart();

        fetchProducts();
        fetchDebts();
        playBeep(950, 0.2, 'triangle');
        showToast('Transaksi berhasil dicatat ke server!');
      } catch (err) {
        showToast('Kesalahan: ' + err.message);
      } finally {
        btnPay.disabled = false;
        btnPay.innerHTML = `<i data-lucide="printer" class="w-4 h-4"></i><span>Selesaikan Transaksi (API)</span>`;
        lucide.createIcons();
      }
    }

    // ==========================================
    // 7. STRUK THERMAL MODAL
    // ==========================================
    function showReceiptModal(order) {
      document.getElementById('rcpt-invoice').textContent = order.invoice_number;
      document.getElementById('rcpt-date').textContent = new Date(order.transaction_date).toLocaleString('id-ID');
      document.getElementById('rcpt-customer').textContent = order.customer ? order.customer.name : 'Umum';

      const itemsContainer = document.getElementById('rcpt-items');
      itemsContainer.innerHTML = (order.items || []).map(i => `
        <div class="space-y-0.5">
          <div class="flex justify-between font-bold">
            <span class="truncate max-w-[180px]">${i.product ? i.product.name : i.unit_name}</span>
            <span>${formatRp(i.subtotal)}</span>
          </div>
          <div class="flex justify-between text-slate-500 text-[10px]">
            <span>${i.quantity} x ${i.unit_name}</span>
            <span>@${formatRp(i.selling_price)}</span>
          </div>
        </div>
      `).join('');

      document.getElementById('rcpt-subtotal').textContent = formatRp(order.total_amount);

      const discRow = document.getElementById('rcpt-discount-row');
      if (Number(order.discount_amount) > 0) {
        discRow.style.display = 'flex';
        document.getElementById('rcpt-discount').textContent = `- ${formatRp(order.discount_amount)}`;
      } else {
        discRow.style.display = 'none';
      }

      document.getElementById('rcpt-total').textContent = formatRp(order.grand_total);
      document.getElementById('rcpt-pay-label').textContent = `Bayar (${order.payment_method.toUpperCase()}):`;
      document.getElementById('rcpt-paid').textContent = formatRp(order.cash_tendered);

      const changeRow = document.getElementById('rcpt-change-row');
      if (order.payment_method === 'cash') {
        changeRow.style.display = 'flex';
        document.getElementById('rcpt-change').textContent = formatRp(order.change_amount);
      } else if (order.payment_method === 'debt') {
        changeRow.style.display = 'flex';
        document.getElementById('rcpt-change').textContent = 'KASBON/TEMPO';
      } else {
        changeRow.style.display = 'none';
      }

      document.getElementById('modal-receipt').classList.remove('hidden');
      lucide.createIcons();
    }

    function closeReceiptModal() {
      document.getElementById('modal-receipt').classList.add('hidden');
      document.getElementById('search-input').focus();
    }

    // ==========================================
    // 8. MASTER DATA BARANG (F2): TABLE & ACTIONS
    // ==========================================
    function renderMasterTable() {
      const tbody = document.getElementById('master-table-body');
      const query = (document.getElementById('master-search-input').value || '').toLowerCase().trim();

      const filtered = products.filter(p => p.name.toLowerCase().includes(query) || (p.barcode && p.barcode.toLowerCase().includes(query)));

      document.getElementById('stat-total-products').textContent = products.length;
      const totalAsset = products.reduce((sum, p) => sum + (Number(p.base_buy_price) * Number(p.current_stock)), 0);
      document.getElementById('stat-total-asset-value').textContent = formatRp(totalAsset);
      const lowStockCount = products.filter(p => Number(p.current_stock) <= Number(p.min_stock_alert)).length;
      document.getElementById('stat-low-stock-count').textContent = `${lowStockCount} Produk`;

      if (filtered.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-8 text-slate-400">Tidak ada produk ditemukan.</td></tr>`;
        return;
      }

      tbody.innerHTML = filtered.map(item => {
        const isOutOfStock = Number(item.current_stock) <= 0;
        const isLow = Number(item.current_stock) > 0 && Number(item.current_stock) <= Number(item.min_stock_alert);

        const unitsDisplay = (item.units || []).map(u => `
          <span class="inline-block bg-slate-100 border border-slate-200 px-1.5 py-0.5 rounded text-[10px] mr-1 mb-1 font-mono">
            ${u.unit_name}: <b>${formatRp(u.sell_price)}</b>
          </span>
        `).join('');

        return `
          <tr class="hover:bg-slate-50/80 transition-colors">
            <td class="py-3 px-4 font-mono font-bold text-slate-700">${item.barcode || '-'}</td>
            <td class="py-3 px-4 font-semibold text-slate-800">
              ${item.name}
              <div class="flex items-center gap-1.5 mt-0.5">
                <span class="text-[10px] text-slate-400 font-mono">Satuan Dasar: <b>${item.base_unit}</b></span>
                ${item.allow_nominal_buy ? '<span class="bg-amber-100 text-amber-800 text-[9px] font-bold px-1 rounded">Beli Nominal (Rp)</span>' : ''}
              </div>
            </td>
            <td class="py-3 px-4">
              <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full text-[10px] font-medium whitespace-nowrap">
                ${item.category ? item.category.name : 'Umum'}
              </span>
            </td>
            <td class="py-3 px-4 text-right font-mono text-slate-600">
              ${formatRp(item.base_buy_price)} <span class="text-[10px] text-slate-400">/${item.base_unit}</span>
            </td>
            <td class="py-3 px-4 max-w-xs">
              <div class="flex flex-wrap">${unitsDisplay}</div>
            </td>
            <td class="py-3 px-4 text-center font-mono font-bold text-slate-800">
              ${formatStock(item.current_stock)} ${item.base_unit}
            </td>
            <td class="py-3 px-4 text-center">
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${
                isOutOfStock ? 'bg-red-100 text-red-700' : isLow ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-700'
              }">
                ${isOutOfStock ? 'Habis' : isLow ? 'Menipis' : 'Aman'}
              </span>
            </td>
            <td class="py-3 px-4 text-center">
              <div class="flex items-center justify-center gap-1.5">
                <button onclick="openRestockModal(${item.id})" title="Koreksi / Restock Cepat" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors">
                  <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                </button>
                <button onclick="editProductModal(${item.id})" title="Ubah Data Barang" class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                  <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                </button>
                <button onclick="deleteProduct(${item.id})" title="Hapus Barang" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                  <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                </button>
              </div>
            </td>
          </tr>
        `;
      }).join('');

      lucide.createIcons();
    }

    function addUnitRow(name = '', multiplier = 1, price = '') {
      const container = document.getElementById('unit-rows-list');
      const row = document.createElement('div');
      row.className = 'unit-entry-row flex items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-200';
      row.innerHTML = `
        <div class="flex-1">
          <input type="text" placeholder="Nama Satuan (cth: 1/2 Kg)" value="${name}" required class="u-name w-full px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs font-semibold outline-none focus:border-brand-500">
        </div>
        <div class="w-24">
          <input type="number" step="any" min="0.0001" placeholder="Faktor" value="${multiplier}" required class="u-mult w-full px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs font-mono outline-none focus:border-brand-500" title="Rasio pengurang terhadap satuan dasar">
        </div>
        <div class="w-28">
          <input type="number" step="100" min="0" placeholder="Harga Jual" value="${price}" required class="u-price w-full px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs font-mono font-bold outline-none focus:border-brand-500">
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="p-1 text-slate-400 hover:text-red-600">
          <i data-lucide="trash" class="w-3.5 h-3.5"></i>
        </button>
      `;
      container.appendChild(row);
      lucide.createIcons();
    }

    function openProductModal() {
      document.getElementById('product-form').reset();
      document.getElementById('p-id').value = '';
      document.getElementById('product-modal-title').innerHTML = `
        <i data-lucide="package-plus" class="w-4 h-4 text-brand-600"></i>
        <span>Tambah Produk Sembako Baru</span>
      `;
      document.getElementById('btn-save-product').textContent = 'Simpan ke Database';
      document.getElementById('p-base-unit').value = 'kg';
      document.getElementById('p-allow-nominal').checked = false;

      const container = document.getElementById('unit-rows-list');
      container.innerHTML = '';
      addUnitRow('1 Kg', 1, 15000);
      addUnitRow('1/2 Kg', 0.5, 8000);

      document.getElementById('modal-product').classList.remove('hidden');
      document.getElementById('p-name').focus();
      lucide.createIcons();
    }

    function editProductModal(id) {
      const p = products.find(prod => prod.id === id);
      if (!p) return;

      document.getElementById('p-id').value = p.id;
      document.getElementById('p-barcode').value = p.barcode || '';
      document.getElementById('p-category-id').value = p.category_id || '';
      document.getElementById('p-name').value = p.name;
      document.getElementById('p-base-unit').value = p.base_unit;
      document.getElementById('p-buy-price').value = p.base_buy_price;
      document.getElementById('p-sell-price').value = p.base_sell_price;
      document.getElementById('p-stock').value = p.current_stock;
      document.getElementById('p-min-stock').value = p.min_stock_alert || 5;
      document.getElementById('p-allow-nominal').checked = Boolean(p.allow_nominal_buy);

      const container = document.getElementById('unit-rows-list');
      container.innerHTML = '';
      if (p.units && p.units.length > 0) {
        p.units.forEach(u => addUnitRow(u.unit_name, u.conversion_factor, u.sell_price));
      } else {
        addUnitRow('1 ' + p.base_unit, 1, p.base_sell_price);
      }

      document.getElementById('product-modal-title').innerHTML = `
        <i data-lucide="pencil" class="w-4 h-4 text-brand-600"></i>
        <span>Ubah Data Barang & Multi-Satuan</span>
      `;
      document.getElementById('btn-save-product').textContent = 'Perbarui Produk';

      document.getElementById('modal-product').classList.remove('hidden');
      document.getElementById('p-name').focus();
      lucide.createIcons();
    }

    function closeProductModal() {
      document.getElementById('modal-product').classList.add('hidden');
    }

    async function handleSaveProduct(e) {
      e.preventDefault();
      const id = document.getElementById('p-id').value;
      const rows = document.querySelectorAll('.unit-entry-row');
      const units = [];
      
      rows.forEach(r => {
        const uName = r.querySelector('.u-name').value.trim();
        const uMult = parseFloat(r.querySelector('.u-mult').value) || 1;
        const uPrice = parseFloat(r.querySelector('.u-price').value) || 0;
        if (uName && uPrice >= 0) {
          units.push({
            unit_name: uName,
            conversion_factor: uMult,
            sell_price: uPrice,
            is_base_unit: uMult === 1
          });
        }
      });

      if (units.length === 0) {
        showToast('Wajib membuat minimal 1 satuan jual!');
        return;
      }

      const payload = {
        name: document.getElementById('p-name').value.trim(),
        barcode: document.getElementById('p-barcode').value.trim() || null,
        category_id: document.getElementById('p-category-id').value || null,
        base_unit: document.getElementById('p-base-unit').value.trim(),
        base_buy_price: parseFloat(document.getElementById('p-buy-price').value) || 0,
        base_sell_price: parseFloat(document.getElementById('p-sell-price').value) || 0,
        current_stock: parseFloat(document.getElementById('p-stock').value) || 0,
        min_stock_alert: parseFloat(document.getElementById('p-min-stock').value) || 5,
        allow_nominal_buy: document.getElementById('p-allow-nominal').checked,
        units: units
      };

      const btnSave = document.getElementById('btn-save-product');
      btnSave.disabled = true;
      btnSave.textContent = 'Menyimpan...';

      try {
        const url = id ? `${API_BASE}/products/${id}` : `${API_BASE}/products`;
        const method = id ? 'PUT' : 'POST';

        const res = await fetch(url, {
          method: method,
          headers: getApiHeaders(),
          body: JSON.stringify(payload)
        });

        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Gagal menyimpan produk');

        closeProductModal();
        await fetchProducts();
        await fetchCategories();
        showToast(id ? 'Data produk berhasil diperbarui!' : 'Produk baru berhasil ditambahkan!');
      } catch (err) {
        showToast('Gagal: ' + err.message);
      } finally {
        btnSave.disabled = false;
        btnSave.textContent = id ? 'Perbarui Produk' : 'Simpan ke Database';
      }
    }

    async function deleteProduct(id) {
      const p = products.find(prod => prod.id === id);
      if (!p) return;

      if (!confirm(`Yakin ingin menghapus produk "${p.name}" dari sistem?`)) {
        return;
      }

      try {
        const res = await fetch(`${API_BASE}/products/${id}`, {
          method: 'DELETE',
          headers: getApiHeaders()
        });

        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Gagal menghapus produk');

        await fetchProducts();
        await fetchCategories();
        showToast(json.message || 'Produk berhasil dihapus.');
      } catch (err) {
        showToast('Gagal: ' + err.message);
      }
    }

    function openRestockModal(id) {
      const p = products.find(prod => prod.id === id);
      if (!p) return;
      document.getElementById('restock-prod-id').value = p.id;
      document.getElementById('restock-prod-name').textContent = `${p.name} (Sisa: ${formatStock(p.current_stock)} ${p.base_unit})`;
      document.getElementById('restock-qty-input').value = '';
      document.getElementById('modal-restock').classList.remove('hidden');
      document.getElementById('restock-qty-input').focus();
    }

    function closeRestockModal() {
      document.getElementById('modal-restock').classList.add('hidden');
    }

    async function executeRestock() {
      const id = document.getElementById('restock-prod-id').value;
      const addQty = parseFloat(document.getElementById('restock-qty-input').value) || 0;
      if (addQty <= 0) {
        showToast('Masukkan jumlah stok yang valid.');
        return;
      }

      try {
        const res = await fetch(`${API_BASE}/products/${id}/stock`, {
          method: 'PATCH',
          headers: getApiHeaders(),
          body: JSON.stringify({ added_stock: addQty })
        });

        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Gagal restock');

        closeRestockModal();
        fetchProducts();
        showToast('Stok berhasil diperbarui di database!');
      } catch (err) {
        showToast('Gagal: ' + err.message);
      }
    }

    function exportMasterCSV() {
      let csv = 'Barcode,Nama Barang,Kategori,Satuan Dasar,Modal (Rp),Stok Fisik,Beli Nominal\n';
      products.forEach(p => {
        csv += `"${p.barcode || ''}","${p.name}","${p.category ? p.category.name : 'Umum'}","${p.base_unit}",${p.base_buy_price},${p.current_stock},"${p.allow_nominal_buy ? 'Ya' : 'Tidak'}"\n`;
      });
      const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.download = `Master_Sembako_${Date.now()}.csv`;
      link.click();
    }

    // ==========================================
    // 9. RIWAYAT TRANSAKSI API (F3)
    // ==========================================
    async function fetchOrders() {
      try {
        const res = await fetch(`${API_BASE}/orders`);
        const json = await res.json();
        if (json.success) {
          transactions = json.data.data;
          renderHistoryTable();
        }
      } catch (err) {
        console.error('Gagal mengambil pesanan:', err);
      }
    }

    function renderHistoryTable() {
      const tbody = document.getElementById('history-table-body');
      const methodFilter = document.getElementById('history-filter-method').value;

      const filtered = transactions.filter(t => {
        if (methodFilter === 'ALL') return true;
        return t.payment_method === methodFilter;
      });

      const totalSales = transactions.reduce((sum, t) => sum + Number(t.grand_total), 0);
      const totalProfit = transactions.reduce((sum, t) => sum + (Number(t.grand_total) - Number(t.total_cogs)), 0);
      const totalItems = transactions.reduce((sum, t) => sum + (t.items ? t.items.reduce((s, i) => s + Number(i.quantity), 0) : 0), 0);

      document.getElementById('stat-total-sales').textContent = formatRp(totalSales);
      document.getElementById('stat-total-profit').textContent = formatRp(totalProfit);
      document.getElementById('stat-total-invoices').textContent = transactions.length;
      document.getElementById('stat-total-items-sold').textContent = totalItems.toFixed(0);

      if (filtered.length === 0) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-8 text-slate-400">Belum ada riwayat transaksi di database.</td></tr>`;
        return;
      }

      tbody.innerHTML = filtered.map(t => {
        const profit = Number(t.grand_total) - Number(t.total_cogs);
        return `
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="py-3 px-4 font-mono font-bold text-slate-800">${t.invoice_number}</td>
            <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">${new Date(t.transaction_date).toLocaleString('id-ID')}</td>
            <td class="py-3 px-4 font-semibold text-slate-700">${t.customer ? t.customer.name : 'Pelanggan Umum'}</td>
            <td class="py-3 px-4 text-center">
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${
                t.payment_method === 'cash' ? 'bg-emerald-100 text-emerald-800' :
                t.payment_method === 'qris' ? 'bg-blue-100 text-blue-800' :
                t.payment_method === 'transfer' ? 'bg-purple-100 text-purple-800' :
                'bg-amber-100 text-amber-800'
              }">
                ${t.payment_method.toUpperCase()}
              </span>
            </td>
            <td class="py-3 px-4 text-right font-mono">${formatRp(t.total_amount)}</td>
            <td class="py-3 px-4 text-right font-mono text-slate-400">${Number(t.discount_amount) > 0 ? '-' + formatRp(t.discount_amount) : '-'}</td>
            <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">${formatRp(t.grand_total)}</td>
            <td class="py-3 px-4 text-right font-mono font-bold text-emerald-700">+${formatRp(profit)}</td>
            <td class="py-3 px-4 text-center">
              <button onclick="reprintReceipt(${t.id})" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold flex items-center gap-1 mx-auto">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                <span>Struk</span>
              </button>
            </td>
          </tr>
        `;
      }).join('');

      lucide.createIcons();
    }

    async function reprintReceipt(orderId) {
      try {
        const res = await fetch(`${API_BASE}/orders/${orderId}`);
        const json = await res.json();
        if (json.success) {
          showReceiptModal(json.data);
        }
      } catch (err) {
        showToast('Gagal memuat struk pesanan.');
      }
    }

    // ==========================================
    // 10. BUKU KASBON / PIUTANG API (F4)
    // ==========================================
    async function fetchDebts() {
      try {
        const res = await fetch(`${API_BASE}/debts`);
        const json = await res.json();
        if (json.success) {
          kasbonList = json.data;
          renderKasbonTable();
        }
      } catch (err) {
        console.error('Gagal mengambil kasbon:', err);
      }
    }

    function renderKasbonTable() {
      const tbody = document.getElementById('kasbon-table-body');
      const query = (document.getElementById('kasbon-search-input').value || '').toLowerCase().trim();

      const filtered = kasbonList.filter(k => 
        (k.customer && k.customer.name.toLowerCase().includes(query)) ||
        (k.customer && k.customer.phone && k.customer.phone.includes(query))
      );

      const unpaidTotal = kasbonList.reduce((sum, k) => sum + Number(k.remaining_balance), 0);
      const paidTotal = kasbonList.reduce((sum, k) => sum + (Number(k.original_amount) - Number(k.remaining_balance)), 0);
      const customerUnpaidCount = kasbonList.filter(k => Number(k.remaining_balance) > 0).length;

      document.getElementById('stat-kasbon-unpaid').textContent = formatRp(unpaidTotal);
      document.getElementById('stat-kasbon-customer-count').textContent = `${customerUnpaidCount} Transaksi`;
      document.getElementById('stat-kasbon-paid').textContent = formatRp(paidTotal);

      const navBadge = document.getElementById('kasbon-nav-badge');
      if (unpaidTotal > 0) {
        navBadge.classList.remove('hidden');
      } else {
        navBadge.classList.add('hidden');
      }

      if (filtered.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-8 text-slate-400">Tidak ada catatan kasbon di database.</td></tr>`;
        return;
      }

      tbody.innerHTML = filtered.map(k => {
        const isLunas = Number(k.remaining_balance) <= 0;
        return `
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="py-3 px-4 font-bold text-slate-800">${k.customer ? k.customer.name : '-'}</td>
            <td class="py-3 px-4 font-mono text-slate-500">${k.customer && k.customer.phone ? k.customer.phone : '-'}</td>
            <td class="py-3 px-4 text-slate-600">
              <span class="font-mono text-xs font-semibold text-slate-700">${k.order ? k.order.invoice_number : '-'}</span>
              <div class="text-[10px] text-slate-400 font-mono mt-0.5">${new Date(k.created_at).toLocaleDateString('id-ID')}</div>
            </td>
            <td class="py-3 px-4 text-right font-mono">${formatRp(k.original_amount)}</td>
            <td class="py-3 px-4 text-right font-mono font-bold text-red-600">${formatRp(k.remaining_balance)}</td>
            <td class="py-3 px-4 text-center">
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${
                isLunas ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'
              }">
                ${k.status.toUpperCase()}
              </span>
            </td>
            <td class="py-3 px-4 text-center">
              ${!isLunas ? `
                <button onclick="openPayKasbonModal(${k.id})" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold flex items-center gap-1 mx-auto shadow-xs">
                  <i data-lucide="hand-coins" class="w-3.5 h-3.5"></i>
                  <span>Bayar / Cicil</span>
                </button>
              ` : `
                <span class="text-[11px] text-slate-400 font-semibold italic">Telah Lunas</span>
              `}
            </td>
          </tr>
        `;
      }).join('');

      lucide.createIcons();
    }

    function openPayKasbonModal(debtId) {
      const k = kasbonList.find(item => item.id === debtId);
      if (!k) return;

      document.getElementById('pk-kasbon-id').value = k.id;
      document.getElementById('pk-cust-name').textContent = k.customer ? k.customer.name : '-';
      document.getElementById('pk-cust-balance').textContent = formatRp(k.remaining_balance);
      document.getElementById('pk-amount-input').value = k.remaining_balance;

      document.getElementById('modal-pay-kasbon').classList.remove('hidden');
      document.getElementById('pk-amount-input').focus();
    }

    function closePayKasbonModal() {
      document.getElementById('modal-pay-kasbon').classList.add('hidden');
    }

    function setPayKasbonFull() {
      const id = parseInt(document.getElementById('pk-kasbon-id').value, 10);
      const k = kasbonList.find(item => item.id === id);
      if (k) {
        document.getElementById('pk-amount-input').value = k.remaining_balance;
      }
    }

    async function confirmPayKasbon() {
      const id = parseInt(document.getElementById('pk-kasbon-id').value, 10);
      const amount = parseFloat(document.getElementById('pk-amount-input').value) || 0;

      if (amount <= 0) {
        showToast('Masukkan nominal pelunasan kasbon yang valid!');
        return;
      }

      const k = kasbonList.find(item => item.id === id);
      if (!k) return;

      if (amount > Number(k.remaining_balance)) {
        showToast('Nominal pembayaran melebihi sisa hutang!');
        return;
      }

      try {
        const res = await fetch(`${API_BASE}/debts/${id}/pay`, {
          method: 'POST',
          headers: getApiHeaders(),
          body: JSON.stringify({
            amount: amount,
            payment_method: 'cash',
            notes: 'Pelunasan Kasbon Kasir'
          })
        });

        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Gagal memproses pembayaran kasbon');

        closePayKasbonModal();
        fetchDebts();
        playBeep(900, 0.15);
        showToast(`Pembayaran kasbon sebesar ${formatRp(amount)} berhasil dicatat.`);
      } catch (err) {
        showToast('Gagal: ' + err.message);
      }
    }

    // ==========================================
    // 11. KEYBOARD SHORTCUTS
    // ==========================================
    document.addEventListener('keydown', function(e) {
      if (e.key === 'F1') {
        e.preventDefault();
        switchTab('pos');
      } else if (e.key === 'F2') {
        e.preventDefault();
        switchTab('master');
      } else if (e.key === 'F3') {
        e.preventDefault();
        switchTab('history');
      } else if (e.key === 'F4') {
        e.preventDefault();
        switchTab('kasbon');
      } else if (e.key === 'F8') {
        e.preventDefault();
        const posTabVisible = !document.getElementById('tab-content-pos').classList.contains('hidden');
        if (posTabVisible && cart.length > 0) {
          openCheckoutModal();
        }
      } else if (e.key === 'Escape') {
        closeReceiptModal();
        closeProductModal();
        closeRestockModal();
        closeNominalModal();
        closeHoldConfirmModal();
        closeHoldQueueModal();
        closeCheckoutModal();
        closePayKasbonModal();
        document.getElementById('modal-manage-categories').classList.add('hidden');
        const searchInput = document.getElementById('search-input');
        if (searchInput && document.activeElement === searchInput) {
          searchInput.value = '';
          renderProducts();
        }
      }
    });

    document.getElementById('search-input').addEventListener('input', renderProducts);

    window.onload = function() {
      startClock();
      fetchCategories();
      fetchProducts();
      fetchHoldQueue();
      fetchDebts();
      renderCart();
      lucide.createIcons();
    };
  </script>
</body>
</html>