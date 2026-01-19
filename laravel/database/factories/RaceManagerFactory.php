<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Race;
use App\Models\RaceManager;
use Illuminate\Database\Eloquent\Factories\Factory;

class RaceManagerFactory extends Factory
{
    protected $model = RaceManager::class;

    public function definition(): array
    {
        return [
            'user_id' => Admin::factory(),
            'race_id' => Race::factory(),
        ];
    }
}
