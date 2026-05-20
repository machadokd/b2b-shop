<?php

namespace App\Providers;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\OrderServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Repositories\EloquentCustomerRepository;
use App\Repositories\EloquentOrderRepository;
use App\Repositories\EloquentProductRepository;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderRepositoryInterface::class, EloquentOrderRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, EloquentCustomerRepository::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
    }
}
