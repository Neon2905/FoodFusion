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
        Schema::create('reviews', function (Blueprint $table) {
            $table->foreignId('recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->foreignId('profile_id')->constrained('profiles')->cascadeOnDelete();
            $table->primary(['recipe_id', 'profile_id']);
            $table->tinyInteger('rating')->unsigned()->default(5);
            $table->text('review')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
