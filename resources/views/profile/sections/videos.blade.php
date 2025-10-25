@props(['profile'])

<div class="card p-6">
    <h2 class="text-heading-lg">Videos by {{ $profile->name }}</h2>

    <div class="mt-4 grid grid-cols-3 gap-4">
        @php $videos = optional($profile)->videos()->latest()->take(9)->get() ?? collect(); @endphp
        @if ($videos->isEmpty())
            <div class="text-muted p-6">No videos yet.</div>
        @else
            @foreach ($videos as $v)
                <div class="bg-black rounded overflow-hidden">
                    <video src="{{ $v->url ?? '' }}" class="w-full h-50 object-cover" controls muted></video>
                    <div class="p-2">{{ $v->title ?? '' }}</div>
                </div>
            @endforeach
        @endif
    </div>
</div>
