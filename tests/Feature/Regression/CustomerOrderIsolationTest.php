<?php

namespace Tests\Feature\Regression;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_view_another_customers_order(): void
    {
        $userA = User::factory()->customer()->create();
        $customerA = Customer::factory()->withUser($userA)->create();
        $address = Address::factory()->create(['customer_id' => $customerA->id]);
        $orderA = Order::factory()->create(['customer_id' => $customerA->id, 'address_id' => $address->id]);

        $userB = User::factory()->customer()->create();
        Customer::factory()->withUser($userB)->create();

        $response = $this->actingAs($userB)->get(route('shop.orders.show', $orderA->id));

        $response->assertForbidden();
    }

    public function test_customer_can_view_their_own_order(): void
    {
        $user = User::factory()->customer()->create();
        $customer = Customer::factory()->withUser($user)->create();
        $address = Address::factory()->create(['customer_id' => $customer->id]);
        $order = Order::factory()->create(['customer_id' => $customer->id, 'address_id' => $address->id]);

        $response = $this->actingAs($user)->get(route('shop.orders.show', $order->id));

        $response->assertOk();
    }
}
