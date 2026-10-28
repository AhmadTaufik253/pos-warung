<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // GET /api/v1/categories (Ambil semua kategori beserta jumlah produknya)
    public function index(): JsonResponse
    {
        $categories = Category::withCount('products')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    // POST /api/v1/categories (Tambah kategori baru)
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah terdaftar.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
        ]);

        $category = Category::create([
            'name' => trim($validated['name']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category->loadCount('products'),
        ], 201);
    }

    // GET /api/v1/categories/{id} (Detail 1 kategori)
    public function show(int $id): JsonResponse
    {
        $category = Category::withCount('products')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }

    // PUT/PATCH /api/v1/categories/{id} (Ubah nama kategori)
    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $id,
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan oleh kategori lain.',
        ]);

        $category->update([
            'name' => trim($validated['name']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui',
            'data' => $category->loadCount('products'),
        ]);
    }

    // DELETE /api/v1/categories/{id} (Hapus kategori dengan proteksi relasi produk)
    public function destroy(int $id): JsonResponse
    {
        $category = Category::withCount('products')->findOrFail($id);

        // Proteksi: cegah hapus jika masih ada produk di dalam kategori ini
        if ($category->products_count > 0) {
            return response()->json([
                'success' => false,
                'message' => "Kategori tidak dapat dihapus karena masih digunakan oleh {$category->products_count} produk. Pindahkan atau hapus produk terlebih dahulu.",
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus',
        ]);
    }
}
