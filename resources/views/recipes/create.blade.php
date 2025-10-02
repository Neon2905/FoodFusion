@extends('layouts.app', ['title' => 'Create Recipe'])

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        <form action="{{ route('recipes.create') }}" method="POST" enctype="multipart/form-data" x-data="{
            title: '',
            description: '',
            servings: 4,
            ingredients: [{ quantity: '', unit: '', name: '' }],
            steps: [{ content: '' }],
            tags: [],
            tips: [],
            tagInput: '',
            imagePreviews: [], // array of data URLs
            addIngredient() { this.ingredients.push({ quantity: '', unit: '', name: '' }) },
            removeIngredient(i) { this.ingredients.splice(i, 1) },
            addStep() { this.steps.push({ content: '' }) },
            removeStep(i) { this.steps.splice(i, 1) },
            moveStepUp(i) {
                if (i === 0) return;
                const a = this.steps[i - 1];
                this.steps.splice(i - 1, 2, this.steps[i], a)
            },
            moveStepDown(i) {
                if (i === this.steps.length - 1) return;
                const a = this.steps[i + 1];
                this.steps.splice(i, 2, a, this.steps[i])
            },
            addTag() {
                const t = this.tagInput && this.tagInput.trim();
                if (!t) return;
                if (!this.tags.includes(t)) this.tags.push(t);
                this.tagInput = ''
            },
            selectedFiles: [], // [{ name, size, type, previewType, dataUrl }]
            previewMedia(e) {
                const fileList = e.target.files;
                if (!fileList || fileList.length === 0) {
                    this.selectedFiles = [];
                    this.imagePreviews = [];
                    return;
                }

                // reset
                this.selectedFiles = [];
                this.imagePreviews = [];

                Array.from(fileList).forEach((file, idx) => {
                    const item = { name: file.name, size: file.size, type: file.type };
                    if (file.type.startsWith('image/')) item.previewType = 'image';
                    else if (file.type.startsWith('video/')) item.previewType = 'video';
                    else if (file.type.startsWith('audio/')) item.previewType = 'audio';
                    else item.previewType = 'other';

                    // create data URL for preview
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        item.dataUrl = ev.target.result;
                        this.selectedFiles.push(item);
                        this.imagePreviews.push(item.dataUrl);
                    };
                    reader.readAsDataURL(file);
                });
            },
            removeMedia(index) {
                this.selectedFiles.splice(index, 1);
                this.imagePreviews.splice(index, 1);
                // also update the file input by rebuilding a DataTransfer (optional)
                if (this.$refs && this.$refs.mediaInput) {
                    try {
                        const dt = new DataTransfer();
                        // can't get actual File objects from dataUrls here, so clear input if any removed
                        // simpler: clear the input entirely when removing any file to avoid mismatch
                        this.$refs.mediaInput.value = null;
                    } catch (e) {
                        this.$refs.mediaInput.value = null;
                    }
                }
            },
            clearMedia() {
                this.selectedFiles = [];
                this.imagePreviews = [];
                if (this.$refs && this.$refs.mediaInput) this.$refs.mediaInput.value = null;
            },
            nutritionLabels: { calories: 'Calories', fat: 'Fat', carbs: 'Carbs', protein: 'Protein', fiber: 'Fiber', sugar: 'Sugar' }
        }"
            x-cloak>
            @csrf

            <div class="modal-card p-0 mb-6 overflow-hidden shadow-lg">
                <div class="bg-gradient-to-r from-primary to-accent px-8 py-7">
                    <h1 class="text-display-sm mb-2 text-white font-highlight">
                        New Recipe
                    </h1>
                    <p class="text-white">Share your best dish with the community!</p>
                </div>
                <div class="px-6 py-4 bg-background">
                    <p class="text-gray-500">Complete the form below to share your recipe with FoodFusion.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="modal-card p-6" x-data>
                        <label class="block mb-2 font-semibold">Title</label>
                        <input name="title" type="text" x-model="title" class="w-full input rounded-md"
                            placeholder="Grandma's Apple Pie" required>
                        @error('title')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="modal-card p-6">
                        <label class="block mb-2 font-semibold">Short Description</label>
                        <textarea name="description" x-model="description" rows="4" class="w-full input rounded-md"
                            placeholder="A quick, cozy dessert..."></textarea>
                        @error('description')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="modal-card p-6"
                        style="background: linear-gradient(180deg, rgba(255,255,255,0.9), var(--color-background));">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold">Ingredients</h2>
                            <button type="button" class="button" @click="addIngredient()"
                                style="background:var(--color-primary); color:white">Add</button>
                        </div>
                        <template x-for="(ing, idx) in ingredients" :key="idx">
                            <div x-transition class="flex gap-3 items-center mb-3">
                                <input :name="`ingredients[${idx}][quantity]`" x-model="ing.quantity" type="text"
                                    class="input w-24" placeholder="1">
                                <input :name="`ingredients[${idx}][unit]`" x-model="ing.unit" type="text"
                                    class="input w-24" placeholder="cup">
                                <input :name="`ingredients[${idx}][name]`" x-model="ing.name" type="text"
                                    class="input flex-1" placeholder="All-purpose flour">
                                <button type="button" class="text-red-600 ml-2" @click="removeIngredient(idx)"
                                    style="background:transparent">Remove</button>
                            </div>
                        </template>
                        @error('ingredients')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="modal-card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold">Steps</h2>
                            <button type="button" class="button" @click="addStep()"
                                style="background:var(--color-tertiary); color:white">Add Step</button>
                        </div>
                        <template x-for="(step, sidx) in steps" :key="sidx">
                            <div x-transition class="mb-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 text-center font-bold text-gray-700">Step <span x-text="sidx+1"></span>
                                    </div>
                                    <textarea :name="`steps[${sidx}][content]`" x-model="step.content" rows="3" class="input flex-1"
                                        placeholder="Do something..."></textarea>
                                    <div class="flex flex-col gap-2">
                                        <button type="button" class="button" @click="moveStepUp(sidx)" title="Move up"
                                            style="background:var(--color-primary); color:white">▲</button>
                                        <button type="button" class="button" @click="moveStepDown(sidx)" title="Move down"
                                            style="background:var(--color-primary); color:white">▼</button>
                                    </div>
                                </div>
                                <div class="text-right mt-2">
                                    <button type="button" class="text-red-600" @click="removeStep(sidx)">Remove</button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="modal-card p-6">
                        <h2 class="text-lg font-bold mb-3">Tips & Categories</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 font-semibold">Tags</label>
                                <div class="flex gap-2 items-center">
                                    <input x-model="tagInput" type="text" class="input flex-1" placeholder="e.g. vegan">
                                    <button type="button" class="button" @click="addTag()"
                                        style="background:var(--color-secondary); color:white">Add</button>
                                </div>
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <template x-for="(t, i) in tags" :key="i">
                                        <div class="tag flex items-center gap-2"
                                            style="background: linear-gradient(90deg,var(--color-tertiary), var(--color-accent)); color:white;">
                                            <span x-text="t"></span>
                                            <button type="button" class="text-sm text-white ml-2"
                                                @click="tags.splice(i,1)">×</button>
                                            <input type="hidden" :name="`tags[${i}]`" :value="t">
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 font-semibold">Tips</label>
                                <div class="space-y-2">
                                    <template x-for="(tp, ti) in tips" :key="ti">
                                        <div class="flex items-center gap-2">
                                            <input :name="`tips[${ti}]`" x-model="tp" type="text"
                                                class="input flex-1">
                                            <button type="button" class="text-red-600"
                                                @click="tips.splice(ti,1)">Remove</button>
                                        </div>
                                    </template>
                                    <div>
                                        <button type="button" class="button" @click="tips.push('')">Add Tip</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="space-y-6 sticky top-6">
                    <div class="modal-card p-6">
                        <label class="block mb-2 font-semibold">Cover Image / Media</label>
                        <div class="mb-3">
                            <template x-if="selectedFiles.length">
                                <div class="grid grid-cols-1 gap-3">
                                    <template x-for="(f, i) in selectedFiles" :key="i">
                                        <div class="flex gap-3 items-start p-2 border rounded-md">
                                            <div class="w-28 h-20 overflow-hidden rounded-md bg-gray flex-center">
                                                <template x-if="f.previewType === 'image'">
                                                    <img :src="f.dataUrl" class="w-full h-full object-cover" />
                                                </template>
                                                <template x-if="f.previewType === 'video'">
                                                    <video :src="f.dataUrl" class="w-full h-full object-cover" muted></video>
                                                </template>
                                                <template x-if="f.previewType === 'audio'">
                                                    {{-- <x-icons.music class="w-8 h-8" /> --}}
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
                                                <button type="button" class="button" @click="removeMedia(i)" style="background:transparent; color:var(--color-primary)">Remove</button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="flex gap-2 items-center mb-3">
                            <input x-ref="mediaInput" type="file" name="media[]" multiple @change="previewMedia($event)"
                                accept="image/*,video/*,audio/*,.heic,.heif,.webp">
                            <button type="button" class="button" @click="clearMedia()">Clear all</button>
                        </div>
                    </div>

                    <div class="modal-card p-6">
                        <label class="block mb-2 font-semibold">Servings</label>
                        <input name="servings" type="number" min="1" x-model.number="servings"
                            class="input w-28">

                        <h3 class="mt-4 font-semibold">Nutrition (per serving)</h3>
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <template x-for="(label, key) in nutritionLabels" :key="key">
                                <div>
                                    <label class="block text-sm text-gray-700">""</label>
                                </div>
                            </template>
                        </div>
                        <div class="mt-2 space-y-2">
                            <input name="nutrition[calories]" placeholder="Calories"
                                class="input w-full border border-10 solid border-primary" />
                            <input name="nutrition[fat]" placeholder="Fat (g)" class="input w-full"
                                style="border-left:4px solid var(--color-secondary)" />
                            <input name="nutrition[carbs]" placeholder="Carbs (g)" class="input w-full"
                                style="border-left:4px solid var(--color-tertiary)" />
                            <input name="nutrition[protein]" placeholder="Protein (g)" class="input w-full"
                                style="border-left:4px solid var(--color-accent)" />
                            <input name="nutrition[fiber]" placeholder="Fiber (g)" class="input w-full"
                                style="border-left:4px solid var(--color-gray)" />
                            <input name="nutrition[sugar]" placeholder="Sugar (g)" class="input w-full"
                                style="border-left:4px solid var(--color-primary)" />
                        </div>
                    </div>

                    <div class="modal-card p-6 flex items-center justify-between">
                        <a href="{{ url()->previous() }}" class="button"
                            style="background:transparent; border:1px solid var(--color-navbar-gray);">Cancel</a>
                        <button type="submit" class="button"
                            style="background:var(--color-accent); color:white;">Publish Recipe</button>
                    </div>
                </aside>
            </div>
        </form>
    </div>



    <style>
        [x-cloak] {
            display: none !important;
        }

        .input {
            padding: .6rem .85rem;
            border: 1px solid var(--color-navbar-gray);
            border-radius: .75rem;
            background: transparent;
        }

        .modal-card {
            background: var(--color-background);
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .06);
        }

        .button {
            padding: .45rem .9rem;
            border-radius: .75rem;
        }

        .tag {
            padding: .25rem .5rem;
            border-radius: .5rem;
            color: white
        }
    </style>
@endsection
