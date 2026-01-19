<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vik_race_manager', function (Blueprint $table) {
            $table->string('race_id');
            $table->unsignedBigInteger('user_id');

            $table->primary(['race_id', 'user_id']);
            $table->foreign('race_id')->references('race_id')->on('vik_race');
            $table->foreign('user_id')->references('user_id')->on('vik_member');
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
