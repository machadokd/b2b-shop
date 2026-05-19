<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function findAll(): Collection
    {
        return Order::with(['customer', 'address'])->get();
    }

    public function findById(int $id): ?Model
    {
        return Order::find($id);
    }

    public function create(array $data): Model
    {
        return Order::create($data);
    }

    public function update(int $id, array $data): Model
    {
        $order = Order::findOrFail($id);
        $order->update($data);

        return $order;
    }

    public function delete(int $id): bool
    {
        return Order::destroy($id) > 0;
    }

    public function findForCustomer(int $customerId): Collection
    {
        return Order::with(['address', 'items.product'])
            ->where('customer_id', $customerId)
            ->latest()
            ->get();
    }

    public function findByIdForCustomer(int $orderId, int $customerId): ?Order
    {
        return Order::with(['address', 'items.product'])
            ->where('id', $orderId)
            ->where('customer_id', $customerId)
            ->first();
    }

    public function findWithItems(int $id): ?Order
    {
        return Order::with(['customer', 'address', 'items.product'])->find($id);
    }
}
