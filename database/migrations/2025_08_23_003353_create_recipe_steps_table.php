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
        Schema::create('recipe_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->integer('order');
            $table->text('description');
            $table->json('media')->nullable(); // images/videos for this step
            $table->integer('duration')->nullable(); // estimated time in minutes
            $table->json('timer')->nullable(); // start/end timer object
            $table->string('temperature')->nullable(); // e.g., "180°C"
            $table->timestamps();
            
            $table->index(['recipe_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_steps');
    }
};
