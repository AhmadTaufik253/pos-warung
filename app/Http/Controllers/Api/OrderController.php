<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DebtRecord;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // GET /api/v1/orders (Riwayat penjualan)
    public function index(): JsonResponse
    {
        $orders = Order::with(['customer', 'items'])
            ->where('status', 'completed')
            ->orderBy('transaction_date', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    // GET /api/v1/orders/{id} (Detail untuk cetak nota thermal)
    public function show(int $id): JsonResponse
    {
        $order = Order::with(['customer', 'items.product'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    // POST /api/v1/orders/checkout (Checkout Transaksi Kasir)
    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,qris,transfer,debt',
            'discount_amount' => 'nullable|numeric|min:0',
            'cash_tendered' => 'nullable|numeric|min:0',
            'customer_name' => 'required_if:payment_method,debt|nullable|string|max:120',
            'customer_id' => 'nullable|exists:customers,id',
            'cashier_note' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_unit_id' => 'nullable|exists:product_units,id',
            'items.*.unit_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|gt:0',
            'items.*.conversion_factor' => 'required|numeric|gt:0',
            'items.*.selling_price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        $order = DB::transaction(function () use ($validated) {
            // 1. Kumpulkan semua ID produk unik & urutkan secara deterministik untuk mencegah DEADLOCK
            $productIds = collect($validated['items'])
                ->pluck('product_id')
                ->unique()
                ->sort()
                ->values()
                ->all();

            // Kunci semua baris produk sekaligus dalam 1 query batch
            $products = Product::whereIn('id', $productIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // 2. Ambil data satuan resmi untuk verifikasi
            $unitIds = collect($validated['items'])
                ->pluck('product_unit_id')
                ->filter()
                ->unique()
                ->values()
                ->all();

            $productUnits = !empty($unitIds)
                ? ProductUnit::whereIn('id', $unitIds)->get()->keyBy('id')
                : collect();

            $totalAmount = 0;
            $totalCogs = 0;
            $orderItemsPayload = [];
            $requiredDeductions = [];

            // 3. Verifikasi item, kalkulasi HPP, dan kumpulkan akumulasi pengurangan stok per produk
            foreach ($validated['items'] as $item) {
                $productId = $item['product_id'];
                $product = $products[$productId] ?? null;

                if (!$product) {
                    abort(404, "Produk dengan ID {$productId} tidak ditemukan.");
                }

                // Gunakan faktor konversi terverifikasi jika ada relasi satuan
                $unit = !empty($item['product_unit_id']) ? ($productUnits[$item['product_unit_id']] ?? null) : null;
                $conversionFactor = $unit ? (float) $unit->conversion_factor : (float) $item['conversion_factor'];
                $quantity = (float) $item['quantity'];

                // Hitung potongan stok pada satuan dasar
                $stockToDeduct = $quantity * $conversionFactor;
                $requiredDeductions[$productId] = ($requiredDeductions[$productId] ?? 0) + $stockToDeduct;

                // Verifikasi kalkulasi subtotal (mencegah manipulasi angka desimal / nilai minus)
                $sellingPrice = (float) $item['selling_price'];
                $calculatedSubtotal = round($quantity * $sellingPrice, 2);
                $subtotal = (abs($calculatedSubtotal - (float) $item['subtotal']) > 1)
                    ? $calculatedSubtotal
                    : (float) $item['subtotal'];

                // Hitung HPP riil item bersangkutan
                $costPricePerUnit = (float) $product->base_buy_price * $conversionFactor;
                $itemCogs = $costPricePerUnit * $quantity;

                $totalAmount += $subtotal;
                $totalCogs += $itemCogs;

                $orderItemsPayload[] = [
                    'product_id' => $product->id,
                    'product_unit_id' => $unit ? $unit->id : null,
                    'unit_name' => $item['unit_name'],
                    'quantity' => $quantity,
                    'conversion_factor' => $conversionFactor,
                    'stock_deducted' => $stockToDeduct,
                    'cost_price' => $costPricePerUnit,
                    'selling_price' => $sellingPrice,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // 4. Validasi kecukupan stok total per produk & potong stok atomik
            foreach ($requiredDeductions as $productId => $totalDeduct) {
                $product = $products[$productId];
                if ($product->current_stock < $totalDeduct) {
                    abort(422, "Stok tidak mencukupi untuk item: {$product->name}. Sisa: {$product->current_stock} {$product->base_unit}, dibutuhkan: {$totalDeduct}");
                }
                $product->decrement('current_stock', $totalDeduct);
            }

            $discount = (float) ($validated['discount_amount'] ?? 0);
            $grandTotal = max(0, $totalAmount - $discount);
            $cashTendered = (float) ($validated['cash_tendered'] ?? $grandTotal);
            $changeAmount = ($validated['payment_method'] === 'cash') ? max(0, $cashTendered - $grandTotal) : 0;

            // 5. Handle Customer Kasbon dengan proteksi race condition
            $customerId = $validated['customer_id'] ?? null;
            if ($validated['payment_method'] === 'debt') {
                if (!$customerId) {
                    $customerName = trim($validated['customer_name']);
                    $customer = Customer::where('name', $customerName)->lockForUpdate()->first();
                    if (!$customer) {
                        $customer = Customer::create([
                            'name' => $customerName,
                            'current_debt_balance' => 0,
                        ]);
                    }
                    $customerId = $customer->id;
                } else {
                    Customer::where('id', $customerId)->lockForUpdate()->firstOrFail();
                }
            }

            // 6. Simpan Header Order (Invoice number unik dengan timestamp presisi tinggi)
            $order = Order::create([
                'invoice_number' => 'INV-' . date('YmdHis') . '-' . strtoupper(Str::random(4)),
                'customer_id' => $customerId,
                'total_amount' => $totalAmount,
                'discount_amount' => $discount,
                'grand_total' => $grandTotal,
                'payment_method' => $validated['payment_method'],
                'cash_tendered' => $cashTendered,
                'change_amount' => $changeAmount,
                'status' => 'completed',
                'total_cogs' => $totalCogs,
                'cashier_note' => $validated['cashier_note'] ?? null,
                'transaction_date' => now(),
            ]);

            // 7. Simpan Relasi Order Items secara massal (Bulk Insert)
            foreach ($orderItemsPayload as &$itemPayload) {
                $itemPayload['order_id'] = $order->id;
            }
            OrderItem::insert($orderItemsPayload);

            // 8. Jika Pembayaran Kasbon, Catat ke Buku Hutang
            if ($validated['payment_method'] === 'debt') {
                DebtRecord::create([
                    'customer_id' => $customerId,
                    'order_id' => $order->id,
                    'original_amount' => $grandTotal,
                    'remaining_balance' => $grandTotal,
                    'status' => 'unpaid',
                ]);

                Customer::where('id', $customerId)->increment('current_debt_balance', $grandTotal);
            }

            return $order->load(['customer', 'items']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diproses',
            'data' => $order,
        ], 201);
    }

    // POST /api/v1/orders/hold (Tahan Transaksi Antrean)
    public function holdCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hold_label' => 'required|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_unit_id' => 'nullable|exists:product_units,id',
            'items.*.unit_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|gt:0',
            'items.*.conversion_factor' => 'required|numeric|gt:0',
            'items.*.selling_price' => 'required|numeric',
            'items.*.subtotal' => 'required|numeric',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $subtotal = collect($validated['items'])->sum('subtotal');

            // Disimpan dengan status hold (STOK BELUM DIPOTONG)
            $order = Order::create([
                'invoice_number' => 'HOLD-' . date('YmdHis') . '-' . strtoupper(Str::random(3)),
                'total_amount' => $subtotal,
                'discount_amount' => 0,
                'grand_total' => $subtotal,
                'payment_method' => 'cash',
                'status' => 'hold',
                'hold_label' => $validated['hold_label'],
                'transaction_date' => now(),
            ]);

            // Optimasi N+1: Ambil produk sekaligus dalam 1 query
            $productIds = collect($validated['items'])->pluck('product_id')->unique()->values()->all();
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            $orderItemsPayload = [];
            foreach ($validated['items'] as $item) {
                $product = $products[$item['product_id']] ?? null;
                $costPrice = $product ? (float) $product->base_buy_price * (float) $item['conversion_factor'] : 0;
                $stockDeducted = (float) $item['quantity'] * (float) $item['conversion_factor'];

                $orderItemsPayload[] = [
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_unit_id' => $item['product_unit_id'] ?? null,
                    'unit_name' => $item['unit_name'],
                    'quantity' => $item['quantity'],
                    'conversion_factor' => $item['conversion_factor'],
                    'stock_deducted' => $stockDeducted,
                    'cost_price' => $costPrice,
                    'selling_price' => $item['selling_price'],
                    'subtotal' => $item['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk Insert item hold
            OrderItem::insert($orderItemsPayload);

            return $order->load('items');
        });

        return response()->json([
            'success' => true,
            'message' => 'Antrean berhasil diparkir',
            'data' => $order,
        ]);
    }

    // GET /api/v1/orders/hold/list (Ambil daftar antrean yang ditahan)
    public function listHold(): JsonResponse
    {
        $holds = Order::with(['items.product'])
            ->where('status', 'hold')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $holds,
        ]);
    }

    // DELETE /api/v1/orders/hold/{id} (Hapus antrean saat dipanggil kembali atau dibatalkan)
    public function destroyHold(int $id): JsonResponse
    {
        $hold = Order::where('status', 'hold')->findOrFail($id);
        $hold->delete();

        return response()->json([
            'success' => true,
            'message' => 'Antrean telah dibersihkan',
        ]);
    }
}
