@props(['label' => 'Nav Item', 'href' => '#'])

<div
    {{ $attributes->class('flex bg-transparent items-center justify-center hover:bg-[rgba(255,255,255,0.5)] filter hover:drop-shadow-[-2px_4px_2px_rgba(0,0,0,0.25)] px-[10px] rounded-full h-full cursor-pointer') }}>
    <span class="text-heading-lg font-bold">
        <a href="{{ $href }}">{{ $label }}</a>
    </span>
</div>
