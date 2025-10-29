<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RecipeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $foodFusionUser = User::factory()->create([
            'email' => 'foodfusion@example.com',
        ]);

        $foodFusionUser->profile()->create([
            'username'=>'foodfusion',
            'name' =>'Food Fusion'
        ]);

        $this->call([
            RecipeSeeder::class,
            ResourceSeeder::class,
        ]);
    }
}
