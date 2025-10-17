@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-6" x-data="{
        items: [],
        selected: 0,
        current() { return this.items[this.selected] ?? null; },
        addFiles(e) {
            const files = Array.from(e.target.files || []);
            files.forEach(file => {
                const kind = file.type.startsWith('video') ? 'video' : 'image';
                const url = URL.createObjectURL(file);
                this.items.push({ type: kind, url, name: file.name });
            });
            // select last added
            if (this.items.length) this.selected = this.items.length - 1;
            // clear input so same file can be re-selected if needed
            e.target.value = '';
            this.$nextTick(() => {
                if (this.current() && this.current().type === 'video') {
                    try { this.$refs.mainVideo && this.$refs.mainVideo.play().catch(() => {}); } catch (e) {}
                }
            });
        },
        remove(i) {
            const removed = this.items.splice(i, 1)[0];
            if (removed && removed.url) URL.revokeObjectURL(removed.url);
            if (this.selected >= this.items.length) this.selected = Math.max(0, this.items.length - 1);
        },
        select(i) {
            this.selected = i;
            this.$nextTick(() => {
                if (this.current() && this.current().type === 'video') {
                    try { this.$refs.mainVideo && this.$refs.mainVideo.play().catch(() => {}); } catch (e) {}
                }
            });
        }
    }">
        {{-- file picker --}}
        <div class="mb-4"></div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Add photos or videos</label>
        <input type="file" accept="image/*,video/*" multiple x-on:change="addFiles($event)"
            class="block w-full text-sm text-gray-700" />
        <p class="text-xs text-gray-500 mt-1">You can select multiple files. Previews are stored in-memory via object URLs.
        </p>
    </div>

    {{-- Main preview --}}
    <div class="rounded overflow-hidden bg-black mb-3">
        <template x-if="current() && current().type === 'image'"></template>
        <img x-bind:src="current() ? current().url : ''" alt="media" class="w-full object-contain max-h-[520px]" />
        </template>

        <template x-if="current() && current().type === 'video'"></template>
        <video x-ref="mainVideo" x-bind:src="current() ? current().url : ''" controls playsinline
            class="w-full max-h-[520px] bg-black"></video>
        </template>

        <template x-if="!current()"></template>
        <div class="w-full h-40 flex items-center justify-center text-gray-400">No media selected</div>
        </template>
    </div>

    {{-- Thumbnails --}}
    <div class="flex items-center gap-2 overflow-x-auto py-1 px-2 no-scrollbar"></div>
    <template x-for="(m, i) in items" :key="i">
        <div class="flex items-center gap-2"></div>
        <button x-on:click.prevent="select(i)" x-bind:aria-pressed="selected === i ? 'true' : 'false'"
            class="flex-shrink-0 w-24 h-16 rounded overflow-hidden border p-0 bg-white transition transform duration-150 ease-in-out focus:outline-none"
            x-bind:class="selected === i ? 'ring-2 ring-indigo-500 scale-105' : ''" type="button">
            <template x-if="m.type === 'video'">
                <div class="relative w-full h-full bg-black">
                    <video muted class="object-cover w-full h-full">
                        <source :src="m.url">
                    </video>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none"></div>
                    <svg class="w-5 h-5 text-white opacity-90" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24"></svg>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14.752 11.168l-6.545 3.773A1 1 0 017 13.999V9.001a1 1 0 011.207-.966l6.545 1.133a1 1 0 01.0.0z" />
                    </svg>
                </div>
                </div>
            </template>

            <template x-if="m.type === 'image'"></template>
            <img class="w-full h-full object-cover" x-bind:src="m.url" alt="thumb">
    </template>
    </button>

    {{-- remove button --}}
    <button x-on:click.prevent="remove(i)" type="button" class="text-red-600 hover:text-red-800 text-sm px-2"
        title="Remove">Remove</button>
    </div>
    </template>
    </div>
    </div>
@endsection
