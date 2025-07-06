<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'name' => 'Laptop',
            'description' => 'High performance laptop',
            'price' => 1200.00,
        ]);
        
        Product::create([
            'name' => 'Smartphone',
            'description' => 'Latest model smartphone',
            'price' => 800.00,
        ]);
        
        Product::create([
            'name' => 'Headphones',
            'description' => 'Noise cancelling headphones',
            'price' => 150.00,
        ]);
        
        // Add more products as needed
    }
}
