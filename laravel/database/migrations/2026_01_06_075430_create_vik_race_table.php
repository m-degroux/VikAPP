<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vik_race', function (Blueprint $table) {
            $table->string('race_id')->primary();

            $table->unsignedBigInteger('raid_id');
            $table->string('type_id');
            $table->string('dif_id');

            $table->string('race_name', 50);
            $table->time('race_duration');
            $table->decimal('race_length', 4, 2);
            $table->decimal('race_reduction', 3, 2)->nullable();

            $table->dateTime('race_start_date');
            $table->dateTime('race_end_date');

            $table->unsignedSmallInteger('race_min_part');
            $table->unsignedSmallInteger('race_max_part');
            $table->unsignedSmallInteger('race_min_team');
            $table->unsignedSmallInteger('race_max_team');
            $table->unsignedTinyInteger('race_max_part_per_team');
            $table->decimal('race_meal_price', 5, 2)->nullable();

            $table->foreign('raid_id')->references('raid_id')->on('vik_raid');
            $table->foreign('type_id')->references('type_id')->on('vik_type');
            $table->foreign('dif_id')->references('dif_id')->on('vik_difficulty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_race');
    }
};
