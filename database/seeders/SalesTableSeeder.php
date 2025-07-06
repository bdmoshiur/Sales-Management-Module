<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::pluck('id')->toArray();
        $products = Product::pluck('id')->toArray();

        if (empty($users)) {
            $users = [User::factory()->create()->id];
        }
        if (empty($products)) {
            $products = [Product::factory()->create()->id];
        }

       
        for ($i = 0; $i < 50; $i++) {
            $sale = Sale::create([
                'user_id' => $users[array_rand($users)],
                'sale_date' => now()->subDays(rand(1, 365))->format('Y-m-d'),
                'total_amount' => 0, 
            ]);

            $totalAmount = 0;
            
            $itemCount = rand(2, 5);
            for ($j = 0; $j < $itemCount; $j++) {
                $quantity = rand(1, 10);
                $unitPrice = rand(100, 1000) + (rand(0, 99) / 100);
                $discount = rand(0, 50) + (rand(0, 99) / 100);
                $itemTotal = ($quantity * $unitPrice) - $discount;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $products[array_rand($products)],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'total_price' => $itemTotal,
                ]);

                $totalAmount += $itemTotal;
            }

            $sale->update(['total_amount' => $totalAmount]);
        }
    }
}
