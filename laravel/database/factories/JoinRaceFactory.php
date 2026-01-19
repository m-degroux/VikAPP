<?php

namespace Database\Factories;

use App\Models\JoinRace;
use Illuminate\Database\Eloquent\Factories\Factory;

class JoinRaceFactory extends Factory
{
    protected $model = JoinRace::class;

    public function definition(): array
    {
        return [
            // user_id, race_id, team_id must be provided by seeder
            'jrace_licence_num' => fake()->numberBetween(100000, 999999),
            'jrace_pps' => fake()->numerify('PPS-####'),
            'jrace_presence_valid' => fake()->boolean(80),
            'jrace_payement_valid' => fake()->boolean(85),
        ];
    }

    public function validated(): static
    {
        return $this->state(fn (array $attributes) => [
            'jrace_presence_valid' => true,
            'jrace_payement_valid' => true,
        ]);
    }
}
