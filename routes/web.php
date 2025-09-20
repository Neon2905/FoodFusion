<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

Route::get('/test', function () {
    
});


Route::get('/', function () {
    return view('welcome');
});

Route::prefix('recipes')->group(function () {
    Route::get('/', [RecipeController::class, 'index']);
    Route::get('/{slug}', [RecipeController::class, 'show'])->name('recipes.show');
});

Route::get('/profile', [ProfileController::class, 'show'])
    ->middleware('auth')
    ->name('profile.show');

Route::get('/profile/setup', [ProfileController::class, 'setup'])
    ->middleware('auth')
    ->name('profile.setup');

Route::post('/profile/setup', [ProfileController::class, 'setup_submit'])
    ->middleware('auth')
    ->name('profile.setup.submit');

Route::get('/resources', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('welcome');
});

/*
    Auth Routes
 */
require __DIR__ . '/auth.php';
