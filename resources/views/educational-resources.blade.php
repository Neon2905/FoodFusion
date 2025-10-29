@extends('layouts.app', ['title' => 'Educational Resources'])

@section('content')
    <div class="mx-auto max-w-7xl py-6">
        <div class="modal-card p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-display-sm">Educational Resources</h1>
                    <p class="text-muted">Downloadable infographics, guides and videos on renewable energy and
                        sustainability.</p>
                </div>
            </div>

            <div x-data="{ filter: 'all', types: ['all', 'card', 'tutorial', 'video', 'technique'], set(f) { this.filter = f } }" class="space-y-4">
                <div class="flex gap-2">
                    <template x-for="t in types" :key="t">
                        <button type="button" :class="filter === t ? 'button bg-primary text-white' : 'button bg-gray'"
                            @click="set(t)"
                            x-text="t === 'all' ? 'All' : (t === 'card' ? 'Cards' : (t === 'tutorial' ? 'Tutorials' : (t === 'video' ? 'Videos' : 'Techniques')))"></button>
                    </template>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($resources as $r)
                        <div class="card p-4" x-show="filter === 'all' || filter === '{{ $r->type }}'">
                            <div class="flex flex-col h-full">
                                @if ($r->thumbnail_url)
                                    <img src="{{ $r->thumbnail_url }}" alt="{{ $r->title }}"
                                        class="w-full h-40 object-cover rounded mb-3">
                                @endif
                                <h3 class="font-semibold">{{ $r->title }}</h3>
                                <p class="text-muted text-sm mb-3">{{ Str::limit($r->description ?? '', 140) }}</p>

                                <div class="mt-auto flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-sm text-muted">
                                        <span class="px-2 py-1 rounded bg-gray">{{ ucfirst($r->type) }}</span>
                                        @if ($r->tags)
                                            <span class="text-xs">• {{ implode(', ', (array) $r->tags) }}</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if ($r->type === 'card' && $r->file_path)
                                            <a href="{{ asset('storage/' . $r->file_path) }}" download
                                                class="button bg-accent text-white">Download</a>
                                        @elseif ($r->type === 'video' && $r->file_path)
                                            <a href="{{ asset('storage/' . $r->file_path) }}" target="_blank"
                                                class="button bg-accent text-white">Play</a>
                                        @elseif ($r->external_url)
                                            <a href="{{ $r->external_url }}" target="_blank"
                                                class="button bg-accent text-white">Open</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card p-6 text-center col-span-full">
                            <p class="text-muted">No resources available yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
