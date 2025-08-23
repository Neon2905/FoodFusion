<x-layout title="FoodFusion - Culinary Platform">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-orange-400 to-red-500 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl font-bold mb-4">Welcome to FoodFusion</h1>
            <p class="text-xl mb-8">Discover, create, and share amazing recipes from around the world</p>
            <div class="space-x-4">
                <a href="{{ route('recipes.index') }}" class="bg-white text-orange-500 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Explore Recipes
                </a>
                <a href="{{ route('recipes.create') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-orange-500 transition">
                    Share Your Recipe
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Recipes -->
    @if($featuredRecipes->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">Featured Recipes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredRecipes as $recipe)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    @if($recipe->images && count($recipe->images) > 0)
                    <img src="{{ $recipe->images[0]['url'] ?? '/images/recipe-placeholder.jpg' }}" 
                         alt="{{ $recipe->title }}" 
                         class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">No image</span>
                    </div>
                    @endif
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">
                            <a href="{{ route('recipes.show', $recipe) }}" class="hover:text-orange-500 transition">
                                {{ $recipe->title }}
                            </a>
                        </h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($recipe->description, 100) }}</p>
                        
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center space-x-4">
                                <span>⏱️ {{ $recipe->total_time }}min</span>
                                <span>👨‍🍳 {{ $recipe->servings }} servings</span>
                                @if($recipe->rating_avg > 0)
                                <span>⭐ {{ number_format($recipe->rating_avg, 1) }}</span>
                                @endif
                            </div>
                            <span class="text-orange-500 font-medium">{{ ucfirst($recipe->difficulty) }}</span>
                        </div>
                        
                        <div class="mt-4">
                            <span class="text-gray-500 text-sm">by</span>
                            <a href="#" class="text-orange-500 font-medium hover:underline">{{ $recipe->user->name }}</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Recent Recipes -->
    @if($recentRecipes->count() > 0)
    <section class="py-16">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">Latest Recipes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($recentRecipes as $recipe)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    @if($recipe->images && count($recipe->images) > 0)
                    <img src="{{ $recipe->images[0]['url'] ?? '/images/recipe-placeholder.jpg' }}" 
                         alt="{{ $recipe->title }}" 
                         class="w-full h-32 object-cover">
                    @else
                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500 text-sm">No image</span>
                    </div>
                    @endif
                    
                    <div class="p-4">
                        <h4 class="font-semibold mb-1">
                            <a href="{{ route('recipes.show', $recipe) }}" class="hover:text-orange-500 transition">
                                {{ $recipe->title }}
                            </a>
                        </h4>
                        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($recipe->description, 60) }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>⏱️ {{ $recipe->total_time }}min</span>
                            <span>{{ ucfirst($recipe->difficulty) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Categories -->
    @if($featuredCategories->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">Browse by Category</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($featuredCategories as $category)
                <a href="{{ route('recipes.index', ['category' => $category->slug]) }}" 
                   class="bg-white rounded-lg p-6 text-center hover:shadow-lg transition group">
                    @if($category->image)
                    <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-16 h-16 mx-auto mb-4 rounded-full">
                    @else
                    <div class="w-16 h-16 mx-auto mb-4 bg-orange-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">🍽️</span>
                    </div>
                    @endif
                    <h3 class="font-semibold group-hover:text-orange-500 transition">{{ $category->name }}</h3>
                    <p class="text-gray-600 text-sm mt-1">{{ $category->description }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Top Creators -->
    @if($topCreators->count() > 0)
    <section class="py-16">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">Top Creators</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($topCreators as $creator)
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                    @if($creator->avatar)
                    <img src="{{ $creator->avatar }}" alt="{{ $creator->name }}" class="w-20 h-20 mx-auto mb-4 rounded-full">
                    @else
                    <div class="w-20 h-20 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                        <span class="text-2xl">👨‍🍳</span>
                    </div>
                    @endif
                    
                    <h3 class="font-semibold text-lg">{{ $creator->name }}</h3>
                    @if($creator->is_verified_creator)
                    <span class="text-blue-500 text-sm">✓ Verified Chef</span>
                    @endif
                    
                    @if($creator->bio)
                    <p class="text-gray-600 text-sm mt-2">{{ Str::limit($creator->bio, 80) }}</p>
                    @endif
                    
                    <div class="mt-4">
                        <span class="text-orange-500 font-semibold">{{ $creator->recipes_count }}</span>
                        <span class="text-gray-500 text-sm">recipes</span>
                    </div>
                    
                    <a href="#" class="mt-4 inline-block bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition">
                        View Profile
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Call to Action -->
    <section class="py-16 bg-orange-500 text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to start cooking?</h2>
            <p class="text-xl mb-8">Join our community of food lovers and share your culinary creations</p>
            <a href="{{ route('recipes.create') }}" 
               class="bg-white text-orange-500 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Create Your First Recipe
            </a>
        </div>
    </section>
</x-layout>