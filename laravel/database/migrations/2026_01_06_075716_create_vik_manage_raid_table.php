<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vik_manage_raid', function (Blueprint $table) {
            $table->unsignedBigInteger('raid_id');
            $table->unsignedBigInteger('user_id');

            $table->primary(['raid_id', 'user_id']);
            $table->foreign('raid_id')->references('raid_id')->on('vik_raid');
            $table->foreign('user_id')->references('user_id')->on('vik_member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_manage_raid');
    }
};
