<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Index komposit pada tabel orders untuk mempercepat riwayat transaksi & antrean hold
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'transaction_date'], 'orders_status_trans_date_idx');
            $table->index(['status', 'created_at'], 'orders_status_created_at_idx');
        });

        // 2. Index komposit pada debt_records untuk sorting status kasbon & waktu
        Schema::table('debt_records', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'debts_status_created_at_idx');
        });

        // 3. Index komposit pada products untuk filter barang aktif dan kategori
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'category_id'], 'products_active_category_idx');
        });

        // 4. Unique index pada categories.name
        Schema::table('categories', function (Blueprint $table) {
            $table->unique('name', 'categories_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique('categories_name_unique');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_category_idx');
        });

        Schema::table('debt_records', function (Blueprint $table) {
            $table->dropIndex('debts_status_created_at_idx');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_created_at_idx');
            $table->dropIndex('orders_status_trans_date_idx');
        });
    }
};
