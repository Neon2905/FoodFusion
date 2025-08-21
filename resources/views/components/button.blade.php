@props(['onClick' => ''])

<button {{ $attributes->class('flex-center rounded-lg drop-shadow-[0_2px_4px_rgba(0,0,0,0.25)] cursor-pointer') }}
    onclick="{{ $onClick }}">
    {{ $slot }}
</button>
