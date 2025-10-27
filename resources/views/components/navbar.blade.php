<div x-data="{ open: false }">
    <nav class="flex sticky top-0 z-50 w-full flex-row items-center justify-between pt-3 px-7">
        <div class="flex items-center gap-0 text-display-md drop-shadow-[0_4px_4px_rgba(0,0,0,.5)]">
            <span class="text-primary">Food</span>
            <span class="text-accent">Fusion</span>
        </div>

        <!-- Desktop nav links: visible md+ -->
        <div
            class="hidden lg:flex items-center justify-center bg-navbar-gray h-15 py-2 rounded-full border border-[rgba(191,191,191,0.5)] filter drop-shadow-[0_2px_2px_rgba(0,0,0,0.25)] px-[10px]">
            <div class="flex-center flex-row h-full gap-3">
                <x-nav-item label="Community" href="/community" />
                <x-nav-item label="Recipes" href="/recipes" />
                <x-nav-item label="Resources" href="/resources" />
                <x-nav-item label="About" href="/about" />
            </div>
        </div>

        @auth
            <!-- Right-side icons (desktop) -->
            <div
                class="hidden lg:flex items-center justify-center bg-navbar-gray h-14 py-2 rounded-full border border-[rgba(191,191,191,0.5)] filter drop-shadow-[0_2px_2px_rgba(0,0,0,0.25)] px-[12px]">
                <div class="flex-center flex-row h-full gap-2">
                    <x-nav-item href="{{ route('search') }}">
                        <x-css-search class="size-6 cursor-pointer" />
                    </x-nav-item>
                    <x-nav-item href="{{ route('profile.show', auth()->user()->username) }}">
                        <img src="{{ auth()->user()->profile->profile }}" class="size-7 rounded-full" alt="profile">
                    </x-nav-item>
                    <x-nav-item class="flex-center">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="flex-center" type="submit">
                                <x-css-push-right class="size-6 cursor-pointer" />
                            </button>
                        </form>
                    </x-nav-item>
                </div>
            </div>

            <!-- Mobile hamburger (visible < md) -->
            <button @click="open = !open" aria-label="Toggle menu" class="lg:hidden p-2">
                <!-- simple hamburger -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        @else
            <!-- Guest controls for desktop -->
            <div class="hidden lg:flex justify-center items-center h-15 py-1 gap-4">
                <button class="nav-button w-25 border-[1px] h-10 bg-white border-primary" @click="toggleLoginModal()">
                    Log In
                </button>
                <button class="nav-button w-25 h-10 bg-accent" @click="toggleRegisterModal()">
                    Join Us
                </button>
            </div>

            <!-- Mobile hamburger for guests (visible < md) -->
            <button @click="open = !open" aria-label="Toggle menu" class="md:hidden p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        @endauth
    </nav>

    <!-- Mobile menu (small screens only) -->
    <div x-cloak x-show="open" @click.away="open = false" class="lg:hidden">
        <div class="bg-white border-t shadow-sm py-4">
            <div class="flex flex-col flex-center gap-2 px-4">
                <x-nav-item href="/community" class="block w-min text-left">Community</x-nav-item>
                <x-nav-item href="/recipes" class="block w-min text-left">Recipes</x-nav-item>
                <x-nav-item href="/resources" class="block w-min text-left">Resources</x-nav-item>
                <x-nav-item href="/about" class="block w-min text-left">About</x-nav-item>

                <hr class="my-2" />

                @auth
                    <x-nav-item href="{{ route('search') }}" class="block w-min text-left">Search</x-nav-item>
                    <x-nav-item href="{{ route('profile.show', auth()->user()->username) }}"
                        class="block w-min text-left">Profile</x-nav-item>
                    <form method="POST" action="{{ route('logout') }}" class="w-full flex-center">
                        @csrf
                        <button type="submit" class="button rounded-full bg-primary">Log out</button>
                    </form>
                @else
                    <div class="flex-center flex-col gap-2">
                        <button class="w-25 button rounded-full bg-primary" @click="open = false; toggleLoginModal()">
                            Log In
                        </button>
                        <button class="w-25 button rounded-full bg-accent" @click="open = false; toggleRegisterModal()">
                            Join Us
                        </button>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
