<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; // Gunakan Schema bawaan Laravel

class SembakoSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan pengecekan foreign key (kompatibel untuk PostgreSQL & MySQL)
        Schema::disableForeignKeyConstraints();

        ProductUnit::truncate();
        Product::truncate();
        Category::truncate();
        Customer::truncate();

        // Aktifkan kembali
        Schema::enableForeignKeyConstraints();

        // 1. KATEGORI
        $catBeras = Category::create(['name' => 'Beras & Tepung']);
        $catMinyak = Category::create(['name' => 'Minyak & Margarin']);
        $catGula = Category::create(['name' => 'Gula & Bumbu']);
        $catMie = Category::create(['name' => 'Mie & Telur']);
        $catMinuman = Category::create(['name' => 'Minuman']);
        $catSabun = Category::create(['name' => 'Sabun & Kebutuhan Rumah']);

        // 2. PRODUK CURAH / TIMBANGAN
        $gula = Product::create([
            'category_id' => $catGula->id,
            'barcode' => 'GLP-001',
            'name' => 'Gula Pasir Putih Curah',
            'base_unit' => 'kg',
            'base_buy_price' => 15000,
            'base_sell_price' => 17500,
            'current_stock' => 100.000,
            'min_stock_alert' => 15.000,
            'allow_nominal_buy' => true,
            'is_active' => true,
        ]);

        ProductUnit::insert([
            [
                'product_id' => $gula->id,
                'unit_name' => '1 Sak (50kg)',
                'conversion_factor' => 50.0000,
                'sell_price' => 840000,
                'is_base_unit' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $gula->id,
                'unit_name' => '1 Kg',
                'conversion_factor' => 1.0000,
                'sell_price' => 17500,
                'is_base_unit' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $gula->id,
                'unit_name' => '1/2 Kg (500g)',
                'conversion_factor' => 0.5000,
                'sell_price' => 9000,
                'is_base_unit' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $gula->id,
                'unit_name' => '1/4 Kg (250g)',
                'conversion_factor' => 0.2500,
                'sell_price' => 4800,
                'is_base_unit' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $beras = Product::create([
            'category_id' => $catBeras->id,
            'barcode' => 'BRS-001',
            'name' => 'Beras Setra Ramos Premium',
            'base_unit' => 'kg',
            'base_buy_price' => 12800,
            'base_sell_price' => 14500,
            'current_stock' => 150.000,
            'min_stock_alert' => 25.000,
            'allow_nominal_buy' => true,
            'is_active' => true,
        ]);

        ProductUnit::insert([
            [
                'product_id' => $beras->id,
                'unit_name' => '1 Karung (25kg)',
                'conversion_factor' => 25.0000,
                'sell_price' => 350000,
                'is_base_unit' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $beras->id,
                'unit_name' => '1 Karung (5kg)',
                'conversion_factor' => 5.0000,
                'sell_price' => 72000,
                'is_base_unit' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $beras->id,
                'unit_name' => '1 Liter (0.8kg)',
                'conversion_factor' => 0.8000,
                'sell_price' => 12000,
                'is_base_unit' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $beras->id,
                'unit_name' => '1 Kg',
                'conversion_factor' => 1.0000,
                'sell_price' => 14500,
                'is_base_unit' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $minyak = Product::create([
            'category_id' => $catMinyak->id,
            'barcode' => 'MYK-001',
            'name' => 'Minyak Goreng Curah',
            'base_unit' => 'liter',
            'base_buy_price' => 14000,
            'base_sell_price' => 16000,
            'current_stock' => 60.000,
            'min_stock_alert' => 10.000,
            'allow_nominal_buy' => true,
            'is_active' => true,
        ]);

        ProductUnit::insert([
            [
                'product_id' => $minyak->id,
                'unit_name' => '1 Jerigen (18 Liter)',
                'conversion_factor' => 18.0000,
                'sell_price' => 275000,
                'is_base_unit' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $minyak->id,
                'unit_name' => '1 Liter',
                'conversion_factor' => 1.0000,
                'sell_price' => 16000,
                'is_base_unit' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $minyak->id,
                'unit_name' => '1/2 Liter',
                'conversion_factor' => 0.5000,
                'sell_price' => 8500,
                'is_base_unit' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $telur = Product::create([
            'category_id' => $catMie->id,
            'barcode' => 'TLR-001',
            'name' => 'Telur Ayam Negeri',
            'base_unit' => 'kg',
            'base_buy_price' => 26000,
            'base_sell_price' => 29000,
            'current_stock' => 30.000,
            'min_stock_alert' => 5.000,
            'allow_nominal_buy' => true,
            'is_active' => true,
        ]);

        ProductUnit::insert([
            [
                'product_id' => $telur->id,
                'unit_name' => '1 Peti (10kg)',
                'conversion_factor' => 10.0000,
                'sell_price' => 280000,
                'is_base_unit' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $telur->id,
                'unit_name' => '1 Kg',
                'conversion_factor' => 1.0000,
                'sell_price' => 29000,
                'is_base_unit' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $telur->id,
                'unit_name' => '1/2 Kg',
                'conversion_factor' => 0.5000,
                'sell_price' => 15000,
                'is_base_unit' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $telur->id,
                'unit_name' => '1 Butir (~0.063kg)',
                'conversion_factor' => 0.0625,
                'sell_price' => 2000,
                'is_base_unit' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. PRODUK PABRIKAN
        $indomie = Product::create([
            'category_id' => $catMie->id,
            'barcode' => '089686010118',
            'name' => 'Indomie Goreng Spesial 85g',
            'base_unit' => 'bungkus',
            'base_buy_price' => 2800,
            'base_sell_price' => 3500,
            'current_stock' => 240.000,
            'min_stock_alert' => 40.000,
            'allow_nominal_buy' => false,
            'is_active' => true,
        ]);

        ProductUnit::insert([
            [
                'product_id' => $indomie->id,
                'unit_name' => '1 Dus (40 Bks)',
                'conversion_factor' => 40.0000,
                'sell_price' => 132000,
                'is_base_unit' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $indomie->id,
                'unit_name' => '1 Bungkus',
                'conversion_factor' => 1.0000,
                'sell_price' => 3500,
                'is_base_unit' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $kapalApi = Product::create([
            'category_id' => $catMinuman->id,
            'barcode' => '8991002101031',
            'name' => 'Kopi Kapal Api Spesial Mix 24g',
            'base_unit' => 'sachet',
            'base_buy_price' => 1300,
            'base_sell_price' => 2000,
            'current_stock' => 150.000,
            'min_stock_alert' => 30.000,
            'allow_nominal_buy' => false,
            'is_active' => true,
        ]);

        ProductUnit::insert([
            [
                'product_id' => $kapalApi->id,
                'unit_name' => '1 Renteng (10 Sachet)',
                'conversion_factor' => 10.0000,
                'sell_price' => 17000,
                'is_base_unit' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $kapalApi->id,
                'unit_name' => '1 Sachet',
                'conversion_factor' => 1.0000,
                'sell_price' => 2000,
                'is_base_unit' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $sunlight = Product::create([
            'category_id' => $catSabun->id,
            'barcode' => '8999999042618',
            'name' => 'Sunlight Pencuci Piring Jeruk Nipis 650ml',
            'base_unit' => 'pouch',
            'base_buy_price' => 12500,
            'base_sell_price' => 15000,
            'current_stock' => 36.000,
            'min_stock_alert' => 6.000,
            'allow_nominal_buy' => false,
            'is_active' => true,
        ]);

        ProductUnit::create([
            'product_id' => $sunlight->id,
            'unit_name' => '1 Pouch',
            'conversion_factor' => 1.0000,
            'sell_price' => 15000,
            'is_base_unit' => true,
        ]);

        // 4. DATA PELANGGAN
        Customer::insert([
            [
                'name' => 'Ibu Rina (Rumah Ujung Blok B)',
                'phone' => '081234567890',
                'address' => 'Jl. Mawar No. 12 RT 03/04',
                'current_debt_balance' => 45000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pak Joko (Bengkel Depan)',
                'phone' => '085678901234',
                'address' => 'Samping Pos Ronda RT 01',
                'current_debt_balance' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mbak Sri (Warung Kopi)',
                'phone' => '087890123456',
                'address' => 'Kontrakan Pintu Merah No. 4',
                'current_debt_balance' => 120000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}