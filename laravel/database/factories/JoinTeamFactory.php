<?php

namespace Database\Factories;

use App\Models\JoinTeam;
use Illuminate\Database\Eloquent\Factories\Factory;

class JoinTeamFactory extends Factory
{
    protected $model = JoinTeam::class;

    public function definition(): array
    {
        return [
            // user_id and team_id must be provided by seeder
        ];
    }
}
