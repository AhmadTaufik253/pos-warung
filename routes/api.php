<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerDebtController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // 1. CRUD KATEGORI
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // 2. MASTER PRODUK & STOK KILAT
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::patch('/products/{id}/stock', [ProductController::class, 'adjustStock']);

    // 3. TRANSAKSI KASIR & ANTREAN
    Route::post('/orders/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders/hold', [OrderController::class, 'holdCart']);
    Route::get('/orders/hold/list', [OrderController::class, 'listHold']);
    Route::delete('/orders/hold/{id}', [OrderController::class, 'destroyHold']);

    // 4. BUKU KASBON & PELUNASAN
    Route::get('/customers', [CustomerDebtController::class, 'indexCustomers']);
    Route::get('/debts', [CustomerDebtController::class, 'indexDebts']);
    Route::post('/debts/{id}/pay', [CustomerDebtController::class, 'payDebt']);
});
