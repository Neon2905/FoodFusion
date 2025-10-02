@props(['name' => 'media', 'multiple' => true, 'label' => 'Media'])

@extends('layouts.app')
@section('content')
    <div x-data="mediaUploader()" x-cloak class="space-y-3">
        <label class="block mb-2 font-semibold">{{ $label }}</label>

        <div class="modal-card p-3">
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-3">
                    <button type="button" class="button" @click.prevent="$refs.fileInput.click()"
                        style="background:var(--color-primary); color:white">Select files</button>
                    <button type="button" class="button" @click.prevent="clearAll()">Clear all</button>
                    <div class="text-sm text-muted" x-show="selectedFiles.length">
                        <span x-text="selectedFiles.length"></span> file(s) selected
                    </div>
                </div>

                <input x-ref="fileInput" type="file" name="{{ $name }}{{ $multiple ? '[]' : '' }}"
                    {{ $multiple ? 'multiple' : '' }} accept="image/*,video/*" class="hidden" @change="handleFiles($event)">

                <template x-if="selectedFiles.length">
                    <div class="grid grid-cols-1 gap-3">
                        <template x-for="(f, i) in selectedFiles" :key="i">
                            <div class="flex gap-3 items-start p-2 border rounded-md">
                                <div class="w-28 h-20 overflow-hidden rounded-md bg-gray flex-center">
                                    <template x-if="f.previewType === 'image'">
                                        <img :src="f.dataUrl" class="w-full h-full object-cover" />
                                    </template>
                                    <template x-if="f.previewType === 'video'">
                                        <video :src="f.dataUrl" class="w-full h-full object-cover"
                                            muted playsinline></video>
                                    </template>
                                    <template x-if="f.previewType === 'other'">
                                        <div class="text-sm">File</div>
                                    </template>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium" x-text="f.name"></div>
                                    <div class="text-sm text-muted" x-text="(f.size/1024).toFixed(1) + ' KB'"></div>
                                </div>
                                <div class="flex flex-col gap-2">
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
            function mediaUploader() {
                return {
                    selectedFiles: [],
                    handleFiles(e) {
                        const fileList = e.target.files;
                        if (!fileList || fileList.length === 0) return;

                        // reset selectedFiles
                        this.selectedFiles = [];

                        Array.from(fileList).forEach((file) => {
                            const item = {
                                name: file.name,
                                size: file.size,
                                type: file.type
                            };
                            if (file.type.startsWith('image/')) item.previewType = 'image';
                            else if (file.type.startsWith('video/')) item.previewType = 'video';
                            else item.previewType = 'other';

                            const reader = new FileReader();
                            reader.onload = (ev) => {
                                item.dataUrl = ev.target.result;
                                this.selectedFiles.push(item);
                            };
                            reader.readAsDataURL(file);
                        });
                    },
                    removeFile(i) {
                        this.selectedFiles.splice(i, 1);
                        // Clear file input (simpler and reliable across browsers)
                        if (this.$refs && this.$refs.fileInput) this.$refs.fileInput.value = null;
                    },
                    clearAll() {
                        this.selectedFiles = [];
                        if (this.$refs && this.$refs.fileInput) this.$refs.fileInput.value = null;
                    }
                }
            }
        </script>
    </div>
@endsection
