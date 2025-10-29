@extends('layouts.app', ['title' => $resource->title ?? 'Resource'])

@section('content')
    <div class="mx-auto max-w-4xl">
        <div class="card p-6 mb-6">
            <div class="flex items-start gap-6">
                <div class="w-40 h-40 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                    @if ($resource->thumbnail_url)
                        <img src="{{ $resource->thumbnail_url }}" alt="{{ $resource->title }}"
                            class="w-full h-full object-cover">
                    @elseif($resource->file_path && preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $resource->file_path))
                        <img src="{{ asset('storage/' . $resource->file_path) }}" alt="{{ $resource->title }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-muted">No image</div>
                    @endif
                </div>

                <div class="flex-1">
                    <h1 class="text-display-sm mb-2">{{ $resource->title }}</h1>

                    <div class="flex items-center gap-3 text-sm text-muted mb-3">
                        <span class="px-2 py-1 rounded bg-gray">{{ ucfirst($resource->category ?? 'culinary') }}</span>
                        <span class="px-2 py-1 rounded bg-gray">{{ ucfirst($resource->type ?? 'tutorial') }}</span>
                        @if ($resource->duration)
                            <span>{{ gmdate('H:i:s', $resource->duration) }}</span>
                        @endif
                        <span class="text-xs">Published: {{ optional($resource->created_at)->format('M j, Y') }}</span>
                        @if ($resource->author)
                            <span>• By <span class="font-semibold">{{ $resource->author->name }}</span></span>
                        @endif
                    </div>

                    <p class="text-body-md text-muted mb-4">{{ $resource->description ?? 'No description provided.' }}</p>

                    @if ($resource->tags)
                        <div class="flex gap-2 mb-4">
                            @foreach ((array) $resource->tags as $t)
                                <span class="text-xs px-2 py-1 bg-gray rounded">{{ $t }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex items-center gap-3">
                        {{-- Local file --}}
                        @if ($resource->file_path)
                            @if ($resource->type === 'card')
                                <a href="{{ asset('storage/' . $resource->file_path) }}" download
                                    class="button bg-accent text-white">Download</a>
                            @elseif($resource->type === 'video' && preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $resource->file_path))
                                <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank"
                                    class="button bg-accent text-white">Play</a>
                            @else
                                <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank"
                                    class="button bg-accent text-white">Open</a>
                            @endif
                        @endif

                        {{-- External URL --}}
                        @if ($resource->external_url)
                            @if (preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $resource->external_url))
                                <a href="{{ $resource->external_url }}" target="_blank"
                                    class="button bg-accent text-white">Play</a>
                            @else
                                <a href="{{ $resource->external_url }}" target="_blank"
                                    class="button bg-accent text-white">Open link</a>
                            @endif
                        @endif

                        {{-- Fallback: no actionable link --}}
                        @if (!$resource->file_path && !$resource->external_url)
                            <span class="text-sm text-muted">No downloadable or external link available.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- If it's a video and local/external source is embeddable, show player --}}
        @php
            $videoUrl = $resource->file_path ? asset('storage/' . $resource->file_path) : $resource->external_url;
        @endphp

        @if ($videoUrl && preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $videoUrl))
            <div class="card p-4 mb-6">
                <video controls class="w-full rounded">
                    <source src="{{ $videoUrl }}"
                        type="video/{{ pathinfo(parse_url($videoUrl, PHP_URL_PATH), PATHINFO_EXTENSION) }}">
                    Your browser does not support the video tag.
                </video>
            </div>
        @endif

        {{-- Related / other resources (simple placeholder) --}}
        <div class="card p-4">
            <h3 class="font-semibold mb-2">More resources</h3>
            <div class="text-sm text-muted">
                <p>Browse all culinary resources <a href="{{ route('resources.culinary') }}"
                        class="text-primary underline">here</a>.</p>
            </div>
        </div>
    </div>
@endsection
