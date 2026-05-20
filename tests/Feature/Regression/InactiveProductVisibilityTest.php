<?php

namespace Tests\Feature\Regression;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InactiveProductVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsCustomer(): static
    {
        $user = User::factory()->customer()->create();
        Customer::factory()->withUser($user)->create();

        return $this->actingAs($user);
    }

    public function test_inactive_product_does_not_appear_in_shop_listing(): void
    {
        $active = Product::factory()->active()->create(['name' => 'Produto Activo']);
        $inactive = Product::factory()->inactive()->create(['name' => 'Produto Inactivo']);

        $response = $this->actingAsCustomer()->get(route('shop.products.index'));

        $response->assertOk();
        $response->assertSee($active->name);
        $response->assertDontSee($inactive->name);
    }

    public function test_inactive_product_detail_page_is_not_accessible(): void
    {
        $inactive = Product::factory()->inactive()->create();

        $response = $this->actingAsCustomer()->get(route('shop.products.show', $inactive));

        $response->assertNotFound();
    }

    public function test_active_product_is_visible_in_shop(): void
    {
        $product = Product::factory()->active()->create();

        $response = $this->actingAsCustomer()->get(route('shop.products.show', $product));

        $response->assertOk();
    }
}
