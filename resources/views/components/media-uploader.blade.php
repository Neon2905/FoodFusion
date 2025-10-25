{{-- TODO: Size issue when media is has many --}}

<div {{ $attributes->merge(['class' => 'flex-col w-full']) }} x-data="mediaUploader()"
    x-on:paste.window="handlePaste($event)">
    <!-- empty placeholder -->
    <div x-show="!items.length" class="flex flex-col w-full gap-2 min-h-59 items-center justify-center text-muted">
        <p class="font-bold">No media selected</p>
        <label for="mediaInput" class="px-4 py-2 border rounded cursor-pointer">Choose files</label>
        <!-- real input lives inside the form so it submits naturally -->
        <input id="mediaInput" x-ref="mediaInput" type="file" name="media[]" multiple accept="image/*,video/*"
            class="hidden" x-on:change="handleFiles($event)" />
    </div>

    <!-- large preview -->
    <div class="relative rounded overflow-hidden bg-black mb-3 w-full" x-show="items.length">
        <template x-if="items.length">
            <div class="w-full bg-gray-100" style="position:relative;padding-top:56.25%;">
                <div style="position:absolute;inset:0;" class="flex items-center justify-center">
                    <template x-if="currentItem && currentItem.type === 'image'">
                        <img :src="currentItem.url" class="w-full h-full object-cover"
                            :style="'transform: rotate(' + (currentItem.rotation || 0) + 'deg)'" />
                    </template>
                    <template x-if="currentItem && currentItem.type === 'video'">
                        <video :src="currentItem.url" controls class="w-full h-full bg-black object-cover"
                            :style="'transform: rotate(' + (currentItem.rotation || 0) + 'deg)'"></video>
                    </template>
                </div>
            </div>
        </template>

        <div class="absolute top-3 right-3 flex flex-col gap-2 items-center justify-center">
            <button type="button" @click.prevent="$refs.mediaInput.click()" class="button bg-gray">
                <x-css-add class="text-accent" />
            </button>

            <button type="button" x-show="items.length" @click.prevent="removeItem(currentIndex)"
                class="button bg-gray">
                <x-css-trash class="text-error" />
            </button>
        </div>
    </div>

    <!-- horizontal thumbnails -->
    <div class="thumbs flex items-center gap-2 overflow-x-auto no-scrollbar py-1 px-2">
        <template x-for="(item, idx) in items" :key="item.id">
            <div class="thumb flex-shrink-0 w-20 h-14 rounded overflow-hidden border p-0 bg-white transition transform duration-150 ease-in-out focus:outline-none"
                :class="{
                    'ring-2 ring-primary rounded-md': idx === currentIndex,
                    'rounded-md': idx !==
                        currentIndex
                }"
                draggable="true" @dragstart="onDragStart($event, idx)" @dragover.prevent @drop="onDrop($event, idx)"
                @click="selectItem(idx)">
                <template x-if="item.type === 'image'">
                    <img :src="item.url" class="w-full h-full object-cover"
                        :style="'transform: rotate(' + (item.rotation || 0) + 'deg)'" />
                </template>
                <template x-if="item.type === 'video'">
                    <video :src="item.url" class="w-full h-full rounded-md border object-cover"></video>
                </template>
            </div>
        </template>
    </div>

    <!-- hidden input to carry metadata (rotation/order) to server -->
    <input type="hidden" name="media_meta"
        :value="JSON.stringify(items.map(i => ({
            name: i.file ? i.file.name : i.name,
            size: i.file ? i.file.size : i
                .size,
            type: i.type,
            rotation: i.rotation || 0,
            order: i.order ?? null
        })))" />
</div>

<script>
    function mediaUploader() {
        return {
            items: [],
            currentIndex: 0,
            dragFrom: null,
            get currentItem() {
                return this.items[this.currentIndex] ?? null;
            },
            handleFiles(e) {
                const files = Array.from(e.target.files || []);
                if (!files.length) return;
                const startIndex = this.items.length;
                files.forEach(f => {
                    const id = Math.random().toString(36).substr(2, 9);
                    const url = URL.createObjectURL(f);
                    const type = f.type.startsWith('image') ? 'image' : (f.type.startsWith('video') ? 'video' :
                        'unknown');
                    this.items.push({
                        id,
                        file: f,
                        name: f.name,
                        size: f.size,
                        url,
                        type,
                        rotation: 0,
                        order: this.items.length
                    });
                });
                // set currentIndex to first of newly added (or last)
                this.currentIndex = Math.min(startIndex, this.items.length - 1);
                this.dispatchUpdate();
                this.rebuildFileList();
            },

            // paste handler kept from previous suggestion (optional)
            async handlePaste(e) {
                try {
                    const clipboard = e.clipboardData || window.clipboardData;
                    if (!clipboard) return;

                    const items = Array.from(clipboard.items || []);
                    const filesFromItems = [];

                    for (const it of items) {
                        if (!it) continue;
                        if (it.kind === 'file' && it.type && it.type.startsWith('image')) {
                            const file = it.getAsFile();
                            if (file) filesFromItems.push(file);
                        }
                    }

                    if (filesFromItems.length === 0 && clipboard.files && clipboard.files.length) {
                        Array.from(clipboard.files).forEach(f => {
                            if (f.type && f.type.startsWith('image')) filesFromItems.push(f);
                        });
                    }

                    if (filesFromItems.length === 0) {
                        const html = clipboard.getData && clipboard.getData('text/html');
                        const text = clipboard.getData && clipboard.getData('text/plain');
                        const dataSource = html || text;
                        if (dataSource) {
                            const m = dataSource.match(/src=["'](data:[^"']+)["']/) || dataSource.match(
                                /(data:image\/[a-zA-Z0-9+;=,-]+base64,[^"\s<]+)/);
                            if (m && m[1]) {
                                const dataUrl = m[1];
                                const res = await fetch(dataUrl);
                                const blob = await res.blob();
                                const file = new File([blob],
                                    `pasted-${Date.now()}.${blob.type.split('/')[1] || 'png'}`, {
                                        type: blob.type
                                    });
                                filesFromItems.push(file);
                            }
                        }
                    }

                    if (filesFromItems.length === 0) return;

                    const startIndex = this.items.length;
                    filesFromItems.forEach(f => {
                        const id = Math.random().toString(36).substr(2, 9);
                        const url = URL.createObjectURL(f);
                        const type = f.type.startsWith('image') ? 'image' : (f.type.startsWith('video') ?
                            'video' : 'unknown');
                        this.items.push({
                            id,
                            file: f,
                            name: f.name || `pasted-${Date.now()}`,
                            size: f.size || 0,
                            url,
                            type,
                            rotation: 0,
                            order: this.items.length
                        });
                    });
                    this.currentIndex = Math.min(startIndex, this.items.length - 1);
                    this.dispatchUpdate();
                    this.rebuildFileList();
                } catch (err) {
                    console.warn('Error handling paste:', err);
                }
            },

            dispatchUpdate() {
                try {
                    this.$dispatch('media-updated', this.items.map(i => ({
                        id: i.id,
                        url: i.url,
                        type: i.type
                    })));
                } catch (err) {}
            },
            clearAll() {
                this.items.forEach(i => {
                    try {
                        URL.revokeObjectURL(i.url);
                    } catch (e) {}
                });
                this.items = [];
                this.currentIndex = 0;
                if (this.$refs && this.$refs.mediaInput) this.$refs.mediaInput.value = null;
                this.dispatchUpdate();
            },
            removeItem(idx) {
                if (idx == null || idx < 0 || idx >= this.items.length) return;
                const it = this.items[idx];
                try {
                    URL.revokeObjectURL(it.url);
                } catch (e) {}
                this.items.splice(idx, 1);
                // reindex order
                this.items.forEach((it, i) => it.order = i);
                // adjust currentIndex
                if (this.items.length === 0) {
                    this.currentIndex = 0;
                } else if (this.currentIndex >= this.items.length) {
                    this.currentIndex = this.items.length - 1;
                } else if (idx < this.currentIndex) {
                    this.currentIndex = Math.max(0, this.currentIndex - 1);
                }
                this.dispatchUpdate();
                this.rebuildFileList();
            },
            moveItem(idx, dir) {
                const to = idx + dir;
                if (to < 0 || to >= this.items.length) return;
                const tmp = this.items[to];
                this.items.splice(to, 1, this.items[idx]);
                this.items.splice(idx, 1, tmp);
                this.items.forEach((it, i) => it.order = i);
                this.dispatchUpdate();
                this.rebuildFileList();
                // if current item was involved, update currentIndex appropriately
                if (this.currentIndex === idx) this.currentIndex = to;
                else if (this.currentIndex === to) this.currentIndex = idx;
            },
            rotateItem(idx) {
                if (!this.items[idx]) return;
                this.items[idx].rotation = (this.items[idx].rotation || 0) + 90;
                if (this.items[idx].rotation >= 360) this.items[idx].rotation = 0;
                this.dispatchUpdate();
            },

            // Drag handlers for thumbnail reorder
            onDragStart(e, idx) {
                this.dragFrom = idx;
                try {
                    e.dataTransfer.setData('text/plain', idx);
                } catch (err) {}
                // hint
                e.dataTransfer.effectAllowed = 'move';
            },
            onDrop(e, idx) {
                let from = this.dragFrom;
                if (from == null) {
                    try {
                        from = parseInt(e.dataTransfer.getData('text/plain'), 10);
                    } catch (err) {}
                }
                if (from == null || isNaN(from)) return;
                this.reorder(from, idx);
                this.dragFrom = null;
            },
            reorder(from, to) {
                if (from === to) return;
                const item = this.items.splice(from, 1)[0];
                this.items.splice(to, 0, item);
                this.items.forEach((it, i) => it.order = i);
                // update currentIndex so selection follows the moved item
                if (this.currentIndex === from) {
                    this.currentIndex = to;
                } else if (from < this.currentIndex && to >= this.currentIndex) {
                    this.currentIndex = this.currentIndex - 1;
                } else if (from > this.currentIndex && to <= this.currentIndex) {
                    this.currentIndex = this.currentIndex + 1;
                }
                this.dispatchUpdate();
                this.rebuildFileList();
            },

            selectItem(idx) {
                if (idx == null || idx < 0 || idx >= this.items.length) return;
                this.currentIndex = idx;
            },

            rebuildFileList() {
                try {
                    const dt = new DataTransfer();
                    this.items.forEach(it => {
                        if (it.file) dt.items.add(it.file);
                    });
                    if (this.$refs && this.$refs.mediaInput) this.$refs.mediaInput.files = dt.files;
                } catch (err) {
                    console.warn('DataTransfer not available; media order may not be preserved in native file input');
                }
            },
            async prepareAndSubmit(e) {
                this.rebuildFileList();
                const form = e.target.closest('form');
                if (!form) return;
                setTimeout(() => form.submit(), 50);
            }
        }
    }
</script>
