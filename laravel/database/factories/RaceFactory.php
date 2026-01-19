<?php

namespace Database\Factories;

use App\Models\Difficulty;
use App\Models\Race;
use App\Models\Raid;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RaceFactory extends Factory
{
    protected $model = Race::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 week', '+3 months');
        $duration = rand(1, 6);
        $endDate = (clone $startDate)->modify('+'.$duration.' hours');

        // Get existing reference data from seeded tables
        $typeId = Type::inRandomOrder()->value('type_id') ?? 'INDIVIDUEL';
        $difId = Difficulty::inRandomOrder()->value('dif_id') ?? 'FACILE';

        return [
            'race_id' => Str::uuid()->toString(),
            'raid_id' => Raid::factory(),
            'type_id' => $typeId,
            'dif_id' => $difId,
            'race_name' => fake()->words(3, true),
            'race_duration' => sprintf('%02d:00:00', $duration),
            'race_length' => fake()->randomFloat(2, 5, 99),
            'race_reduction' => fake()->randomFloat(2, 0, 9),
            'race_start_date' => $startDate,
            'race_end_date' => $endDate,
            'race_min_part' => rand(1, 5),
            'race_max_part' => rand(50, 200),
            'race_min_team' => rand(1, 3),
            'race_max_team' => rand(20, 100),
            'race_max_part_per_team' => rand(2, 10),
            'race_meal_price' => fake()->randomFloat(2, 10, 50),
        ];
    }
}
