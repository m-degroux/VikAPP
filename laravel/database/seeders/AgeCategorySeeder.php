<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['age_id' => 'JUNIOR', 'age_min' => 10, 'age_max' => 17],
            ['age_id' => 'SENIOR', 'age_min' => 18, 'age_max' => 39],
            ['age_id' => 'VETERAN', 'age_min' => 40, 'age_max' => 59],
            ['age_id' => 'MASTER', 'age_min' => 60, 'age_max' => 99],
        ];

        foreach ($categories as $category) {
            DB::table('vik_age_category')->updateOrInsert(
                ['age_id' => $category['age_id']],
                $category
            );
        }
    }
}
