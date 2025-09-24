@extends('layouts.app')

@php
    $profile = $user->profile ?? null;
    if (!isset($recipes)) {
        $recipes = collect([
            (object) [
                'title' => 'Spaghetti Carbonara',
                'slug' => 'spaghetti-carbonara',
                'excerpt' => 'A classic Italian pasta dish.',
                'description' => 'Rich and creamy pasta with pancetta.',
                'created_at' => now()->subDays(2),
                'likes_count' => 12,
                'media' => collect([(object) ['path' => 'recipes/carbonara.jpg']]),
            ],
            (object) [
                'title' => 'Avocado Toast',
                'slug' => 'avocado-toast',
                'excerpt' => null,
                'description' => 'Healthy and delicious breakfast option.',
                'created_at' => now()->subDays(5),
                'likes_count' => 8,
                'media' => collect([]),
            ],
        ]);
    }
@endphp

@section('content')
    {{-- Header --}}
    <div class="flex">
        <div class="flex w-full gap-3">
            <img src="{{ $profile->profile ?? asset('images/profile-icons/profile.png') }}" alt="user profile"
                class="rounded-full size-40">
            <div class="flex-col gap-3">
                <h1 class="text-display-sm">{{ $profile->name }}</h1>
                <h3 class="gap-2 text-muted">{{ '@' . $profile->username . '  •  ' . '20K Followers' }}</h3>
                <div class="flex flex-row justify-between text-body-md font-bold">
                    <p>{{ 'Recipes: ' . $recipes->count() }}</p>
                    <p>{{ 'Recipes: ' . $recipes->count() }}</p>
                </div>
                <p class="text-body-md font-bold text-muted">{{ $profile->bio }}</p>
            </div>

        </div>
    </div>
@endsection
