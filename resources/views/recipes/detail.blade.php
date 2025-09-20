@php
    $author = $recipe->author;

    $nutrition = $recipe->nutrition;
    $nutritions = [];
    $nutritionFields = [
        'calories' => '',
        'fat' => ' g',
        'carbs' => ' g',
        'protein' => ' g',
        'fiber' => ' g',
        'sugar' => ' g',
    ];

    foreach ($nutritionFields as $field => $suffix) {
        if (!is_null($nutrition->$field)) {
            $nutritions[$field] = $nutrition->$field . $suffix;
        }
    }
@endphp

@extends('layouts.app', ['title' => $recipe->title])

@section('content')
    <div class="flex flex-col lg:flex-row gap-4 w-full">
        <div class="modal-card px-6 flex gap-7">
            {{-- header --}}
            <div class="flex-center flex-col gap-1">
                <h1 class="">{{ $recipe->title }}</h1>
                <div class="flex-center flex-col">
                    <h3>RECIPE BY {{ strtoupper($author->name) }}</h3>
                    <h4 class="text-muted">{{ $recipe->created_at->diffForHumans() }}</h4>
                </div>
                <div class="flex-center flex-col mt-2 gap-1">
                    <x-rating :value="$recipe->rating" class="w-40" />
                    {{-- TODO: optimize this count fun --}}
                    <h4 class="text-muted">{{ $recipe->reviews->count() }} Reviews</h4>
                </div>
            </div>
            {{-- media --}}
            <img src="{{ $recipe->hero_url }}" alt="hero-image">
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
            <div class="flex flex-col items-start rounded-xl w-full bg-gray px-6 py-7 gap-5">
                <div class="flex flex-col gap-1 items-start">
                    <h2 class="text-heading-lg">{{ $recipe->reviews->count() }} Reviews</h2>
                    <x-rating value="{{ $recipe->rating }}" class="w-40" />
                </div>
                <form class="flex-center flex-col space-between gap-2 w-full p-3" method="POST" action="#">
                    @csrf
                    <div class="flex justify-start gap-2 w-full">
                        @auth
                            <img class="rounded rounded-full size-13" src="{{ auth()->user()->profile->profile }}"
                                alt="">
                        @endauth
                        <textarea name="review"
                            class="flex-1 rounded-lg px-4 py-2 text-body-lg bg-background focus:outline-none focus:ring-2 focus:ring-primary resize-y min-h-20"
                            placeholder="Did you make this recipe? Leave a review!"></textarea>
                    </div>
                    <div class="flex justify-between space-between w-full">
                        <div class="flex items-center gap-2 text-subtitle-lg font-semibold">
                            Your Rating:
                            <x-rating class="w-40" />
                        </div>
                        <button
                            class="flex-center justify-between button bg-tertiary text-black text-subtitle-md font-semibold rounded-full">
                            Post Review
                        </button>
                    </div>
                </form>
                <div class="flex-center w-full flex-col gap-3">
                    <div
                        class="flex justify-end items-center w-full border-b border-on-background text-subtitle-md gap-1 pb-2">
                        Sort by
                        <x-icons.chevron-down class="ml-2" />
                    </div>
                    @foreach ($recipe->reviews as $review)
                        <div
                            class="flex flex-col justify-start items-start px-2 pb-2 w-full gap-1 border-b border-on-background">
                            {{-- Header --}}
                            <div class="flex gap-3">
                                <img class="size-10 rounded-full" src="{{ $review->reviewer->profile }}"
                                    alt="{{ $review->name }}">
                                <div class="flex flex-col">
                                    <h3>
                                        {{ $review->reviewer->name }} <span
                                            class="text-subtitle-md font-normal text-muted">
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </h3>
                                    {{-- TODO:Fix sizing issue on rating component [can't use with under 4] --}}
                                    <x-rating class="w-20" size='4' value="{{ $review->rating }}" />
                                </div>
                            </div>
                            {{-- Review --}}
                            <h3 class="font-highlight font-normal">
                                {{ $review->review }}
                            </h3>
                        </div>
                    @endforeach
                    <button class="button rounded-full text-body-md font-normal bg-tertiary">
                        Show more reviews
                    </button>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-5 w-full lg:w-2/3 lg:max-w-100">
            {{-- Ingredients --}}
            <div class="modal-card flex-center w-full px-7 py-5">
                {{-- title --}}
                <h2 class="text-heading-lg text-primary">Ingredients</h2>
                <ul class="list-disc w-full ml-10 gap-1 flex flex-col">
                    @foreach ($recipe->ingredients as $ingredient)
                        <li class="text-body-lg font-semibold">
                            {{ $ingredient->quantity }} {{ $ingredient->unit }} {{ $ingredient->name }}
                        </li>
                    @endforeach
                </ul>
                <div class="flex-center flex-col w-full">
                    <div class="flex-center items-center gap-4">
                        <button class="button rounded-full p-0 size-7 text-gray-700 shadow-md border border-gray-300">
                            <x-css-math-minus class="size-5 font-bold" />
                        </button>
                        <h2 class="text-heading-lg pt-1">{{ $recipe->servings }}</h2>
                        <button class="button rounded-full p-0 size-7 text-gray-700 shadow-md border border-gray-300">
                            <x-css-math-plus class="size-5 font-bold" />
                        </button>
                    </div>
                    <h2>Serving</h2>
                </div>
            </div>
            {{-- Categories & Tips --}}
            <div class="modal-card gap-5 w-full px-7 py-5">
                <div class="flex flex-col gap-3">
                    <h2 class="text-heading-lg">Categories</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($recipe->tags as $tag)
                            <div class="tag">
                                {{ $tag->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex flex-col gap-3">
                    <h2 class="text-heading-lg">Tips</h2>
                    <ul class="list-disc w-full ml-5 gap-1 flex flex-col">
                        @foreach ($recipe->tips as $tips)
                            <li class="text-body-lg font-semibold">
                                {{ ucfirst($tips->content) }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            {{-- Nutrition --}}
            <div class="modal-card flex-center gap-5 w-full px-7 py-5">
                <h2 class="text-heading-lg text-accent">Nutrition Info (per serving)</h2>
                @foreach ($nutritions as $name => $amount)
                    <div class="flex flex-row justify-between border-b w-full px-2 pb-2">
                        <p class="font-bold">{{ ucfirst($name) }}</p>
                        <p>{{ $amount }}</p>
                    </div>
                @endforeach
            </div>
            <div class="flex-1"></div>
        </div>
    </div>
@endsection
