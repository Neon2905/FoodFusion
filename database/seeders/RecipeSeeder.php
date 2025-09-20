<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Profile;
use App\Models\Recipe;
use App\Models\RecipeStep;
use App\Models\Ingredient;
use App\Models\Media;
use App\Models\Nutrition;
use App\Models\Review;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Recipe::factory()
            ->count(10)
            ->create()
            ->each(function (Recipe $recipe) {
                // Steps
                $stepsCount = fake()->numberBetween(5, 15);
                for ($i = 1; $i <= $stepsCount; $i++) {
                    RecipeStep::create([
                        'recipe_id' => $recipe->id,
                        'step_order' => $i,
                        'title' => fake()->word(),
                        'instruction' => fake()->sentence(),
                        'duration' => fake()->numberBetween(1, 30),
                    ]);
                }

                $fakeTags = [
                    'vegan',
                    'gluten-free',
                    'spicy',
                    'quick',
                    'healthy',
                    'dessert',
                    'breakfast',
                    'dinner',
                    'snack',
                    'low-carb',
                    'high-protein',
                    'vegetarian',
                    'easy',
                    'family',
                    'holiday',
                    'comfort',
                    'classic',
                    'fresh',
                    'seasonal',
                    'grilled'
                ];

                // Tags
                for ($i = 1; $i <= fake()->numberBetween(1, 10); $i++) {
                    $recipe->tags()->create([
                        'name' => fake()->randomElement($fakeTags),
                    ]);
                }

                // Tips
                $tipCount = fake()->numberBetween(1, 6);
                for ($i = 1; $i <= $tipCount; $i++) {
                    $recipe->tips()->create([
                        'content' => fake()->sentence(),
                    ]);
                }

                // Ingredients
                $ingredients = [
                    'flour' => [1, 'cup'],
                    'eggs' => [2],
                    'sugar' => [1 / 2, 'cup'],
                    'salt' => [1, 'tbsp'],
                    'olive oil' => [1, 'tbsp'],
                    'milk' => [1, 'cup'],
                    'butter' => [2, 'tbsp'],
                    'baking powder' => [1, 'tsp'],
                    'vanilla extract' => [1, 'tsp'],
                    'chocolate chips' => [1 / 2, 'cup'],
                    'cheese' => [1 / 2, 'cup'],
                    'tomato' => [1],
                    'onion' => [1],
                    'garlic' => [2, 'cloves'],
                    'pepper' => [1, 'tsp'],
                    'spinach' => [1, 'cup'],
                    'chicken breast' => [1],
                    'lemon juice' => [1, 'tbsp'],
                    'parsley' => [2, 'tbsp'],
                    'mushrooms' => [1 / 2, 'cup'],
                ];
                foreach ($ingredients as $name => $ing) {
                    Ingredient::create([
                        'recipe_id' => $recipe->id,
                        'name' => $name,
                        'quantity' => $ing[0],
                        'unit' => $ing[1] ?? null,
                    ]);
                }

                // Media
                Media::create([
                    'recipe_id' => $recipe->id,
                    'url' => $recipe->hero_url,
                    'type' => 'image',
                    'alt' => $recipe->title,
                ]);

                // Nutrition
                Nutrition::create([
                    'recipe_id' => $recipe->id,
                    'calories' => fake()->numberBetween(100, 800),
                    'fat' => fake()->randomFloat(2, 0, 50),
                    'carbs' => fake()->randomFloat(2, 0, 150),
                    'protein' => fake()->randomFloat(2, 0, 80),
                ]);

                // Reviews (attach random profiles or author)
                $profiles = Profile::inRandomOrder()->limit(5)->get();
                foreach ($profiles as $profile) {
                    Review::create([
                        'recipe_id' => $recipe->id,
                        'profile_id' => $profile->id,
                        'rating' => fake()->numberBetween(3, 5),
                        'review' => fake()->sentence(),
                    ]);
                }
            });
    }
}
