<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        // Create test user
        Member::factory()->create([
            'user_username' => 'testuser',
            'user_password' => Hash::make('password'),
            'mem_email' => 'test@vikingraids.com',
            'mem_name' => 'User',
            'mem_firstname' => 'Test',
        ]);

        // Create club manager
        Member::factory()->create([
            'user_username' => 'clubmanager',
            'user_password' => Hash::make('password'),
            'mem_email' => 'manager@vikingraids.com',
            'mem_name' => 'Manager',
            'mem_firstname' => 'Club',
        ]);

        // Create 20 random members
        Member::factory(20)->create();
    }
}
