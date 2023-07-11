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
        Schema::create('species', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('classification')->nullable();
            $table->string('designation')->nullable();
            $table->string('average_height')->nullable();
            $table->string('skin_colors')->nullable();
            $table->string('hair_colors')->nullable();
            $table->string('eye_colors')->nullable();
            $table->string('average_lifespan')->nullable();
            $table->string('language')->nullable();
            $table->unsignedBigInteger('movie_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('species');
    }
};
