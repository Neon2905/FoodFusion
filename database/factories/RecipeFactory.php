<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\RecipeStep;
use App\Models\Ingredient;
use App\Models\Media;
use App\Models\Nutrition;
use App\Models\Review;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Faker\ImageFakerProvider;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = $this->faker->sentence(3);

        // generate prep and cook times so we can compute total_time
        $prep = $this->faker->numberBetween(5, 60);
        $cook = $this->faker->numberBetween(0, 180);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'description' => $this->faker->paragraph(),
            // assign a random existing profile if available; otherwise null (seeder may override)
            'profile_id' => Profile::inRandomOrder()->value('id') ?? null,

            'servings' => $this->faker->numberBetween(1, 8),
            'difficulty' => $this->faker->randomElement(['easy', 'medium', 'hard']),
            'meal_type' => $this->faker->randomElement(['breakfast', 'lunch', 'dinner', 'snack']),
            'prep_time' => $prep,
            'cook_time' => $cook,
            'total_time' => $prep + $cook,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Recipe $recipe) {
            // local tag choices and ingredient template to mirror previous seeder
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

            // Steps
            $steps = [];
            $stepsCount = $this->faker->numberBetween(5, 15);
            for ($i = 1; $i <= $stepsCount; $i++) {
                $steps[] = [
                    'recipe_id' => $recipe->id,
                    'step_order' => $i,
                    'title' => $this->faker->word(),
                    'instruction' => $this->faker->sentence(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            RecipeStep::insert($steps);

            // Tags (insert via relationship to preserve original approach)
            $tags = [];
            $tagCount = $this->faker->numberBetween(1, 10);
            for ($i = 1; $i <= $tagCount; $i++) {
                $tags[] = [
                    'recipe_id' => $recipe->id,
                    'name' => $this->faker->randomElement($fakeTags),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (method_exists($recipe, 'tags')) {
                $recipe->tags()->insert($tags);
            }

            // Tips
            $tips = [];
            $tipCount = $this->faker->numberBetween(1, 6);
            for ($i = 1; $i <= $tipCount; $i++) {
                $tips[] = [
                    'recipe_id' => $recipe->id,
                    'content' => $this->faker->sentence(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (method_exists($recipe, 'tips')) {
                $recipe->tips()->insert($tips);
            }

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

            fake()->addProvider(new ImageFakerProvider($this->faker));
            // Create Media Collection (use faker imageUrl)
            $mediaCount = $this->faker->numberBetween(5, 15);
            for ($j = 0; $j < $mediaCount; $j++) {
                Media::create([
                    'recipe_id' => $recipe->id,
                    'url' => fake()->imageUrl(800, 600, 'food'),
                    'type' => 'image',
                    'alt' => $recipe->title . ' extra ' . ($j + 1),
                ]);
            }
            
            // Add hero image
            if (empty($recipe->hero_url)) {
                $firstMedia = $recipe->media()->orderBy('id')->first();
                if ($firstMedia) {
                    $url = $firstMedia->url ?? $firstMedia->path ?? null;
                    if ($url) {
                        $recipe->hero_url = $url;
                        $recipe->saveQuietly();
                    }
                }
            }

            // Nutrition
            Nutrition::create([
                'recipe_id' => $recipe->id,
                'calories' => $this->faker->numberBetween(100, 800),
                'fat' => $this->faker->randomFloat(2, 0, 50),
                'carbs' => $this->faker->randomFloat(2, 0, 150),
                'protein' => $this->faker->randomFloat(2, 0, 80),
            ]);

            // Reviews from random profiles
            $profiles = Profile::query()->inRandomOrder()->limit(random_int(1, 8))->get();
            foreach ($profiles as $profile) {
                Review::create([
                    'recipe_id' => $recipe->id,
                    'profile_id' => $profile->id,
                    'rating' => $this->faker->numberBetween(3, 5),
                    'review' => $this->faker->sentence(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
