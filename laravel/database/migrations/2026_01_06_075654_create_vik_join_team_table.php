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
        Schema::create('vik_join_team', function (Blueprint $table) {
            $table->id('team_id');
            $table->foreignId('user_id')->constrained('vik_member', 'user_id');

            $table->primary(['team_id', 'user_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_join_team');
    }
};
