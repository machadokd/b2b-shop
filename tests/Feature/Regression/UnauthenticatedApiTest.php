<?php

namespace Tests\Feature\Regression;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnauthenticatedApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_endpoint_requires_authentication(): void
    {
        $this->getJson('/api/v1/products')->assertUnauthorized();
    }

    public function test_catalogs_endpoint_requires_authentication(): void
    {
        $this->getJson('/api/v1/catalogs')->assertUnauthorized();
    }

    public function test_categories_endpoint_requires_authentication(): void
    {
        $this->getJson('/api/v1/categories')->assertUnauthorized();
    }

    public function test_customers_endpoint_requires_authentication(): void
    {
        $this->getJson('/api/v1/customers')->assertUnauthorized();
    }

    public function test_logout_endpoint_requires_authentication(): void
    {
        $this->postJson('/api/v1/logout')->assertUnauthorized();
    }

    public function test_create_product_requires_authentication(): void
    {
        $this->postJson('/api/v1/products', [])->assertUnauthorized();
    }
}
