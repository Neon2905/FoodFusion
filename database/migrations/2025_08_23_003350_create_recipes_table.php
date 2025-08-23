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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('slug')->unique();
            $table->text('description');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // chef/creator
            $table->json('images')->nullable(); // Array of image objects
            $table->json('hero_video')->nullable(); // Video object
            $table->integer('prep_time')->nullable(); // in minutes
            $table->integer('cook_time')->nullable(); // in minutes
            $table->integer('total_time')->nullable(); // computed
            $table->integer('servings')->default(1);
            $table->json('nutrition')->nullable(); // nutrition facts object
            $table->json('tags')->nullable(); // array of tags
            $table->string('cuisine')->nullable();
            $table->json('meal_type')->nullable(); // array: breakfast, lunch, dinner
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->decimal('rating_avg', 3, 2)->default(0.00);
            $table->integer('rating_count')->default(0);
            $table->boolean('comments_enabled')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->enum('visibility', ['public', 'unlisted', 'private'])->default('public');
            $table->string('language', 5)->default('en');
            $table->json('related_recipes')->nullable(); // array of recipe IDs
            $table->json('schema_org')->nullable(); // JSON-LD schema
            $table->json('metadata')->nullable(); // SEO metadata
            $table->enum('moderation_status', ['approved', 'pending', 'rejected'])->default('pending');
            $table->json('analytics')->nullable(); // views, saves, conversions
            $table->timestamps();
            
            $table->index(['published_at', 'visibility', 'moderation_status']);
            $table->index(['user_id', 'published_at']);
            $table->index(['cuisine', 'difficulty']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
