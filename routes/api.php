<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecipeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Recipe API routes
Route::apiResource('recipes', RecipeController::class);

// Search and filter endpoints
Route::get('/search/recipes', [RecipeController::class, 'search']);
Route::get('/search/suggest', [RecipeController::class, 'suggest']);