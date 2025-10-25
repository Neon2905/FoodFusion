@props(['profile'])

<div class="card p-6">
    <h2 class="text-heading-lg">About {{ $profile->name }}</h2>
    <p class="text-body-md text-muted mt-2">{{ $profile->bio }}</p>

    <div class="mt-4 grid grid-cols-3 gap-4">
        <div class="p-3 bg-gray-50 rounded">
            <div class="text-sm text-muted">Followers</div>
            <div class="text-xl font-bold">{{ optional($profile)->followers()->count() ?? 0 }}</div>
        </div>
        <div class="p-3 bg-gray-50 rounded">
            <div class="text-sm text-muted">Recipes</div>
            <div class="text-xl font-bold">{{ optional($profile)->recipes()->count() ?? 0 }}</div>
        </div>
        <div class="p-3 bg-gray-50 rounded">
            <div class="text-sm text-muted">Joined</div>
            <div class="text-xl font-bold">{{ optional($profile)->created_at ? optional($profile)->created_at->format('M Y') : '-' }}</div>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="text-heading-sm">Recent recipes</h3>
        <div class="grid grid-cols-3 gap-3 mt-3">
            @foreach(optional($profile)->recipes()->latest()->take(6)->get() ?? collect() as $r)
                <a href="{{ route('recipes.show', ['slug' => $r->slug]) }}" class="block rounded overflow-hidden border">
                    <img src="{{ $r->hero_url ?? (optional($r->media->first())->url ?? '/images/placeholder.png') }}" class="w-full h-28 object-cover" alt="{{ $r->title }}">
                    <div class="p-2">
                        <div class="font-semibold">{{ $r->title }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
