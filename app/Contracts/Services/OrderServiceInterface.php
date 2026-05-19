<?php

namespace App\Contracts\Services;

use App\DTOs\StoreOrderDTO;
use App\DTOs\UpdateOrderStatusDTO;
use App\Models\Order;

interface OrderServiceInterface
{
    public function placeOrder(StoreOrderDTO $dto): Order;

    public function updateStatus(Order $order, UpdateOrderStatusDTO $dto): Order;

    public function getOrderForCustomer(int $orderId, int $customerId): Order;
}
