@component('mail::message')
    # Hello

    You requested a password reset for {{ $user->email }}.

    @component('mail::button', ['url' => $actionUrl])
        Reset password
    @endcomponent

    If you did not request a password reset, ignore this email.

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
