<?php

namespace Tests\Feature\Regression;

use App\Models\Address;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmptyOrderRejectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_with_empty_cart_is_rejected(): void
    {
        $user = User::factory()->customer()->create();
        $customer = Customer::factory()->withUser($user)->create();
        $address = Address::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAs($user)
            ->withSession(['cart' => []])
            ->post(route('shop.checkout.store'), ['address_id' => $address->id]);

        $response->assertSessionHasErrors('cart');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_without_cart_session_is_rejected(): void
    {
        $user = User::factory()->customer()->create();
        $customer = Customer::factory()->withUser($user)->create();
        $address = Address::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAs($user)
            ->post(route('shop.checkout.store'), ['address_id' => $address->id]);

        $response->assertSessionHasErrors('cart');
        $this->assertDatabaseCount('orders', 0);
    }
}
