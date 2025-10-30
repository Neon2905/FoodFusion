<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create sample culinary resources
        Resource::factory()->count(6)->culinary()->create();

        // create sample educational resources
        Resource::factory()->count(4)->educational()->create();

        // create a few explicit demo entries (useful if you import demo files later)
        Resource::firstOrCreate(
            ['slug' => 'knife-cuts-julienne'],
            [
                'title' => 'Knife Cuts: Julienne & Brunoise',
                'category' => 'culinary',
                'type' => 'technique',
                'description' => 'Step-by-step guide to classic knife cuts used in restaurants.',
                'tags' => ['knifecuts', 'technique'],
                'published' => true,
            ]
        );

        Resource::firstOrCreate(
            ['slug' => 'making-tomato-paste'],
            [
                'title' => 'Making Tomato Paste from Fresh Tomatoes',
                'category' => 'culinary',
                'type' => 'tutorial',
                'description' => 'How to cook down fresh tomatoes into concentrated paste for sauces.',
                'tags' => ['sauce', 'preserve'],
                'published' => true,
            ]
        );
    }
}
