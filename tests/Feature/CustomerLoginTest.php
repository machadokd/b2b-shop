<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_login_and_is_redirected_to_shop(): void
    {
        $user = User::factory()->customer()->create();
        Customer::factory()->withUser($user)->create();

        $response = $this->post('/shop/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('shop.products.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_customer_login_with_wrong_password_fails(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->post('/shop/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_blocked_customer_cannot_login(): void
    {
        $user = User::factory()->customer()->create();
        Customer::factory()->withUser($user)->blocked()->create();

        $response = $this->post('/shop/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_cannot_login_via_shop_form(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->post('/shop/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
