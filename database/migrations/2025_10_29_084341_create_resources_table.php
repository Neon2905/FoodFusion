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
        Schema::create('resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('profile_id')->nullable()->constrained('profiles')->nullOnDelete();
            $table->string('slug')->unique()->nullable();
            $table->string('title');
            $table->enum('category', ['culinary', 'educational'])->default('culinary');
            $table->enum('type', ['card', 'tutorial', 'video', 'technique'])->default('tutorial');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->integer('duration')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('published')->default(true);
            $table->integer('download_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
