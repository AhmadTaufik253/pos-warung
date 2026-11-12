// Inisialisasi (dimuat paling akhir)
window.addEventListener('DOMContentLoaded', () => {
  startClock();
  fetchCategories();
  fetchProducts();
  fetchHoldQueue();
  fetchDebts();
  renderCart();
  lucide.createIcons();
  document.getElementById('search-input').addEventListener('input', renderProducts);
});
