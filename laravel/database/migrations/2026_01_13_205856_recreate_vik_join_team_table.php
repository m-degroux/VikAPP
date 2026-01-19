<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('vik_join_team');

        Schema::create('vik_join_team', function (Blueprint $table) {
            $table->string('team_id');
            $table->unsignedBigInteger('user_id');

            $table->primary(['team_id', 'user_id']);
            $table->foreign('team_id')->references('team_id')->on('vik_team');
            $table->foreign('user_id')->references('user_id')->on('vik_member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_join_team');

        // Recreate the table as it was before, with the incorrect id() for team_id
        Schema::create('vik_join_team', function (Blueprint $table) {
            $table->id('team_id');
            $table->foreignId('user_id')->constrained('vik_member', 'user_id');

            $table->primary(['team_id', 'user_id']);
        });
    }
};
