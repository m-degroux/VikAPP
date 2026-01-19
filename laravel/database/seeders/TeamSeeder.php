<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Race;
use App\Models\Team;
use App\Models\Type;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        // Get team type ID - column is type_id not type_label
        $teamTypeId = Type::where('type_id', 'EQUIPE')->value('type_id');

        if (! $teamTypeId) {
            $this->command->warn('No EQUIPE type found, skipping team seeding');

            return;
        }

        $races = Race::where('type_id', $teamTypeId)->get();
        $members = Member::all();

        // Create 2-4 teams per team race
        foreach ($races->take(20) as $race) {
            $teamCount = rand(2, 4);

            for ($i = 0; $i < $teamCount; $i++) {
                Team::factory()->create([
                    'race_id' => $race->race_id,
                    'user_id' => $members->random()->user_id,
                ]);
            }
        }
    }
}
