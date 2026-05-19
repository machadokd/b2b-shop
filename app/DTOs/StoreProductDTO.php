<?php

namespace App\DTOs;

readonly class StoreProductDTO
{
    public function __construct(
        public int $category_id,
        public string $name,
        public string $slug,
        public string $sku,
        public float $price,
        public int $stock,
        public ?string $description = null,
        public ?string $image = null,
        public bool $is_active = true,
    ) {}
}
