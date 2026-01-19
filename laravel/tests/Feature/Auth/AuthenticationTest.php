<?php

namespace Tests\Feature\Auth;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $member = Member::factory()->create();

        $response = $this->post('/login', [
            'username' => $member->user_username,
            'password' => 'password',
        ]);

        $this->assertAuthenticated('web');
        $response->assertRedirect(route('welcome', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $member = Member::factory()->create();

        $this->post('/login', [
            'username' => $member->user_username,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('web');
    }

    public function test_users_can_logout(): void
    {
        $member = Member::factory()->create();

        $response = $this->actingAs($member)->post('/logout');

        $this->assertGuest('web');
        $response->assertRedirect('/');
    }
}
