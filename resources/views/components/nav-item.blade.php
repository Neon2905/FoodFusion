@props(['label' => null, 'href' => '#', 'active' => null])

{{-- TODO:migrate as css later --}}
<div
    {{ $attributes->class([
        $active || request()->getRequestUri() == $href //TODO: find better solution
            ? 'bg-light-gray drop-shadow-[-2px_4px_2px_rgba(0,0,0,0.25)]'
            : 'bg-transparent hover:bg-light-gray hover:drop-shadow-[-2px_4px_2px_rgba(0,0,0,0.25)]',
        'transition duration-100 ease-in-out flex-center px-[10px] rounded-full h-full cursor-pointer',
    ]) }}>
    <span class="text-heading-lg font-bold">
        <a class="no-underline" href="{{ $href }}">{{ $label }}
            {{ $slot }}</a>
    </span>
</div>
