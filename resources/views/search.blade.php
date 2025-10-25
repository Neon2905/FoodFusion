@extends('layouts.app', ['title' => 'Search Recipes'])

@section('content')
    <div class="w-full">
        <div class="modal-card p-6">
            <div x-data="searchPage()" x-init="init()" class="flex flex-col gap-4">
                <div class="flex gap-3 items-center">
                    <input x-model="query" @input.debounce.300="search(true)" type="search"
                        placeholder="Search recipes, ingredients, cuisines..." class="flex-1 input-box" />

                    <select x-model="filters.meal_type" @change="search(true)" class="input-box w-40">
                        <option value="">All meal types</option>
                        @foreach ($meal_types as $m)
                            <option value="{{ $m }}">{{ $m }}</option>
                        @endforeach
                    </select>

                    <select x-model="filters.tag" @change="search(true)" class="input-box w-40">
                        <option value="">All tags</option>
                        @foreach ($tags as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>

                    <select x-model="filters.difficulty" @change="search(true)" class="input-box w-36">
                        <option value="">Any difficulty</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>

                    <button @click="search(true)" class="button bg-primary text-white px-4">Search</button>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-subtitle-md">Results: <span x-text="meta.total"></span></div>
                    <div>
                        <button @click="reset()" class="text-secondary">Reset</button>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4" x-show="results.length" x-cloak>
                    <template x-for="r in results" :key="r.id">
                        <a :href="'/recipe/' + r.slug" class="card no-underline hover:shadow-lg transition">
                            <img src="r.heroUrl" alt="r.title" class="w-full h-48 object-cover rounded">
                            <div class="mt-3">
                                <h3 class="font-semibold text-lg" x-text="r.title"></h3>
                                <p class="text-muted" x-text="`${r.meal_type} • ${r.servings || 'unknown'} servings`"></p>
                            </div>
                        </a>
                    </template>
                </div>

                <div x-show="!results.length && !loading" class="flex-center p-8 text-muted" x-text="noResultsText"></div>

                <div class="flex-center mt-4 gap-3">
                    <button @click="loadMore()" x-show="meta.page * meta.per < meta.total" :disabled="loading"
                        class="button bg-gray px-4">Load more</button>
                    <div x-show="loading" class="text-subtitle-md">
                        <x-loader></x-loader>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function searchPage() {
            return {
                query: '',
                filters: {
                    meal_type: '',
                    tag: '',
                    difficulty: ''
                },
                results: [],
                meta: {
                    total: 0,
                    page: 1,
                    per: 12
                },
                loading: false,
                noResultsText: 'Type what you are looking for..',

                init() {
                    // optional: pre-fill from URL query params
                    const params = new URLSearchParams(location.search);
                    if (params.get('query')) {
                        this.query = params.get('query');
                        this.filters.meal_type = params.get('meal_type') || '';
                        this.filters.tag = params.get('tag') || '';
                        this.filters.difficulty = params.get('difficulty') || '';
                        this.search(true);
                    }
                },

                async search(reset = false) {
                    if (reset) {
                        this.meta.page = 1;
                        this.results = [];
                    }

                    const q = this.query.trim();
                    if (q === '' && !this.filters.meal_type && !this.filters.tag && !this.filters.difficulty) {
                        this.noResultsText = 'Type what you are looking for..';
                        this.results = [];
                        this.meta.total = 0;
                        return;
                    }

                    this.loading = true;
                    try {
                        const params = new URLSearchParams();
                        params.set('query', q);
                        if (this.filters.meal_type) params.set('meal_type', this.filters.meal_type);
                        if (this.filters.tag) params.set('tag', this.filters.tag);
                        if (this.filters.difficulty) params.set('difficulty', this.filters.difficulty);
                        params.set('page', this.meta.page);

                        const res = await fetch(`/search?${params.toString()}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                        });
                        const json = await res.json();
                        if (reset) this.results = [];
                        this.results.push(...json.data);
                        this.meta = json.meta;
                        if (this.results.length === 0) this.noResultsText = 'No results';
                    } catch (err) {
                        console.error('Search failed: '.err);
                        this.noResultsText = 'Search failed';
                    } finally {
                        this.loading = false;
                    }
                },

                loadMore() {
                    if (this.loading) return;
                    if (this.meta.page * this.meta.per >= this.meta.total) return;
                    this.meta.page++;
                    this.search(false);
                },

                reset() {
                    this.query = '';
                    this.filters = {
                        meal_type: '',
                        tag: '',
                        difficulty: ''
                    };
                    this.results = [];
                    this.meta = {
                        total: 0,
                        page: 1,
                        per: 12
                    };
                    history.replaceState({}, '', '/search');
                }
            }
        }
    </script>
@endsection
