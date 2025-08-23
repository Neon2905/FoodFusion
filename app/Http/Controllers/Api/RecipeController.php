<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RecipeController extends Controller
{
    /**
     * Display a listing of recipes
     */
    public function index(Request $request): JsonResponse
    {
        $query = Recipe::published()->with(['user:id,name', 'reviews:id,recipe_id,rating']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereJsonContains('tags', $search);
            });
        }

        if ($request->filled('cuisine')) {
            $query->where('cuisine', $request->get('cuisine'));
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->get('difficulty'));
        }

        if ($request->filled('max_time')) {
            $query->where('total_time', '<=', $request->get('max_time'));
        }

        if ($request->filled('tags')) {
            $tags = explode(',', $request->get('tags'));
            foreach ($tags as $tag) {
                $query->whereJsonContains('tags', trim($tag));
            }
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'rating':
                $query->orderBy('rating_avg', 'desc');
                break;
            case 'time':
                $query->orderBy('total_time', 'asc');
                break;
            case 'popular':
                $query->orderByRaw("JSON_EXTRACT(analytics, '$.views') DESC");
                break;
            default:
                $query->latest('published_at');
        }

        $perPage = min($request->get('per_page', 15), 50); // Max 50 items per page
        $recipes = $query->paginate($perPage);

        return response()->json([
            'data' => $recipes->items(),
            'meta' => [
                'current_page' => $recipes->currentPage(),
                'last_page' => $recipes->lastPage(),
                'per_page' => $recipes->perPage(),
                'total' => $recipes->total(),
            ]
        ]);
    }

    /**
     * Display the specified recipe
     */
    public function show(Recipe $recipe): JsonResponse
    {
        // Check if recipe is accessible
        if (!$recipe->is_published) {
            return response()->json(['error' => 'Recipe not found'], 404);
        }

        $recipe->load([
            'user:id,name,avatar,is_verified_creator',
            'steps:id,recipe_id,order,description,duration,temperature',
            'recipeIngredients:id,recipe_id,ingredient_id,text,quantity,unit,optional,notes,order',
            'recipeIngredients.ingredient:id,name',
            'reviews:id,recipe_id,user_id,rating,comment,created_at',
            'reviews.user:id,name'
        ]);

        // Increment view count
        $analytics = $recipe->analytics ?? [];
        $analytics['views'] = ($analytics['views'] ?? 0) + 1;
        $recipe->update(['analytics' => $analytics]);

        return response()->json(['data' => $recipe]);
    }

    /**
     * Search recipes with autocomplete suggestions
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['data' => []]);
        }

        $recipes = Recipe::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhereJsonContains('tags', $query)
                  ->orWhere('cuisine', 'like', "%{$query}%");
            })
            ->with('user:id,name')
            ->limit(10)
            ->get(['id', 'title', 'slug', 'description', 'images', 'prep_time', 'cook_time', 'rating_avg', 'user_id']);

        return response()->json(['data' => $recipes]);
    }

    /**
     * Get search suggestions/autocomplete
     */
    public function suggest(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        // Get recipe title suggestions
        $titleSuggestions = Recipe::published()
            ->where('title', 'like', "%{$query}%")
            ->limit(5)
            ->pluck('title')
            ->toArray();

        // Get cuisine suggestions
        $cuisineSuggestions = Recipe::published()
            ->where('cuisine', 'like', "%{$query}%")
            ->distinct()
            ->limit(3)
            ->pluck('cuisine')
            ->filter()
            ->toArray();

        $suggestions = array_merge($titleSuggestions, $cuisineSuggestions);

        return response()->json(['suggestions' => array_slice($suggestions, 0, 8)]);
    }

    /**
     * Store a newly created recipe (requires authentication)
     */
    public function store(Request $request): JsonResponse
    {
        // This would require authentication middleware
        return response()->json(['error' => 'Authentication required'], 401);
    }

    /**
     * Update the specified recipe (requires authentication)
     */
    public function update(Request $request, Recipe $recipe): JsonResponse
    {
        // This would require authentication middleware
        return response()->json(['error' => 'Authentication required'], 401);
    }

    /**
     * Remove the specified recipe (requires authentication)
     */
    public function destroy(Recipe $recipe): JsonResponse
    {
        // This would require authentication middleware
        return response()->json(['error' => 'Authentication required'], 401);
    }
}
