<?php

namespace Database\Factories;

use App\Models\RaceAgeCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class RaceAgeCategoryFactory extends Factory
{
    protected $model = RaceAgeCategory::class;

    public function definition(): array
    {
        return [
            // race_id and age_cat_id must be provided by seeder
        ];
    }
}
