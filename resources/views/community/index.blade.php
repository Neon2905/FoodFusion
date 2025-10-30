@extends('layouts.app', ['title' => 'Community Cookbook'])

@section('content')
    @php
        $items = collect(
            $recipes instanceof \Illuminate\Pagination\LengthAwarePaginator ? $recipes->items() : $recipes,
        );
        $cuisines = $items->pluck('cuisine')->filter()->unique()->values();
        $difficulties = $items->pluck('difficulty')->filter()->unique()->values();
        $dietTags = $items
            ->flatMap(function ($r) {
                return optional($r->tags)->pluck('name') ?? [];
            })
            ->filter()
            ->unique()
            ->values();
    @endphp

    <div class="max-w-6xl mx-auto" x-data="communityIndex()" x-init="init()" x-on:destroyed.window="destroy()">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-display-sm">Community Cookbook</h1>
                <p class="text-muted">Share your favourite recipes and tips.</p>
            </div>
            <p class="text-muted">Use filters to narrow results.</p>
        </div>

        <div class="card p-4 mb-4">
            <p class="text-body-md">Contribute recipes, comment, and interact with other cooks. Use the <a
                    href="{{ route('recipes.create.view') }}" class="text-primary">Create recipe</a> page to publish.</p>
        </div>

        <div class="card mb-4">
            <div class="flex gap-3 items-end flex-wrap p-3">
                <div class="flex flex-col">
                    <label class="text-sm text-muted">Cuisine</label>
                    <select x-model="filters.cuisine" @change="applyFilters()" class="input-box">
                        <option value="">All cuisines</option>
                        @foreach ($cuisines as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-sm text-muted">Dietary</label>
                    <select x-model="filters.tag" @change="applyFilters()" class="input-box">
                        <option value="">All</option>
                        @foreach ($dietTags as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-sm text-muted">Difficulty</label>
                    <select x-model="filters.difficulty" @change="applyFilters()" class="input-box">
                        <option value="">Any</option>
                        @foreach ($difficulties as $d)
                            <option value="{{ $d }}">{{ ucfirst($d) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="button" @click="clearFilters()" class="button bg-gray">Reset</button>
                </div>
            </div>
        </div>

        @if ($recipes->isEmpty())
            <div class="p-8 text-center">
                <p class="text-body-lg">No recipes available.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="recipes-grid">
                @foreach ($recipes as $r)
                    <x-recipe.card :recipe="$r" />
                @endforeach
            </div>

            @if (method_exists($recipes, 'links'))
                <div id="infinite-loader" class="mt-6 flex flex-col items-center">
                    <template x-if="loading">
                        <x-loader class="size-10"></x-loader>
                    </template>
                    <div id="scroll-sentinel" style="height:1px;"></div>
                </div>
            @endif
        @endif
    </div>
@endsection

<script>
    function communityIndex() {
        return {
            gridSelector: '#recipes-grid',
            currentPage: {{ $recipes->currentPage() }},
            lastPage: {{ $recipes->lastPage() }},
            loading: false,
            observer: null,
            filters: {},
            init() {
                const grid = document.querySelector(this.gridSelector);
                if (!grid) return;
                const sentinel = document.getElementById('scroll-sentinel');
                this.observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && !this.loading && this.currentPage < this.lastPage) {
                            this.fetchPage(this.currentPage + 1);
                        }
                    });
                }, {
                    rootMargin: '200px'
                });
                if (sentinel) this.observer.observe(sentinel);
            },
            async fetchPage(page = 1) {
                this.loading = true;
                try {
                    const url = new URL(window.location.href);
                    url.searchParams.set('page', page);
                    if (this.filters.cuisine) url.searchParams.set('cuisine', this.filters.cuisine);
                    else url.searchParams.delete('cuisine');
                    if (this.filters.tag) url.searchParams.set('tag', this.filters.tag);
                    else url.searchParams.delete('tag');
                    if (this.filters.difficulty) url.searchParams.set('difficulty', this.filters.difficulty);
                    else url.searchParams.delete('difficulty');

                    const res = await fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!res.ok) throw new Error('Network response was not ok');

                    const text = await res.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(text, 'text/html');
                    const newGrid = doc.querySelector(this.gridSelector) || doc.querySelectorAll('.card');
                    const grid = document.querySelector(this.gridSelector);

                    if (newGrid) {
                        if (newGrid.children) {
                            Array.from(newGrid.children).forEach(child => grid.appendChild(child));
                        } else {
                            Array.from(newGrid).forEach(c => grid.appendChild(c));
                        }
                    }

                    this.currentPage = page;
                    if (this.currentPage >= this.lastPage && this.observer) {
                        this.observer.disconnect();
                        const sentinel = document.getElementById('scroll-sentinel');
                        if (sentinel) sentinel.remove();
                    }
                } catch (err) {
                    console.error('Error loading more recipes:', err);
                } finally {
                    this.loading = false;
                }
            },
            applyFilters() {
                const grid = document.querySelector(this.gridSelector);
                if (!grid) return;
                grid.innerHTML = '';
                this.currentPage = 0;
                this.fetchPage(1);
                const url = new URL(window.location.href);
                if (this.filters.cuisine) url.searchParams.set('cuisine', this.filters.cuisine);
                else url.searchParams.delete('cuisine');
                if (this.filters.tag) url.searchParams.set('tag', this.filters.tag);
                else url.searchParams.delete('tag');
                if (this.filters.difficulty) url.searchParams.set('difficulty', this.filters.difficulty);
                else url.searchParams.delete('difficulty');
                history.replaceState({}, '', url.toString());
            },
            clearFilters() {
                this.filters = {};
                this.applyFilters();
            },
            destroy() {
                if (this.observer) {
                    this.observer.disconnect();
                    this.observer = null;
                }
            }
        };
    }
</script>
