@props(['label' => 'Button', 'onClick' => ''])

<button
    {{ $attributes->class('items-center justify-center rounded-full h-full drop-shadow-[0_2px_4px_rgba(0,0,0,0.25)] cursor-pointer') }}
    onclick="{{ $onClick }}">
    <span class="text-heading-lg font-semibold">
        {{ $label }}
    </span>
</button>
