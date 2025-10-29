@extends('layouts.app', ['title' => 'Culinary Resources'])

@section('content')
    <div class="mx-auto">
        <div class="modal-card">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-display-sm">Culinary Resources</h1>
                    <p class="text-muted">Download recipe cards, watch tutorials, and learn kitchen hacks.</p>
                </div>
            </div>

            <div x-data="{
                filter: 'all',
                types: ['all', 'card', 'tutorial', 'video'],
                set(f) { this.filter = f }
            }" class="space-y-4">

                <div class="flex gap-2">
                    <template x-for="t in types" :key="t">
                        <button type="button" :class="filter === t ? 'button bg-primary text-white' : 'button bg-gray'"
                            @click="set(t)"
                            x-text="t === 'all' ? 'All' : (t === 'card' ? 'Cards' : (t === 'tutorial' ? 'Tutorials' : 'Videos'))"></button>
                    </template>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($resources as $r)
                        <x-resource.card :resource="$r" />
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
