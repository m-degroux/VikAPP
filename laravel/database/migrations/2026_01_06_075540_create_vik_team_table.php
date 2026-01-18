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
        Schema::create('vik_team', function (Blueprint $table) {
            $table->id('team_id')->primary();
            $table->foreignId('race_id')->constrained('vik_race', 'race_id');
            $table->foreignId('user_id')->constrained('vik_member', 'user_id');

            $table->string('team_name', 50);
            $table->string('team_picture', 128)->nullable();
            $table->time('team_time')->nullable();
            $table->unsignedSmallInteger('team_point')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_team');
    }
};
