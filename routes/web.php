<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});



Route::resource('sales', SaleController::class);
Route::get('trash/sales', [SaleController::class, 'trash'])->name('trash.sales');
Route::post('sales/{id}/restore', [SaleController::class, 'restore'])->name('sales.restore');
Route::post('sales/{id}/notes', [SaleController::class, 'addNote'])->name('sales.addNote');

// Product AJAX routes
Route::resource('products', ProductController::class);
Route::get('products/{id}/price', [ProductController::class, 'getPrice'])->name('products.price');
Route::post('products/{id}/notes', [ProductController::class, 'addNote'])->name('products.addNote');