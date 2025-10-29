<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Resource;
use App\Models\Recipe;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function about()
    {
        return view('about');
    }
    public function community()
    {
        $request = request();
        $user = $request->user();
        $followedIds = $user ? $user->following()->pluck('profiles.id')->toArray() : [];

        $query = Recipe::with(['author', 'media', 'tags']);

        // Put followed users' recipes first if any followed profiles exist
        if (count($followedIds)) {
            $ids = implode(',', array_map('intval', $followedIds));
            $query->orderByRaw("profile_id IN ({$ids}) DESC");
        }

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

        $perPage = 12;
        $recipes = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->only(['cuisine', 'difficulty', 'tag']));

        return view('community.index', ['recipes' => $recipes]);
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // TODO: persist to DB / send email. For now flash success.
        return back()->with('success', 'Thanks — we received your message and will respond shortly.');
    }

    public function culinaryResources()
    {
        $resources = Resource::query()
            ->where('published', true)
            ->where('category', 'culinary')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('resource.culinary', compact('resources'));
    }

    public function educationalResources()
    {
        $resources = Resource::query()
            ->where('published', true)
            ->where('category', 'educational')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('resource.educational', compact('resources'));
    }

    public function home()
    {
        // Featured carousel: prefer recipes with hero_url, fallback to recipes with media
        $featured = Recipe::with('media', 'author')
            ->whereNotNull('hero_url')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        if ($featured->isEmpty()) {
            $featured = Recipe::with('media', 'author')
                ->has('media')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        }

        $carouselItems = $featured->map(function ($r) {
            return [
                'id' => $r->id,
                'title' => $r->title,
                'date' => optional($r->created_at)->format('Y-m-d'),
                'img' => $r->hero_url ?? optional($r->media->first())->url ?? null,
            ];
        })->values();

        // News: recent recipes
        $newsRecipes = Recipe::orderBy('created_at', 'desc')->limit(5)->get();
        $news = $newsRecipes->map(function ($r) {
            return [
                'id' => $r->id,
                'title' => $r->title,
                'excerpt' => Str::limit($r->description ?? '', 120),
                'hero_url' => $r->hero_url ?? optional($r->media->first())->url ?? null,
            ];
        })->values();

        $events = Resource::where('published', true)
            ->where('category', 'event')
            ->latest()
            ->limit(3)
            ->get()
            ->map(function ($e) {
                return [
                    'id' => $e->id,
                    'title' => $e->title ?? $e->name ?? 'Event',
                    'date' => optional($e->created_at)->format('Y-m-d'),
                ];
            })->values();

        return view('home', compact('carouselItems', 'news', 'events'));
    }
}
