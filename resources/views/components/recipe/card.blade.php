@props(['recipe' => null])

<a href="{{ route('recipes.show', ['slug' => $recipe->slug]) }}" class="card no-underline hover:shadow-lg transition">
    <img src="{{ $recipe->hero_url }}" alt="{{ $recipe->title }}" class="w-full h-48 object-cover rounded">
    <div class="mt-3">
        <h3 class="font-semibold text-lg">{{ $recipe->title }}</h3>
        <p class="text-muted">{{ $recipe->cuisine }} • {{ $recipe->servings }} servings</p>
    </div>
</a>
