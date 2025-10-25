@props(['profile'])

<div class="card p-6">
    <h2 class="text-heading-lg">Recipes by {{ $profile->name }}</h2>

    @php $items = optional($profile)->recipes ?? (collect($recipes ?? [])); @endphp

    @if($items->isEmpty())
        <div class="text-muted p-6">No recipes yet.</div>
    @else
        <div class="grid grid-cols-3 gap-4 mt-4">
            @foreach($items as $r)
                <a href="{{ route('recipes.show', ['slug' => $r->slug]) }}" class="block rounded overflow-hidden border">
                    <img src="{{ $r->hero_url ?? (optional($r->media->first())->url ?? '/images/placeholder.png') }}" class="w-full h-40 object-cover" alt="{{ $r->title }}">
                    <div class="p-3">
                        <h3 class="font-semibold">{{ $r->title }}</h3>
                        <p class="text-muted text-sm mt-1">{{ \Illuminate\Support\Str::limit($r->description ?? $r->excerpt ?? '', 110) }}</p>
                        <div class="flex items-center justify-between mt-2 text-sm text-muted">
                            <span>{{ $r->created_at ? $r->created_at->diffForHumans() : '' }}</span>
                            <span>{{ $r->likes_count ?? $r->likes ?? 0 }} likes</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
