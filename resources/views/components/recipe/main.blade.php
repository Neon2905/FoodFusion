@props(['recipe', 'author' => $recipe->author])

@php
    $userReview = $recipe->reviews->firstWhere('profile_id', auth()->user()->profile->id);
    $reviews = $recipe->reviews->filter(fn($review) => $review->profile_id !== auth()->user()->profile->id);
@endphp

<div class="modal-card px-6 flex gap-7 lg:max-w-3/4">
    {{-- header --}}
    <div class="flex-center flex-col gap-1">
        <h1 class="text-center">{{ $recipe->title }}</h1>
        <div class="flex-center flex-col">
            <h3>RECIPE BY {{ strtoupper($author->name) }}</h3>
            <h4 class="text-muted">{{ $recipe->created_at->diffForHumans() }}</h4>
        </div>
        <div class="flex-center flex-col mt-2 gap-1">
            <x-rating :value="$recipe->rating" class="w-40" />
            {{-- TODO: optimize this count fun --}}
            <h4 class="text-muted">{{ $reviews->count() }} Reviews</h4>
        </div>
    </div>
    {{-- media --}}
    <x-media-container :media="$recipe->media" :hero="$recipe->hero_url" />
    {{-- summary --}}
    <div class="flex-center border border-navbar-gray h-30 p-2">
        <div class="flex flex-center flex-1 h-full">
            <div class="flex-center flex-col items-start h-25 gap-3 text-left">
                <h4 class="w-full">
                    <span class="font-normal">Ready In:</span>
                    {{-- TODO:abstract this. --}}
                    {{ floor($recipe->total_time / 60) > 0 ? floor($recipe->total_time / 60) . ' hr ' : '' }}{{ $recipe->total_time % 60 > 0 ? $recipe->total_time % 60 . ' mins' : '' }}
                </h4>
                <h4 class="w-full">
                    <span class="font-normal">Prep Time:</span>
                    {{ $recipe->prep_time }} mins
                </h4>
            </div>
        </div>
        <div class="flex flex-center flex-1 h-full border-x border-navbar-gray">
            <div class="flex-center flex-col items-start h-25 gap-3 text-left">
                <h4 class="w-full">
                    <span class="font-normal">Level:</span>
                    <span class="text-accent">Easy</span>
                </h4>
                <h4 class="w-full">
                    <span class="font-normal">Ingredients:</span>
                    {{ $recipe->ingredients()->count() }}
                </h4>
            </div>
        </div>
        <div class="flex flex-center flex-1 h-full">
            <div class="flex-center flex-col items-start h-25 gap-3 text-left">
                <h4 class="w-full">
                    <span class="font-normal">Cuisine:</span>
                    {{ $recipe->cuisine }}
                </h4>
                <h4 class="w-full">
                    <span class="font-normal">Meal:</span>
                    {{ $recipe->meal }}
                </h4>
                <h4 class="w-full">
                    <span class="font-normal">Yield:</span>
                    {{ $recipe->yield }} servings
                </h4>
            </div>
        </div>
    </div>
    {{-- description --}}
    <div class="px-3 w-full">
        <h3 class="text-heading-md font-highlight font-medium">
            {{ $recipe->description }}
        </h3>
    </div>
    {{-- directions --}}
    <div class="flex flex-col text-left w-full p-2 gap-2">
        <h2 class="text-heading-lg">Directions:</h2>
        <ol class="list-decimal list-inside space-y-2 px-2 text-body-lg font-semibold">
            @foreach ($recipe->steps as $instruction)
                <li>
                    {{ ucfirst($instruction->title) }}: <span
                        class="font-medium">{{ ucfirst($instruction->instruction) }}</span>
                </li>
            @endforeach
        </ol>
    </div>
    {{-- author --}}
    <div class="flex items-center justify-between rounded-xl w-full bg-gray px-6 py-3">
        <div class="flex gap-4">
            <img class="size-23 rounded-full" src="{{ $author->profile }}" alt="profile">
            <div class="flex justify-center flex-col text-left">
                <h2 class="text-heading-lg">By {{ $author->name }}</h2>
                <h5 class="font-normal">{{ $author->name }}</h5>
            </div>
        </div>
        <button class="flex-center button h-10 rounded-full bg-light-gray w-auto px-4">
            <h2 class="text-heading-lg">Follow</h2>
            <x-icons.user-plus class="text-gray-700" />
        </button>
    </div>
    {{-- reviews --}}
    <div x-data="{ showCount: 3 }" class="flex flex-col items-start rounded-xl w-full bg-gray px-6 py-7 gap-5">
        <div class="flex flex-col gap-1 items-start">
            <h2 class="text-heading-lg">{{ $recipe->reviews->count() }} Reviews</h2>
            <x-rating value="{{ $recipe->rating }}" />
        </div>
        @if ($userReview)
            <div class="flex-center p-3 w-full justify-between border border-muted rounded-lg"
                x-data="{ isEditing: false }">
                {{-- TODO: work on update feature --}}
                <div class="flex flex-col w-full">
                    {{-- Header --}}
                    <div class="flex gap-3">
                        <img class="size-10 rounded-full" src="{{ auth()->user()->profile->profile }}"
                            alt="{{ auth()->user()->name }}">
                        <div class="flex flex-col">
                            <h3>
                                {{ auth()->user()->profile->name }}
                                <span class="text-subtitle-md font-normal text-muted">
                                    {{ $userReview->created_at->diffForHumans() }}
                                </span>
                            </h3>
                            <x-rating class="w-20" size='4' value="{{ $userReview->rating }}" />
                        </div>
                    </div>
                    {{-- Review --}}
                    <h3 class="font-highlight font-normal">
                        {{ $userReview->review }}
                    </h3>
                </div>
                <button class="button rounded-md text-body-md font-bold bg-tertiary h-6 mr-2"
                    @click="isEditing = true">Edit</button>
            </div>
        @else
            <x-recipe.review-submit slug="{{ $recipe->slug }}" />
        @endif
        <div class="flex-center w-full flex-col gap-3">
            {{-- TODO: Work on sorting feature --}}
            <div class="flex justify-end items-center w-full border-b border-muted text-subtitle-md gap-1 pb-2">
                Sort by
                <x-icons.chevron-down class="ml-2" />
            </div>
            @foreach ($reviews as $i => $review)
                <div x-show="{{ $i }} < showCount"
                    class="flex flex-col justify-start items-start px-2 pb-2 w-full gap-1 border-b border-muted">
                    {{-- Header --}}
                    <div class="flex gap-3">
                        <img class="size-10 rounded-full" src="{{ $review->reviewer->profile }}"
                            alt="{{ $review->reviewer->name }}">
                        <div class="flex flex-col">
                            <h3>
                                {{ $review->reviewer->name }}
                                <span class="text-subtitle-md font-normal text-muted">
                                    {{ $review->created_at->diffForHumans() }}
                                </span>
                            </h3>
                            <x-rating class="w-20" size='4' value="{{ $review->rating }}" />
                        </div>
                    </div>
                    {{-- Review --}}
                    <h3 class="font-highlight font-normal">
                        {{ $review->review }}
                    </h3>
                </div>
            @endforeach
            <template x-if="showCount < {{ $reviews->count() }}">
                <button class="button rounded-full text-body-md font-normal bg-tertiary"
                    @click="showCount = showCount + 3">
                    Show more reviews
                </button>
            </template>
        </div>
    </div>
</div>
