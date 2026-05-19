<?php

namespace App\Contracts\Repositories;

use App\Models\Order;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function findForCustomer(int $customerId): Collection;

    public function findByIdForCustomer(int $orderId, int $customerId): ?Order;

    public function findWithItems(int $id): ?Order;
}
