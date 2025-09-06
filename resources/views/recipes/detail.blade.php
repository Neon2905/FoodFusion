@php
    /*$recipe = (object) [
        'title' => 'Lemon-Garlic Chicken',
        'author' => (object) [
            'name' => 'Katie Lee Biegel',
            'profile_url' => '/images/profile-icons/katile-lee-biegel.png',
        ],
        'date' => 'Nov 29, 2023',
        'rating' => 4,
        'reviews_count' => 132,
        'image' =>
            'https://food.fnr.sndimg.com/content/dam/images/food/fullset/2022/12/19/KC3213-katie-lee-biegel-lemon-garlic-chicken_s4x3.jpg.rend.hgtvcom.826.620.suffix/1671496800903.webp',
        'total_time' => 125,
        'description' => '
                I love dishes that can be made in advance for a
                dinner party. This
                chicken is totally simple. You marinate
                it in the same dish you cook it in, then pop it in the oven about 45 minutes before you want to eat.
                It’s so
                easy and flavorful—a real crowd pleaser!',
        'prep_time' => 25,
        'level' => 'Easy',
        'cuisine' => 'Malaysian',
        'ingredients_count' => 14,
        'meal' => 'Lunch',
        'yield' => 4,
        'reviews' => [
            (object)
[
                'name' => 'Alice',
                'review' => 'Loved this recipe! Super creamy.',
                'profile_url' => '/images/profile-icons/alice.png',
                'date' => '08/07/2024',
                'rating' => 4,
            ],
            (object) [
                'name' => 'Someone',
                'review' => 'It’s very tasty when I added mushroom.',
                'profile_url' => '/images/profile-icons/someone.png',
                'date' => '20/06/2024',
                'rating' => 5,
            ],
            (object) [
                'name' => 'Bob',
                'review' => 'Added shrimp — turned out amazing.',
                'profile_url' => '/images/profile-icons/bob.png',
                'date' => '12/12/2023',
                'rating' => 4,
            ],
        ],
    ];

    $directions = [
        'Prepare' =>
            'In a small bowl, combine the olive oil, mustard, garlic powder, paprika, minced garlic, lemon zest and juice and 1 teaspoon salt.',
        'Prepare Chicken' =>
            'Arrange the chicken pieces in a single layer in a 9-by-13-inch baking dish and sprinkle with salt. Pour the marinade over the chicken and use your hands to coat the chicken. Cover and let marinate in the refrigerator for 1 hour.',
        'Preheat Oven' => 'Preheat the oven to 400 degrees F.',
        'Season & Arrange' =>
            'Sprinkle the chicken with additional salt and some pepper, then roll the pieces around and arrange them skin-side up. Scatter the lemon quarters around the dish. Sprinkle the sugar on top of the chicken and dot the dish with the butter. Pour the broth and wine around the chicken.',
        'Cook for Juice' =>
            'Bake, basting with the juices about every 15 minutes, until an instant-read thermometer inserted in the chicken registers 165 degrees F, 40 to 45 minutes.',
        'Boil the Chicken' =>
            'Turn on the broiler. Broil the chicken until it’s deep golden brown and slightly charred in spots, 2 to 3 minutes. Spoon the liquid in the pan over the chicken and sprinkle the parsley on top.',
    ]; */
    $author = $recipe->author;
@endphp

@extends('layouts.app', ['title' => $recipe->title])

@section('content')
    <div class="modal-card px-6 flex-1 gap-7">
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
                        {{ ucfirst($instruction->title) }}: <span class="font-medium">{{ ucfirst($instruction->instruction) }}</span>
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
                        <img class="rounded rounded-full size-13" src="{{ auth()->user()->profile }}" alt="">
                    @endauth
                    <textarea
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
                <div class="flex justify-end items-center w-full border-b border-on-background text-subtitle-md gap-1 pb-2">
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
                                    {{ $review->reviewer->name }} <span class="text-subtitle-md font-normal text-muted">
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
    <div class="flex flex-col items-start justify-start mr-0 gap-5 w-1/4">
        <div class="modal-card w-full h-50"></div>
        <div class="modal-card w-full h-50"></div>
        <div class="modal-card w-full h-50"></div>
        <div class="flex-1"></div>
    </div>
@endsection
