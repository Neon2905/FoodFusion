<nav class="w-full flex flex-row items-center justify-between px-[30px] mt-[10px]">
    <div class="flex items-center gap-0 text-display-sm drop-shadow-[0_4px_4px_rgba(0,0,0,.75)]">
        <span class="text-primary">
            Food
        </span><span class="text-accent">
            Fusion
        </span>
    </div>
    <div
        class="bg-navbar-gray flex justify-center items-center h-[60px] py-[8px] rounded-full border border-[rgba(191,191,191,0.5)] filter drop-shadow-[0_2px_2px_rgba(0,0,0,0.25)] px-[10px]">
        <div class="flex flex-row h-full items-center gap-[26px]">
            <x-nav-item label="Community" />
            <x-nav-item label="Recipes" />
            <x-nav-item label="Resources" />
            <x-nav-item label="About" />
        </div>
    </div>

    @if (true)
        <div
            class="bg-navbar-gray flex justify-center items-center h-[60px] py-[8px] rounded-full border border-[rgba(191,191,191,0.5)] filter drop-shadow-[0_2px_2px_rgba(0,0,0,0.25)] px-[20px]">
            <div class="flex flex-row h-full justify-center items-center gap-[16px]">
                <x-css-search class="h-[32px] w-[32px] cursor-pointer" />
                <x-css-profile class="h-[32px] w-[32px] cursor-pointer" />
            </div>
        </div>
    @else
        <div class="h-[60px] py-[4px] flex items-center gap-[16px]">
            <x-button-rounded label="Log In" class="w-[132px] border-[1px] bg-white border-primary" />
            <x-button-rounded label="Join Us" class="w-[132px] bg-accent" />
        </div>
    @endif
</nav>
