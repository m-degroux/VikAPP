<?php


namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    // Réinitialise la DB entre chaque test

    /**
     * Test de l'inscription (Signup)
     */
    public function test_member_can_register(): void
    {
        $data = [
            'mem_name' => 'Buzzy',
            'mem_firstname' => 'Eclair',
            'mem_email' => 'buzzy@test.com',
            'mem_birthdate' => '1990-01-01',
            'mem_adress' => '123 Rue de Caen',
            'mem_phone' => '0600000000',
            'user_username' => 'buzzy_user',
            'user_password' => 'password123',
            'user_password_confirmation' => 'password123',
            'club_id' => null,
        ];

        $response = $this->postJson('/api/signup', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'access_token', 'user']);

        $this->assertDatabaseHas('vik_member', [
            'user_username' => 'buzzy_user',
            'mem_email' => 'buzzy@test.com'
        ]);
    }

    /**
     * Test de la connexion (Login)
     */
    public function test_member_can_login(): void
    {
        // Création d'un membre manuellement
        $member = Member::create([
            'mem_name' => 'Test',
            'mem_firstname' => 'User',
            'mem_email' => 'test@user.com',
            'mem_birthdate' => '1990-01-01',
            'mem_adress' => 'Adress',
            'mem_phone' => '0707070707',
            'user_username' => 'login_test',
            'user_password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'login_test',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token']);
    }

    /**
     * Test de l'accès protégé (Middleware Sanctum)
     */
    public function test_unauthenticated_user_cannot_access_profile(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401); // Doit être bloqué
    }

    /**
     * Test de la déconnexion (Logout)
     */
    public function test_member_can_logout(): void
    {
        $member = Member::create([
            'mem_name' => 'Logout',
            'mem_firstname' => 'User',
            'mem_email' => 'out@test.com',
            'mem_birthdate' => '1990-01-01',
            'mem_adress' => 'Adress',
            'mem_phone' => '0707070707',
            'user_username' => 'logout_test',
            'user_password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        $token = $member->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200);

        $this->app->make(\Illuminate\Contracts\Auth\Guard::class)->forgetUser();

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/user')
            ->assertStatus(401);
    }

    public function test_member_can_delete_their_account(): void
    {
        $member = Member::create([
            'mem_name' => 'To Delete',
            'mem_firstname' => 'User',
            'mem_email' => 'delete@test.com',
            'mem_birthdate' => '1990-01-01',
            'mem_adress' => 'Adress',
            'mem_phone' => '0707070707',
            'user_username' => 'delete_me',
            'user_password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        $token = $member->createToken('delete-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/user/profile');

        $response->assertStatus(200);

        // On vérifie que le membre n'existe plus dans la table vik_member
        $this->assertDatabaseMissing('vik_member', [
            'user_username' => 'delete_me'
        ]);
    }
    /**
     * Test de la suppression du compte utilisateur.
     */
    public function test_member_can_delete_their_own_account(): void
    {
        // 1. Préparation : Création d'un membre
        $member = Member::create([
            'mem_name' => 'To Delete',
            'mem_firstname' => 'User',
            'mem_email' => 'delete@test.com',
            'mem_birthdate' => '1990-01-01',
            'mem_adress' => '123 Rue de la Paix',
            'mem_phone' => '0611223344',
            'user_username' => 'user_to_delete',
            'user_password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        // Génération du token
        $token = $member->createToken('delete-token')->plainTextToken;

        // 2. Action : Appel de la route DELETE
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/user/profile');

        // 3. Vérifications (Assertions)
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Votre compte a été supprimé définitivement.'
            ]);

        // On vérifie que le membre n'existe plus dans la base de données
        $this->assertDatabaseMissing('vik_member', [
            'user_id' => $member->user_id,
            'user_username' => 'user_to_delete'
        ]);

        // On vérifie que les tokens associés ont aussi été supprimés
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $member->user_id,
            'tokenable_type' => get_class($member)
        ]);
    }
}
