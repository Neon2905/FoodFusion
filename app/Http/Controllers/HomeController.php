<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured recipes and data for homepage
        $featuredRecipes = Recipe::published()
            ->with(['user', 'reviews'])
            ->orderBy('rating_avg', 'desc')
            ->take(6)
            ->get();

        $recentRecipes = Recipe::published()
            ->with(['user'])
            ->latest('published_at')
            ->take(8)
            ->get();

        $featuredCategories = Category::featured()
            ->take(8)
            ->get();

        $topCreators = User::creators()
            ->withCount('recipes')
            ->orderBy('recipes_count', 'desc')
            ->limit(6)
            ->get()
            ->filter(function ($user) {
                return $user->recipes_count > 0;
            });

        return view('home', compact(
            'featuredRecipes',
            'recentRecipes', 
            'featuredCategories',
            'topCreators'
        ));
    }
}
