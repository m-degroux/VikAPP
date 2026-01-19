<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AgeCategory;
use App\Models\ManageRaid;
use App\Models\Race;
use App\Models\RaceAgeCategory;
use App\Models\RaceManager;
use App\Models\Raid;
use Illuminate\Database\Seeder;

class ManagementSeeder extends Seeder
{
    public function run(): void
    {
        // Only get admins that have corresponding members
        $admins = Admin::whereHas('member')->get();
        $raids = Raid::all();
        $races = Race::all();
        $ageCategories = AgeCategory::all();

        // Skip if no admins with members exist
        if ($admins->isEmpty()) {
            $this->command->warn('⚠️  No admins with members found, skipping management seeding');
            return;
        }

        // Assign admins to manage raids
        foreach ($raids->take(20) as $raid) {
            $admin = $admins->random();
            if (! ManageRaid::where('user_id', $admin->user_id)->where('raid_id', $raid->raid_id)->exists()) {
                ManageRaid::factory()->create([
                    'user_id' => $admin->user_id,
                    'raid_id' => $raid->raid_id,
                ]);
            }
        }

        // Assign admins to manage races
        foreach ($races->take(30) as $race) {
            $admin = $admins->random();
            if (! RaceManager::where('user_id', $admin->user_id)->where('race_id', $race->race_id)->exists()) {
                RaceManager::factory()->create([
                    'user_id' => $admin->user_id,
                    'race_id' => $race->race_id,
                ]);
            }
        }

        // Create race-age category associations - columns are race_id and age_id
        foreach ($races->take(40) as $race) {
            // Each race can have 1-3 age categories
            $catCount = rand(1, 3);

            foreach ($ageCategories->random($catCount) as $category) {
                // Avoid duplicates - column is age_id not age_cat_id
                if (! RaceAgeCategory::where('race_id', $race->race_id)
                    ->where('age_id', $category->age_id)
                    ->exists()) {
                    RaceAgeCategory::factory()->create([
                        'race_id' => $race->race_id,
                        'age_id' => $category->age_id,
                        'bel_price' => fake()->randomFloat(2, 10, 100),
                    ]);
                }
            }
        }
    }
}
