<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticatedApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_products(): void
    {
        Sanctum::actingAs(User::factory()->admin()->create());

        $response = $this->getJson('/api/v1/products');

        $response->assertOk();
    }

    public function test_unauthenticated_request_returns_401(): void
    {
        $response = $this->getJson('/api/v1/products');

        $response->assertUnauthorized();
    }

    public function test_api_login_returns_token(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['token']);
    }

    public function test_api_login_with_wrong_credentials_returns_422(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrong',
        ]);

        $response->assertUnprocessable();
    }
}
