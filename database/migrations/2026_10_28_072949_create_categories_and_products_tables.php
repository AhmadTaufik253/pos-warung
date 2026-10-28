<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('barcode', 64)->nullable()->unique();
            $table->string('name', 150);
            $table->string('base_unit', 30); // kg, liter, pcs, bks
            $table->decimal('base_buy_price', 14, 2)->default(0);
            $table->decimal('base_sell_price', 14, 2)->default(0);
            $table->decimal('current_stock', 12, 3)->default(0); // decimal untuk timbangan
            $table->decimal('min_stock_alert', 12, 3)->default(5);
            $table->boolean('allow_nominal_buy')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
        });

        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('unit_name', 50); // Sak 50kg, 1 Kg, 1/2 Kg, 1/4 Kg
            $table->decimal('conversion_factor', 10, 4); // 50, 1, 0.5, 0.25
            $table->decimal('sell_price', 14, 2);
            $table->boolean('is_base_unit')->default(false);
            $table->timestamps();

            $table->index(['product_id', 'unit_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_units');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
