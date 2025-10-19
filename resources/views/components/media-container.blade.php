@props([
    'media' => null,
    'alt' => 'Media',
    'class' => '',
])
{{-- TODO: Review and fix this crap --}}
@php
    // normalize media into simple arrays for Alpine (url, type)
    $raw = collect($media ?? []);
    $normalize = function ($m) {
        if (is_string($m)) {
            $url = $m;
            $type = preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $url) ? 'video' : 'image';
        } else {
            $url = $m->url ?? ($m->path ?? ($m['url'] ?? ($m['path'] ?? null)));
            $mime = $m->type ?? ($m->mime ?? null);
            if ($mime && str_starts_with($mime, 'video')) {
                $type = 'video';
            } elseif ($mime && str_starts_with($mime, 'image')) {
                $type = 'image';
            } else {
                $type = $url && preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $url) ? 'video' : 'image';
            }
        }
        return ['url' => $url, 'type' => $type];
    };

    $items = $raw->map($normalize)->filter(fn($i) => !empty($i['url']))->values()->all();

    // if (empty($items) && $hero) {
    //     $items = [['url' => $hero, 'type' => preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $hero) ? 'video' : 'image']];
    // }
@endphp

<div class="flex-col {{ $class }}" x-data="{
    items: {{ json_encode($items, JSON_UNESCAPED_SLASHES) }},
    selected: 0,
    scrollable: false,
    init() {
        this.$nextTick(() => {
            this.checkScrollable();
            // re-check a few times (images may load later)
            setTimeout(() => this.checkScrollable(), 250);
            window.addEventListener('resize', () => this.checkScrollable());
        });
    },
    current() { return this.items[this.selected] ?? null; },
    checkScrollable() {
        const c = this.$refs.thumbContainer;
        this.scrollable = !!(c && c.scrollWidth > c.clientWidth + 6);
    },
    scrollLeft() {
        const c = this.$refs.thumbContainer;
        if (!c) return;
        const amount = Math.max(120, Math.floor(c.clientWidth * 0.7));
        c.scrollBy({ left: -amount, behavior: 'smooth' });
    },
    scrollRight() {
        const c = this.$refs.thumbContainer;
        if (!c) return;
        const amount = Math.max(120, Math.floor(c.clientWidth * 0.7));
        c.scrollBy({ left: amount, behavior: 'smooth' });
    },
    select(i) {
        try { if (this.$refs.mainVideo) this.$refs.mainVideo.pause(); } catch (e) {}
        this.selected = i;
        this.$nextTick(() => {
            if (this.current() && this.current().type === 'video') {
                try { this.$refs.mainVideo && this.$refs.mainVideo.play().catch(() => {}); } catch (e) {}
            }
            const t = this.$refs['thumb-' + i];
            if (t && t.scrollIntoView) t.scrollIntoView({ inline: 'center', behavior: 'smooth', block: 'nearest' });
        });
    }
}">
    {{-- Main Media --}}
    <div class="rounded overflow-hidden bg-black mb-3 w-full" style="position:relative;padding-top:56.25%;">
        <div style="position:absolute;inset:0;" class="flex items-center justify-center">
            <template x-if="current() && current().type === 'image'">
                <img x-bind:src="current() ? current().url : ''" alt="media" class="w-full h-full object-cover" />
            </template>

            <template x-if="current() && current().type === 'video'">
                <video x-ref="mainVideo" x-bind:src="current() ? current().url : ''" controls playsinline
                    class="w-full h-full bg-black object-cover"></video>
            </template>

            <template x-if="!current()">
                <div class="w-full h-full flex items-center justify-center text-gray-400">No media</div>
            </template>
        </div>
    </div>

    {{-- Selector bar (fills parent's width) --}}
    <div class="relative max-w-full">
        {{-- left arrow --}}
        <button x-show="scrollable" x-on:click.prevent="scrollLeft" type="button"
            class="absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden sm:flex items-center justify-center w-8 h-8 bg-white/90 text-gray-700 rounded-full shadow"
            x-bind:class="{ 'hidden': !scrollable }" aria-label="Scroll left">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        {{-- thumbs container --}}
        <div x-ref="thumbContainer"
            class="thumbs flex items-center gap-2 overflow-x-auto no-scrollbar py-1 px-2 max-w-full" tabindex="0"
            @keydown.arrow-left.prevent="select(Math.max(0, selected - 1))"
            @keydown.arrow-right.prevent="select(Math.min(items.length - 1, selected + 1))">
            <template x-for="(m, i) in items" :key="i">
                <button :ref="'thumb-' + i" x-on:click.prevent="select(i)"
                    x-bind:aria-pressed="selected === i ? 'true' : 'false'"
                    class="thumb flex-shrink-0 w-20 h-14 rounded overflow-hidden border p-0 bg-white transition transform duration-150 ease-in-out focus:outline-none"
                    x-bind:class="selected === i ? 'ring-2 ring-indigo-500 scale-105' : 'opacity-95'" type="button">
                    <template x-if="m.type === 'video'">
                        <div class="relative w-full h-full bg-black">
                            <video muted class="object-cover w-full h-full">
                                <source :src="m.url">
                            </video>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <svg class="w-5 h-5 text-white opacity-90" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.752 11.168l-6.545 3.773A1 1 0 017 13.999V9.001a1 1 0 011.207-.966l6.545 1.133a1 1 0 01.0.0z" />
                                </svg>
                            </div>
                        </div>
                    </template>

                    <template x-if="m.type === 'image'">
                        <img class="w-full h-full object-cover" x-bind:src="m.url" alt="thumb">
                    </template>
                </button>
            </template>
        </div>

        {{-- right arrow --}}
        <button x-show="scrollable" x-on:click.prevent="scrollRight" type="button"
            class="absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden sm:flex items-center justify-center w-8 h-8 bg-white/90 text-gray-700 rounded-full shadow"
            x-bind:class="{ 'hidden': !scrollable }" aria-label="Scroll right">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>
