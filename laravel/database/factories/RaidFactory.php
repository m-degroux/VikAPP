<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Raid;
use Illuminate\Database\Eloquent\Factories\Factory;

class RaidFactory extends Factory
{
    protected $model = Raid::class;

    public function definition(): array
    {
        $regStartDate = fake()->dateTimeBetween('now', '+1 month');
        $regEndDate = (clone $regStartDate)->modify('+'.rand(20, 40).' days');
        $startDate = (clone $regEndDate)->modify('+1 day');
        $endDate = (clone $startDate)->modify('+'.rand(2, 4).' days');

        return [
            'raid_name' => fake()->words(3, true).' Raid',
            'raid_reg_start_date' => $regStartDate,
            'raid_reg_end_date' => $regEndDate,
            'raid_start_date' => $startDate,
            'raid_end_date' => $endDate,
            'raid_contact' => fake()->safeEmail(),
            'raid_website' => 'https://'.fake()->domainName(),  // Short URL
            'raid_place' => fake()->city(),
            'raid_picture' => 'raids/'.fake()->uuid().'.jpg',
            'RAID_LAT' => fake()->latitude(),
            'RAID_LNG' => fake()->longitude(),
            'club_id' => Club::factory(),
        ];
    }
}
