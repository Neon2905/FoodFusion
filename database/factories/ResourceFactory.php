<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Resource;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resource>
 */
class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['card', 'tutorial', 'video', 'technique']);
        $category = $this->faker->randomElement(['culinary', 'educational']);
        $title = $this->faker->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(4),
            'category' => $category,
            'type' => $type,
            'description' => $this->faker->paragraph(),
            'file_path' => null,
            'external_url' => $type === 'video' ? $this->faker->url() : null,
            'thumbnail_url' => null,
            'duration' => $type === 'video' ? $this->faker->numberBetween(30, 3600) : null,
            'tags' => $this->faker->randomElements(['knife', 'pasta', 'sauce', 'baking', 'knifecuts', 'sous-vide', 'preserve'], 2),
            'published' => true,
        ];
    }

    public function culinary()
    {
        return $this->state(fn() => ['category' => 'culinary']);
    }

    public function educational()
    {
        return $this->state(fn() => ['category' => 'educational']);
    }
}
