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

        User::factory()->create([
            'email' => 'tpshine1234@gmail.com',
            'password' => bcrypt('Password'),
        ]);

        $this->call(RecipeSeeder::class);
    }
}
