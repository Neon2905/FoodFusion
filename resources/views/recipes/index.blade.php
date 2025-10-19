@extends('layouts.app', ['title' => 'Recipes'])

@section('content')
    <div class="flex flex-col gap-4 w-full">
        <h1 class="text-display-sm">Recipes</h1>
        <div class="grid grid-cols-3 gap-4">
            @foreach ($recipes as $recipe)
                <a href="{{ route('recipes.show', ['slug' => $recipe->slug]) }}" class="card no-underline">
                    <img src="{{ $recipe->hero_url }}" alt="{{ $recipe->title }} " class="w-full h-40 object-cover rounded">
                    <h3 class="mt-2">{{ $recipe->title }}</h3>
                    <p class="text-muted">{{ $recipe->cuisine }} • {{ $recipe->servings }} servings</p>
                </a>
            @endforeach
        </div>

        <div class="mt-4">
            {{-- {{ $recipes->links() }} --}}
        </div>
    </div>
@endsection
