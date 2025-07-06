<?php

namespace App\Helpers;

class SaleHelper
{
    public static function calculateSaleTotal(array $itemsData): float
    {
        $total = 0;
        
        foreach ($itemsData as $item) {
            $quantity = $item['quantity'];
            $price = $item['unit_price'];
            $discount = $item['discount'] ?? 0;
            
            $subtotal = ($quantity * $price) - $discount;
            $total += $subtotal;
        }
        
        return $total;
    }
}