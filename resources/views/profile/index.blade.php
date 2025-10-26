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
                <img src="{{ $profile->profile }}" alt="user profile" class="rounded-full size-35 border-muted">
                <div class="flex-col gap-3 items-start w-full">
                    <h1 class="text-display-sm">{{ $profile->name }}</h1>
                    <h3 class="gap-2 text-muted">
                        {{ '@' . $profile->username . ' • ' . $profile->followers()->count() . ' Followers' }}
                    </h3>
                    <div class="flex flex-row gap-5 text-body-md font-bold">
                        <p>{{ 'Recipes: ' . $recipes->count() }}</p>
                        <span class="flex flex-row items-center text-body-lg gap-1">
                            Rating:
                            <x-rating :value="$profile->average_rating()" :size="4" />
                        </span>
                    </div>
                    <p class="text-body-md font-bold text-muted lg:w-1/4 w-full">{{ $profile->bio }}</p>
                </div>
            </div>

            <x-follow :profile="$profile" />
        </div>
        <div class="flex items-center justify-between px-8 w-full border-b border-accent">
            {{-- Menu --}}
            {{-- TODO: Review later --}}
            @php
                $route =
                    auth()->check() && optional(auth()->user()->profile)->id === optional($profile)->id
                    ? 'profile.show'
                    : 'profile.view';
            @endphp
            <div class="flex">
                <x-tab active="{{ $tab === 'home' }}"
                    href="{{ route($route, ['user' => $profile->username, 'tab' => 'home']) }}">Home</x-tab>
                <x-tab active="{{ $tab === 'videos' }}"
                    href="{{ route($route, ['user' => $profile->username, 'tab' => 'videos']) }}">Videos</x-tab>
                <x-tab active="{{ $tab === 'recipes' }}"
                    href="{{ route($route, ['user' => $profile->username, 'tab' => 'recipes']) }}">Recipes</x-tab>
                <x-tab active="{{ $tab === 'resources' }}"
                    href="{{ route($route, ['user' => $profile->username, 'tab' => 'resources']) }}">Resources</x-tab>
            </div>
            <x-css-search class="size-8 cursor-pointer" />
        </div>

        <div class="mt-6 px-6">
            @includeIf("profile.sections.{$tab}", ['profile' => $profile, 'recipes' => $recipes ?? null])
        </div>
    </div>
@endsection
