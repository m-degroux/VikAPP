<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vik_team', function (Blueprint $table) {
            $table->string('team_id')->primary();
            $table->string('race_id');
            $table->unsignedBigInteger('user_id');

            $table->string('team_name', 50);
            $table->string('team_picture', 128)->nullable();
            $table->time('team_time')->nullable();
            $table->unsignedSmallInteger('team_point')->nullable();

            $table->foreign('race_id')->references('race_id')->on('vik_race');
            $table->foreign('user_id')->references('user_id')->on('vik_member');
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
