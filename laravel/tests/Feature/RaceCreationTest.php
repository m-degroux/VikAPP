<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\Difficulty;
use App\Models\Member;
use App\Models\Raid;
use App\Models\Type; // Ajout du modèle Member
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RaceCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_race_can_be_created(): void
    {
        // 0. Authentification : On crée un membre et on l'authentifie
        $member = Member::create([
            'mem_name' => 'Admin',
            'mem_firstname' => 'Test',
            'mem_email' => 'admin@test.com',
            'mem_birthdate' => '1990-01-01',
            'mem_adress' => 'Adresse',
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

        // 1. Création des pré-requis (Raid, Type, Difficulty)
        $raid = Raid::create([
            'raid_name' => 'Raid Normand',
            'raid_contact' => 'contact@raid.com',
            'raid_website' => 'https://raid-normand.fr',
            'raid_place' => 'Caen',
            'raid_start_date' => '2026-06-01 08:00:00',
            'raid_end_date' => '2026-06-02 18:00:00',
            'raid_reg_start_date' => '2026-01-01 00:00:00',
            'raid_reg_end_date' => '2026-05-15 23:59:59',
            'club_id' => $club->club_id, // Add club_id
        ]);

        $type = Type::create(['type_id' => 1, 'type_name' => 'VTT']);

        $difficulty = Difficulty::create([
            'dif_id' => 1,
            'dif_name' => 'Expert',
            'dif_dist_min' => 20,
            'dif_dist_max' => 50,
        ]);

        $raceData = [
            'raid_id' => $raid->raid_id,
            'type_id' => $type->type_id,
            'dif_id' => $difficulty->dif_id,
            'race_name' => 'La Trans Eure',
            'race_duration' => '04:00:00',
            'race_length' => 45.5,
            'race_start_date' => '2026-06-01 09:00:00',
            'race_end_date' => '2026-06-01 13:00:00',
            'race_min_part' => 20,
            'race_max_part' => 100,
            'race_meal_price' => 12.50,
            'race_reduction' => 0,
            'race_min_team' => 1,
            'race_max_team' => 50,
            'race_max_part_per_team' => 2,
            'race_location' => 'Louviers',
            'race_price_ext' => 15.00,
        ];

        // 3. Exécution avec actingAs
        $response = $this->actingAs($member, 'sanctum') // On précise le guard sanctum
            ->postJson('/api/races', $raceData);

        if ($response->status() !== 201) {
            dump($response->json());
        }

        $response->assertStatus(201)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('vik_race', [
            'race_name' => 'La Trans Eure',
        ]);
    }

    public function test_race_creation_fails_validation(): void
    {
        // Il faut aussi être authentifié pour tester la validation
        $member = Member::create([
            'mem_name' => 'Admin', 'mem_firstname' => 'Test', 'mem_email' => 'admin2@test.com',
            'mem_birthdate' => '1990-01-01', 'mem_adress' => 'Adresse', 'mem_phone' => '0102030405',
            'user_username' => 'admin_test2', 'user_password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($member, 'sanctum')->postJson('/api/races', []);

        $response->assertStatus(422);
    }
}
