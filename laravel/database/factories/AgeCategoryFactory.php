<?php

namespace Database\Factories;

use App\Models\AgeCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgeCategoryFactory extends Factory
{
    protected $model = AgeCategory::class;

    public function definition(): array
    {
        return [
            'age_id' => $this->faker->unique()->word(),
            'age_min' => $this->faker->numberBetween(10, 18),
            'age_max' => $this->faker->numberBetween(19, 99),
        ];
    }
}
