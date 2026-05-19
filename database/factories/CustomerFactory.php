<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Customer> */
class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->customer(),
            'company_name' => fake()->company(),
            'nif' => fake()->unique()->numerify('#########'),
            'phone' => fake()->phoneNumber(),
            'is_blocked' => false,
        ];
    }

    public function withUser(User $user): static
    {
        return $this->state(fn () => ['user_id' => $user->id]);
    }

    public function blocked(): static
    {
        return $this->state(fn () => ['is_blocked' => true]);
    }
}
