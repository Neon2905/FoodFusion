<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Review;
use Illuminate\Http\Request;

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

    public function submitReview(Request $request, $slug)
    {
        $recipe = Recipe::where('slug', $slug)->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        $recipe->reviews()->create([
            'profile_id' => $request->user()->profile->id,
            'rating' => $request->input('rating'),
            'review' => $request->input('review'),
        ]);

        return redirect()->route('recipes.show', ['slug' => $slug])->with('success', 'Review submitted successfully!');
    }
}
