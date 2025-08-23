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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('aliases')->nullable(); // alternative names/synonyms
            $table->json('nutrition_per_100g')->nullable(); // nutritional data
            $table->string('category')->nullable(); // vegetables, spices, dairy, etc.
            $table->boolean('is_common')->default(false); // frequently used ingredients
            $table->timestamps();
            
            $table->index(['category', 'is_common']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
