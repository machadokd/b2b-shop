<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\DTOs\StoreProductDTO;
use App\Models\Product;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $products,
    ) {}

    public function store(StoreProductDTO $dto): Product
    {
        /** @var Product $product */
        $product = $this->products->create([
            'category_id' => $dto->category_id,
            'name' => $dto->name,
            'slug' => $dto->slug,
            'sku' => $dto->sku,
            'description' => $dto->description,
            'price' => $dto->price,
            'stock' => $dto->stock,
            'image' => $dto->image,
            'is_active' => $dto->is_active,
        ]);

        return $product;
    }

    public function update(Product $product, StoreProductDTO $dto): Product
    {
        $product->update([
            'category_id' => $dto->category_id,
            'name' => $dto->name,
            'slug' => $dto->slug,
            'sku' => $dto->sku,
            'description' => $dto->description,
            'price' => $dto->price,
            'stock' => $dto->stock,
            'image' => $dto->image ?? $product->image,
            'is_active' => $dto->is_active,
        ]);

        return $product->refresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function toggleActive(Product $product): Product
    {
        $product->update(['is_active' => ! $product->is_active]);

        return $product->refresh();
    }
}
