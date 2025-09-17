<div class="flex-center rounded-full bg-gray-200 p-1 h-[40px]">
    <x-nav-item href="{{ route('oauth.redirect', ['provider' => 'facebook']) }}">
        <img src="{{ asset('images/logos/facebook.svg') }}" alt="Facebook Logo" class="h-[24px] w-[24px]">
    </x-nav-item>
    <x-nav-item href="{{ route('oauth.redirect', ['provider' => 'google']) }}">
        <img src="{{ asset('images/logos/google.svg') }}" alt="Google Logo" class="h-[24px] w-[24px]">
    </x-nav-item>
    <x-nav-item href="{{ route('oauth.redirect', ['provider' => 'apple']) }}">
        <img src="{{ asset('images/logos/apple.svg') }}" alt="Apple Logo" class="h-[24px] w-[24px]">
    </x-nav-item>
</div>
