<?php

use App\Http\Controllers\FollowsController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

Route::get('/test', function () {
    return view('test');
});

Route::get('/test2', function () {
    return view('components.experiment', ['recipe' => \App\Models\Recipe::first()]);
});

Route::post('/test', function () {
    dd(request()->all());
})->name('test.post');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/recipe/create', [RecipeController::class, 'createView'])
    ->middleware(['auth', 'verified', 'auth.setup'])
    ->name('recipes.create.view');

Route::post('/recipes/{slug}/review', [RecipeController::class, 'submitReview'])
    ->middleware(['auth.setup'])
    ->name('review.submit');

Route::get('/recipes', [RecipeController::class, 'index'])->middleware(['auth', 'verified'])->name('recipes');
Route::get('/recipe/{slug}', [RecipeController::class, 'show'])->middleware(['auth', 'verified', 'auth.setup'])->name('recipes.show');
Route::post('/recipe', [RecipeController::class, 'create'])->middleware(['auth', 'verified', 'auth.setup'])->name('recipes.create');

Route::post('/follow/{profile:username}', [FollowsController::class, 'store'])
    ->middleware(middleware: ['auth', 'verified', 'auth.setup'])
    ->name('follow.store');

Route::get('/search', [SearchController::class, 'index'])->name('search');

require __DIR__ . '/profile.route.php';
require __DIR__ . '/auth.route.php';
