<?php

namespace Tests\Feature\Regression;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiOrderIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_view_another_customers_order_via_api(): void
    {
        $userA = User::factory()->customer()->create();
        $customerA = Customer::factory()->withUser($userA)->create();
        $address = Address::factory()->create(['customer_id' => $customerA->id]);
        $orderA = Order::factory()->create(['customer_id' => $customerA->id, 'address_id' => $address->id]);

        $userB = User::factory()->customer()->create();
        Customer::factory()->withUser($userB)->create();

        Sanctum::actingAs($userB);

        $response = $this->getJson("/api/orders/{$orderA->id}");

        $response->assertForbidden();
    }

    public function test_customer_can_view_their_own_order_via_api(): void
    {
        $user = User::factory()->customer()->create();
        $customer = Customer::factory()->withUser($user)->create();
        $address = Address::factory()->create(['customer_id' => $customer->id]);
        $order = Order::factory()->create(['customer_id' => $customer->id, 'address_id' => $address->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/orders/{$order->id}");

        $response->assertOk();
        $response->assertJsonPath('data.id', $order->id);
    }

    public function test_customer_only_sees_own_orders_in_listing(): void
    {
        $userA = User::factory()->customer()->create();
        $customerA = Customer::factory()->withUser($userA)->create();
        $addressA = Address::factory()->create(['customer_id' => $customerA->id]);
        Order::factory()->count(2)->create(['customer_id' => $customerA->id, 'address_id' => $addressA->id]);

        $userB = User::factory()->customer()->create();
        $customerB = Customer::factory()->withUser($userB)->create();
        $addressB = Address::factory()->create(['customer_id' => $customerB->id]);
        Order::factory()->count(3)->create(['customer_id' => $customerB->id, 'address_id' => $addressB->id]);

        Sanctum::actingAs($userA);

        $response = $this->getJson('/api/orders');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    }
}
