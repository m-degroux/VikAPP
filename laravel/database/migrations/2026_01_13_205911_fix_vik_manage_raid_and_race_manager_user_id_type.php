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
        Schema::table('vik_manage_raid', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Drop foreign key first
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('user_id')->on('vik_member'); // Re-add foreign key
        });

        Schema::table('vik_race_manager', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Drop foreign key first
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('user_id')->on('vik_member'); // Re-add foreign key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vik_manage_raid', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedSmallInteger('user_id')->change();
            $table->foreign('user_id')->references('user_id')->on('vik_member');
        });

        Schema::table('vik_race_manager', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedSmallInteger('user_id')->change();
            $table->foreign('user_id')->references('user_id')->on('vik_member');
        });
    }
};
