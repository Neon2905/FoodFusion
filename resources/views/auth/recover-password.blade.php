@extends('layouts.app')

@section('content')
    <div class="flex-center w-full pt-20">
        <div class="modal-card flex-center flex-col p-10 gap-4">
            <h3 class="text-display-sm mb-2">Forgot Your Password?</h3>
            <p class="text-center">
                Enter your email address below and we'll send you a link to reset your password.
            </p>
            <form method="POST" action="{{ route('password.recover') }}" class="w-full flex flex-col items-center gap-4">
                @csrf
                <input type="email" name="email" class="input-box mb-2 w-full"
                    placeholder="Enter your registered email address" required autofocus>
                <button type="submit" class="button bg-tertiary w-full">
                    Send Password Reset Link
                </button>
            </form>
            @if (session('status'))
                <div class="flex-center p-2 mx-2 w-full text-accent">
                    {{ session('status') }}
                </div>
            @endif
            @error('email')
                <div class="flex-center p-2 mx-2 w-full text-red-500">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
@endsection
