<?php

namespace App\Contracts\Repositories;

use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findActive(): Collection;

    public function findByCategory(int $categoryId): Collection;

    public function findBySku(string $sku): ?Product;

    public function decrementStock(int $productId, int $quantity): void;
}
