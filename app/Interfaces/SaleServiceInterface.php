<?php

namespace App\Interfaces;

interface SaleServiceInterface
{
    public function createSale(array $saleData, array $itemsData);
    public function updateSale(int $id, array $saleData, array $itemsData);
    public function deleteSale(int $id);
    public function restoreSale(int $id);
    public function getFilteredSales(array $filters);
    public function addNoteToSale(int $saleId, string $content);
}