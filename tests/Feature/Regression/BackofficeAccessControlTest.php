<?php

namespace Tests\Feature\Regression;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackofficeAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        // auth:admin middleware redirects unauthenticated requests
        $response->assertRedirect();
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->customer()->create();
        Customer::factory()->withUser($user)->create();

        // Customer authenticated via web guard is not in admin guard → redirected
        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertRedirect();
    }

    public function test_customer_cannot_access_admin_products_list(): void
    {
        $user = User::factory()->customer()->create();
        Customer::factory()->withUser($user)->create();

        $response = $this->actingAs($user)->get(route('admin.products.index'));

        $response->assertRedirect();
    }

    public function test_customer_cannot_access_admin_orders(): void
    {
        $user = User::factory()->customer()->create();
        Customer::factory()->withUser($user)->create();

        $response = $this->actingAs($user)->get(route('admin.orders.index'));

        $response->assertRedirect();
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
    }
}
