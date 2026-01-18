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
        Schema::create('vik_race_manager', function (Blueprint $table) {
            $table->foreignId('race_id')->constrained('vik_race', 'race_id');
            $table->foreignId('user_id')->constrained('vik_member', 'user_id');

            $table->primary(['race_id', 'user_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_race_manager');
    }
};
