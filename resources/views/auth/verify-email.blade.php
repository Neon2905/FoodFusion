@extends('layouts.app')

@php
    $status = session('session_status', 'default');
    $cooldown = session('cooldown', $cooldown ?? 0);
@endphp

@section('content')
    <div class="flex-center w-full pt-20">
        <div class="card flex-center flex-col p-10 w-full max-w-lg">
            <h3 class="text-display-sm mb-4">Verify Your Email Address</h3>

            <p class="text-center mb-4">
                We'll send a verification link to
                <span class="font-semibold">
                    {{ mask_email(auth()->user()->email) }}
                </span>.
                Click the link in the email to verify your account.
            </p>

            @if (session('message'))
                <div class="flex-center p-2 mx-2 w-full text-accent mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <p class="text-center mb-4 text-sm text-gray-600">
                If you didn't receive the email, you can request a new one.
            </p>

            <form id="resend-form" method="POST" action="{{ route('verification.send') }}" class="w-full flex flex-col items-center">
                @csrf
                <button
                    id="resend-button"
                    type="submit"
                    class="button bg-tertiary w-full py-3 rounded-md text-white disabled:opacity-60"
                    data-initial-cooldown="{{ $cooldown }}">
                    <span id="resend-label">
                        @if($cooldown > 0)
                            Resend ({{ $cooldown }}s)
                        @else
                            Send verification email
                        @endif
                    </span>
                </button>
            </form>

            <p class="text-center mt-4 text-sm text-gray-500">
                Session status: {{ $status }}
            </p>
        </div>
    </div>

    <script>

        // TODO: Optimize and put this somewhere
        (function () {
            const btn = document.getElementById('resend-button');
            const label = document.getElementById('resend-label');
            let remaining = parseInt(btn.getAttribute('data-initial-cooldown') || '0', 10);

            function startCountdown(seconds) {
                remaining = seconds;
                if (remaining <= 0) {
                    btn.disabled = false;
                    label.textContent = 'Send verification email';
                    return;
                }

                btn.disabled = true;
                label.textContent = `Resend (${remaining}s)`;

                const iv = setInterval(() => {
                    remaining--;
                    if (remaining <= 0) {
                        clearInterval(iv);
                        btn.disabled = false;
                        label.textContent = 'Send verification email';
                    } else {
                        label.textContent = `Resend (${remaining}s)`;
                    }
                }, 1000);
            }

            // initialize from server-provided cooldown
            if (remaining > 0) {
                startCountdown(remaining);
            }

            // optimistic client-side disable to avoid double-click and immediate retriggers
            document.getElementById('resend-form').addEventListener('submit', function () {
                btn.disabled = true;
                btn.classList.add('opacity-60');
                label.textContent = 'Sending...';
            });
        })();
    </script>
@endsection
