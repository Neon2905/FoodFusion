<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            // Proteins
            ['name' => 'Chicken breast', 'category' => 'protein', 'is_common' => true],
            ['name' => 'Ground beef', 'category' => 'protein', 'is_common' => true],
            ['name' => 'Salmon fillet', 'category' => 'protein', 'is_common' => false],
            ['name' => 'Eggs', 'category' => 'protein', 'is_common' => true],
            ['name' => 'Tofu', 'category' => 'protein', 'is_common' => false],
            
            // Vegetables
            ['name' => 'Onion', 'category' => 'vegetable', 'is_common' => true],
            ['name' => 'Garlic', 'category' => 'vegetable', 'is_common' => true],
            ['name' => 'Tomato', 'category' => 'vegetable', 'is_common' => true],
            ['name' => 'Bell pepper', 'category' => 'vegetable', 'is_common' => true],
            ['name' => 'Carrot', 'category' => 'vegetable', 'is_common' => true],
            ['name' => 'Broccoli', 'category' => 'vegetable', 'is_common' => true],
            ['name' => 'Spinach', 'category' => 'vegetable', 'is_common' => true],
            ['name' => 'Mushrooms', 'category' => 'vegetable', 'is_common' => true],
            
            // Grains & Starches
            ['name' => 'Rice', 'category' => 'grain', 'is_common' => true],
            ['name' => 'Pasta', 'category' => 'grain', 'is_common' => true],
            ['name' => 'Bread', 'category' => 'grain', 'is_common' => true],
            ['name' => 'Quinoa', 'category' => 'grain', 'is_common' => false],
            ['name' => 'Potato', 'category' => 'starch', 'is_common' => true],
            
            // Dairy
            ['name' => 'Milk', 'category' => 'dairy', 'is_common' => true],
            ['name' => 'Cheese', 'category' => 'dairy', 'is_common' => true],
            ['name' => 'Butter', 'category' => 'dairy', 'is_common' => true],
            ['name' => 'Greek yogurt', 'category' => 'dairy', 'is_common' => true],
            
            // Pantry basics
            ['name' => 'Olive oil', 'category' => 'oil', 'is_common' => true],
            ['name' => 'Vegetable oil', 'category' => 'oil', 'is_common' => true],
            ['name' => 'Salt', 'category' => 'seasoning', 'is_common' => true],
            ['name' => 'Black pepper', 'category' => 'seasoning', 'is_common' => true],
            ['name' => 'Garlic powder', 'category' => 'seasoning', 'is_common' => true],
            ['name' => 'Paprika', 'category' => 'seasoning', 'is_common' => true],
            ['name' => 'Cumin', 'category' => 'seasoning', 'is_common' => false],
            ['name' => 'Oregano', 'category' => 'herb', 'is_common' => true],
            ['name' => 'Basil', 'category' => 'herb', 'is_common' => true],
            ['name' => 'Thyme', 'category' => 'herb', 'is_common' => true],
            
            // Baking
            ['name' => 'All-purpose flour', 'category' => 'baking', 'is_common' => true],
            ['name' => 'Sugar', 'category' => 'baking', 'is_common' => true],
            ['name' => 'Brown sugar', 'category' => 'baking', 'is_common' => true],
            ['name' => 'Baking powder', 'category' => 'baking', 'is_common' => true],
            ['name' => 'Vanilla extract', 'category' => 'baking', 'is_common' => true],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
