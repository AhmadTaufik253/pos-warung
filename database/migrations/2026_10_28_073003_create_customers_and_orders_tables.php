<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('phone', 30)->nullable();
            $table->text('address')->nullable();
            $table->decimal('current_debt_balance', 14, 2)->default(0);
            $table->timestamps();

            $table->index('name');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('total_amount', 14, 2);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('grand_total', 14, 2);
            $table->enum('payment_method', ['cash', 'qris', 'transfer', 'debt'])->default('cash');
            $table->decimal('cash_tendered', 14, 2)->default(0);
            $table->decimal('change_amount', 14, 2)->default(0);
            $table->enum('status', ['completed', 'hold', 'cancelled'])->default('completed');
            $table->string('hold_label', 100)->nullable();
            $table->decimal('total_cogs', 14, 2)->default(0);
            $table->string('cashier_note', 255)->nullable();
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();

            $table->index('transaction_date');
            $table->index('status');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('product_unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('unit_name', 50);
            $table->decimal('quantity', 10, 3);
            $table->decimal('conversion_factor', 10, 4);
            $table->decimal('stock_deducted', 12, 3); // quantity * conversion_factor
            $table->decimal('cost_price', 14, 2);
            $table->decimal('selling_price', 14, 2);
            $table->decimal('subtotal', 14, 2);
            $table->timestamps();

            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('customers');
    }
};
