@extends('layouts.app')

@section('content')
    <div class="mx-auto w-auto">
        <div class="card p-8 w-80">
            <h1 class="text-heading-lg mb-4">Change Password</h1>

            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.change') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-heading-sm font-medium">Current
                            Password</label>
                        <input id="current_password" name="current_password" type="password" required
                            class="input-box w-full" autocomplete="current-password">
                        @error('current_password')
                            <p class="mt-1 text-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-heading-sm">New Password</label>
                        <input id="password" name="password" type="password" required class="input-box w-full"
                            autocomplete="new-password">
                        @error('password')
                            <p class="mt-1 text-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-heading-sm font-medium">Confirm New
                            Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="input-box w-full" autocomplete="new-password">
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <div class="flex gap-3">
                            <button type="submit" class="button bg-accent text-white px-4 py-2 rounded">
                                Save changes
                            </button>
                            <a href="{{ auth()->check() ? route('profile.show', auth()->user()->username) : route('home') }}"
                                class="button border border-gray border-1">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
