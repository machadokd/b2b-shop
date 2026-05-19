<?php

namespace App\Providers;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\OrderServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Events\OrderPlaced;
use App\Events\OrderStatusChanged;
use App\Listeners\AuditOrderEventListener;
use App\Listeners\NotifyOrderStatusChangedListener;
use App\Listeners\SendOrderConfirmationEmailListener;
use App\Listeners\UpdateProductStockListener;
use App\Repositories\EloquentCustomerRepository;
use App\Repositories\EloquentOrderRepository;
use App\Repositories\EloquentProductRepository;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Event;
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

    public function boot(): void
    {
        Event::listen(OrderPlaced::class, SendOrderConfirmationEmailListener::class);
        Event::listen(OrderPlaced::class, UpdateProductStockListener::class);
        Event::listen(OrderPlaced::class, [AuditOrderEventListener::class, 'handleOrderPlaced']);
        Event::listen(OrderStatusChanged::class, NotifyOrderStatusChangedListener::class);
        Event::listen(OrderStatusChanged::class, [AuditOrderEventListener::class, 'handleOrderStatusChanged']);
    }
}
