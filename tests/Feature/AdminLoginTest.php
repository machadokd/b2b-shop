<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_is_redirected_to_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_admin_login_with_wrong_password_fails(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    public function test_customer_cannot_login_via_admin_form(): void
    {
        $customer = User::factory()->customer()->create();

        $response = $this->post('/admin/login', [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
    }
}
