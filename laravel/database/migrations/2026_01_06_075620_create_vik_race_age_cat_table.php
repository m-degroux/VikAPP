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
        Schema::create('vik_race_age_cat', function (Blueprint $table) {
            $table->id('age_id');
            $table->foreignId('race_id')->constrained('vik_race', 'race_id');
            $table->decimal('bel_price', 6, 2);

            $table->primary(['age_id', 'race_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_race_age_cat');
    }
};
