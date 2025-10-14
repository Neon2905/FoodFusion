<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return view('recipes.create');
});

Route::post('/test', function () {
    dd(request()->all());
})->name('test.post');

Route::get('/', function () {
    return view('welcome');
});

Route::post('/recipes/{slug}/review', [RecipeController::class, 'submitReview'])
    ->middleware(['auth.setup'])
    ->name('review.submit');

Route::get('/recipes', [RecipeController::class, 'index'])->middleware(['auth', 'verified'])->name('recipes');
Route::get('/recipes/{slug}', [RecipeController::class, 'show'])->middleware(['auth', 'verified', 'auth.setup'])->name('recipes.show');
Route::post('/recipes', [RecipeController::class, 'create'])->middleware(['auth', 'verified', 'auth.setup'])->name('recipes.create');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
