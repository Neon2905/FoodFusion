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

    @if (auth()->check())
        <div
            class="bg-navbar-gray flex-center h-[56px] py-[8px] rounded-full border border-[rgba(191,191,191,0.5)] filter drop-shadow-[0_2px_2px_rgba(0,0,0,0.25)] px-[12px]">
            <div class="flex-center flex-row h-full gap-2">
                <x-nav-item>
                    <x-css-search class="h-[24px] w-[24px] cursor-pointer" />
                </x-nav-item>
                <x-nav-item>
                    <x-css-profile class="h-[24px] w-[24px] cursor-pointer" />
                </x-nav-item>
            </div>
        </div>
    @else
        <div class="h-[60px] py-[4px] flex-center gap-[16px]">
            <button class="nav-button w-[100px] border-[1px] h-[40px] bg-white border-primary"
                onclick="window.location.href='{{ request()->fullUrlWithQuery(['login' => 1]) }}'">
                Log In
            </button>
            <button class="nav-button w-[100px] h-[40px] bg-accent"
                onclick="window.location.href='{{ request()->fullUrlWithQuery(['register' => 1]) }}'">
                Join Us
            </button>
        </div>
    @endif
</nav>
