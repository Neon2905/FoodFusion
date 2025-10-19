@extends('layouts.app')

@section('content')
    <div class="flex-center w-full pt-20">
        <div class="card flex-center w-auto flex-col p-10 gap-2">
            <h1>Reset Your Password</h1>
            <p class="text-center">
                Enter your new password below.
            </p>
            <form method="POST" action="{{ route('password.recover.submit', ['token' => $token]) }}"
                class="w-full flex flex-col items-center gap-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <input type="password" id="password" name="password" class="input-box mb-2 w-full"
                    placeholder="New password" required minlength="8">

                <input type="password" id="password_confirmation" name="password_confirmation" class="input-box mb-2 w-full"
                    placeholder="Confirm new password" required minlength="8">

                <button type="submit" class="button bg-tertiary w-full">
                    Reset Password
                </button>
            </form>

            @if (session('status'))
                <div class="flex-center p-2 mx-2 w-full text-accent">
                    {{ session('status') }}
                </div>
            @endif
            @error('password')
                <div class="flex-center p-2 mx-2 w-full text-red-500">
                    {{ $message }}
                </div>
            @enderror
            @error('token')
                <div class="flex-center p-2 mx-2 w-full text-red-500">
                    {{ $message }}
                </div>
            @enderror
            @error('email')
                <div class="flex-center p-2 mx-2 w-full text-red-500">
                    {{ $message }}
                </div>
            @enderror
            @error('token')
                <div class="flex-center p-2 mx-2 w-full text-red-500">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
@endsection
