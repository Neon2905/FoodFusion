@extends('layouts.app')

@php
    // Dummy data for testing
    if (!isset($user)) {
        $user = (object) [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'profile' => (object) [
                'name' => 'Jane D.',
                'bio' => 'Food lover and recipe creator.',
                'profile' => null, // or 'avatars/dummy.jpg'
                'recipes' => [],
            ],
        ];
    }

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
    <div class="flex justify-center items-start min-h-[70vh] bg-gray-50 py-12">
        <div class="w-full max-w-5xl flex flex-col gap-6">
            <div class="bg-white shadow-lg rounded-lg p-6 flex items-center gap-6">
                <img class="w-28 h-28 rounded-full object-cover border-4 border-tertiary"
                    src="{{ $user->profile && $user->profile->profile ? asset('storage/' . $user->profile->profile) : asset('/images/profile-icons/default.png') }}"
                    alt="Profile Avatar">
                <div class="flex-1">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold">{{ $user->profile->name ?? $user->name }}</h2>
                            <p class="text-gray-500 text-sm">{{ mask_email($user->email) }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Recipes</div>
                            <div class="text-xl font-semibold">{{ $recipes->count() }}</div>
                        </div>
                    </div>

                    @if ($user->profile && $user->profile->bio)
                        <p class="mt-3 text-gray-700">{{ $user->profile->bio }}</p>
                    @endif

                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('profile.setup') }}" class="button bg-tertiary text-white rounded-full px-5 py-2">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            {{-- Recipes list --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">My Recipes</h3>

                @if ($recipes->count())
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach ($recipes as $recipe)
                            @php
                                $media = $recipe->media->first() ?? null;
                                $thumb =
                                    $media && isset($media->path)
                                        ? asset('storage/' . $media->path)
                                        : asset('/images/recipe-default.png');
                            @endphp

                            <article class="border rounded overflow-hidden">
                                <a href="{{ route('recipes.show', $recipe->slug) }}" class="block">
                                    <img class="w-full h-40 object-cover" src="{{ $thumb }}"
                                        alt="{{ $recipe->title }}">
                                </a>
                                <div class="p-4">
                                    <a href="{{ route('recipes.show', $recipe->slug) }}"
                                        class="text-lg font-semibold hover:underline">
                                        {{ $recipe->title }}
                                    </a>
                                    @if ($recipe->excerpt ?? false)
                                        <p class="text-sm text-gray-600 mt-2">{{ $recipe->excerpt }}</p>
                                    @elseif($recipe->description ?? false)
                                        <p class="text-sm text-gray-600 mt-2">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($recipe->description), 100) }}</p>
                                    @endif

                                    <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                                        <div>{{ $recipe->created_at->diffForHumans() }}</div>
                                        <div>{{ $recipe->likes_count ?? 0 }} likes</div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- <div class="mt-6">
                        {{ $recipes->links() }}
                    </div> --}}
                @else
                    <div class="text-gray-500">You haven't published any recipes yet.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
