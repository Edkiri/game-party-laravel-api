<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->unsignedBigInteger('game_id');
            $table->foreign('game_id')->references('id')->on('games');

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
