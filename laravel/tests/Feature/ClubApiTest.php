<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClubApiTest extends TestCase
{
    use RefreshDatabase;

    private Member $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = Member::factory()->create();
    }

    public function test_can_list_clubs(): void
    {
        Club::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/clubs');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_club(): void
    {
        $data = [
            'club_id' => rand(1000, 9999),
            'user_id' => $this->user->user_id,
            'club_name' => 'Test Club',
            'club_address' => '123 Test Street',
            'club_active' => true,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/clubs', $data);

        $response->assertCreated();
        $this->assertDatabaseHas('vik_club', [
            'club_name' => 'Test Club',
        ]);
    }

    public function test_can_show_club(): void
    {
        $club = Club::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/clubs/{$club->club_id}");

        $response->assertOk()
            ->assertJsonPath('data.club_id', $club->club_id);
    }

    public function test_can_update_club(): void
    {
        $club = Club::factory()->create(['user_id' => $this->user->user_id]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/clubs/{$club->club_id}", [
                'club_name' => 'Updated Club Name',
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('vik_club', [
            'club_id' => $club->club_id,
            'club_name' => 'Updated Club Name',
        ]);
    }

    public function test_can_delete_club(): void
    {
        $club = Club::factory()->create(['user_id' => $this->user->user_id]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/clubs/{$club->club_id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('vik_club', [
            'club_id' => $club->club_id,
        ]);
    }
}
