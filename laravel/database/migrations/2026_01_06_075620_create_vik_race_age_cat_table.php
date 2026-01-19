<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vik_race_age_cat', function (Blueprint $table) {
            $table->string('age_id');
            $table->string('race_id');
            $table->decimal('bel_price', 6, 2);

            $table->primary(['age_id', 'race_id']);
            $table->foreign('age_id')->references('age_id')->on('vik_age_category');
            $table->foreign('race_id')->references('race_id')->on('vik_race');
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
