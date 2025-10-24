<?php

use App\Http\Controllers\FollowsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/{user:username}', [ProfileController::class, 'view'])->name('profile.view');
});

Route::post('/follow/{user}', [FollowsController::class, 'store'])
    ->middleware(middleware: ['auth', 'verified', 'auth.setup'])
    ->name('follow.store');

require __DIR__ . '/auth.route.php';
