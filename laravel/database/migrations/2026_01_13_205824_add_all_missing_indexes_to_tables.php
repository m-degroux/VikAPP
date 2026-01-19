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
        Schema::table('vik_member', function (Blueprint $table) {
            $table->index('user_username');
        });

        Schema::table('vik_raid', function (Blueprint $table) {
            $table->index('raid_start_date');
            $table->index('raid_end_date');
            $table->index('raid_place');
        });

        Schema::table('vik_race', function (Blueprint $table) {
            $table->index('race_start_date');
            $table->index('race_end_date');
        });

        Schema::table('vik_team', function (Blueprint $table) {
            $table->index('team_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vik_member', function (Blueprint $table) {
            $table->dropIndex(['user_username']);
        });

        Schema::table('vik_raid', function (Blueprint $table) {
            $table->dropIndex(['raid_start_date']);
            $table->dropIndex(['raid_end_date']);
            $table->dropIndex(['raid_place']);
        });

        Schema::table('vik_race', function (Blueprint $table) {
            $table->dropIndex(['race_start_date']);
            $table->dropIndex(['race_end_date']);
        });

        Schema::table('vik_team', function (Blueprint $table) {
            $table->dropIndex(['team_name']);
        });
    }
};
