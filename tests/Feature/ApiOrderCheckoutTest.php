<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiOrderCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Customer $customer;

    private Address $address;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->customer()->create();
        $this->customer = Customer::factory()->withUser($this->user)->create();
        $this->address = Address::factory()->create(['customer_id' => $this->customer->id]);
        $this->product = Product::factory()->active()->create(['price' => 10.00, 'stock' => 20]);
    }

    private function validPayload(int $quantity = 2): array
    {
        return [
            'address_id' => $this->address->id,
            'items' => [
                ['product_id' => $this->product->id, 'quantity' => $quantity],
            ],
        ];
    }

    public function test_customer_can_create_order_via_api(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/orders', $this->validPayload(3));

        $response->assertCreated();
        $response->assertJsonPath('data.status', OrderStatus::Pending->value);
        $response->assertJsonPath('data.total', 30);

        $this->assertDatabaseHas('orders', [
            'customer_id' => $this->customer->id,
            'address_id' => $this->address->id,
        ]);
    }

    public function test_order_creation_decrements_stock(): void
    {
        Sanctum::actingAs($this->user);

        $this->postJson('/api/v1/orders', $this->validPayload(5));

        $this->assertEquals(15, $this->product->fresh()->stock);
    }

    public function test_order_creation_fails_when_stock_insufficient(): void
    {
        Sanctum::actingAs($this->user);
        $this->product->update(['stock' => 1]);

        $response = $this->postJson('/api/v1/orders', $this->validPayload(5));

        $response->assertUnprocessable();
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_order_creation_fails_with_foreign_address(): void
    {
        Sanctum::actingAs($this->user);

        $otherCustomer = Customer::factory()->withUser(User::factory()->customer()->create())->create();
        $foreignAddress = Address::factory()->create(['customer_id' => $otherCustomer->id]);

        $response = $this->postJson('/api/v1/orders', [
            'address_id' => $foreignAddress->id,
            'items' => [['product_id' => $this->product->id, 'quantity' => 1]],
        ]);

        $response->assertUnprocessable();
    }

    public function test_order_creation_fails_with_empty_items(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/orders', [
            'address_id' => $this->address->id,
            'items' => [],
        ]);

        $response->assertUnprocessable();
    }

    public function test_admin_cannot_create_order_via_customer_endpoint(): void
    {
        Sanctum::actingAs(User::factory()->admin()->create());

        $response = $this->postJson('/api/v1/orders', $this->validPayload());

        $response->assertForbidden();
    }
}
