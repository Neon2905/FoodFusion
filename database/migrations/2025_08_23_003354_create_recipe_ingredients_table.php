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
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->string('group_id')->nullable(); // e.g., "dough", "filling"
            $table->string('text'); // full text like "2 cups flour, sifted"
            $table->decimal('quantity', 10, 3)->nullable();
            $table->string('unit')->nullable(); // cup, tablespoon, etc.
            $table->boolean('optional')->default(false);
            $table->string('notes')->nullable(); // "sifted", "room temperature"
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index(['recipe_id', 'order']);
            $table->index(['recipe_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};
