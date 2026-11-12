// Escape teks dari server sebelum dimasukkan ke innerHTML (cegah XSS)
function esc(s) {
  return String(s ?? '').replace(/[&<>"']/g, c => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
  }[c]));
}

function formatRp(val) {
  return 'Rp ' + Math.round(Number(val) || 0).toLocaleString('id-ID');
}

function formatStock(val) {
  if (val === undefined || val === null) return '0';
  return Number(val).toLocaleString('id-ID', { maximumFractionDigits: 3 });
}

function cartSubtotal() {
  return cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
}

function cartGrandTotal() {
  return Math.max(0, cartSubtotal() - discountAmount);
}

function resetCartState() {
  cart = [];
  discountAmount = 0;
  document.getElementById('input-discount-val').value = '';
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

let toastTimer = null;
function showToast(text) {
  const toast = document.getElementById('toast');
  document.getElementById('toast-text').textContent = text;
  toast.classList.remove('translate-y-20', 'opacity-0');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => {
    toast.classList.add('translate-y-20', 'opacity-0');
  }, 2500);
}

function startClock() {
  const clockEl = document.getElementById('live-clock');
  const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
  function update() {
    clockEl.textContent = new Date().toLocaleDateString('id-ID', options).replace('.', ':');
  }
  update();
  setInterval(update, 1000);
}
