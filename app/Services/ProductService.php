<?php

namespace App\Services;

use App\Interfaces\ProductServiceInterface;
use App\Models\Product;
use App\Models\Note;

class ProductService implements ProductServiceInterface
{
    public function getProductPrice(int $productId)
    {
        $product = Product::findOrFail($productId);
        return $product->price;
    }

    public function addNoteToProduct(int $productId, string $content)
    {
        $product = Product::findOrFail($productId);
        return $product->notes()->create(['content' => $content]);
    }
}