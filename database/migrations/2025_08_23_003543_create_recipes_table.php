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
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text(column: 'description')->nullable();
            // $table->foreignUuid('chef_id')->nullable()->constrained('users')->nullOnDelete();

            $table->text('hero_video_url')->nullable();
            $table->integer('hero_video_duration')->nullable();

            $table->integer('prep_time')->nullable();
            $table->integer('cook_time')->nullable();
            $table->integer('total_time')->nullable();

            $table->integer('servings')->nullable();
            $table->string('cuisine', 100)->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');

            $table->boolean('comments_enabled')->default(true);
            $table->enum('visibility', ['public', 'unlisted', 'private'])->default('public');
            // $table->string('language', 20)->nullable();

            // $table->json('schema_org')->nullable();
            // $table->string('seo_title')->nullable();
            // $table->text('seo_description')->nullable();

            $table->enum('moderation_status', ['approved', 'pending', 'rejected'])->default('pending');

            $table->decimal('rating_avg', 3, 2)->default(0.00);
            $table->integer('rating_count')->default(0);

            $table->integer('analytics_views')->default(0);
            $table->integer('analytics_saves')->default(0);
            $table->integer('analytics_conversions')->default(0);

            $table->timestamps();
        });

        Schema::create('recipe_contributors', function (Blueprint $table) {
            $table->foreignUuid('recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->primary(['recipe_id', 'user_id']);
        });

        Schema::create('recipe_images', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->text('url');
            $table->string('alt')->nullable();
            $table->string('caption')->nullable();
            $table->timestamps();
        });

        Schema::create('recipe_nutrition', function (Blueprint $table) {
            $table->foreignUuid('recipe_id')->primary()->constrained('recipes')->cascadeOnDelete();
            $table->integer('calories')->nullable();
            $table->decimal('fat', 6, 2)->nullable();
            $table->decimal('carbs', 6, 2)->nullable();
            $table->decimal('protein', 6, 2)->nullable();
            $table->decimal('fiber', 6, 2)->nullable();
            $table->decimal('sugar', 6, 2)->nullable();
            $table->decimal('sodium', 6, 2)->nullable();
        });

        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->string('group_id')->nullable();
            $table->text('name');
            $table->decimal('quantity', 8, 2)->nullable();
            $table->string('unit', 50)->nullable();
            $table->uuid('ingredient_ref')->nullable(); // can constrain to ingredients table
            $table->boolean('optional')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('recipe_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->integer('step_order');
            $table->text('description');
            $table->integer('duration')->nullable();
            $table->integer('timer_start')->nullable();
            $table->integer('timer_end')->nullable();
            $table->string('temperature')->nullable();
            $table->timestamps();
        });

        Schema::create('recipe_step_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('step_id')->constrained('recipe_steps')->cascadeOnDelete();
            $table->text('url');
            $table->string('type', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('recipe_tags', function (Blueprint $table) {
            $table->foreignUuid('recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->string('tag', 50);
            $table->primary(['recipe_id', 'tag']);
        });

        Schema::create('recipe_meal_types', function (Blueprint $table) {
            $table->foreignUuid('recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->string('meal_type', 50);
            $table->primary(['recipe_id', 'meal_type']);
        });

        Schema::create('recipe_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->text('modifications')->nullable();
            $table->timestamps();
        });

        Schema::create('related_recipes', function (Blueprint $table) {
            $table->foreignUuid('recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->foreignUuid('related_id')->constrained('recipes')->cascadeOnDelete();
            $table->primary(['recipe_id', 'related_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('recipe_contributors');
        Schema::dropIfExists('recipe_images');
        Schema::dropIfExists('recipe_nutrition');
        Schema::dropIfExists('recipe_ingredients');
        Schema::dropIfExists('recipe_steps');
        Schema::dropIfExists('recipe_step_media');
        Schema::dropIfExists('recipe_tags');
        Schema::dropIfExists('recipe_meal_types');
        Schema::dropIfExists('recipe_variants');
        Schema::dropIfExists('related_recipes');
    }
};
