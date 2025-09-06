<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::get();
        return view('recipes.index', ['recipes' => $recipes]);
    }
    public function show($slug)
    {
        // return view('recipe');

        $recipe = Recipe::where('slug', $slug)
            ->with(['author', 'steps', 'ingredients', 'media', 'nutrition', 'reviews'])
            ->firstOrFail();

        return view('recipes.detail', compact('recipe'));
    }
}
