@extends('layouts.app', ['title' => 'About FoodFusion'])

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="card p-6">
            <h1 class="text-2xl font-bold">About FoodFusion</h1>
            <p class="text-muted mt-3">
                FoodFusion is a community for home cooks and creators — share recipes, learn techniques, and connect.
                Our mission is to inspire home cooking by making great content easy to find and share.
            </p>

            <h2 class="mt-6 font-semibold">Our Values</h2>
            <ul class="list-disc pl-5 text-body-md mt-2 text-muted">
                <li>Community-first sharing</li>
                <li>Accessibility & inclusivity</li>
                <li>Practical, trustworthy recipes</li>
            </ul>

            <h2 class="mt-6 font-semibold">Team</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                <div class="p-4 border rounded">
                    <h3 class="font-bold">Alex Chef</h3>
                    <p class="text-muted">Founder & Culinary Director</p>
                </div>
                <div class="p-4 border rounded">
                    <h3 class="font-bold">Sam Dev</h3>
                    <p class="text-muted">Product & Engineering</p>
                </div>
            </div>
        </div>
    </div>
@endsection
