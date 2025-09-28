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
        Schema::create('recipes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('profile_id')->constrained('profiles')->cascadeOnDelete();
            $table->string('slug')->unique();

            $table->string('title');
            $table->text('description')->nullable();

            $table->text('hero_url')->nullable();

            $table->integer('prep_time')->nullable();
            $table->integer('cook_time')->nullable();
            $table->integer('total_time')->nullable();

            $table->integer('servings')->nullable();
            $table->string('cuisine', 100)->nullable(); //TODO:
            $table->string('meal_type')->nullable(); //TODO:
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');

            // $table->boolean('comments_enabled')->default(true);
            $table->enum('visibility', ['public', 'unlisted', 'private'])->default('public');
            // $table->string('language', 20)->nullable();

            $table->integer('rating')->default(0);

            // $table->integer('analytics_views')->default(0);
            $table->bigInteger('analytics_views')->default(0);

            $table->timestamps();
        });

        // Schema::create('recipe_meal_types', function (Blueprint $table) {
        //     $table->foreignUuid('recipe_id')->constrained('recipes')->cascadeOnDelete();
        //     $table->string('meal_type', 50);
        //     $table->primary(['recipe_id', 'meal_type']);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
        // Schema::dropIfExists('comments');
        // Schema::dropIfExists('recipe_meal_types');
    }
};
