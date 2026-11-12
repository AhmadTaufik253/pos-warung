// ==========================================
// NAVIGASI TAB & KEYBOARD SHORTCUTS
// ==========================================
function switchTab(tabId) {
  ['pos', 'master', 'history', 'kasbon'].forEach(t => {
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

document.addEventListener('keydown', function (e) {
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
    if (posTabVisible && cart.length > 0) openCheckoutModal();
  } else if (e.key === 'Escape') {
    closeReceiptModal();
    closeProductModal();
    closeRestockModal();
    closeNominalModal();
    closeHoldConfirmModal();
    closeHoldQueueModal();
    closeCheckoutModal();
    closePayKasbonModal();
    closeCategoriesModal();
    const searchInput = document.getElementById('search-input');
    if (searchInput && document.activeElement === searchInput) {
      searchInput.value = '';
      renderProducts();
    }
  }
});
