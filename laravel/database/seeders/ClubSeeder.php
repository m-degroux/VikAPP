<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Member;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing members
        $members = Member::all();

        // Create 10 clubs with existing members
        foreach ($members->take(10) as $member) {
            Club::factory()->create([
                'user_id' => $member->user_id,
            ]);
        }

        // Create 5 more random clubs
        Club::factory(5)->create();
    }
}
