<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\ManageRaid;
use App\Models\Raid;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManageRaidFactory extends Factory
{
    protected $model = ManageRaid::class;

    public function definition(): array
    {
        return [
            'user_id' => Admin::factory(),
            'raid_id' => Raid::factory(),
        ];
    }
}
