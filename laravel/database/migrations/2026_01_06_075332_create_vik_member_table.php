<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vik_member', function (Blueprint $table) {
            $table->id('user_id');
            $table->unsignedBigInteger('club_id')->nullable();

            $table->string('mem_name', 50);
            $table->string('mem_firstname', 50);
            $table->date('mem_birthdate');
            $table->string('mem_adress', 128);
            $table->string('mem_phone', 10);
            $table->string('mem_email', 128);
            $table->string('mem_default_licence')->nullable();

            $table->string('user_username', 50);
            $table->string('user_password', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vik_member');
    }
};
