<?php

namespace App\Services;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\OrderServiceInterface;
use App\DTOs\StoreOrderDTO;
use App\DTOs\UpdateOrderStatusDTO;
use App\Enums\OrderStatus;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\InvalidOrderStateTransitionException;
use App\Exceptions\OrderNotOwnedByCustomerException;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface $orders,
        private ProductRepositoryInterface $products,
    ) {}

    public function placeOrder(StoreOrderDTO $dto): Order
    {
        foreach ($dto->items as $item) {
            /** @var Product|null $product */
            $product = $this->products->findById($item->product_id);

            if (! $product || $product->stock < $item->quantity) {
                throw new InsufficientStockException(
                    $product ? $product->name : 'Produto',
                    $item->quantity,
                    $product ? $product->stock : 0,
                );
            }
        }

        return DB::transaction(function () use ($dto) {
            $total = collect($dto->items)->sum(fn ($i) => $i->unit_price * $i->quantity);

            /** @var Order $order */
            $order = $this->orders->create([
                'customer_id' => $dto->customer_id,
                'address_id' => $dto->address_id,
                'status' => OrderStatus::Pending,
                'total' => $total,
                'notes' => $dto->notes,
            ]);

            foreach ($dto->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                ]);
            }

            return $order;
        });
    }

    public function updateStatus(Order $order, UpdateOrderStatusDTO $dto): Order
    {
        if (! $order->status->canTransitionTo($dto->status)) {
            throw new InvalidOrderStateTransitionException($order->status, $dto->status);
        }

        $order->update(['status' => $dto->status]);

        return $order->refresh();
    }

    public function getOrderForCustomer(int $orderId, int $customerId): Order
    {
        $order = $this->orders->findByIdForCustomer($orderId, $customerId);

        if (! $order) {
            throw new OrderNotOwnedByCustomerException;
        }

        return $order;
    }
}
