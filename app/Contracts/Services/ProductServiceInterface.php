<?php

namespace App\Contracts\Services;

use App\DTOs\StoreProductDTO;
use App\Models\Product;

interface ProductServiceInterface
{
    public function store(StoreProductDTO $dto): Product;

    public function update(Product $product, StoreProductDTO $dto): Product;

    public function delete(Product $product): void;

    public function toggleActive(Product $product): Product;
}
