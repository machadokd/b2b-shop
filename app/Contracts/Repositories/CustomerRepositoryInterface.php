<?php

namespace App\Contracts\Repositories;

use App\Models\Customer;

interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    public function findWithUser(int $id): ?Customer;

    public function findByUserId(int $userId): ?Customer;
}
