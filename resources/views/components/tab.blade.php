@props([
    'href' => '#',
    'active' => null,
])

<a href="{{ $href }}"
    class="py-2 px-4 no-underline text-heading-lg font-bold border-b-4 transition-all {{ $active ?? request()->is($href) ? 'border-primary' : 'border-transparent text-muted hover:border-muted' }}">
    {{ $slot }}
</a>
