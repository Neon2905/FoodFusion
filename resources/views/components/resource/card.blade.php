@props(['resource' => null])

<div class="card p-4" x-show="filter === 'all' || filter === '{{ $resource->type }}'">
    <a href="{{ route('resources.show', $resource->slug) }}"class="no-underline">
        <div class="flex flex-col h-full">
            @if ($resource->thumbnail_url)
                <img src="{{ $resource->thumbnail_url }}" alt="{{ $resource->title }}"
                    class="w-full h-40 object-cover rounded mb-3">
            @endif
            <h3 class="font-semibold">{{ $resource->title }}</h3>
            <p class="text-muted text-sm mb-3">{{ Str::limit($resource->description ?? '', 140) }}</p>

            <div class="mt-auto flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-muted">
                    <span class="px-2 py-1 rounded bg-gray">{{ ucfirst($resource->type) }}</span>
                    @if ($resource->tags)
                        <span class="text-xs">• {{ implode(', ', (array) $resource->tags) }}</span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    @if ($resource->type === 'card')
                        @if ($resource->file_path)
                            <a href="{{ asset('storage/' . $resource->file_path) }}" download
                                class="button bg-accent text-white">Download</a>
                        @elseif ($resource->external_url)
                            <a href="{{ $resource->external_url }}" target="_blank"
                                class="button bg-accent text-white">Open</a>
                        @endif
                    @elseif ($resource->type === 'video')
                        @if ($resource->external_url && preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $resource->external_url))
                            <a href="{{ $resource->external_url }}" target="_blank"
                                class="button bg-accent text-white">Play</a>
                        @elseif ($resource->external_url)
                            <a href="{{ $resource->external_url }}" target="_blank"
                                class="button bg-accent text-white">Watch</a>
                        @endif
                    @else
                        @if ($resource->external_url)
                            <a href="{{ $resource->external_url }}" target="_blank"
                                class="button bg-accent text-white">View</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </a>
</div>
