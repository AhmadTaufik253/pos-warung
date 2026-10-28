<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // GET /api/v1/products (List produk lengkap dengan varian satuannya)
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'units'])->where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', $search);
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        $products = $query->orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    // POST /api/v1/products (Tambah master barang baru + satuan eceran)
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'barcode' => 'nullable|string|max:64|unique:products,barcode',
            'category_id' => 'nullable|exists:categories,id',
            'base_unit' => 'required|string|max:30',
            'base_buy_price' => 'required|numeric|min:0',
            'base_sell_price' => 'required|numeric|min:0',
            'current_stock' => 'required|numeric|min:0',
            'min_stock_alert' => 'nullable|numeric|min:0',
            'allow_nominal_buy' => 'boolean',
            'units' => 'nullable|array',
            'units.*.unit_name' => 'required_with:units|string|max:50',
            'units.*.conversion_factor' => 'required_with:units|numeric|gt:0',
            'units.*.sell_price' => 'required_with:units|numeric|min:0',
            'units.*.is_base_unit' => 'nullable|boolean',
        ]);

        $product = DB::transaction(function () use ($validated) {
            $product = Product::create([
                'category_id' => $validated['category_id'] ?? null,
                'barcode' => $validated['barcode'] ?? null,
                'name' => $validated['name'],
                'base_unit' => $validated['base_unit'],
                'base_buy_price' => $validated['base_buy_price'],
                'base_sell_price' => $validated['base_sell_price'],
                'current_stock' => $validated['current_stock'],
                'min_stock_alert' => $validated['min_stock_alert'] ?? 5,
                'allow_nominal_buy' => $validated['allow_nominal_buy'] ?? false,
            ]);

            // Jika ada varian satuan khusus, masukkan ke product_units
            if (!empty($validated['units'])) {
                foreach ($validated['units'] as $unit) {
                    $product->units()->create($unit);
                }
            } else {
                // Default buat 1 unit dasar jika kosong
                $product->units()->create([
                    'unit_name' => '1 ' . ucfirst($product->base_unit),
                    'conversion_factor' => 1.0000,
                    'sell_price' => $product->base_sell_price,
                    'is_base_unit' => true,
                ]);
            }

            return $product->load('units');
        });

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product,
        ], 201);
    }

    // PATCH /api/v1/products/{id}/stock (Restock kilat dari kasir)
    public function adjustStock(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'added_stock' => 'required|numeric',
        ]);

        $product = Product::findOrFail($id);
        $product->increment('current_stock', $validated['added_stock']);

        return response()->json([
            'success' => true,
            'message' => 'Stok produk berhasil diperbarui',
            'current_stock' => $product->fresh()->current_stock,
        ]);
    }

    // PUT/PATCH /api/v1/products/{id} (Update Barang & Varian Satuannya)
    public function update(Request $request, int$id): JsonResponse
    {
        $validated =$request->validate([
            'name' => 'required|string|max:150',
            'barcode' => 'nullable|string|max:64|unique:products,barcode,' . $id,
            'category_id' => 'nullable|exists:categories,id',
            'base_unit' => 'required|string|max:30',
            'base_buy_price' => 'required|numeric|min:0',
            'base_sell_price' => 'required|numeric|min:0',
            'current_stock' => 'required|numeric|min:0',
            'min_stock_alert' => 'nullable|numeric|min:0',
            'allow_nominal_buy' => 'boolean',
            'units' => 'nullable|array',
            'units.*.unit_name' => 'required_with:units|string|max:50',
            'units.*.conversion_factor' => 'required_with:units|numeric|gt:0',
            'units.*.sell_price' => 'required_with:units|numeric|min:0',
            'units.*.is_base_unit' => 'nullable|boolean',
        ]);

        $product = DB::transaction(function () use ($validated,$id) {
            $product = Product::findOrFail($id);

            $product->update([
                'name' => $validated['name'],
                'barcode' => $validated['barcode'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
                'base_unit' => $validated['base_unit'],
                'base_buy_price' => $validated['base_buy_price'],
                'base_sell_price' => $validated['base_sell_price'],
                'current_stock' => $validated['current_stock'],
                'min_stock_alert' => $validated['min_stock_alert'] ?? 5, 'allow_nominal_buy' =>$validated['allow_nominal_buy'] ?? false,
            ]);

            // Sinkronisasi ulang varian satuan (hapus yang lama dan insert yang baru)
            $product->units()->delete();

            if (!empty($validated['units'])) {
                foreach ($validated['units'] as$unit) {
                    $product->units()->create($unit);
                }
            } else {
                $product->units()->create([
                    'unit_name' => '1 ' . ucfirst($product->base_unit),
                    'conversion_factor' => 1.0000,
                    'sell_price' => $product->base_sell_price,
                    'is_base_unit' => true,
                ]);
            }

            return $product->load('units');
        });

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diperbarui',
            'data' => $product,
        ]);
    }

    // DELETE /api/v1/products/{id} (Hapus Barang / Soft Delete)
    public function destroy(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        
        // Cek jika produk pernah ada di transaksi
        $hasTransactions =$product->orderItems()->exists();
        if ($hasTransactions) {
            // Jika ada riwayat transaksi, cukup nonaktifkan agar data pembukuan lampau aman
            $product->update(['is_active' => false]);
            return response()->json([
                'success' => true,
                'message' => 'Produk dinonaktifkan karena memiliki riwayat transaksi',
            ]);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari database',
        ]);
    }
}
