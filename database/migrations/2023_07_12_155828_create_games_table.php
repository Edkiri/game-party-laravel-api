<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('img_url');
            $table->string('url');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
