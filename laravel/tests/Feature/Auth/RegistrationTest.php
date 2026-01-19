<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'mem_name' => 'Test User',
            'mem_firstname' => 'Test',
            'mem_email' => 'test@example.com',
            'mem_birthdate' => '1990-01-01',
            'mem_adress' => 'Test Address',
            'mem_phone' => '0123456789',
            'user_username' => 'testuser',
            'user_password' => 'password',
            'user_password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated('web');
        $response->assertRedirect(route('welcome', absolute: false));
    }
}
