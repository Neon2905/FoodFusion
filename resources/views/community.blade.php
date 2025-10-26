@extends('layouts.app', ['title' => 'Community Cookbook'])

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-display-sm">Community Cookbook</h1>
            <p class="text-muted">Share your favourite recipes and tips.</p>
        </div>

        <div class="card p-4 mb-4">
            <p class="text-body-md">Contribute recipes, comment, and interact with other cooks. Use the <a
                    href="{{ route('recipes.create.view') }}" class="text-primary">Create recipe</a> page to publish.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Reuse recipe cards; fallback to placeholder if none --}}
            @foreach (\App\Models\Recipe::latest()->take(12)->get() as $r)
                <x-recipe.card :recipe="$r" />
            @endforeach
        </div>
    </div>
@endsection
