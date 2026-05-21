<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiOrderStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();

        $customer = Customer::factory()->withUser(User::factory()->customer()->create())->create();
        $address = Address::factory()->create(['customer_id' => $customer->id]);
        $this->order = Order::factory()->create([
            'customer_id' => $customer->id,
            'address_id' => $address->id,
            'status' => OrderStatus::Pending,
        ]);
    }

    public function test_admin_can_update_order_status_with_valid_transition(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->patchJson("/api/v1/admin/orders/{$this->order->id}/status", [
            'status' => OrderStatus::Confirmed->value,
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.status', OrderStatus::Confirmed->value);

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => OrderStatus::Confirmed->value,
        ]);
    }

    public function test_admin_gets_422_on_invalid_transition(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->patchJson("/api/v1/admin/orders/{$this->order->id}/status", [
            'status' => OrderStatus::Completed->value,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonStructure(['message']);
    }

    public function test_admin_gets_422_with_invalid_status_value(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->patchJson("/api/v1/admin/orders/{$this->order->id}/status", [
            'status' => 'nonexistent',
        ]);

        $response->assertUnprocessable();
    }

    public function test_admin_can_list_all_orders(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/v1/admin/orders');

        $response->assertOk();
        $response->assertJsonStructure(['data', 'meta']);
    }

    public function test_admin_can_filter_orders_by_status(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/v1/admin/orders?status=pending');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    }

    public function test_customer_cannot_access_admin_orders_endpoint(): void
    {
        $customerUser = User::factory()->customer()->create();
        Sanctum::actingAs($customerUser);

        $response = $this->patchJson("/api/v1/admin/orders/{$this->order->id}/status", [
            'status' => OrderStatus::Confirmed->value,
        ]);

        $response->assertForbidden();
    }
}
