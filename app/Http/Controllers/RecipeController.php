<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    /**
     * Display a listing of recipes
     */
    public function index(Request $request)
    {
        $query = Recipe::published()->with(['user', 'reviews']);

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
                // Assuming analytics contains view count
                $query->orderByRaw("JSON_EXTRACT(analytics, '$.views') DESC");
                break;
            default:
                $query->latest('published_at');
        }

        $recipes = $query->paginate(12);
        $categories = Category::featured()->get();

        return view('recipes.index', compact('recipes', 'categories'));
    }

    /**
     * Show the form for creating a new recipe
     */
    public function create()
    {
        $this->authorize('create', Recipe::class);
        
        $ingredients = Ingredient::common()->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        return view('recipes.create', compact('ingredients', 'categories'));
    }

    /**
     * Store a newly created recipe
     */
    public function store(Request $request)
    {
        $this->authorize('create', Recipe::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'required|integer|min:1',
            'difficulty' => 'required|in:easy,medium,hard',
            'cuisine' => 'nullable|string|max:100',
            'tags' => 'nullable|array',
            'meal_type' => 'nullable|array',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'nullable|numeric',
            'ingredients.*.unit' => 'nullable|string|max:50',
            'ingredients.*.text' => 'required|string',
            'ingredients.*.optional' => 'boolean',
            'ingredients.*.notes' => 'nullable|string',
            'steps' => 'required|array|min:1',
            'steps.*.description' => 'required|string',
            'steps.*.duration' => 'nullable|integer',
            'steps.*.temperature' => 'nullable|string|max:50',
        ]);

        $recipe = Recipe::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'description' => $validated['description'],
            'prep_time' => $validated['prep_time'],
            'cook_time' => $validated['cook_time'],
            'servings' => $validated['servings'],
            'difficulty' => $validated['difficulty'],
            'cuisine' => $validated['cuisine'],
            'tags' => $validated['tags'] ?? [],
            'meal_type' => $validated['meal_type'] ?? [],
            'visibility' => 'public',
            'moderation_status' => Auth::user()->is_admin ? 'approved' : 'pending',
            'published_at' => now(),
        ]);

        // Add ingredients
        foreach ($validated['ingredients'] as $index => $ingredientData) {
            $recipe->recipeIngredients()->create([
                'ingredient_id' => $ingredientData['ingredient_id'],
                'quantity' => $ingredientData['quantity'],
                'unit' => $ingredientData['unit'],
                'text' => $ingredientData['text'],
                'optional' => $ingredientData['optional'] ?? false,
                'notes' => $ingredientData['notes'] ?? null,
                'order' => $index + 1,
            ]);
        }

        // Add steps
        foreach ($validated['steps'] as $index => $stepData) {
            $recipe->steps()->create([
                'order' => $index + 1,
                'description' => $stepData['description'],
                'duration' => $stepData['duration'],
                'temperature' => $stepData['temperature'],
            ]);
        }

        return redirect()->route('recipes.show', $recipe)
                        ->with('success', 'Recipe created successfully!');
    }

    /**
     * Display the specified recipe
     */
    public function show(Recipe $recipe)
    {
        // Check if recipe is accessible
        if (!$recipe->is_published && $recipe->user_id !== Auth::id()) {
            abort(404);
        }

        $recipe->load([
            'user',
            'steps' => function ($query) {
                $query->orderBy('order');
            },
            'recipeIngredients.ingredient',
            'reviews.user'
        ]);

        // Increment view count
        $analytics = $recipe->analytics ?? [];
        $analytics['views'] = ($analytics['views'] ?? 0) + 1;
        $recipe->update(['analytics' => $analytics]);

        // Get related recipes
        $relatedRecipes = Recipe::published()
            ->where('id', '!=', $recipe->id)
            ->where(function ($query) use ($recipe) {
                if ($recipe->cuisine) {
                    $query->where('cuisine', $recipe->cuisine);
                }
                if ($recipe->tags) {
                    foreach ($recipe->tags as $tag) {
                        $query->orWhereJsonContains('tags', $tag);
                    }
                }
            })
            ->with('user')
            ->limit(4)
            ->get();

        return view('recipes.show', compact('recipe', 'relatedRecipes'));
    }

    /**
     * Show the form for editing the recipe
     */
    public function edit(Recipe $recipe)
    {
        $this->authorize('update', $recipe);
        
        $recipe->load(['recipeIngredients.ingredient', 'steps']);
        $ingredients = Ingredient::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        return view('recipes.edit', compact('recipe', 'ingredients', 'categories'));
    }

    /**
     * Update the specified recipe
     */
    public function update(Request $request, Recipe $recipe)
    {
        $this->authorize('update', $recipe);

        // Similar validation and update logic as store
        // For brevity, implementing basic update
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'required|integer|min:1',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        $recipe->update($validated);

        return redirect()->route('recipes.show', $recipe)
                        ->with('success', 'Recipe updated successfully!');
    }

    /**
     * Remove the specified recipe
     */
    public function destroy(Recipe $recipe)
    {
        $this->authorize('delete', $recipe);
        
        $recipe->delete();
        
        return redirect()->route('recipes.index')
                        ->with('success', 'Recipe deleted successfully!');
    }
}
