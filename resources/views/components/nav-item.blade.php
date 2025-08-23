@props(['label' => null, 'href' => '#', 'active' => null])

<div
    {{ $attributes->class([
        $active || request()->getRequestUri() == $href //TODO: find better solution
            ? 'bg-[rgba(255,255,255,0.5)] drop-shadow-[-2px_4px_2px_rgba(0,0,0,0.25)]'
            : 'bg-transparent hover:bg-[rgba(255,255,255,0.5)] hover:drop-shadow-[-2px_4px_2px_rgba(0,0,0,0.25)]',
        'transition duration-100 ease-in-out flex-center px-[10px] rounded-full h-full cursor-pointer',
    ]) }}>
    <span class="text-heading-lg font-bold">
        {{ $slot }}
        @if ($label)
            <a class="no-underline" href="{{ $href }}">{{ $label }}</a>
        @endif
    </span>
</div>
