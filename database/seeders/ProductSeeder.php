<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            ['name' => 'Laptop', 'price' => 1200.00, 'stock_quantity' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Headphones', 'price' => 150.00, 'stock_quantity' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mouse', 'price' => 40.00, 'stock_quantity' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Desktop PC', 'price' => 1300.00, 'stock_quantity' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Monitor', 'price' => 350.00, 'stock_quantity' => 18, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mechanical Keyboard', 'price' => 150.00, 'stock_quantity' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'External SSD', 'price' => 150.00, 'stock_quantity' => 22, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Graphics Card (GPU)', 'price' => 800.00, 'stock_quantity' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'RAM', 'price' => 120.00, 'stock_quantity' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'USB Flash Drive', 'price' => 40.00, 'stock_quantity' => 30, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
