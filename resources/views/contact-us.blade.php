@extends('layouts.app', ['title' => 'Contact Us'])

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="card p-6">
            <h1 class="text-2xl font-bold">Contact FoodFusion</h1>
            <p class="text-muted mt-2">Questions, feedback or recipe requests — send us a note.</p>

            @if (session('success'))
                <div class="mt-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="font-semibold">Name</label>
                    <input name="name" required class="input-box w-full" placeholder="Your name" />
                    <x-error-message names="name" />
                </div>

                <div>
                    <label class="font-semibold">Email</label>
                    <input name="email" type="email" required class="input-box w-full" placeholder="you@example.com" />
                    <x-error-message names="email" />
                </div>

                <div>
                    <label class="font-semibold">Subject</label>
                    <input name="subject" class="input-box w-full" placeholder="Subject (optional)" />
                </div>

                <div>
                    <label class="font-semibold">Message</label>
                    <textarea name="message" rows="6" required class="input-box w-full" placeholder="Tell us what's on your mind"></textarea>
                    <x-error-message names="message" />
                </div>

                <div class="flex justify-end">
                    <button class="button bg-accent" type="submit">Send Message</button>
                </div>
            </form>
        </div>
    </div>
@endsection
