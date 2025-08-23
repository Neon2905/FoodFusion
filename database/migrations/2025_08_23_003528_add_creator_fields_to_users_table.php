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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'creator', 'admin'])->default('user')->after('email_verified_at');
            $table->boolean('is_verified_creator')->default(false)->after('role');
            $table->text('bio')->nullable()->after('is_verified_creator');
            $table->string('avatar')->nullable()->after('bio');
            $table->json('social_links')->nullable()->after('avatar');
            $table->json('dietary_preferences')->nullable()->after('social_links');
            $table->string('measurement_units')->default('metric')->after('dietary_preferences');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 
                'is_verified_creator', 
                'bio', 
                'avatar', 
                'social_links', 
                'dietary_preferences', 
                'measurement_units'
            ]);
        });
    }
};
