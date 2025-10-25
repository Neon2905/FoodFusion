@extends('layouts.app')

@section('content')
    <div class="flex-center flex-col w-full min-h-[70vh]">
        <div class="card w-full max-w-xl px-8 py-10 flex flex-col gap-6">
            <h2 class="text-heading-lg flex-center mb-2">Set Up Your Profile</h2>

            @if ($errors->any())
                <div class="w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2">
                    <ul class="mb-0 pl-5 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.setup.submit') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col gap-5">
                @csrf

                <div class="flex flex-col gap-2">
                    <label for="name" class="font-semibold text-subtitle-lg">Display Name</label>
                    <input type="text" name="name" id="name" class="input-box"
                        value="{{ old('name', auth()->user()->name) }}" required maxlength="255">
                </div>

                <div class="flex flex-col gap-2">
                    <label for="username" class="font-semibold text-subtitle-lg">Username</label>
                    <input type="text" name="username" id="username" class="input-box"
                        value="{{ old('username', auth()->user()->username) }}" required maxlength="50" autocomplete="username">
                </div>

                <div class="flex flex-col gap-2">
                    <label for="bio" class="font-semibold text-subtitle-lg">Bio</label>
                    <textarea name="bio" id="bio" class="input-box resize-y min-h-20" maxlength="1000" rows="4">{{ old('bio', optional(auth()->user()->profile)->bio) }}</textarea>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="avatar" class="font-semibold text-subtitle-lg">Avatar <span class="text-muted">(optional,
                            max 2MB)</span></label>
                    <input type="file" name="avatar" id="avatar" class="input-box" accept="image/*">
                </div>

                <button type="submit" class="button bg-tertiary text-white rounded-full w-full mt-2">
                    Save Profile
                </button>
            </form>
        </div>
    </div>
@endsection
