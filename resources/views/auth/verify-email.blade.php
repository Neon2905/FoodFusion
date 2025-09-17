@extends('layouts.app')

@section('content')
    <div class="flex-center w-full pt-20">
        <div class="modal-card flex-center flex-col p-10">
            <h3 class="text-display-sm mb-4">Verify Your Email Address</h3>
            <p class="text-center mb-6">
                Before proceeding, please check your email
                <span class="font-semibold">
                    {{-- TODO: fix later --}}
                    {{ mask_email(auth()->user()->email) }}
                </span> for
                a verification link.<br>
                If you did not receive the email,
            </p>
            <form method="POST" action="{{ route('verification.send') }}" class="w-full flex flex-col items-center">
                @csrf
                <button type="submit" class="button bg-tertiary">
                    Click here to request another
                </button>
            </form>
            @if (session('message'))
                <div class="flex-center p-2 mx-2 w-full text-accent">
                    {{ session('message') }}
                </div>
            @endif
        </div>
    </div>
@endsection
