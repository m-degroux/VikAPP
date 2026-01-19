<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vik_difficulty', function (Blueprint $table) {
            $table->string('dif_id')->primary();
            $table->decimal('dif_dist_min', 6, 2)->nullable();
            $table->decimal('dif_dist_max', 6, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_difficulty');
    }
};
