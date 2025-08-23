<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Breakfast',
                'description' => 'Start your day with delicious breakfast recipes',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Lunch',
                'description' => 'Quick and satisfying lunch ideas',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Dinner',
                'description' => 'Hearty dinner recipes for the whole family',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet treats and desserts',
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Appetizers',
                'description' => 'Perfect starters and snacks',
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Healthy',
                'description' => 'Nutritious and wholesome recipes',
                'is_featured' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Quick & Easy',
                'description' => 'Recipes ready in 30 minutes or less',
                'is_featured' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Vegetarian',
                'description' => 'Delicious meat-free recipes',
                'is_featured' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Vegan',
                'description' => 'Plant-based recipes',
                'is_featured' => false,
                'sort_order' => 9,
            ],
            [
                'name' => 'Gluten-Free',
                'description' => 'Gluten-free recipe options',
                'is_featured' => false,
                'sort_order' => 10,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
