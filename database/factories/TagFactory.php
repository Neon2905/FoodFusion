<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'vegan', 'gluten-free', 'spicy', 'quick', 'healthy', 'dessert', 'breakfast', 'dinner', 'snack', 'low-carb', 'high-protein', 'vegetarian', 'easy', 'family', 'holiday', 'comfort', 'classic', 'fresh', 'seasonal', 'grilled'
            ]),
        ];
    }
}
