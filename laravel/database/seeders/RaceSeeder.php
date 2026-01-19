<?php

namespace Database\Seeders;

use App\Models\Difficulty;
use App\Models\Race;
use App\Models\Raid;
use App\Models\Type;
use Illuminate\Database\Seeder;

class RaceSeeder extends Seeder
{
    public function run(): void
    {
        $raids = Raid::all();
        $types = Type::pluck('type_id')->toArray();
        $difficulties = Difficulty::pluck('dif_id')->toArray();

        // Create 3-5 races per raid
        foreach ($raids as $raid) {
            $raceCount = rand(3, 5);

            for ($i = 0; $i < $raceCount; $i++) {
                Race::factory()->create([
                    'raid_id' => $raid->raid_id,
                    'type_id' => $types[array_rand($types)],
                    'dif_id' => $difficulties[array_rand($difficulties)],
                ]);
            }
        }
    }
}
