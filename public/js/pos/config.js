const API_BASE = window.POS.apiBase;
const csrfToken = window.POS.csrf;

function getApiHeaders() {
  return {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': csrfToken,
  };
}
