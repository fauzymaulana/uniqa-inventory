<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@inventory.test'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Create cashier users
        User::firstOrCreate(
            ['email' => 'cashier1@inventory.test'],
            [
                'name' => 'Kasir 1',
                'password' => bcrypt('password'),
                'role' => 'cashier',
            ]
        );

        User::firstOrCreate(
            ['email' => 'cashier2@inventory.test'],
            [
                'name' => 'Kasir 2',
                'password' => bcrypt('password'),
                'role' => 'cashier',
            ]
        );

        // Create categories
        $categories = [
            ['name' => 'Makanan & Minuman', 'description' => 'Kategori untuk makanan dan minuman'],
            ['name' => 'Elektronik', 'description' => 'Kategori untuk produk elektronik'],
            ['name' => 'Pakaian', 'description' => 'Kategori untuk pakaian dan fashion'],
            ['name' => 'Peralatan Rumah Tangga', 'description' => 'Kategori untuk peralatan rumah tangga'],
            ['name' => 'custom', 'description' => 'Kategori untuk produk dengan harga fleksibel'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        // Create sample products
        $products = [
            // Makanan & Minuman
            [
                'name' => 'Mie Goreng Instant',
                'sku' => 'MGI-001',
                'description' => 'Mie goreng instant berkualitas',
                'price' => 3500,
                'stock' => 100,
                'barcode' => '8991234567890',
                'category_id' => 1,
            ],
            [
                'name' => 'Minyak Goreng 2L',
                'sku' => 'MG-002',
                'description' => 'Minyak goreng premium 2 liter',
                'price' => 28000,
                'stock' => 50,
                'barcode' => '8991234567891',
                'category_id' => 1,
            ],
            [
                'name' => 'Gula Pasir 1kg',
                'sku' => 'GP-003',
                'description' => 'Gula pasir putih 1 kilogram',
                'price' => 12000,
                'stock' => 75,
                'barcode' => '8991234567892',
                'category_id' => 1,
            ],
            [
                'name' => 'Teh Celup 25 Pcs',
                'sku' => 'TC-004',
                'description' => 'Teh celup isi 25 pcs',
                'price' => 15000,
                'stock' => 40,
                'barcode' => '8991234567893',
                'category_id' => 1,
            ],
            // Elektronik
            [
                'name' => 'Lampu LED 10W',
                'sku' => 'LED-005',
                'description' => 'Lampu LED hemat energi 10 watt',
                'price' => 50000,
                'stock' => 30,
                'barcode' => '8991234567894',
                'category_id' => 2,
            ],
            [
                'name' => 'Kabel USB 2 Meter',
                'sku' => 'USB-006',
                'description' => 'Kabel USB data 2 meter',
                'price' => 25000,
                'stock' => 60,
                'barcode' => '8991234567895',
                'category_id' => 2,
            ],
            // Pakaian
            [
                'name' => 'Kaos Polos Putih',
                'sku' => 'KP-007',
                'description' => 'Kaos polos putih 100% cotton',
                'price' => 45000,
                'stock' => 80,
                'barcode' => '8991234567896',
                'category_id' => 3,
            ],
            // Peralatan Rumah Tangga
            [
                'name' => 'Sapu Lidi',
                'sku' => 'SL-008',
                'description' => 'Sapu lidi standar',
                'price' => 20000,
                'stock' => 45,
                'barcode' => '8991234567897',
                'category_id' => 4,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }
    }
}
