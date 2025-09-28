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
        <x-recipe.main :recipe="$recipe" :author="$author" />
        <div class="flex flex-col gap-5">
            {{-- Ingredients --}}
            <div class="modal-card flex-center w-full px-7 py-5" x-data="{ serving_size: {{ $recipe->servings }} }">
                {{-- title --}}
                <h2 class="text-heading-lg text-primary">Ingredients</h2>
                <ul class="list-disc w-full ml-10 gap-1 flex flex-col">
                    @foreach ($recipe->ingredients as $ingredient)
                        <li class="text-body-lg font-semibold">
                            <span
                                x-text="($ingredient = {{ json_encode($ingredient) }}, ($ingredient.quantity * serving_size / {{ $recipe->servings }}) + ' ' + $ingredient.unit + ' ' + $ingredient.name)"></span>
                        </li>
                    @endforeach
                </ul>
                <div class="flex-center flex-col w-full">
                    <div class="flex-center items-center gap-4">
                        <button class="button rounded-full p-0 size-7 text-gray-700 shadow-md border border-gray-300"
                            @click="serving_size = Math.max(1, serving_size - 1)">
                            <x-css-math-minus class="size-5 font-bold" />
                        </button>
                        <h2 class="text-heading-lg pt-1" x-text="serving_size"></h2>
                        <button class="button rounded-full p-0 size-7 text-gray-700 shadow-md border border-gray-300"
                            @click="serving_size++">
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
