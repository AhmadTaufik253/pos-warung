// ==========================================
// STRUK THERMAL
// ==========================================
function showReceiptModal(order) {
  document.getElementById('rcpt-invoice').textContent = order.invoice_number;
  document.getElementById('rcpt-date').textContent = new Date(order.transaction_date).toLocaleString('id-ID');
  document.getElementById('rcpt-customer').textContent = order.customer ? order.customer.name : 'Umum';

  document.getElementById('rcpt-items').innerHTML = (order.items || []).map(i => `
    <div class="space-y-0.5">
      <div class="flex justify-between font-bold">
        <span class="truncate max-w-[180px]">${esc(i.product ? i.product.name : i.unit_name)}</span>
        <span>${formatRp(i.subtotal)}</span>
      </div>
      <div class="flex justify-between text-slate-500 text-[10px]">
        <span>${i.quantity} x ${esc(i.unit_name)}</span>
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
