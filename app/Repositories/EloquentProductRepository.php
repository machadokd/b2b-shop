<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function findAll(): Collection
    {
        return Product::with('category')->get();
    }

    public function findById(int $id): ?Model
    {
        return Product::find($id);
    }

    public function create(array $data): Model
    {
        return Product::create($data);
    }

    public function update(int $id, array $data): Model
    {
        $product = Product::findOrFail($id);
        $product->update($data);

        return $product;
    }

    public function delete(int $id): bool
    {
        return Product::destroy($id) > 0;
    }

    public function findActive(): Collection
    {
        return Product::with('category')->active()->get();
    }

    public function findByCategory(int $categoryId): Collection
    {
        return Product::with('category')
            ->where('category_id', $categoryId)
            ->get();
    }

    public function findBySku(string $sku): ?Product
    {
        return Product::where('sku', $sku)->first();
    }

    public function decrementStock(int $productId, int $quantity): void
    {
        Product::where('id', $productId)->decrement('stock', $quantity);
    }
}
