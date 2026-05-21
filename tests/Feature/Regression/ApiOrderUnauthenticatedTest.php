<?php

namespace Tests\Feature\Regression;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiOrderUnauthenticatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_orders_requires_authentication(): void
    {
        $this->getJson('/api/orders')->assertUnauthorized();
    }

    public function test_show_order_requires_authentication(): void
    {
        $this->getJson('/api/orders/1')->assertUnauthorized();
    }

    public function test_create_order_requires_authentication(): void
    {
        $this->postJson('/api/orders', [])->assertUnauthorized();
    }

    public function test_admin_list_orders_requires_authentication(): void
    {
        $this->getJson('/api/admin/orders')->assertUnauthorized();
    }

    public function test_admin_show_order_requires_authentication(): void
    {
        $this->getJson('/api/admin/orders/1')->assertUnauthorized();
    }

    public function test_admin_update_status_requires_authentication(): void
    {
        $this->patchJson('/api/admin/orders/1/status', [])->assertUnauthorized();
    }
}
