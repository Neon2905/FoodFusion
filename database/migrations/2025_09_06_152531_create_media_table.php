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
        // TODO: Review and adjust columns as needed.
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->text('url');
            $table->enum('type', ['image', 'video'])->default('image');
            $table->string('alt')->nullable();
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
