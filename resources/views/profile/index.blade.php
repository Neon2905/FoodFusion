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
    <div class="flex-col w-full">
        {{-- Header --}}
        <div class="flex-center flex-row justify-between w-full pb-4 px-6">
            <div class="flex w-full gap-3 items-start">
                <img src="{{ asset('images/profile-icons/profile.png') }}" alt="user profile"
                    class="rounded-full size-35 border-muted">
                <div class="flex-col gap-3 items-start">
                    <h1 class="text-display-sm">{{ $profile->name }}</h1>
                    <h3 class="gap-2 text-muted">{{ '@' . $profile->username . ' • ' . '20K Followers' }}</h3>
                    <div class="flex flex-row justify-between text-body-md font-bold">
                        <p>{{ 'Recipes: ' . $recipes->count() }}</p>
                        <p>{{ 'Recipes: ' . $recipes->count() }}</p>
                    </div>
                    <p class="text-body-md font-bold text-muted">{{ $profile->bio }}</p>
                </div>
            </div>
            <button class="flex-center button h-10 rounded-full border border-muted bg-light-gray w-auto px-4">
                <h2 class="text-heading-lg">Follow</h2>
                <x-icons.user-plus class="text-gray-700" />
            </button>
        </div>
        <div class="flex items-center justify-between px-8 w-full border-b border-accent">
            {{-- Menu --}}
            {{-- TODO: Implement menu items --}}
            <div class="flex">
                <x-tab href="#">Home</x-tab>
                <x-tab href="#">Videos</x-tab>
                <x-tab href="#">Recipes</x-tab>
                <x-tab href="#">Resources</x-tab>
            </div>
            <x-css-search class="size-8 cursor-pointer" />
        </div>
    </div>
@endsection
