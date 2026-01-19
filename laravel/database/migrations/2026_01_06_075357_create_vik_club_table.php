<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vik_club', function (Blueprint $table) {
            $table->id('club_id');
            $table->foreignId('user_id')->constrained('vik_member', 'user_id');
            $table->string('club_name', 50);
            $table->string('club_address', 50);
            $table->boolean('club_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_club');
    }
};
