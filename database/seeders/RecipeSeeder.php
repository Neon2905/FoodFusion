<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Ingredient;
use App\Models\RecipeStep;
use App\Models\RecipeIngredient;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creators = User::whereIn('role', ['creator', 'admin'])->get();
        $ingredients = Ingredient::all();

        // Recipe 1: Classic Spaghetti Bolognese
        $recipe1 = Recipe::create([
            'title' => 'Classic Spaghetti Bolognese',
            'subtitle' => 'Traditional Italian comfort food',
            'description' => 'A rich and hearty meat sauce served over perfectly cooked spaghetti. This classic Italian recipe has been passed down through generations.',
            'user_id' => $creators->where('name', 'Chef Julia Child')->first()->id,
            'prep_time' => 20,
            'cook_time' => 120,
            'servings' => 4,
            'cuisine' => 'Italian',
            'difficulty' => 'medium',
            'tags' => ['pasta', 'italian', 'comfort-food', 'meat'],
            'meal_type' => ['dinner'],
            'visibility' => 'public',
            'moderation_status' => 'approved',
            'published_at' => now()->subDays(5),
            'rating_avg' => 4.8,
            'rating_count' => 42,
            'analytics' => ['views' => 234, 'saves' => 18, 'conversions' => 12],
        ]);

        // Add ingredients for Bolognese
        $bologneseIngredients = [
            ['ingredient' => 'Ground beef', 'quantity' => 500, 'unit' => 'g', 'text' => '500g ground beef'],
            ['ingredient' => 'Pasta', 'quantity' => 400, 'unit' => 'g', 'text' => '400g spaghetti'],
            ['ingredient' => 'Onion', 'quantity' => 1, 'unit' => 'large', 'text' => '1 large onion, diced'],
            ['ingredient' => 'Garlic', 'quantity' => 3, 'unit' => 'cloves', 'text' => '3 cloves garlic, minced'],
            ['ingredient' => 'Tomato', 'quantity' => 400, 'unit' => 'g', 'text' => '400g canned tomatoes'],
            ['ingredient' => 'Olive oil', 'quantity' => 2, 'unit' => 'tbsp', 'text' => '2 tablespoons olive oil'],
            ['ingredient' => 'Salt', 'quantity' => null, 'unit' => null, 'text' => 'Salt to taste'],
            ['ingredient' => 'Black pepper', 'quantity' => null, 'unit' => null, 'text' => 'Black pepper to taste'],
        ];

        foreach ($bologneseIngredients as $index => $ingredientData) {
            $ingredient = $ingredients->where('name', $ingredientData['ingredient'])->first();
            if ($ingredient) {
                RecipeIngredient::create([
                    'recipe_id' => $recipe1->id,
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $ingredientData['quantity'],
                    'unit' => $ingredientData['unit'],
                    'text' => $ingredientData['text'],
                    'order' => $index + 1,
                ]);
            }
        }

        // Add steps for Bolognese
        $bologneseSteps = [
            'Heat olive oil in a large pan over medium heat.',
            'Add diced onion and cook until translucent, about 5 minutes.',
            'Add minced garlic and cook for another minute.',
            'Add ground beef and cook until browned, breaking it up with a spoon.',
            'Add canned tomatoes and season with salt and pepper.',
            'Simmer for 1-2 hours, stirring occasionally.',
            'Meanwhile, cook spaghetti according to package directions.',
            'Serve the sauce over the cooked pasta.',
        ];

        foreach ($bologneseSteps as $index => $step) {
            RecipeStep::create([
                'recipe_id' => $recipe1->id,
                'order' => $index + 1,
                'description' => $step,
                'duration' => $index < 6 ? 5 : null,
            ]);
        }

        // Recipe 2: Chicken Caesar Salad
        $recipe2 = Recipe::create([
            'title' => 'Grilled Chicken Caesar Salad',
            'subtitle' => 'Fresh and protein-packed',
            'description' => 'A classic Caesar salad elevated with perfectly grilled chicken breast, crispy croutons, and a creamy homemade dressing.',
            'user_id' => $creators->where('name', 'Chef Gordon Ramsay')->first()->id,
            'prep_time' => 15,
            'cook_time' => 15,
            'servings' => 2,
            'cuisine' => 'American',
            'difficulty' => 'easy',
            'tags' => ['salad', 'healthy', 'protein', 'quick'],
            'meal_type' => ['lunch', 'dinner'],
            'visibility' => 'public',
            'moderation_status' => 'approved',
            'published_at' => now()->subDays(2),
            'rating_avg' => 4.6,
            'rating_count' => 28,
            'analytics' => ['views' => 156, 'saves' => 24, 'conversions' => 8],
        ]);

        // Add ingredients for Caesar Salad
        $caesarIngredients = [
            ['ingredient' => 'Chicken breast', 'quantity' => 2, 'unit' => 'pieces', 'text' => '2 chicken breasts'],
            ['ingredient' => 'Spinach', 'quantity' => 200, 'unit' => 'g', 'text' => '200g fresh spinach leaves'],
            ['ingredient' => 'Cheese', 'quantity' => 50, 'unit' => 'g', 'text' => '50g parmesan cheese, grated'],
            ['ingredient' => 'Bread', 'quantity' => 4, 'unit' => 'slices', 'text' => '4 slices bread for croutons'],
            ['ingredient' => 'Olive oil', 'quantity' => 3, 'unit' => 'tbsp', 'text' => '3 tablespoons olive oil'],
            ['ingredient' => 'Garlic', 'quantity' => 2, 'unit' => 'cloves', 'text' => '2 cloves garlic, minced'],
            ['ingredient' => 'Salt', 'quantity' => null, 'unit' => null, 'text' => 'Salt and pepper to taste'],
        ];

        foreach ($caesarIngredients as $index => $ingredientData) {
            $ingredient = $ingredients->where('name', $ingredientData['ingredient'])->first();
            if ($ingredient) {
                RecipeIngredient::create([
                    'recipe_id' => $recipe2->id,
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $ingredientData['quantity'],
                    'unit' => $ingredientData['unit'],
                    'text' => $ingredientData['text'],
                    'order' => $index + 1,
                ]);
            }
        }

        // Add steps for Caesar Salad
        $caesarSteps = [
            'Season chicken breasts with salt and pepper.',
            'Grill chicken for 6-7 minutes per side until cooked through.',
            'Let chicken rest for 5 minutes, then slice.',
            'Cut bread into cubes and toss with olive oil and garlic.',
            'Toast bread cubes in oven until golden.',
            'Arrange spinach on plates.',
            'Top with sliced chicken, croutons, and parmesan cheese.',
            'Drizzle with Caesar dressing and serve.',
        ];

        foreach ($caesarSteps as $index => $step) {
            RecipeStep::create([
                'recipe_id' => $recipe2->id,
                'order' => $index + 1,
                'description' => $step,
                'duration' => $index < 3 ? 7 : 2,
            ]);
        }

        // Recipe 3: Chocolate Chip Cookies
        $recipe3 = Recipe::create([
            'title' => 'Perfect Chocolate Chip Cookies',
            'subtitle' => 'Crispy edges, chewy center',
            'description' => 'The ultimate chocolate chip cookie recipe that delivers the perfect balance of crispy edges and chewy centers every time.',
            'user_id' => $creators->where('name', 'Baker Mike')->first()->id,
            'prep_time' => 15,
            'cook_time' => 12,
            'servings' => 24,
            'cuisine' => 'American',
            'difficulty' => 'easy',
            'tags' => ['cookies', 'dessert', 'chocolate', 'baking'],
            'meal_type' => ['dessert'],
            'visibility' => 'public',
            'moderation_status' => 'approved',
            'published_at' => now()->subDays(1),
            'rating_avg' => 4.9,
            'rating_count' => 67,
            'analytics' => ['views' => 423, 'saves' => 52, 'conversions' => 31],
        ]);

        // Add ingredients for Cookies
        $cookieIngredients = [
            ['ingredient' => 'All-purpose flour', 'quantity' => 225, 'unit' => 'g', 'text' => '225g all-purpose flour'],
            ['ingredient' => 'Butter', 'quantity' => 115, 'unit' => 'g', 'text' => '115g butter, softened'],
            ['ingredient' => 'Brown sugar', 'quantity' => 100, 'unit' => 'g', 'text' => '100g brown sugar'],
            ['ingredient' => 'Sugar', 'quantity' => 50, 'unit' => 'g', 'text' => '50g granulated sugar'],
            ['ingredient' => 'Eggs', 'quantity' => 1, 'unit' => 'large', 'text' => '1 large egg'],
            ['ingredient' => 'Vanilla extract', 'quantity' => 1, 'unit' => 'tsp', 'text' => '1 teaspoon vanilla extract'],
            ['ingredient' => 'Baking powder', 'quantity' => 0.5, 'unit' => 'tsp', 'text' => '1/2 teaspoon baking soda'],
            ['ingredient' => 'Salt', 'quantity' => 0.5, 'unit' => 'tsp', 'text' => '1/2 teaspoon salt'],
        ];

        foreach ($cookieIngredients as $index => $ingredientData) {
            $ingredient = $ingredients->where('name', $ingredientData['ingredient'])->first();
            if ($ingredient) {
                RecipeIngredient::create([
                    'recipe_id' => $recipe3->id,
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $ingredientData['quantity'],
                    'unit' => $ingredientData['unit'],
                    'text' => $ingredientData['text'],
                    'order' => $index + 1,
                ]);
            }
        }

        // Add steps for Cookies
        $cookieSteps = [
            'Preheat oven to 375°F (190°C).',
            'Cream together butter and sugars until light and fluffy.',
            'Beat in egg and vanilla extract.',
            'In a separate bowl, whisk together flour, baking soda, and salt.',
            'Gradually mix dry ingredients into wet ingredients.',
            'Fold in chocolate chips.',
            'Drop rounded tablespoons of dough onto baking sheets.',
            'Bake for 9-11 minutes until edges are golden.',
            'Cool on baking sheet for 5 minutes before transferring.',
        ];

        foreach ($cookieSteps as $index => $step) {
            RecipeStep::create([
                'recipe_id' => $recipe3->id,
                'order' => $index + 1,
                'description' => $step,
                'duration' => $index === 7 ? 10 : 2,
                'temperature' => $index === 0 ? '190°C' : null,
            ]);
        }
    }
}
