<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Strategy:
     * 1. Import insert.sql file FIRST (contains base data)
     * 2. Add supplementary random data with factories (optional)
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        $this->command->newLine();

        // ===================================================================
        // STEP 1: Import insert.sql (PRIORITY - contains real base data)
        // ===================================================================
        $this->command->info('📄 STEP 1: Importing insert.sql...');
        $this->call(SqlFileSeeder::class);
        $this->command->newLine();

        // ===================================================================
        // STEP 2: Add supplementary data (optional - more variety)
        // ===================================================================
        $this->command->info('📊 STEP 2: Adding supplementary data...');
        
        // Add more admins
        $this->call(AdminSeeder::class);
        $this->command->line('   ✓ Additional admins');
        
        // Add more members
        $this->call(MemberSeeder::class);
        $this->command->line('   ✓ Additional members');
        
        // Add more clubs
        $this->call(ClubSeeder::class);
        $this->command->line('   ✓ Additional clubs');
        
        // Add more raids
        $this->call(RaidSeeder::class);
        $this->command->line('   ✓ Additional raids');
        
        // Add more races
        $this->call(RaceSeeder::class);
        $this->command->line('   ✓ Additional races');
        
        // Add more teams
        $this->call(TeamSeeder::class);
        $this->command->line('   ✓ Additional teams');
        
        // Add more participations
        $this->call(ParticipationSeeder::class);
        $this->command->line('   ✓ Additional participations');
        
        // Add management relationships
        $this->call(ManagementSeeder::class);
        $this->command->line('   ✓ Additional management');

        $this->command->newLine();
        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->newLine();

        // Display summary table
        $this->command->table(
            ['Table', 'Count'],
            [
                ['Types', \App\Models\Type::count()],
                ['Difficulties', \App\Models\Difficulty::count()],
                ['Age Categories', \App\Models\AgeCategory::count()],
                ['Admins', \App\Models\Admin::count()],
                ['Members', \App\Models\Member::count()],
                ['Clubs', \App\Models\Club::count()],
                ['Raids', \App\Models\Raid::count()],
                ['Races', \App\Models\Race::count()],
                ['Teams', \App\Models\Team::count()],
                ['Race Participations', \App\Models\JoinRace::count()],
                ['Team Members', \App\Models\JoinTeam::count()],
                ['Managed Raids', \App\Models\ManageRaid::count()],
                ['Race Managers', \App\Models\RaceManager::count()],
            ]
        );
        
        $this->command->newLine();
        $this->command->info('💡 Note: insert.sql data loaded first, then supplementary data added.');
    }
}
