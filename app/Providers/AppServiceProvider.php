<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\SaleServiceInterface;
use App\Services\SaleService;
use App\Interfaces\ProductServiceInterface;
use App\Services\ProductService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SaleServiceInterface::class, SaleService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
