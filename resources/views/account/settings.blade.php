@extends('layouts.app', ['title' => 'Account Settings'])

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-display-sm">Account Settings</h1>

        {{-- Success --}}
        @if (session('success'))
            <div class="mb-4 p-3 rounded bg-green-50 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Edit Profile --}}
            <div class="card p-4">
                <h2 class="text-lg font-medium mb-3">Edit Profile</h2>
                <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data" novalidate>
                    @csrf

                    <div class="mb-3 flex flex-col items-center">
                        <label class="text-sm text-muted">Avatar (jpg, png, webp)</label>
                        <div class="mt-2">
                            <img src="{{ optional($profile)->profile }}" alt="avatar"
                                class="w-20 h-20 rounded-full object-cover border" />
                        </div>
                        <input type="file" name="avatar" accept="image/*" class="w-full" />
                    </div>

                    <div class="mb-3">
                        <label class="text-sm text-muted">Full name</label>
                        <input name="name" value="{{ old('name', $profile->name ?? '') }}" required
                            class="input w-full px-3 py-2 rounded border" />
                    </div>

                    <div class="mb-3">
                        <label class="text-sm text-muted">Username</label>
                        <input name="username" value="{{ old('username', optional($profile)->username ?? '') }}" required
                            class="input w-full px-3 py-2 rounded border" />
                    </div>

                    <div class="mb-3">
                        <label class="text-sm text-muted">Bio</label>
                        <textarea name="bio" rows="4" class="input w-full px-3 py-2 rounded border">{{ old('bio', optional($profile)->bio ?? '') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="button bg-primary text-white px-4 py-2 rounded">Save Profile</button>
                    </div>
                </form>
            </div>

            {{-- Password actions (link out to existing routes) --}}
            <div class="card p-4">
                <h2 class="text-lg font-medium mb-3">Password & recovery</h2>

                <p class="text-sm text-muted mb-4">To change your password securely, use the password change or recovery
                    pages. These routes are protected and handle verification securely.</p>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('password.change') }}"
                        class="button w-full text-center bg-primary text-white px-4 py-2 rounded">
                        Change password
                    </a>

                    <a href="{{ route('password.recover') }}" class="button w-full text-center border px-4 py-2 rounded">
                        Recover / Reset password
                    </a>

                    <p class="text-xs text-muted mt-2">If you've forgotten your current password, use Recover. If you know
                        your current password and want to update it, use Change.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
