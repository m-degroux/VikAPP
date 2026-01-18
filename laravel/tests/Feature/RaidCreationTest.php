<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Import indispensable

class RaidCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_raid_can_be_created(): void
    {
        // 0. Authentification
        $member = Member::create([
            'mem_name' => 'Organisateur',
            'mem_firstname' => 'Chef',
            'mem_email' => 'orga@test.com',
            'mem_birthdate' => '1985-05-05',
            'mem_adress' => '10 Rue du Raid',
            'mem_phone' => '0600000000',
            'user_username' => 'admin_raid',
            'user_password' => bcrypt('password123'),
        ]);

        // 1. Préparation des données (AJOUT DU RESPONSABLE)
        $raidData = [
            'raid_name'           => 'Viking Adventure 2026',
            'raid_reg_start_date' => '2026-01-01 09:00:00',
            'raid_reg_end_date'   => '2026-02-01 18:00:00',
            'raid_start_date'     => '2026-03-01 08:00:00',
            'raid_end_date'       => '2026-03-02 17:00:00',
            'raid_contact'        => 'contact@viking.com',
            'raid_place'          => 'Caen, France',
            'raid_website'        => 'https://viking-raid.com',
            'responsible_id'      => $member->user_id, // <-- On ajoute l'ID du membre ici
        ];

        // 2. Envoi de la requête
        $response = $this->actingAs($member, 'sanctum')
            ->postJson(route('raids.store'), $raidData);

        // 3. Assertions
        $response->assertStatus(201);

        $this->assertDatabaseHas('vik_raid', [
            'raid_name' => 'Viking Adventure 2026'
        ]);
    }

    public function test_raid_creation_fails_without_name(): void
    {
        // Même pour une erreur de validation, il faut être connecté pour passer le middleware auth
        $member = Member::create([
            'mem_name' => 'User', 'mem_firstname' => 'Test', 'mem_email' => 'test@test.com',
            'mem_birthdate' => '1990-01-01', 'mem_adress' => '...', 'mem_phone' => '0600000000',
            'user_username' => 'tester', 'user_password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($member, 'sanctum')
            ->postJson(route('raids.store'), [
                'raid_contact' => 'test@test.com'
                // raid_name manquant
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['raid_name']);
    }
}
