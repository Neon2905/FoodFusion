<x-layout title="Recipes - FoodFusion">
    <!-- Header -->
    <section class="bg-white border-b py-8">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Discover Recipes</h1>
                    <p class="text-gray-600 mt-2">Find the perfect recipe for any occasion</p>
                </div>
                
                <!-- Search Bar -->
                <div class="flex-1 max-w-md">
                    <form method="GET" action="{{ route('recipes.index') }}" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search recipes..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-orange-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters -->
    <section class="bg-gray-50 py-6">
        <div class="container mx-auto px-6">
            <form method="GET" action="{{ route('recipes.index') }}" class="flex flex-wrap gap-4 items-center">
                <input type="hidden" name="search" value="{{ request('search') }}">
                
                <!-- Cuisine Filter -->
                <select name="cuisine" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    <option value="">All Cuisines</option>
                    <option value="italian" {{ request('cuisine') === 'italian' ? 'selected' : '' }}>Italian</option>
                    <option value="american" {{ request('cuisine') === 'american' ? 'selected' : '' }}>American</option>
                    <option value="asian" {{ request('cuisine') === 'asian' ? 'selected' : '' }}>Asian</option>
                    <option value="mexican" {{ request('cuisine') === 'mexican' ? 'selected' : '' }}>Mexican</option>
                    <option value="french" {{ request('cuisine') === 'french' ? 'selected' : '' }}>French</option>
                </select>

                <!-- Difficulty Filter -->
                <select name="difficulty" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    <option value="">All Difficulties</option>
                    <option value="easy" {{ request('difficulty') === 'easy' ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ request('difficulty') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard" {{ request('difficulty') === 'hard' ? 'selected' : '' }}>Hard</option>
                </select>

                <!-- Time Filter -->
                <select name="max_time" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    <option value="">Any Time</option>
                    <option value="15" {{ request('max_time') === '15' ? 'selected' : '' }}>Under 15 min</option>
                    <option value="30" {{ request('max_time') === '30' ? 'selected' : '' }}>Under 30 min</option>
                    <option value="60" {{ request('max_time') === '60' ? 'selected' : '' }}>Under 1 hour</option>
                </select>

                <!-- Sort Filter -->
                <select name="sort" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Highest Rated</option>
                    <option value="time" {{ request('sort') === 'time' ? 'selected' : '' }}>Quickest</option>
                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                </select>

                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
                    Filter
                </button>

                @if(request()->hasAny(['search', 'cuisine', 'difficulty', 'max_time', 'sort']))
                <a href="{{ route('recipes.index') }}" class="text-gray-500 hover:text-gray-700 underline">
                    Clear Filters
                </a>
                @endif
            </form>
        </div>
    </section>

    <!-- Results -->
    <section class="py-8">
        <div class="container mx-auto px-6">
            @if($recipes->count() > 0)
                <div class="mb-6">
                    <p class="text-gray-600">
                        Showing {{ $recipes->firstItem() }}-{{ $recipes->lastItem() }} of {{ $recipes->total() }} recipes
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($recipes as $recipe)
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
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2">
                                <a href="{{ route('recipes.show', $recipe) }}" class="hover:text-orange-500 transition">
                                    {{ $recipe->title }}
                                </a>
                            </h3>
                            
                            @if($recipe->subtitle)
                            <p class="text-gray-500 text-sm mb-2">{{ $recipe->subtitle }}</p>
                            @endif
                            
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($recipe->description, 80) }}</p>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                <div class="flex items-center space-x-3">
                                    <span>⏱️ {{ $recipe->total_time }}min</span>
                                    <span>👨‍🍳 {{ $recipe->servings }}</span>
                                    @if($recipe->rating_avg > 0)
                                    <span>⭐ {{ number_format($recipe->rating_avg, 1) }}</span>
                                    @endif
                                </div>
                                <span class="px-2 py-1 bg-orange-100 text-orange-600 rounded text-xs">
                                    {{ ucfirst($recipe->difficulty) }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    @if($recipe->user->avatar)
                                    <img src="{{ $recipe->user->avatar }}" alt="{{ $recipe->user->name }}" class="w-6 h-6 rounded-full">
                                    @else
                                    <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center">
                                        <span class="text-xs">👨‍🍳</span>
                                    </div>
                                    @endif
                                    <span class="text-sm text-gray-600">{{ $recipe->user->name }}</span>
                                    @if($recipe->user->is_verified_creator)
                                    <span class="text-blue-500 text-xs">✓</span>
                                    @endif
                                </div>
                                
                                @if($recipe->cuisine)
                                <span class="text-xs text-gray-500">{{ ucfirst($recipe->cuisine) }}</span>
                                @endif
                            </div>
                            
                            @if($recipe->tags && count($recipe->tags) > 0)
                            <div class="mt-3 flex flex-wrap gap-1">
                                @foreach(array_slice($recipe->tags, 0, 3) as $tag)
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">
                                    #{{ $tag }}
                                </span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $recipes->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-xl font-semibold mb-2">No recipes found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your search criteria or browse all recipes</p>
                    <a href="{{ route('recipes.index') }}" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">
                        View All Recipes
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Categories Section -->
    @if($categories->count() > 0)
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold text-center mb-8">Browse by Category</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                @foreach($categories as $category)
                <a href="{{ route('recipes.index', ['category' => $category->slug]) }}" 
                   class="bg-white rounded-lg p-4 text-center hover:shadow-lg transition group">
                    @if($category->image)
                    <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-12 h-12 mx-auto mb-2 rounded-full">
                    @else
                    <div class="w-12 h-12 mx-auto mb-2 bg-orange-100 rounded-full flex items-center justify-center">
                        <span class="text-lg">🍽️</span>
                    </div>
                    @endif
                    <h3 class="font-medium text-sm group-hover:text-orange-500 transition">{{ $category->name }}</h3>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</x-layout>