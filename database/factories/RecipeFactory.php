<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Profile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $prep = $this->faker->numberBetween(5, 40);
        $cook = $this->faker->numberBetween(10, 90);
        return [
            'profile_id' => Profile::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'hero_url' => 'https://picsum.photos/seed/' . $this->faker->unique()->word() . '/1200/800',
            'prep_time' => $prep,
            'cook_time' => $cook,
            'total_time' => $prep + $cook,
            'servings' => $this->faker->numberBetween(1, 8),
            'cuisine' => $this->faker->randomElement(['Italian', 'Malaysian', 'American', 'Mexican', 'Indian']),
            'meal_type' => $this->faker->randomElement(['Lunch', 'Dinner', 'Breakfast']),
            'difficulty' => $this->faker->randomElement(['easy', 'medium', 'hard']),
            'comments_enabled' => true,
            'visibility' => 'public',
            'analytics_views' => $this->faker->numberBetween(0, 500),
        ];
    }
}
