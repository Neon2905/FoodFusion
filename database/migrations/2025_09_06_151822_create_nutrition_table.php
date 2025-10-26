<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nutrition', function (Blueprint $table) {
            $table->foreignId('recipe_id')->primary()->constrained('recipes')->cascadeOnDelete();
            $table->decimal('calories', 6, 2)->nullable();
            $table->decimal('fat', 6, 2)->nullable();
            $table->decimal('carbs', 6, 2)->nullable();
            $table->decimal('protein', 6, 2)->nullable();
            $table->decimal('fiber', 6, 2)->nullable();
            $table->decimal('sugar', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrition');
    }
};
