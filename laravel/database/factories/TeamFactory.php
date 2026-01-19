<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        return [
            'team_id' => Str::uuid()->toString(),
            'team_name' => fake()->words(2, true).' Team',
            'team_picture' => 'teams/'.fake()->uuid().'.jpg',
            'team_time' => null,
            'team_point' => null,
            // race_id and user_id must be provided by seeder
        ];
    }
}
