<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('vik_join_race');

        Schema::create('vik_join_race', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('race_id');
            $table->string('team_id');

            $table->unsignedInteger('jrace_licence_num')->nullable();
            $table->string('jrace_pps', 128)->nullable();
            $table->boolean('jrace_presence_valid')->default(false);
            $table->boolean('jrace_payement_valid')->default(false);

            $table->primary(['user_id', 'race_id', 'team_id']);
            $table->foreign('user_id')->references('user_id')->on('vik_member');
            $table->foreign('race_id')->references('race_id')->on('vik_race');
            $table->foreign('team_id')->references('team_id')->on('vik_team');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_join_race');

        // Recreate the table as it was before, with unsignedSmallInteger for user_id
        Schema::create('vik_join_race', function (Blueprint $table) {
            $table->unsignedSmallInteger('user_id');
            $table->unsignedInteger('race_id');
            $table->unsignedInteger('team_id');

            $table->unsignedInteger('jrace_licence_num')->nullable();
            $table->string('jrace_pps', 128)->nullable();
            $table->boolean('jrace_presence_valid')->default(false);
            $table->boolean('jrace_payement_valid')->default(false);

            $table->primary(['user_id', 'race_id', 'team_id']);
        });
    }
};
