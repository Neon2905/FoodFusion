<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecipeController;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Recipe routes
Route::resource('recipes', RecipeController::class);

// Search routes
Route::get('/search', [RecipeController::class, 'index'])->name('search');

// Auth routes (Laravel Breeze or similar will add these)
Route::post('/login', function () {
    // Handle login logic
    dd('Login route hit');
});

Route::get('/login', function () {
    // Handle login logic
    dd('Login route hit');
})->name('login');
