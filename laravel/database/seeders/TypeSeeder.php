<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['type_id' => 'INDIVIDUEL', 'type_name' => 'Individuel'],
            ['type_id' => 'EQUIPE', 'type_name' => 'Équipe'],
            ['type_id' => 'RELAIS', 'type_name' => 'Relais'],
        ];

        foreach ($types as $type) {
            DB::table('vik_type')->updateOrInsert(
                ['type_id' => $type['type_id']],
                $type
            );
        }
    }
}
