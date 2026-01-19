<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Race;
use App\Models\Raid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RaceApiTest extends TestCase
{
    use RefreshDatabase;

    private Member $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = Member::factory()->create();
    }

    public function test_can_list_races(): void
    {
        Race::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/races');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_race(): void
    {
        $raid = Raid::factory()->create();

        $data = [
            'race_id' => 'RACE-'.uniqid(),
            'raid_id' => $raid->raid_id,
            'type_id' => 'INDIVIDUEL',
            'dif_id' => 'FACILE',
            'race_name' => 'Test Race',
            'race_duration' => '02:00:00',
            'race_length' => 10.5,
            'race_reduction' => 5.0,
            'race_start_date' => now()->addDays(10)->toDateTimeString(),
            'race_end_date' => now()->addDays(10)->addHours(3)->toDateTimeString(),
            'race_min_part' => 1,
            'race_max_part' => 100,
            'race_min_team' => 1,
            'race_max_team' => 20,
            'race_max_part_per_team' => 4,
            'race_meal_price' => 15.0,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/races', $data);

        $response->assertCreated();
        $this->assertDatabaseHas('vik_race', [
            'race_name' => 'Test Race',
        ]);
    }

    public function test_can_show_race(): void
    {
        $race = Race::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/races/{$race->race_id}");

        $response->assertOk()
            ->assertJsonPath('data.race_id', $race->race_id);
    }

    public function test_can_update_race(): void
    {
        $race = Race::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/races/{$race->race_id}", [
                'race_name' => 'Updated Race Name',
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('vik_race', [
            'race_id' => $race->race_id,
            'race_name' => 'Updated Race Name',
        ]);
    }

    public function test_can_delete_race(): void
    {
        $race = Race::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/races/{$race->race_id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('vik_race', [
            'race_id' => $race->race_id,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_races(): void
    {
        $response = $this->getJson('/api/races');

        $response->assertUnauthorized();
    }
}
