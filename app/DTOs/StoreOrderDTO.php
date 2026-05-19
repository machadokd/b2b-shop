<?php

namespace App\DTOs;

readonly class StoreOrderDTO
{
    /**
     * @param StoreOrderItemDTO[] $items
     */
    public function __construct(
        public int $customer_id,
        public int $address_id,
        public array $items,
        public ?string $notes = null,
    ) {}
}
