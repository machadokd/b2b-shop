<?php

namespace App\DTOs;

readonly class StoreOrderItemDTO
{
    public function __construct(
        public int $product_id,
        public int $quantity,
        public float $unit_price,
    ) {}
}
