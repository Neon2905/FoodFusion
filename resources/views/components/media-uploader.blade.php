@props(['name' => 'media', 'multiple' => true, 'label' => 'Media'])

<div x-data="mediaUploader({{ $attributes->has('initial') ? $attributes->get('initial') : 'null' }})" x-cloak class="space-y-3">
    <label class="block mb-2 font-semibold">{{ $label }}</label>

    <div class="modal-card p-3">
        <div class="flex flex-col gap-3">
            <div class="flex items-center gap-3">
                <button type="button" class="button" @click.prevent="$refs.fileInput.click()"
                    style="background:var(--color-primary); color:white">Select files</button>
                <button type="button" class="button" @click.prevent="clearAll()">Clear all</button>
                <div class="text-sm text-muted" x-show="items.length">
                    <span x-text="items.length"></span> file(s) selected
                </div>
            </div>

            {{-- Hidden native input --}}
            <input x-ref="fileInput" type="file"
                :name="'{{ $name }}' + ({{ $multiple ? 'true' : 'false' }} ? '[]' : '')"
                {{ $multiple ? 'multiple' : '' }} accept="image/*,video/*,audio/*,.heic,.heif,.webp" class="hidden"
                @change="handleFiles($event)">

            {{-- Preview area: main + thumbs similar to media-container --}}
            <div class="rounded overflow-hidden bg-black mb-3" x-show="items.length">
                <template x-if="current && current.type === 'image'">
                    <img :src="current.url" alt="media" class="w-full object-cover" />
                </template>
                <template x-if="current && current.type === 'video'">
                    <video x-ref="mainVideo" :src="current.url" controls playsinline
                        class="w-full max-h-[520px] bg-black"></video>
                </template>
            </div>

            <div class="relative max-w-full" x-show="items.length">
                <div x-ref="thumbContainer"
                    class="thumbs flex items-center gap-2 overflow-x-auto no-scrollbar py-1 px-2 max-w-full">
                    <template x-for="(m, i) in items" :key="i">
                        <button @click.prevent="select(i)" type="button"
                            class="thumb flex-shrink-0 w-20 h-14 rounded overflow-hidden border p-0 bg-white transition transform duration-150 ease-in-out focus:outline-none"
                            :class="selected === i ? 'ring-2 ring-indigo-500 scale-105' : 'opacity-95'">
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
                                <img class="w-full h-full object-cover" :src="m.url" alt="thumb">
                            </template>
                        </button>
                    </template>
                </div>
            </div>

            {{-- list with remove/rotate/reorder controls --}}
            <template x-if="items.length">
                <div class="grid grid-cols-1 gap-3 mt-2">
                    <template x-for="(f, i) in items" :key="i">
                        <div class="flex gap-3 items-start p-2 border rounded-md">
                            <div class="w-28 h-20 overflow-hidden rounded-md bg-gray flex-center">
                                <template x-if="f.type === 'image'">
                                    <img :src="f.url" class="w-full h-full object-cover"
                                        :style="'transform: rotate(' + (f.rotation || 0) + 'deg)'" />
                                </template>
                                <template x-if="f.type === 'video'">
                                    <video :src="f.url" class="w-full h-full object-cover" muted
                                        playsinline></video>
                                </template>
                                <template x-if="f.type === 'other'">
                                    <div class="text-sm">File</div>
                                </template>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium" x-text="f.name"></div>
                                <div class="text-sm text-muted" x-text="(f.size/1024).toFixed(1) + ' KB'"></div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <div class="flex flex-col gap-2 mb-1">
                                    <button type="button" class="button" @click.prevent="moveItem(i, -1)"
                                        title="Move left" style="background:transparent">←</button>
                                    <button type="button" class="button" @click.prevent="moveItem(i, 1)"
                                        title="Move right" style="background:transparent">→</button>
                                </div>
                                <button type="button" class="button" @click.prevent="rotateItem(i)" title="Rotate"
                                    style="background:transparent">⤾</button>
                                <button type="button" class="button" @click.prevent="removeFile(i)"
                                    style="background:transparent; color:var(--color-primary)">Remove</button>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>

    <script>
        function mediaUploader(initial) {
            return {
                items: initial ? JSON.parse(initial) : [],
                selected: 0,
                get current() {
                    return this.items[this.selected] ?? null
                },
                handleFiles(e) {
                    const fileList = e.target.files;
                    if (!fileList || fileList.length === 0) return;

                    Array.from(fileList).forEach((file) => {
                        const f = { name: file.name, size: file.size, type: file.type, file: file, rotation: 0 };
                        if (file.type.startsWith('image/')) f.type = 'image';
                        else if (file.type.startsWith('video/')) f.type = 'video';
                        else f.type = 'other';

                        const reader = new FileReader();
                        reader.onload = (ev) => {
                            f.url = ev.target.result;
                            this.items.push(f);
                            this.rebuildFileInput();
                            this.$dispatch('media-updated', this.items.map(i => ({ name: i.name, size: i.size, type: i.type, url: i.url, rotation: i.rotation })));
                        };
                        reader.readAsDataURL(file);
                    });
                },
                select(i) {
                    this.selected = i;
                    this.$nextTick(() => {
                        try { this.$refs.mainVideo && this.$refs.mainVideo.play().catch(()=>{}); } catch(e){}
                    });
                },
                rebuildFileInput() {
                    if (!(this.$refs && this.$refs.fileInput)) return;
                    try {
                        const dt = new DataTransfer();
                        this.items.forEach((it) => {
                            if (it.file) dt.items.add(it.file);
                        });
                        this.$refs.fileInput.files = dt.files;
                    } catch (e) {
                        // ignore if DataTransfer not supported
                    }
                },
                moveItem(i, dir) {
                    const j = i + dir;
                    if (j < 0 || j >= this.items.length) return;
                    const temp = this.items[j];
                    this.items.splice(j, 1, this.items[i]);
                    this.items.splice(i, 1, temp);
                    this.rebuildFileInput();
                    this.$dispatch('media-updated', this.items.map(i => ({ name: i.name, size: i.size, type: i.type, url: i.url, rotation: i.rotation })));
                },
                rotateItem(i) {
                    this.items[i].rotation = ((this.items[i].rotation || 0) + 90) % 360;
                    this.$dispatch('media-updated', this.items.map(i => ({ name: i.name, size: i.size, type: i.type, url: i.url, rotation: i.rotation })));
                },
                removeFile(i) {
                    this.items.splice(i, 1);
                    this.rebuildFileInput();
                    this.$dispatch('media-updated', this.items.map(i => ({ name: i.name, size: i.size, type: i.type, url: i.url, rotation: i.rotation })));
                },
                clearAll() {
                    this.items = [];
                    this.selected = 0;
                    if (this.$refs && this.$refs.fileInput) this.$refs.fileInput.value = null;
                    this.$dispatch('media-updated', this.items.map(i => ({ name: i.name, size: i.size, type: i.type, url: i.url, rotation: i.rotation })));
                }
            }
        }
    </script>
</div>
