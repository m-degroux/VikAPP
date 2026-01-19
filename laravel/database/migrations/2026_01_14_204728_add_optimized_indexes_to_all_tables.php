<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vik_member', function (Blueprint $table) {
            $table->index('club_id');
            $table->index('mem_email');
        });

        Schema::table('vik_raid', function (Blueprint $table) {
            $table->index('club_id');
            $table->index(['raid_lat', 'raid_lng']);
        });

        Schema::table('vik_race', function (Blueprint $table) {
            $table->index('raid_id');
            $table->index('type_id');
            $table->index('dif_id');
        });

        Schema::table('vik_team', function (Blueprint $table) {
            $table->index('race_id');
            $table->index('user_id');
        });

        Schema::table('vik_join_race', function (Blueprint $table) {
            $table->index('team_id');
        });

        Schema::table('vik_manage_raid', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('raid_id');
        });

        Schema::table('vik_race_manager', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('race_id');
        });

        Schema::table('vik_race_age_cat', function (Blueprint $table) {
            $table->index('race_id');
            $table->index('age_id');
        });

        Schema::table('vik_join_team', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('team_id');
        });
    }

    public function down(): void
    {
        Schema::table('vik_member', function (Blueprint $table) {
            $table->dropIndex(['club_id']);
            $table->dropIndex(['mem_email']);
        });

        Schema::table('vik_raid', function (Blueprint $table) {
            $table->dropIndex(['club_id']);
            $table->dropIndex(['raid_lat', 'raid_lng']);
        });

        Schema::table('vik_race', function (Blueprint $table) {
            $table->dropIndex(['raid_id']);
            $table->dropIndex(['type_id']);
            $table->dropIndex(['dif_id']);
        });

        Schema::table('vik_team', function (Blueprint $table) {
            $table->dropIndex(['race_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('vik_join_race', function (Blueprint $table) {
            $table->dropIndex(['team_id']);
        });

        Schema::table('vik_manage_raid', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['raid_id']);
        });

        Schema::table('vik_race_manager', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['race_id']);
        });

        Schema::table('vik_race_age_cat', function (Blueprint $table) {
            $table->dropIndex(['race_id']);
            $table->dropIndex(['age_id']);
        });

        Schema::table('vik_join_team', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['team_id']);
        });
    }
};
