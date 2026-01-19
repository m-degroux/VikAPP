<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Raid;
use Illuminate\Database\Seeder;

class RaidSeeder extends Seeder
{
    public function run(): void
    {
        $clubs = Club::all();

        // Create 15 raids
        foreach ($clubs->take(15) as $club) {
            Raid::factory()->create([
                'club_id' => $club->club_id,
            ]);
        }

        // Create 10 more random raids
        Raid::factory(10)->create();
    }
}
