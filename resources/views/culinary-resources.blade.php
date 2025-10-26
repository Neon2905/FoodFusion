@extends('layouts.app', ['title' => 'Culinary Resources'])

@section('content')
    <div class="max-w-5xl mx-auto">
        <h1 class="text-display-sm mb-4">Culinary Resources</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="card p-4">
                <h3 class="font-semibold">Recipe Cards (PDF)</h3>
                <p class="text-muted mt-2">Download printable recipe cards.</p>
                <ul class="mt-3 space-y-2">
                    <li><a href="/downloads/recipe-card-sample.pdf" class="text-primary">Sample recipe card (PDF)</a></li>
                </ul>
            </div>

            <div class="card p-4">
                <h3 class="font-semibold">Tutorials</h3>
                <p class="text-muted mt-2">Step-by-step guides and videos.</p>
                <ul class="mt-3 space-y-2">
                    <li><a href="#" class="text-primary">How to proof sourdough</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
