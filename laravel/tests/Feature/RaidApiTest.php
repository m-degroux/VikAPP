<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\Member;
use App\Models\Raid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RaidApiTest extends TestCase
{
    use RefreshDatabase;

    private Member $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = Member::factory()->create();
    }

    public function test_can_list_raids(): void
    {
        Raid::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/raids');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_raid(): void
    {
        $club = Club::factory()->create();

        $data = [
            'raid_name' => 'Test Raid',
            'raid_reg_start_date' => now()->toDateTimeString(),
            'raid_reg_end_date' => now()->addDays(30)->toDateTimeString(),
            'raid_start_date' => now()->addDays(35)->toDateTimeString(),
            'raid_end_date' => now()->addDays(37)->toDateTimeString(),
            'raid_contact' => 'test@example.com',
            'raid_website' => 'https://example.com',
            'raid_place' => 'Test City',
            'raid_lat' => 48.8566,
            'raid_lng' => 2.3522,
            'club_id' => $club->club_id,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/raids', $data);

        $response->assertCreated();
        $this->assertDatabaseHas('vik_raid', [
            'raid_name' => 'Test Raid',
        ]);
    }

    public function test_can_show_raid(): void
    {
        $raid = Raid::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/raids/{$raid->raid_id}");

        $response->assertOk()
            ->assertJsonPath('data.raid_id', $raid->raid_id);
    }

    public function test_can_update_raid(): void
    {
        $raid = Raid::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/raids/{$raid->raid_id}", [
                'raid_name' => 'Updated Raid Name',
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('vik_raid', [
            'raid_id' => $raid->raid_id,
            'raid_name' => 'Updated Raid Name',
        ]);
    }

    public function test_can_delete_raid(): void
    {
        $raid = Raid::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/raids/{$raid->raid_id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('vik_raid', [
            'raid_id' => $raid->raid_id,
        ]);
    }
}
