// ==========================================
// PRODUK & KATALOG KASIR
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
    const stockBadge = isOutOfStock ? 'bg-red-100 text-red-700' : isLowStock ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600';

    return `
      <div class="bg-white rounded-2xl p-3.5 border border-slate-200 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group ${isOutOfStock ? 'opacity-60' : 'hover:border-brand-300'}">
        <div>
          <div class="flex items-center justify-between gap-1 mb-1.5">
            <span class="text-[10px] font-mono text-slate-400 font-bold">${esc(item.barcode || '-')}</span>
            <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold ${stockBadge}">
              ${isOutOfStock ? 'Habis' : `Stok: ${formatStock(item.current_stock)} ${esc(item.base_unit)}`}
            </span>
          </div>

          <h3 class="font-bold text-slate-800 text-xs sm:text-sm line-clamp-1 group-hover:text-brand-700 transition-colors">
            ${esc(item.name)}
          </h3>

          <div class="mt-2.5">
            <label for="unit-sel-${item.id}" class="text-[10px] font-semibold text-slate-400 block mb-1">Pilih Satuan Jual:</label>
            <select id="unit-sel-${item.id}" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-slate-700 outline-none focus:border-brand-500 focus:bg-white cursor-pointer">
              ${item.units.map(u => `<option value="${u.id}">${esc(u.unit_name)} — ${formatRp(u.sell_price)}</option>`).join('')}
            </select>
          </div>

          ${item.allow_nominal_buy && !isOutOfStock ? `
            <button type="button" onclick="openNominalModal(${item.id})" class="mt-2 w-full py-1.5 px-2 bg-amber-50 hover:bg-amber-100 border border-amber-300 text-amber-900 text-[11px] font-bold rounded-lg flex items-center justify-center gap-1.5 transition-all active:scale-95 shadow-xs">
              <i data-lucide="coins" class="w-3.5 h-3.5 text-amber-600"></i>
              <span>Beli Nominal (Rp)</span>
            </button>
          ` : ''}
        </div>

        <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between">
          <span class="text-[10px] text-slate-400 font-medium">${esc(item.category ? item.category.name : 'Umum')}</span>
          <button type="button" onclick="addToCart(${item.id})" ${isOutOfStock ? 'disabled' : ''} class="px-3.5 py-1.5 bg-brand-600 hover:bg-brand-700 disabled:bg-slate-300 text-white text-xs font-bold rounded-lg flex items-center gap-1 transition-all active:scale-95 shadow-sm">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            <span>Tambah</span>
          </button>
        </div>
      </div>
    `;
  }).join('');

  lucide.createIcons();
}
