<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Media;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'servings' => 'required|integer|min:1',
            'difficulty' => 'required|string',
            'meal_type' => 'required|string',
            'ingredients' => 'required|array|min:1',
            'steps' => 'required|array|min:1',
            'tips' => 'array',
            'prep_time' => 'nullable|integer',
            'cook_time' => 'nullable|integer',
            'nutritions' => 'array',
            'tags' => 'array',
        ]);

        // dd($validated['nutritions']);

        // dd($request->all());

        // dd($request['tips']);

        $media_meta = json_decode($request->input('media_meta', '[]'), true) ?? [];
        if (!is_array($media_meta)) {
            $media_meta = [];
        }

        $recipe = Recipe::create([
            'profile_id' => $request->user()->profile->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'servings' => $validated['servings'],
            'difficulty' => $validated['difficulty'],
            'meal_type' => $validated['meal_type'],
            'prep_time' => $validated['prep_time'] ?? null,
            'cook_time' => $validated['cook_time'] ?? null,
            'total_time' => ($validated['prep_time'] ?? 0) + ($validated['cook_time'] ?? 0)
        ]);

        $recipe->ingredients()->createMany($validated['ingredients']);
        $recipe->steps()->createMany($validated['steps']);
        $recipe->tags()->createMany($validated['tags'] ?? []); // TODO: fix this. no data found here!
        $recipe->tips()->createMany($validated['tips'] ?? []);

        $recipe->nutrition()->create(
            $validated['nutritions'] ?? []
        );

        // store uploaded media files to local storage (storage/app/public/recipes/{recipe_id})
        if ($request->hasFile('media')) {
            $files = $request->file('media');
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $index => $file) {
                if (!$file || !$file->isValid()) {
                    continue;
                }

                $storedPath = $this->generateStoragePath($file, $recipe->id);

                $recipe->media()->create([
                    'url' => asset('storage/' . $storedPath),
                    // 'caption' => $filename, TODO
                    'type' => $media_meta[$index]['type'],
                ]);
            }

            $recipe->update([
                'hero_url' => $recipe->media()->first()->url,
            ]);
        }

        return redirect()->route('recipes.show', ['slug' => $recipe->slug])->with('success', 'Recipe created successfully!');
    }

    public function createView()
    {
        return view('recipes.create');
    }

    protected function generateStoragePath($file, $recipeId)
    {
        $filename = time() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        return $file->storeAs('recipes_resource/' . $recipeId, $filename, 'public');
    }

    public function index()
    {
        $recipes = Recipe::get();
        return view('recipes.index', ['recipes' => $recipes]);
    }
    public function show($slug)
    {
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
