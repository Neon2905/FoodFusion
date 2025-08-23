<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@foodfusion.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'is_verified_creator' => true,
            'bio' => 'Platform administrator and head chef',
        ]);

        // Create verified creators
        User::create([
            'name' => 'Chef Gordon Ramsay',
            'email' => 'gordon@foodfusion.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'creator',
            'is_verified_creator' => true,
            'bio' => 'World-renowned chef and restaurateur',
            'social_links' => [
                'twitter' => '@GordonRamsay',
                'instagram' => '@gordongram',
            ],
        ]);

        User::create([
            'name' => 'Chef Julia Child',
            'email' => 'julia@foodfusion.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'creator',
            'is_verified_creator' => true,
            'bio' => 'Master of French cuisine and cookbook author',
            'social_links' => [
                'website' => 'juliachild.com',
            ],
        ]);

        User::create([
            'name' => 'Chef Jamie Oliver',
            'email' => 'jamie@foodfusion.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'creator',
            'is_verified_creator' => true,
            'bio' => 'Celebrity chef focused on healthy, accessible cooking',
            'social_links' => [
                'twitter' => '@jamieoliver',
                'instagram' => '@jamieoliver',
            ],
        ]);

        // Create regular creators
        User::create([
            'name' => 'Home Cook Sarah',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'creator',
            'is_verified_creator' => false,
            'bio' => 'Home cook sharing family recipes',
        ]);

        User::create([
            'name' => 'Baker Mike',
            'email' => 'mike@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'creator',
            'is_verified_creator' => false,
            'bio' => 'Passionate baker specializing in bread and pastries',
        ]);

        // Create regular users
        User::create([
            'name' => 'Food Lover Emma',
            'email' => 'emma@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'user',
            'dietary_preferences' => ['vegetarian'],
            'measurement_units' => 'metric',
        ]);

        User::create([
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'user',
            'dietary_preferences' => ['gluten-free'],
            'measurement_units' => 'imperial',
        ]);
    }
}
