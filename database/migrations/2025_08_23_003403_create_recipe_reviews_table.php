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
        Schema::create('recipe_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('rating'); // 1-5 stars
            $table->text('comment')->nullable();
            $table->json('images')->nullable(); // user photos of their cooking
            $table->boolean('is_verified')->default(false); // verified purchase/cook
            $table->timestamps();
            
            $table->unique(['recipe_id', 'user_id']); // one review per user per recipe
            $table->index(['recipe_id', 'rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_reviews');
    }
};
