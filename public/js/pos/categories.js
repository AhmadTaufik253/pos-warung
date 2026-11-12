// ==========================================
// KATEGORI (CRUD)
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
  const selectedInForm = catSelect.value;

  let pills = `
    <button onclick="setCategoryFilter('all')" class="cat-pill px-3 py-1.5 rounded-lg font-bold ${currentCategory === 'all' ? 'bg-brand-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'} shadow-xs whitespace-nowrap">
      Semua Sembako
    </button>`;
  let options = `<option value="">Umum (Tanpa Kategori)</option>`;

  categories.forEach(cat => {
    pills += `
      <button onclick="setCategoryFilter('${cat.id}')" class="cat-pill px-3 py-1.5 rounded-lg font-semibold ${currentCategory == cat.id ? 'bg-brand-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'} whitespace-nowrap">
        ${esc(cat.name)}
      </button>`;
    options += `<option value="${cat.id}">${esc(cat.name)}</option>`;
  });

  pillsContainer.innerHTML = pills;
  catSelect.innerHTML = options;
  catSelect.value = selectedInForm;
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
    if (!document.getElementById('modal-manage-categories').classList.contains('hidden')) {
      manageCategoriesModal();
    }
  } catch (err) {
    showToast('Gagal: ' + err.message);
  }
}

function manageCategoriesModal() {
  const listContainer = document.getElementById('category-manage-list');

  if (categories.length === 0) {
    listContainer.innerHTML = `<p class="text-slate-400 text-center py-4">Belum ada kategori.</p>`;
  } else {
    listContainer.innerHTML = categories.map(cat => `
      <div class="flex items-center justify-between p-2.5 bg-slate-50 border rounded-xl">
        <div>
          <span class="font-bold text-xs text-slate-800">${esc(cat.name)}</span>
          <span class="text-[10px] text-slate-400 ml-1">(${cat.products_count || 0} produk)</span>
        </div>
        <div class="flex items-center gap-1.5">
          <button onclick="editCategoryName(${cat.id})" class="p-1 text-blue-600 hover:bg-blue-50 rounded" title="Ubah Nama">
            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
          </button>
          <button onclick="deleteCategoryAction(${cat.id})" class="p-1 text-red-600 hover:bg-red-50 rounded" title="Hapus Kategori">
            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
          </button>
        </div>
      </div>
    `).join('');
  }
  document.getElementById('modal-manage-categories').classList.remove('hidden');
  lucide.createIcons();
}

function closeCategoriesModal() {
  document.getElementById('modal-manage-categories').classList.add('hidden');
}

async function editCategoryName(id) {
  const cat = categories.find(c => c.id === id);
  if (!cat) return;

  const newName = prompt('Ubah Nama Kategori:', cat.name);
  if (!newName || !newName.trim() || newName.trim() === cat.name) return;

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
