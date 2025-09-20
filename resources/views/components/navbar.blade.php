<nav class="sticky top-0 z-50 w-full flex flex-row items-center justify-between pt-[10px] px-[30px]">
    <div class="flex items-center gap-0 text-display-md drop-shadow-[0_4px_4px_rgba(0,0,0,.5)]">
        <span class="text-primary">
            Food
        </span>
        <span class="text-accent">
            Fusion
        </span>
    </div>
    <div
        class="bg-navbar-gray flex-center h-[60px] py-[8px] rounded-full border border-[rgba(191,191,191,0.5)] filter drop-shadow-[0_2px_2px_rgba(0,0,0,0.25)] px-[10px]">
        <div class="flex-center flex-row h-full gap-3">
            <x-nav-item label="Community" href="/" />
            <x-nav-item label="Recipes" href="/recipes" />
            <x-nav-item label="Resources" href="/resources" />
            <x-nav-item label="About" href="/about" />
        </div>
    </div>

    @auth
        <div
            class="bg-navbar-gray flex-center h-[56px] py-[8px] rounded-full border border-[rgba(191,191,191,0.5)] filter drop-shadow-[0_2px_2px_rgba(0,0,0,0.25)] px-[12px]">
            <div class="flex-center flex-row h-full gap-2">
                <x-nav-item>
                    <x-css-search class="size-6 cursor-pointer" />
                </x-nav-item>
                {{-- TODO: Optimize or create new route --}}
                <x-nav-item href="{{ route('profile.show', auth()->user()->username) }}">
                    <x-css-profile class="size-6 cursor-pointer" />
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
    @else
        <div class="h-15 py-1 flex-center gap-4">
            <button class="nav-button w-25 border-[1px] h-10 bg-white border-primary" @click="toggleLoginModal()">
                Log In
            </button>
            <button class="nav-button w-25 h-10 bg-accent" @click="toggleRegisterModal()">
                Join Us
            </button>
        </div>
    @endauth
</nav>
