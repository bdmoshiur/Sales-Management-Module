<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Interfaces\SaleServiceInterface;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\User;
use App\Models\Product;

class SaleController extends Controller
{
    private $saleService;

    public function __construct(SaleServiceInterface $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index(Request $request)
    {
        $sales = $this->saleService->getFilteredSales($request->all());
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        return view('sales.create', [
            'users' => User::all(),
            'products' => Product::all()
        ]);
    }

    public function store(SaleRequest $request)
    {
        try {
            $sale = $this->saleService->createSale(
                saleData: $request->only(['user_id', 'sale_date']),
                itemsData: $request->items
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Sale created successfully',
                'data' => $sale
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating sale: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $sale = Sale::with(['user', 'items.product', 'notes'])->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    public function edit($id)
    {
        $sale = Sale::with(['items'])->findOrFail($id);
        $users = User::all();
        $products = Product::all();
        
        return view('sales.edit', compact('sale', 'users', 'products'));
    }

    public function update(SaleRequest $request, $id)
    {
        try {
            $sale = $this->saleService->updateSale(
                id: $id,
                saleData: $request->only(['user_id', 'sale_date']),
                itemsData: $request->items
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Sale updated successfully',
                'data' => $sale
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating sale: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->saleService->deleteSale($id);
            return redirect()->route('sales.index')->with('success', 'Sale deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('sales.index')->with('error', 'Error deleting sale: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $sales = Sale::onlyTrashed()->with('user')->paginate(10);
        return view('sales.trash', compact('sales'));
    }

    public function restore($id)
    {
        try {
            $this->saleService->restoreSale($id);
            return redirect()->route('sales.index')->with('success', 'Sale restored successfully');
        } catch (\Exception $e) {
            return redirect()->route('sales.trash')->with('error', 'Error restoring sale: ' . $e->getMessage());
        }
    }

    public function addNote(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);
        
        $note = $this->saleService->addNoteToSale($id, $validated['content']);
        
        return response()->json([
            'success' => true,
            'message' => 'Note added successfully',
            'data' => $note
        ]);
    }
}