<x-layout title="{{ $recipe->title }} - FoodFusion">
    <!-- Recipe Header -->
    <section class="bg-white border-b">
        <div class="container mx-auto px-6 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recipe Image -->
                <div>
                    @if($recipe->images && count($recipe->images) > 0)
                    <img src="{{ $recipe->images[0]['url'] ?? '/images/recipe-placeholder.jpg' }}" 
                         alt="{{ $recipe->title }}" 
                         class="w-full h-96 object-cover rounded-lg shadow-md">
                    @else
                    <div class="w-full h-96 bg-gray-200 rounded-lg shadow-md flex items-center justify-center">
                        <span class="text-gray-500 text-xl">No image available</span>
                    </div>
                    @endif
                </div>

                <!-- Recipe Info -->
                <div>
                    <div class="mb-4">
                        @if($recipe->tags && count($recipe->tags) > 0)
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach($recipe->tags as $tag)
                            <span class="px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-sm">
                                #{{ $tag }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $recipe->title }}</h1>
                        
                        @if($recipe->subtitle)
                        <p class="text-xl text-gray-600 mb-4">{{ $recipe->subtitle }}</p>
                        @endif
                    </div>

                    <p class="text-gray-700 mb-6 leading-relaxed">{{ $recipe->description }}</p>

                    <!-- Recipe Meta -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="text-2xl mb-1">⏱️</div>
                            <div class="text-sm text-gray-600">Prep Time</div>
                            <div class="font-semibold">{{ $recipe->prep_time ?? 0 }}min</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="text-2xl mb-1">🔥</div>
                            <div class="text-sm text-gray-600">Cook Time</div>
                            <div class="font-semibold">{{ $recipe->cook_time ?? 0 }}min</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="text-2xl mb-1">👨‍🍳</div>
                            <div class="text-sm text-gray-600">Servings</div>
                            <div class="font-semibold">{{ $recipe->servings }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="text-2xl mb-1">📊</div>
                            <div class="text-sm text-gray-600">Difficulty</div>
                            <div class="font-semibold">{{ ucfirst($recipe->difficulty) }}</div>
                        </div>
                    </div>

                    <!-- Creator Info -->
                    <div class="flex items-center space-x-4 mb-6 p-4 bg-gray-50 rounded-lg">
                        @if($recipe->user->avatar)
                        <img src="{{ $recipe->user->avatar }}" alt="{{ $recipe->user->name }}" class="w-12 h-12 rounded-full">
                        @else
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-xl">👨‍🍳</span>
                        </div>
                        @endif
                        <div>
                            <div class="flex items-center space-x-2">
                                <h3 class="font-semibold">{{ $recipe->user->name }}</h3>
                                @if($recipe->user->is_verified_creator)
                                <span class="text-blue-500" title="Verified Creator">✓</span>
                                @endif
                            </div>
                            @if($recipe->user->bio)
                            <p class="text-sm text-gray-600">{{ Str::limit($recipe->user->bio, 60) }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Rating and Actions -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            @if($recipe->rating_avg > 0)
                            <div class="flex items-center space-x-1">
                                <span class="text-yellow-400 text-xl">⭐</span>
                                <span class="font-semibold">{{ number_format($recipe->rating_avg, 1) }}</span>
                                <span class="text-gray-600">({{ $recipe->rating_count }} reviews)</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition">
                                💾 Save
                            </button>
                            <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 transition">
                                📤 Share
                            </button>
                            <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 transition">
                                🖨️ Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recipe Content -->
    <section class="py-8">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Ingredients -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                        <h2 class="text-2xl font-bold mb-4">Ingredients</h2>
                        
                        <!-- Servings Adjuster -->
                        <div class="flex items-center justify-between mb-6 p-3 bg-gray-50 rounded">
                            <span class="font-medium">Servings:</span>
                            <div class="flex items-center space-x-3">
                                <button class="w-8 h-8 bg-orange-500 text-white rounded-full hover:bg-orange-600 transition">-</button>
                                <span class="font-semibold text-lg">{{ $recipe->servings }}</span>
                                <button class="w-8 h-8 bg-orange-500 text-white rounded-full hover:bg-orange-600 transition">+</button>
                            </div>
                        </div>

                        @if($recipe->recipeIngredients->count() > 0)
                        <ul class="space-y-3">
                            @foreach($recipe->recipeIngredients as $recipeIngredient)
                            <li class="flex items-start space-x-3">
                                <input type="checkbox" class="mt-1.5 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                                <div class="flex-1">
                                    <span class="text-gray-900">{{ $recipeIngredient->text }}</span>
                                    @if($recipeIngredient->optional)
                                    <span class="text-sm text-gray-500">(optional)</span>
                                    @endif
                                    @if($recipeIngredient->notes)
                                    <div class="text-sm text-gray-600 mt-1">{{ $recipeIngredient->notes }}</div>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @endif

                        <!-- Nutrition Info (if available) -->
                        @if($recipe->nutrition)
                        <div class="mt-6 pt-6 border-t">
                            <h3 class="font-semibold mb-3">Nutrition (per serving)</h3>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                @if(isset($recipe->nutrition['calories']))
                                <div>
                                    <span class="text-gray-600">Calories:</span>
                                    <span class="font-medium">{{ $recipe->nutrition['calories'] }}</span>
                                </div>
                                @endif
                                @if(isset($recipe->nutrition['protein']))
                                <div>
                                    <span class="text-gray-600">Protein:</span>
                                    <span class="font-medium">{{ $recipe->nutrition['protein'] }}g</span>
                                </div>
                                @endif
                                @if(isset($recipe->nutrition['carbs']))
                                <div>
                                    <span class="text-gray-600">Carbs:</span>
                                    <span class="font-medium">{{ $recipe->nutrition['carbs'] }}g</span>
                                </div>
                                @endif
                                @if(isset($recipe->nutrition['fat']))
                                <div>
                                    <span class="text-gray-600">Fat:</span>
                                    <span class="font-medium">{{ $recipe->nutrition['fat'] }}g</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Instructions -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold mb-6">Instructions</h2>
                        
                        @if($recipe->steps->count() > 0)
                        <div class="space-y-6">
                            @foreach($recipe->steps as $step)
                            <div class="flex space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center font-semibold">
                                        {{ $step->order }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-900 leading-relaxed">{{ $step->description }}</p>
                                    
                                    @if($step->duration || $step->temperature)
                                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                        @if($step->duration)
                                        <span>⏱️ {{ $step->duration }} minutes</span>
                                        @endif
                                        @if($step->temperature)
                                        <span>🌡️ {{ $step->temperature }}</span>
                                        @endif
                                    </div>
                                    @endif
                                    
                                    @if($step->timer)
                                    <button class="mt-2 px-3 py-1 bg-blue-100 text-blue-600 rounded text-sm hover:bg-blue-200 transition">
                                        ⏰ Start Timer
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Video Section (if available) -->
                        @if($recipe->hero_video)
                        <div class="mt-8 pt-8 border-t">
                            <h3 class="text-xl font-semibold mb-4">Cooking Video</h3>
                            <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500">Video player would go here</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    @if($recipe->reviews->count() > 0)
    <section class="py-8 bg-gray-50">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold mb-6">Reviews ({{ $recipe->reviews->count() }})</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($recipe->reviews->take(6) as $review)
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <div class="flex items-center space-x-3 mb-3">
                        @if($review->user->avatar)
                        <img src="{{ $review->user->avatar }}" alt="{{ $review->user->name }}" class="w-10 h-10 rounded-full">
                        @else
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-sm">👤</span>
                        </div>
                        @endif
                        <div>
                            <h4 class="font-semibold">{{ $review->user->name }}</h4>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                <span class="text-yellow-400">{{ $i <= $review->rating ? '⭐' : '☆' }}</span>
                                @endfor
                            </div>
                        </div>
                    </div>
                    
                    @if($review->comment)
                    <p class="text-gray-700">{{ $review->comment }}</p>
                    @endif
                    
                    <div class="text-sm text-gray-500 mt-3">
                        {{ $review->created_at->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Related Recipes -->
    @if($relatedRecipes->count() > 0)
    <section class="py-8">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold mb-6">You might also like</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedRecipes as $relatedRecipe)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    @if($relatedRecipe->images && count($relatedRecipe->images) > 0)
                    <img src="{{ $relatedRecipe->images[0]['url'] ?? '/images/recipe-placeholder.jpg' }}" 
                         alt="{{ $relatedRecipe->title }}" 
                         class="w-full h-32 object-cover">
                    @else
                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500 text-sm">No image</span>
                    </div>
                    @endif
                    
                    <div class="p-4">
                        <h4 class="font-semibold mb-1">
                            <a href="{{ route('recipes.show', $relatedRecipe) }}" class="hover:text-orange-500 transition">
                                {{ $relatedRecipe->title }}
                            </a>
                        </h4>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>⏱️ {{ $relatedRecipe->total_time }}min</span>
                            <span>{{ ucfirst($relatedRecipe->difficulty) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</x-layout>