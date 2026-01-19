<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste l'inscription d'un nouveau membre via l'API.
     */
    public function test_member_can_register(): void
    {
        $response = $this->postJson('/api/signup', [
            'mem_name' => 'NomTest',
            'mem_firstname' => 'PrenomTest',
            'mem_email' => 'test@example.com',
            'mem_birthdate' => '1990-01-01',
            'mem_adress' => '123 Rue de Test',
            'mem_phone' => '0123456789',
            'user_username' => 'testuser',
            'user_password' => 'password',
            'user_password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'access_token', 'user']);

        $this->assertDatabaseHas('vik_member', [
            'user_username' => 'testuser',
            'mem_email' => 'test@example.com',
        ]);
    }

    /**
     * Teste la connexion d'un membre via l'API.
     */
    public function test_member_can_login(): void
    {
        $member = Member::create([
            'mem_name' => 'NomTest',
            'mem_firstname' => 'PrenomTest',
            'mem_email' => 'login@example.com',
            'mem_birthdate' => '1990-01-01',
            'mem_adress' => '123 Rue de Login',
            'mem_phone' => '0987654321',
            'user_username' => 'loginuser',
            'user_password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'loginuser',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'access_token', 'token_type', 'user']);
    }

    /**
     * Teste qu'un utilisateur non authentifié ne peut pas accéder aux routes protégées.
     */
    public function test_unauthenticated_user_cannot_access_profile(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    /**
     * Teste la déconnexion d'un membre.
     */
    public function test_member_can_logout(): void
    {
        $member = Member::create([
            'mem_name' => 'NomTest',
            'mem_firstname' => 'PrenomTest',
            'mem_email' => 'logout@example.com',
            'mem_birthdate' => '1990-01-01',
            'mem_adress' => '123 Rue de Logout',
            'mem_phone' => '0123456780',
            'user_username' => 'logoutuser',
            'user_password' => bcrypt('password'),
        ]);

        $token = $member->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
    }

    /**
     * Teste la suppression d'un compte de membre via l'API.
     */
    public function test_member_can_delete_their_account(): void
    {
        // 1. Création d'un membre et d'un club pour qu'il puisse gérer
        $member = Member::create([
            'mem_name' => 'Delete', 'mem_firstname' => 'Me', 'mem_email' => 'delete_me@example.com',
            'mem_birthdate' => '1990-01-01', 'mem_adress' => '123 Delete Street', 'mem_phone' => '0123456789',
            'user_username' => 'delete_me', 'user_password' => bcrypt('password'),
        ]);

        $club = Club::create([
            'club_id' => 99,
            'user_id' => $member->user_id,
            'club_name' => 'Delete Club',
            'club_address' => '456 Delete Avenue',
            'club_active' => true,
        ]);

        $token = $member->createToken('delete_token')->plainTextToken;

        // 2. Requête API pour supprimer le compte
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/user/profile');

        // 3. Vérifications (Assertions)
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Votre compte a été supprimé définitivement.',
            ]);

        // On vérifie que le membre n'existe plus dans la table vik_member
        $this->assertDatabaseMissing('vik_member', [
            'user_username' => 'delete_me',
        ]);

        // On vérifie que le club géré par l'utilisateur a été supprimé
        $this->assertDatabaseMissing('vik_club', [
            'club_id' => $club->club_id,
        ]);
    }

    /**
     * Teste qu'un membre ne peut pas supprimer un compte qui n'est pas le sien (non implémenté côté API)
     */
    public function test_member_cannot_delete_another_account(): void
    {
        // Créer deux membres, dont un administrateur
        $member1 = Member::create([
            'mem_name' => 'User1', 'mem_firstname' => 'Test', 'mem_email' => 'user1@example.com',
            'mem_birthdate' => '1990-01-01', 'mem_adress' => 'Address 1', 'mem_phone' => '0123456789',
            'user_username' => 'user1', 'user_password' => bcrypt('password'),
        ]);
        $member2 = Member::create([
            'mem_name' => 'User2', 'mem_firstname' => 'Test', 'mem_email' => 'user2@example.com',
            'mem_birthdate' => '1990-01-01', 'mem_adress' => 'Address 2', 'mem_phone' => '0123456788',
            'user_username' => 'user2', 'user_password' => bcrypt('password'),
        ]);

        // Assigner le membre 1 à un club pour qu'il soit manager (arbitraire pour l'exemple)
        Club::create([
            'club_id' => 100,
            'user_id' => $member1->user_id,
            'club_name' => 'Club1',
            'club_address' => 'Address Club1',
            'club_active' => true,
        ]);

        $token = $member1->createToken('test_token_1')->plainTextToken;

        // Tentative de suppression du compte du membre 2 par le membre 1
        // L'API ne permet pas de spécifier l'ID d'un autre utilisateur à supprimer.
        // La route DELETE /api/user/profile supprime toujours le compte de l'utilisateur authentifié.
        // Donc, ce test devrait toujours résulter en la suppression du compte de member1.
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/user/profile'); // Member1 essaie de supprimer son propre compte

        $response->assertStatus(200); // L'opération réussit pour le membre 1

        $this->assertDatabaseMissing('vik_member', ['user_username' => 'user1']);
        $this->assertDatabaseHas('vik_member', ['user_username' => 'user2']); // Le membre 2 n'est pas affecté
    }
}
