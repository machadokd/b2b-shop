<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
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
        $this->product = Product::factory()->active()->create(['price' => 25.00, 'stock' => 10]);
    }

    private function cartWith(int $quantity = 2): array
    {
        return [
            $this->product->id => [
                'product_id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => $quantity,
            ],
        ];
    }

    public function test_checkout_creates_order_and_clears_cart(): void
    {
        $response = $this->actingAs($this->user)
            ->withSession(['cart' => $this->cartWith(2)])
            ->post(route('shop.checkout.store'), ['address_id' => $this->address->id]);

        $response->assertRedirect();
        $response->assertSessionMissing('cart');

        $this->assertDatabaseHas('orders', [
            'customer_id' => $this->customer->id,
            'address_id' => $this->address->id,
        ]);
    }

    public function test_checkout_calculates_total_correctly(): void
    {
        $this->actingAs($this->user)
            ->withSession(['cart' => $this->cartWith(3)])
            ->post(route('shop.checkout.store'), ['address_id' => $this->address->id]);

        $order = Order::where('customer_id', $this->customer->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals(75.00, $order->total);
    }

    public function test_checkout_decrements_stock_via_queue(): void
    {
        $initialStock = $this->product->stock;

        $this->actingAs($this->user)
            ->withSession(['cart' => $this->cartWith(2)])
            ->post(route('shop.checkout.store'), ['address_id' => $this->address->id]);

        $this->assertEquals($initialStock - 2, $this->product->fresh()->stock);
    }

    public function test_checkout_fails_when_stock_is_insufficient(): void
    {
        $this->product->update(['stock' => 1]);

        $response = $this->actingAs($this->user)
            ->withSession(['cart' => $this->cartWith(5)])
            ->post(route('shop.checkout.store'), ['address_id' => $this->address->id]);

        $response->assertSessionHasErrors('stock');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_redirects_unauthenticated_user_to_login(): void
    {
        $response = $this->post(route('shop.checkout.store'), ['address_id' => $this->address->id]);

        $response->assertRedirect(route('customer.login'));
    }
}
