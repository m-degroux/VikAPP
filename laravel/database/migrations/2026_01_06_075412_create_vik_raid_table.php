<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vik_raid', function (Blueprint $table) {
            $table->id('raid_id');
            $table->string('raid_name', 50);

            $table->dateTime('raid_reg_start_date');
            $table->dateTime('raid_reg_end_date');
            $table->dateTime('raid_start_date');
            $table->dateTime('raid_end_date');

            $table->string('raid_contact', 50);
            $table->string('raid_website', 50)->nullable();
            $table->string('raid_place', 50)->nullable();
            $table->string('raid_picture', 128)->nullable();

            $table->decimal('RAID_LAT', 10, 8)->nullable();
            $table->decimal('RAID_LNG', 11, 8)->nullable();

            $table->foreignId('club_id')->constrained('vik_club', 'club_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_raid');
    }
};
