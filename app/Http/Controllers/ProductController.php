<?php

namespace App\Http\Controllers;

use App\Interfaces\ProductServiceInterface;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = Product::withCount('notes')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('notes');
        return view('products.show', compact('product'));
    }

    public function getPrice($id)
    {
        try {
            $price = $this->productService->getProductPrice($id);
            
            return response()->json([
                'success' => true,
                'price' => $price
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product price: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addNote(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);
        
        $note = $this->productService->addNoteToProduct($id, $validated['content']);
        
        return response()->json([
            'success' => true,
            'message' => 'Note added successfully',
            'data' => $note
        ]);
    }
}