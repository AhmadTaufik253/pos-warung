// ==========================================
// MASTER DATA BARANG (F2)
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
    const statusBadge = isOutOfStock ? 'bg-red-100 text-red-700' : isLow ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-700';

    const unitsDisplay = (item.units || []).map(u => `
      <span class="inline-block bg-slate-100 border border-slate-200 px-1.5 py-0.5 rounded text-[10px] mr-1 mb-1 font-mono">
        ${esc(u.unit_name)}: <b>${formatRp(u.sell_price)}</b>
      </span>
    `).join('');

    return `
      <tr class="hover:bg-slate-50/80 transition-colors">
        <td class="py-3 px-4 font-mono font-bold text-slate-700">${esc(item.barcode || '-')}</td>
        <td class="py-3 px-4 font-semibold text-slate-800">
          ${esc(item.name)}
          <div class="flex items-center gap-1.5 mt-0.5">
            <span class="text-[10px] text-slate-400 font-mono">Satuan Dasar: <b>${esc(item.base_unit)}</b></span>
            ${item.allow_nominal_buy ? '<span class="bg-amber-100 text-amber-800 text-[9px] font-bold px-1 rounded">Beli Nominal (Rp)</span>' : ''}
          </div>
        </td>
        <td class="py-3 px-4">
          <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full text-[10px] font-medium whitespace-nowrap">${esc(item.category ? item.category.name : 'Umum')}</span>
        </td>
        <td class="py-3 px-4 text-right font-mono text-slate-600">
          ${formatRp(item.base_buy_price)} <span class="text-[10px] text-slate-400">/${esc(item.base_unit)}</span>
        </td>
        <td class="py-3 px-4 max-w-xs"><div class="flex flex-wrap">${unitsDisplay}</div></td>
        <td class="py-3 px-4 text-center font-mono font-bold text-slate-800">${formatStock(item.current_stock)} ${esc(item.base_unit)}</td>
        <td class="py-3 px-4 text-center">
          <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${statusBadge}">${isOutOfStock ? 'Habis' : isLow ? 'Menipis' : 'Aman'}</span>
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

// ---------- Form produk & multi-satuan ----------
function addUnitRow(name = '', multiplier = 1, price = '') {
  const container = document.getElementById('unit-rows-list');
  const row = document.createElement('div');
  row.className = 'unit-entry-row flex items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-200';
  row.innerHTML = `
    <div class="flex-1">
      <input type="text" placeholder="Nama Satuan (cth: 1/2 Kg)" value="${esc(name)}" required class="u-name w-full px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs font-semibold outline-none focus:border-brand-500">
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

  document.getElementById('unit-rows-list').innerHTML = '';
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

  document.getElementById('unit-rows-list').innerHTML = '';
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
  const units = [];

  document.querySelectorAll('.unit-entry-row').forEach(r => {
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
    const res = await fetch(id ? `${API_BASE}/products/${id}` : `${API_BASE}/products`, {
      method: id ? 'PUT' : 'POST',
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
  if (!confirm(`Yakin ingin menghapus produk "${p.name}" dari sistem?`)) return;

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

// ---------- Restock ----------
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
    await fetchProducts();
    showToast('Stok berhasil diperbarui di database!');
  } catch (err) {
    showToast('Gagal: ' + err.message);
  }
}

// ---------- Ekspor ----------
function exportMasterCSV() {
  const q = v => `"${String(v ?? '').replace(/"/g, '""')}"`;
  let csv = 'Barcode,Nama Barang,Kategori,Satuan Dasar,Modal (Rp),Stok Fisik,Beli Nominal\n';
  products.forEach(p => {
    csv += [
      q(p.barcode), q(p.name), q(p.category ? p.category.name : 'Umum'), q(p.base_unit),
      p.base_buy_price, p.current_stock, q(p.allow_nominal_buy ? 'Ya' : 'Tidak')
    ].join(',') + '\n';
  });
  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = `Master_Sembako_${Date.now()}.csv`;
  link.click();
}
