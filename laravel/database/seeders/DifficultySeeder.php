<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DifficultySeeder extends Seeder
{
    public function run(): void
    {
        $difficulties = [
            ['dif_id' => 'FACILE', 'dif_dist_min' => 0, 'dif_dist_max' => 10],
            ['dif_id' => 'MOYEN', 'dif_dist_min' => 10, 'dif_dist_max' => 25],
            ['dif_id' => 'DIFFICILE', 'dif_dist_min' => 25, 'dif_dist_max' => 50],
            ['dif_id' => 'EXPERT', 'dif_dist_min' => 50, 'dif_dist_max' => 100],
        ];

        foreach ($difficulties as $difficulty) {
            DB::table('vik_difficulty')->updateOrInsert(
                ['dif_id' => $difficulty['dif_id']],
                $difficulty
            );
        }
    }
}
