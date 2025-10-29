@props(['recipe' => null])

<a href="{{ route('recipes.show', ['slug' => $recipe->slug]) }}"
    class="group block bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-200 transform hover:-translate-y-0.5 no-underline"
    aria-label="View recipe {{ $recipe->title }}">

    <div class="relative overflow-hidden">
        <img src="{{ $recipe->hero_url }}" alt="{{ $recipe->title }}"
            class="w-full h-48 object-cover transform transition-transform duration-300 group-hover:scale-105"
            loading="lazy">

        {{-- top-left badge: meal type --}}
        @if (!empty($recipe->meal_type))
            <span
                class="absolute top-3 left-3 inline-flex items-center gap-2 bg-black/60 text-white text-xs font-semibold px-2.5 py-1 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3v2m0 14v2M4.2 7.2l1.4 1.4M18.4 16.8l1.4 1.4M3 12h2m14 0h2" />
                </svg>
                <span class="capitalize">{{ $recipe->meal_type }}</span>
            </span>
        @endif

        {{-- top-right small author chip --}}
        <div class="absolute top-3 right-3 flex items-center gap-2">
            <span class="bg-white/90 text-xs text-slate-700 font-medium px-2 py-0.5 rounded-full shadow-sm">
                {{ $recipe->author->name }}
            </span>
        </div>
    </div>

    <div class="p-4 space-y-2">
        <h3 class="text-slate-900 font-semibold text-lg leading-tight line-clamp-2">
            {{ $recipe->title }}
        </h3>

        @if (!empty($recipe->description))
            <p class="text-sm text-slate-500 leading-snug line-clamp-3">
                {{ \Illuminate\Support\Str::limit($recipe->description, 120) }}
            </p>
        @endif

        <div class="flex items-center justify-between gap-3 mt-2">
            <div class="flex items-center gap-3">
                {{-- rating component + numeric value --}}
                <div class="flex items-center gap-2">
                    <x-rating :value="$recipe->rating" size="4" class="items-center" />
                    <span class="text-sm font-medium text-slate-700 tabular-nums">
                        {{ number_format($recipe->rating ?? 0, 1) }}
                    </span>
                </div>

                {{-- servings --}}
                @if (!empty($recipe->servings))
                    <div class="flex items-center gap-1 text-xs text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v2m0 14v2M4.2 7.2l1.4 1.4M18.4 16.8l1.4 1.4" />
                        </svg>
                        <span>{{ $recipe->servings }} servings</span>
                    </div>
                @endif
            </div>

            {{-- optional prep/cook times if available --}}
            <div class="text-right text-xs text-slate-500">
                @if (!empty($recipe->prep_time) || !empty($recipe->cook_time))
                    <div class="whitespace-nowrap">
                        @if (!empty($recipe->prep_time))
                            <span class="mr-2" title="Prep time">Prep: {{ $recipe->prep_time }}</span>
                        @endif
                        @if (!empty($recipe->cook_time))
                            <span title="Cook time">Cook: {{ $recipe->cook_time }}</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</a>
