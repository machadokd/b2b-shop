<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Product> */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'sku' => strtoupper(fake()->unique()->bothify('??-####')),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 5, 500),
            'stock' => fake()->numberBetween(10, 100),
            'image' => null,
            'is_active' => true,
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['is_active' => true, 'stock' => fake()->numberBetween(10, 100)]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function lowStock(): static
    {
        return $this->state(fn () => ['stock' => fake()->numberBetween(1, 3)]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock' => 0]);
    }
}
