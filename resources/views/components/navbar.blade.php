<div x-data="{ open: false }">
    <nav class="flex sticky top-0 z-50 w-full flex-row items-center justify-between pt-3 px-7">
        <a href="{{ route('home') }}" class="no-underline">
            <div class="flex items-center gap-0 text-display-md drop-shadow-[0_4px_4px_rgba(0,0,0,.5)]">
                <span class="text-primary">Food</span>
                <span class="text-accent">Fusion</span>
            </div>
        </a>

        <!-- Desktop nav links: visible md+ -->
        <div
            class="hidden lg:flex items-center justify-center bg-navbar-gray h-15 py-2 rounded-full border border-[rgba(191,191,191,0.5)] filter drop-shadow-[0_2px_2px_rgba(0,0,0,0.25)] px-[10px]">
            <div class="flex-center flex-row h-full gap-3">
                <x-nav-item label="Community" href="{{ route('community') }}" />
                <x-nav-item label="Recipes" href="{{ route('recipes') }}" />
                <div x-data="{ openResources: false }" class="relative">
                    <x-nav-item>
                        <button type="button" @click="openResources = !openResources"
                            @keydown.escape="openResources = false" :aria-expanded="openResources.toString()">
                            <span>Resources</span>
                            </svg>
                        </button>
                    </x-nav-item>

                    <div x-cloak x-show="openResources" @click.away="openResources = false" x-transition.duration.150ms
                        class="absolute right-0 mt-4 bg-navbar-gray rounded-lg shadow-lg p-4 z-50">
                        <x-nav-item href="{{ route('resources.educational') }}">Educational</x-nav-item>
                        <x-nav-item href="{{ route('resources.culinary') }}">Culinary</x-nav-item>
                    </div>
                </div>
                <x-nav-item label="About" href="{{ route('about') }}" />
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
                    <div x-data="{ openProfile: false }" class="relative">
                        <x-nav-item class="p-0">
                            <button type="button" @click="openProfile = !openProfile" @keydown.escape="openProfile = false"
                                :aria-expanded="openProfile.toString()">
                                <img src="{{ auth()->user()->profile->profile ? auth()->user()->profile->profile : asset('images/profile-icons/profile.png') }}" class="size-10 rounded-full"
                                    alt="profile">
                            </button>
                        </x-nav-item>

                        <div x-cloak x-show="openProfile" @click.away="openProfile = false" x-transition.duration.150ms
                            class="absolute right-0 mt-6 p-5 bg-gray rounded-lg shadow-lg z-50">
                            <div class="flex flex-col gap-2">
                                <x-nav-item href="{{ route('profile.show', auth()->user()->username) }}"
                                    class="items-start">
                                    Profile
                                </x-nav-item>
                                <x-nav-item href="{{ route('account.settings') }}">
                                    Settings
                                </x-nav-item>
                                <form action={{ route('logout') }} method="POST">
                                    @csrf
                                    <button class="button text-heading-sm bg-error">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
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
            <div class="flex flex-col gap-2 px-4" x-data="{ openResources: false }">
                <x-nav-item href="/community" class="block w-min text-left">Community</x-nav-item>
                <x-nav-item href="/recipes" class="block w-min text-left">Recipes</x-nav-item>
                <x-nav-item class="block w-min text-left">
                    <button type="button" @click="openResources = !openResources"
                        @keydown.escape="openResources = false" :aria-expanded="openResources.toString()">
                        <span>Resources</span>
                        </svg>
                    </button>
                </x-nav-item>

                <div x-cloak x-show="openResources" @click.away="openResources = false" x-transition.duration.150ms
                    class="pl-5">
                    <x-nav-item href="{{ route('resources.educational') }}"
                        class="block w-min text-left">Educational</x-nav-item>
                    <x-nav-item href="{{ route('resources.culinary') }}"
                        class="block w-min text-left">Culinary</x-nav-item>
                </div>
                <x-nav-item href="/about" class="block w-min text-left">About</x-nav-item>

                <hr class="my-2" />

                @auth
                    <x-nav-item href="{{ route('search') }}" class="block w-min text-left">Search</x-nav-item>
                    <x-nav-item href="{{ route('profile.show', auth()->user()->username) }}"
                        class="block w-min text-left">Profile</x-nav-item>
                    <x-nav-item href="{{ route('account.settings') }}"
                        class="block w-min text-left">settings</x-nav-item>
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
