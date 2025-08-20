@props([
    'triggerText' => 'Open Login',
    'open' => false,
])

<div x-data="{ open: @js((bool) $open) }" x-show="open" class="modal flex-center">
    <div class="bg-background p-[20px] rounded-lg drop-shadow-[0px_4px_4px_rgba(0,0,0,0.25)] w-[380px] h-[510px]">
        <div class="flex justify-end w-full">
            <button type="button" class="text-gray-400 hover:text-gray-800" aria-label="Close" @click="open = false">
                <x-css-close class="h-[24px] w-[24px]" />
            </button>
        </div>

        {{-- Example: Trigger text inside modal --}}
        <div class="mt-4 text-center">
            <span>{{ $triggerText }}</span>
        </div>
    </div>
</div>
