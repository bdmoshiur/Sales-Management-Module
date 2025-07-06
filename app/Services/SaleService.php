<?php

namespace App\Services;

use App\Interfaces\SaleServiceInterface;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Note;
use App\Helpers\SaleHelper;
use Illuminate\Support\Facades\DB;

class SaleService implements SaleServiceInterface
{
    public function createSale(array $saleData, array $itemsData)
    {
        return DB::transaction(function () use ($saleData, $itemsData) {
            // Calculate total
            $saleData['total_amount'] = SaleHelper::calculateSaleTotal(itemsData: $itemsData);
            
            // Create sale
            $sale = Sale::create($saleData);
            
            // Create sale items
            foreach ($itemsData as $item) {
                $sale->items()->create($item);
            }
            
            return $sale;
        });
    }

    public function updateSale(int $id, array $saleData, array $itemsData)
    {
        return DB::transaction(function () use ($id, $saleData, $itemsData) {
            $sale = Sale::findOrFail($id);
            
            // Calculate total
            $saleData['total_amount'] = SaleHelper::calculateSaleTotal(itemsData: $itemsData);
            
            // Update sale
            $sale->update($saleData);
            
            // Delete existing items and create new ones
            $sale->items()->delete();
            foreach ($itemsData as $item) {
                $sale->items()->create($item);
            }
            
            return $sale;
        });
    }

    public function deleteSale(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $sale = Sale::findOrFail($id);
            
            // Soft delete all related items first
            $sale->items()->delete();
            
            // Then delete the sale
            return $sale->delete();
        });
    }
    
    public function restoreSale(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $sale = Sale::withTrashed()->findOrFail($id);
            
            // Restore all related items first
            $sale->items()->onlyTrashed()->restore();
            
            // Then restore the sale
            return $sale->restore();
        });
    }

    public function getFilteredSales(array $filters)
    {
        $query = Sale::with(['user', 'items.product']);
        
        if (isset($filters['customer_name'])) {
            $query->whereHas('user', function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['customer_name'] . '%');
            });
        }
        
        if (isset($filters['product_name'])) {
            $query->whereHas('items.product', function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['product_name'] . '%');
            });
        }
        
        if (isset($filters['date_from']) && isset($filters['date_to'])) {
            $query->whereBetween('sale_date', [$filters['date_from'], $filters['date_to']]);
        }
        
        return $query->paginate(10);
    }

    public function addNoteToSale(int $saleId, string $content)
    {
        $sale = Sale::findOrFail($saleId);
        return $sale->notes()->create(['content' => $content]);
    }
}