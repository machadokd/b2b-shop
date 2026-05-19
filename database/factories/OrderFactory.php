<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Order> */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $customer = Customer::factory()->create();
        $address = Address::factory()->create(['customer_id' => $customer->id]);

        return [
            'customer_id' => $customer->id,
            'address_id' => $address->id,
            'status' => OrderStatus::Pending,
            'total' => fake()->randomFloat(2, 20, 2000),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => OrderStatus::Pending]);
    }

    public function confirmed(): static
    {
        return $this->state(fn () => ['status' => OrderStatus::Confirmed]);
    }

    public function shipped(): static
    {
        return $this->state(fn () => ['status' => OrderStatus::Shipped]);
    }

    public function completed(): static
    {
        return $this->state(fn () => ['status' => OrderStatus::Completed]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => ['status' => OrderStatus::Cancelled]);
    }
}
