<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\Member;
use App\Models\Raid; // Add Club model
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RaidCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_raid_can_be_created(): void
    {
        // 0. Authentification : On crée un membre et on l'authentifie
        $member = Member::create([
            'mem_name' => 'NomTest',
            'mem_firstname' => 'PrenomTest',
            'mem_email' => 'admin@test.com',
            'mem_birthdate' => '1990-01-01',
            'mem_adress' => '123 Rue de Test',
            'mem_phone' => '0123456789',
            'user_username' => 'admin_test',
            'user_password' => bcrypt('password123'),
        ]);

        // Create a Club managed by the member
        $club = Club::create([
            'club_id' => 1,
            'user_id' => $member->user_id,
            'club_name' => 'Test Club',
            'club_address' => '123 Test Street',
            'club_active' => true,
        ]);

        // 1. Données de test pour un raid
        $raidData = [
            'raid_name' => 'Viking Adventure 2026',
            'raid_reg_start_date' => '2026-01-01 09:00:00',
            'raid_reg_end_date' => '2026-02-01 18:00:00',
            'raid_start_date' => '2026-03-01 08:00:00',
            'raid_end_date' => '2026-03-02 17:00:00',
            'raid_contact' => 'contact@viking.com',
            'raid_place' => 'Caen, France',
            'raid_website' => 'https://viking-raid.com',
            'club_id' => $club->club_id,
            'responsible_id' => $member->user_id,
        ];

        // 2. Exécution de la requête API pour créer un raid
        $response = $this->actingAs($member, 'sanctum')
            ->postJson(route('raids.store'), $raidData);

        // 3. Assertions
        $response->assertStatus(201);

        $this->assertDatabaseHas('vik_raid', [
            'raid_name' => 'Viking Adventure 2026',
        ]);
    }

    public function test_raid_creation_fails_without_name(): void
    {
        // 0. Authentification d'un membre (requis pour la route)
        $member = Member::create([
            'mem_name' => 'Admin',
            'mem_firstname' => 'Test',
            'mem_email' => 'admin_fail@test.com',
            'mem_birthdate' => '1990-01-01',
            'mem_adress' => 'Adresse',
            'mem_phone' => '0102030405',
            'user_username' => 'admin_fail',
            'user_password' => bcrypt('password123'),
        ]);

        // Club
        Club::create([
            'club_id' => 2,
            'user_id' => $member->user_id,
            'club_name' => 'Fail Club',
            'club_address' => '123 Fail Street',
            'club_active' => true,
        ]);

        // 1. Tentative de création d'un raid sans nom
        $response = $this->actingAs($member, 'sanctum')->postJson(route('raids.store'), [
            'raid_contact' => 'test@test.com',
            // raid_name manquant
        ]);

        // 2. Assertion: la requête doit échouer avec des erreurs de validation pour 'raid_name'
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['raid_name']);
    }
}
