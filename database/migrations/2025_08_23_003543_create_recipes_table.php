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

            $table->integer('servings');
            $table->string('meal_type');
            $table->string('difficulty')->default('easy');

            // $table->boolean('comments_enabled')->default(true);
            $table->tinyInteger('rating')->default('0'); // TODO: Remove this. Currently, has issue when taken out!
            $table->enum('visibility', ['public', 'unlisted', 'private'])->default('public');
            // $table->string('language', 20)->nullable();

            // $table->integer('analytics_views')->default(0);
            $table->bigInteger('analytics_views')->default(0);

            $table->timestamps();
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
