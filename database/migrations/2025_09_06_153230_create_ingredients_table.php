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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('quantity', 8, 2)->nullable();
            $table->string('unit', 50)->nullable();
            // $table->uuid('ingredient_ref')->nullable(); // can constrain to ingredients table
            $table->boolean('is_optional')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();
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
