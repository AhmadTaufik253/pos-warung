<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DebtPayment;
use App\Models\DebtRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerDebtController extends Controller
{
    // GET /api/v1/customers
    public function indexCustomers(): JsonResponse
    {
        $customers = Customer::orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    // GET /api/v1/debts (Daftar seluruh catatan kasbon aktif)
    public function indexDebts(): JsonResponse
    {
        $debts = DebtRecord::with(['customer', 'order', 'payments'])
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'total_unpaid' => Customer::sum('current_debt_balance'),
            'data' => $debts,
        ]);
    }

    // POST /api/v1/debts/{id}/pay (Bayar cicilan atau pelunasan kasbon)
    public function payDebt(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|gt:0',
            'payment_method' => 'required|in:cash,qris,transfer',
            'notes' => 'nullable|string|max:255',
        ]);

        $debtRecord = DB::transaction(function () use ($validated, $id) {
            $debt = DebtRecord::where('id', $id)->lockForUpdate()->firstOrFail();

            if ($debt->remaining_balance <= 0) {
                abort(422, 'Kasbon transaksi ini sudah lunas.');
            }

            $payAmount = min((float) $validated['amount'], (float) $debt->remaining_balance);

            // Simpan riwayat setoran/cicilan
            DebtPayment::create([
                'debt_record_id' => $debt->id,
                'payment_amount' => $payAmount,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? 'Cicilan kasbon',
                'paid_at' => now(),
            ]);

            // Update sisa hutang di record dan customer
            $debt->remaining_balance -= $payAmount;
            $debt->status = ($debt->remaining_balance <= 0) ? 'paid' : 'partially_paid';
            $debt->save();

            Customer::where('id', $debt->customer_id)->decrement('current_debt_balance', $payAmount);

            return $debt->load(['customer', 'payments']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran kasbon berhasil dicatat',
            'data' => $debtRecord,
        ]);
    }
}
