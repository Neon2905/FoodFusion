<?php

namespace Database\Seeders;

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
        fake()->addProvider(new \App\Faker\ImageFakerProvider(fake()));
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

        $ingredients = [
            'flour' => [1, 'cup'],
            'eggs' => [2],
            'sugar' => [0.5, 'cup'],
            'salt' => [1, 'tbsp'],
            'olive oil' => [1, 'tbsp'],
            'milk' => [1, 'cup'],
            'butter' => [2, 'tbsp'],
            'baking powder' => [1, 'tsp'],
            'vanilla extract' => [1, 'tsp'],
            'chocolate chips' => [0.5, 'cup'],
            'cheese' => [0.5, 'cup'],
            'tomato' => [1],
            'onion' => [1],
            'garlic' => [2, 'cloves'],
            'pepper' => [1, 'tsp'],
            'spinach' => [1, 'cup'],
            'chicken breast' => [1],
            'lemon juice' => [1, 'tbsp'],
            'parsley' => [2, 'tbsp'],
            'mushrooms' => [0.5, 'cup'],
        ];

        Recipe::factory()
            ->count(10)
            ->create()
            ->each(function (Recipe $recipe) use ($fakeTags, $ingredients) {
                // Steps
                $steps = [];
                $stepsCount = fake()->numberBetween(5, 15);
                for ($i = 1; $i <= $stepsCount; $i++) {
                    $steps[] = [
                        'recipe_id' => $recipe->id,
                        'step_order' => $i,
                        'title' => fake()->word(),
                        'instruction' => fake()->sentence(),
                    ];
                }
                RecipeStep::insert($steps);

                // Tags
                $tags = [];
                $tagCount = fake()->numberBetween(1, 10);
                for ($i = 1; $i <= $tagCount; $i++) {
                    $tags[] = [
                        'recipe_id' => $recipe->id,
                        'name' => fake()->randomElement($fakeTags),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $recipe->tags()->insert($tags);

                // Tips
                $tips = [];
                $tipCount = fake()->numberBetween(1, 6);
                for ($i = 1; $i <= $tipCount; $i++) {
                    $tips[] = [
                        'recipe_id' => $recipe->id,
                        'content' => fake()->sentence(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $recipe->tips()->insert($tips);

                // Ingredients
                $ings = [];
                foreach ($ingredients as $name => $ing) {
                    $ings[] = [
                        'recipe_id' => $recipe->id,
                        'name' => $name,
                        'quantity' => $ing[0],
                        'unit' => $ing[1] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                Ingredient::insert($ings);

                // Create Media Collection
                $mediaCount = fake()->numberBetween(5, 15);
                for ($j = 0; $j < $mediaCount; $j++) {
                    Media::create([
                        'recipe_id' => $recipe->id,
                        'url' => fake()->imageUrl(category: 'food'),
                        'type' => 'image',
                        'alt' => $recipe->title . ' extra ' . ($j + 1),
                    ]);
                }

                // Nutrition
                Nutrition::create([
                    'recipe_id' => $recipe->id,
                    'calories' => fake()->numberBetween(100, 800),
                    'fat' => fake()->randomFloat(2, 0, 50),
                    'carbs' => fake()->randomFloat(2, 0, 150),
                    'protein' => fake()->randomFloat(2, 0, 80),
                ]);

                $profiles = Profile::query()->inRandomOrder()->limit(random_int(1, 8))->get();
                // Reviews
                foreach ($profiles as $profile) {
                    Review::create([
                        'recipe_id' => $recipe->id,
                        'profile_id' => $profile->id,
                        'rating' => fake()->numberBetween(3, 5),
                        'review' => fake()->sentence(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
    }
}
