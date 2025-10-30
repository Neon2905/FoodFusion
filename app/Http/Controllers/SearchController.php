<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Tag;

class SearchController extends Controller
{
    /**
     * Show search page or return JSON results for AJAX queries.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('query', ''));
        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $page = max(1, (int) $request->get('page', 1));
            $per = 12;

            $query = Recipe::query();

            if ($q !== '') {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('meal_type', 'like', "%{$q}%");
                });
            }

            if ($request->filled('meal_type')) {
                $query->where('meal_type', $request->get('meal_type'));
            }
            if ($request->filled('tag')) {
                $tag = $request->get('tag');
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->where('name', $tag);
                });
            }
            if ($request->filled('difficulty')) {
                $query->where('difficulty', $request->get('difficulty'));
            }

            $total = $query->count();
            $items = $query->orderBy('created_at', 'desc')
                ->skip(($page - 1) * $per)
                ->take($per)
                ->get(['id', 'title', 'slug', 'description', 'servings', 'hero_url', 'meal_type', 'rating']);

            return response()->json([
                'meta' => ['total' => $total, 'page' => $page, 'per' => $per],
                'data' => $items,
            ]);
        }

        // initial page render: provide list of meal types and tags for filter dropdowns
        $meal_types = Recipe::select('meal_type')->distinct()->whereNotNull('meal_type')->pluck('meal_type')->filter()->values();
        $tags = Tag::select('name')->distinct()->whereNotNull('name')->pluck('name')->filter()->values();

        return view('search', compact('meal_types', 'tags'));
    }
}
