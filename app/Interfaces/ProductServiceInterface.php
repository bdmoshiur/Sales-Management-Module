<?php

namespace App\Interfaces;

interface ProductServiceInterface
{
    public function getProductPrice(int $productId);
    public function addNoteToProduct(int $productId, string $content);
}