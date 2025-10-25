@extends('layouts.app', ['title' => 'Recipes'])

@section('content')
    <div class="mx-auto max-w-7xl" x-data="index()" x-init="init()" x-on:destroyed.window="destroy()">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-display-sm">Recipes</h1>
            <p class="text-muted">
                {{ 'Recipes: ' .
                    ($recipes instanceof \Illuminate\Pagination\LengthAwarePaginator ? $recipes->total() : $recipes->count() ?? 0) }}
            </p>
        </div>

        @if ($recipes->isEmpty())
            <div class="card p-8 text-center">
                <p class="text-body-lg">No recipes available.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($recipes as $recipe)
                    <x-recipe.card :recipe="$recipe" />
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
    function index() {
        return {
            // explicitly define the selector used to find the recipes grid
            gridSelector: '.grid',
            currentPage: {{ $recipes->currentPage() }},
            lastPage: {{ $recipes->lastPage() }},
            loading: false,
            observer: null,

            init() {
                const grid = document.querySelector(this.gridSelector);
                if (!grid) return;

                const sentinel = document.getElementById('scroll-sentinel');
                const loadingEl = document.getElementById('loading');

                const fetchPage = async (page) => {
                    this.loading = true;
                    if (loadingEl) loadingEl.classList.remove('hidden');

                    try {
                        const url = new URL(window.location.href);
                        url.searchParams.set('page', page);

                        const res = await fetch(url.toString(), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        if (!res.ok) throw new Error('Network response was not ok');

                        const text = await res.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(text, 'text/html');

                        const newGrid = doc.querySelector(this.gridSelector);
                        if (newGrid) {
                            Array.from(newGrid.children).forEach(child => grid.appendChild(child));
                        } else {
                            const cards = doc.querySelectorAll('.card');
                            cards.forEach(c => grid.appendChild(c));
                        }

                        this.currentPage = page;
                        if (this.currentPage >= this.lastPage && this.observer) {
                            this.observer.disconnect();
                            if (sentinel) sentinel.remove();
                        }
                    } catch (err) {
                        console.error('Error loading more recipes:', err);
                    } finally {
                        this.loading = false;
                    }
                };

                this.observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && !this.loading && this.currentPage < this.lastPage) {
                            fetchPage(this.currentPage + 1);
                        }
                    });
                }, {
                    rootMargin: '200px'
                });

                if (sentinel) this.observer.observe(sentinel);
            },

            destroy() {
                if (this.observer) {
                    this.observer.disconnect();
                    this.observer = null;
                }
            }
        }
    }
</script>
