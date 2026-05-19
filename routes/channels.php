<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('orders.{orderId}', function (User $user, int $orderId) {
    /** @var Order|null $order */
    $order = Order::find($orderId);

    if (! $order || ! $user->customer) {
        return false;
    }

    return $order->customer_id === $user->customer->id;
});
