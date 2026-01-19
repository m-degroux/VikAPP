<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create superadmin (must create member first, then admin)
        $superadminMember = \App\Models\Member::factory()->create([
            'user_username' => 'superadmin',
            'user_password' => Hash::make('password'),
        ]);
        
        Admin::factory()->create([
            'user_id' => $superadminMember->user_id,
            'user_username' => 'superadmin',
            'user_password' => Hash::make('password'),
        ]);

        // Create regular admin
        $adminMember = \App\Models\Member::factory()->create([
            'user_username' => 'admin',
            'user_password' => Hash::make('password'),
        ]);
        
        Admin::factory()->create([
            'user_id' => $adminMember->user_id,
            'user_username' => 'admin',
            'user_password' => Hash::make('password'),
        ]);

        // Create 3 more random admins
        for ($i = 0; $i < 3; $i++) {
            $member = \App\Models\Member::factory()->create();
            Admin::factory()->create([
                'user_id' => $member->user_id,
                'user_username' => $member->user_username,
                'user_password' => $member->user_password,
            ]);
        }
    }
}
