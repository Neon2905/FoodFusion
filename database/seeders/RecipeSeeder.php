<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\Recipe;
use App\Faker\ImageFakerProvider;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // keep image provider for any direct fake() usage elsewhere
        fake()->addProvider(new ImageFakerProvider(fake()));

        // Create some general recipes (factory will attach steps/tags/etc.)
        Recipe::factory()
            ->count(10)
            ->create();

        // Ensure an 'owner' profile exists
        $owner = Profile::where('username', 'foodfusion')->orWhere('name', 'foodfusion')->first();

        if (!$owner) {
            try {
                $owner = Profile::factory()->create([
                    'name' => 'foodfusion',
                    'username' => 'foodfusion',
                ]);
            } catch (\Throwable $e) {
                // fallback to any existing profile if factory isn't available
                $owner = Profile::inRandomOrder()->first();
            }
        }

        // Create more recipes owned by the 'foodfusion' profile
        Recipe::factory()
            ->count(50)
            ->create(['profile_id' => $owner->id]);
    }
}
