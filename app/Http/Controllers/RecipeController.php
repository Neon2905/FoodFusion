<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $recipe->tags()->createMany($validated['tags'] ?? []);
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

            // Keep a mutable, re-indexed copy of media_meta to allow consuming matches
            $remainingMeta = is_array($media_meta) ? array_values($media_meta) : [];

            foreach ($files as $index => $file) {
                if (!$file || !$file->isValid()) {
                    continue;
                }

                // Try to find corresponding meta: prefer index match, else match by name+size
                $meta = $remainingMeta[$index] ?? null;

                if (!$meta) {
                    $origName = $file->getClientOriginalName();
                    $origSize = $file->getSize();
                    $foundKey = null;
                    foreach ($remainingMeta as $k => $m) {
                        if ((isset($m['name']) && $m['name'] === $origName) || (isset($m['size']) && intval($m['size']) === intval($origSize))) {
                            $meta = $m;
                            $foundKey = $k;
                            break;
                        }
                    }
                    // consume matched meta so duplicates don't match again
                    if ($foundKey !== null) {
                        unset($remainingMeta[$foundKey]);
                    }
                } else {
                    // consume index-matched meta
                    unset($remainingMeta[$index]);
                }

                // store the file on the public disk; generateStoragePath will store and return the path
                $storedPath = $this->generateStoragePath($file, $recipe->id);

                // determine type from mime if not provided by meta
                $mime = $file->getClientMimeType();
                $type = 'image';
                if (str_starts_with($mime, 'video/')) {
                    $type = 'video';
                } elseif (isset($meta['type']) && in_array($meta['type'], ['image', 'video'])) {
                    $type = $meta['type'];
                }

                $recipe->media()->create([
                    'url' => '/storage/' . ltrim($storedPath, '/'),
                    'type' => $type,
                    'alt' => $meta['alt'] ?? null,
                    'caption' => $meta['caption'] ?? null,
                ]);
            }

            if ($first = $recipe->media()->first()) {
                $recipe->update([
                    'hero_url' => $first->url,
                ]);
            }
        }

        return redirect()->route('recipes.show', ['slug' => $recipe->slug])->with('success', 'Recipe created successfully!');
    }

    public function createView()
    {
        return view('recipe.create');
    }

    protected function generateStoragePath($file, $recipeId)
    {
        $filename = time() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        return $file->storeAs('recipes_resource/' . $recipeId, $filename, 'public');
    }

    public function index(Request $request)
    {
        $authors = ['foodfusion', 'someone'];

        // Only include recipes from the listed author usernames
        $query = Recipe::query()->with(['author', 'media', 'tags'])
            ->whereHas('author', function ($q) use ($authors) {
                $q->whereIn('username', $authors);
            });

        // Apply filters from query string
        if ($request->filled('cuisine')) {
            $query->where('cuisine', $request->get('cuisine'));
        }
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->get('difficulty'));
        }
        if ($request->filled('tag')) {
            $tag = $request->get('tag');
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('name', $tag);
            });
        }

        // Pagination (preserve filters in links)
        $perPage = 10;
        $recipes = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->only(['cuisine', 'difficulty', 'tag']));

        // Return the listing view (AJAX fetch in front-end expects HTML containing #recipes-grid)
        return view('recipe.index', ['recipes' => $recipes]);
    }
    public function show($slug)
    {
        $recipe = Recipe::where('slug', $slug)
            ->with(['author', 'steps', 'ingredients', 'media', 'nutrition', 'reviews'])
            ->firstOrFail();

        return view('recipe.detail', compact('recipe'));
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

        return back()->with('success', 'Review submitted successfully!');
    }
}
