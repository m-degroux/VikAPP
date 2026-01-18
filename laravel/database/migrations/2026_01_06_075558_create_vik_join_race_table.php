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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_join_race');
    }
};
