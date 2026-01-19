<?php

namespace Database\Factories;

use App\Models\Difficulty;
use Illuminate\Database\Eloquent\Factories\Factory;

class DifficultyFactory extends Factory
{
    protected $model = Difficulty::class;

    public function definition(): array
    {
        return [
            'dif_id' => $this->faker->unique()->word(),
            'dif_dist_min' => $this->faker->numberBetween(0, 10),
            'dif_dist_max' => $this->faker->numberBetween(11, 50),
        ];
    }
}
