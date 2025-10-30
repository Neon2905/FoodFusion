<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ResourceController;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/community', [PageController::class, 'community'])->name('community');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');


Route::get('/recipe/create', [RecipeController::class, 'createView'])
    ->middleware(['auth', 'verified', 'auth.setup'])
    ->name('recipes.create.view');

Route::post('/recipes/{slug}/review', [RecipeController::class, 'submitReview'])
    ->middleware(['auth.setup'])
    ->name('review.submit');

Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes');
Route::get('/recipe/{slug}', [RecipeController::class, 'show'])->middleware(['auth', 'verified', 'auth.setup'])->name('recipes.show');
Route::post('/recipe', [RecipeController::class, 'create'])->middleware(['auth', 'verified', 'auth.setup'])->name('recipes.create');

Route::get('/resources/culinary', [PageController::class, 'culinaryResources'])->name('resources.culinary');
Route::get('/resources/educational', [PageController::class, 'educationalResources'])->name('resources.educational');
Route::get('/resources/{slug}', [ResourceController::class, 'show'])->name('resources.show');

Route::post('/follow/{profile:username}', [FollowsController::class, 'store'])
    ->middleware(middleware: ['auth', 'verified', 'auth.setup'])
    ->name('follow.store');

Route::get('/search', [SearchController::class, 'index'])->name('search');

require __DIR__ . '/profile.route.php';
require __DIR__ . '/auth.route.php';

// Static policy pages
Route::view('/privacy', 'policy.privacy')->name('privacy');
Route::view('/cookies', 'policy.cookies')->name('cookies');

Route::middleware(['auth', 'verified', 'auth.setup'])->group(function () {
    // Account settings
    Route::get('/account/settings', [AccountController::class, 'settings'])->name('account.settings');
    Route::post('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::post('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
});
