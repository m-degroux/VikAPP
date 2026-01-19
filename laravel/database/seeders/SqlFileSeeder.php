<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SqlFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Imports data from insert.sql file at project root.
     */
    public function run(): void
    {
        $sqlFilePath = base_path('insert.sql');

        if (!File::exists($sqlFilePath)) {
            $this->command->error('❌ File insert.sql not found at project root!');
            return;
        }

        $this->command->info('📄 Reading insert.sql...');
        
        // Read the SQL file
        $sql = File::get($sqlFilePath);
        
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Split SQL by semicolons and execute each statement
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            fn($stmt) => !empty($stmt) && !str_starts_with($stmt, '--')
        );
        
        $this->command->info("📊 Executing " . count($statements) . " SQL statements...");
        
        $executed = 0;
        $skipped = 0;
        
        foreach ($statements as $statement) {
            // Skip comments and empty statements
            if (empty(trim($statement)) || str_starts_with(trim($statement), '--')) {
                continue;
            }
            
            try {
                DB::statement($statement);
                $executed++;
            } catch (\Exception $e) {
                // Skip if already exists or other non-critical error
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    $skipped++;
                } else {
                    $this->command->warn("⚠️  Warning: " . $e->getMessage());
                }
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info("✅ SQL import completed!");
        $this->command->line("   - Executed: {$executed} statements");
        if ($skipped > 0) {
            $this->command->line("   - Skipped: {$skipped} duplicates");
        }
    }
}
