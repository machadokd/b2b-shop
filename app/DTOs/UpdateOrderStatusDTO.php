<?php

namespace App\DTOs;

use App\Enums\OrderStatus;

readonly class UpdateOrderStatusDTO
{
    public function __construct(
        public OrderStatus $status,
    ) {}
}
