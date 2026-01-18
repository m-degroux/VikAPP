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
            $table->foreign('club_id')->references('club_id')->on('vik_club');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vik_team', function (Blueprint $table) {
            $table->dropForeign(['race_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('vik_race', function (Blueprint $table) {
            $table->dropForeign(['raid_id']);
            $table->dropForeign(['type_id']);
            $table->dropForeign(['dif_id']);
        });

        Schema::table('vik_club', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('vik_member', function (Blueprint $table) {
            $table->dropForeign(['club_id']);
        });
    }
};
