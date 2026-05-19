<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->customer?->getKey() === $order->customer_id;
    }

    public function updateStatus(User $user): bool
    {
        return $user->isAdmin();
    }
}
