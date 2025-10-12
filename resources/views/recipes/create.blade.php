@extends('layouts.app')

@section('content')
    <form method="{{ route('recipes.create') }}" method="POST" enctype="multipart/form-data" class="flex-col w-full"
        x-data="{
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
        }" x-cloak>
        @csrf
        <div class="modal-card w-full flex-col p-0 overflow-hidden gap-0">
            <div class="py-7 px-6 gap-3 text-white bg-gradient-to-r from-primary to-accent">
                <span class="font-highlight font-semibold text-display-sm">Cook a Recipe</span>
                <p>Share your best dishes with community!</p>
            </div>
            <div class="px-6 py-4 text-body-md font-semibold text-muted">
                Complete the form below to share your recipe with FoodFusion.
            </div>
        </div>
        <div class="flex w-full pt-5">
            <div class="flex flex-col w-full gap-3">
                <div class="modal-card bg-on-background">
                    <p class="font-bold">
                        Title
                    </p>
                    <input type="text" name="title" class="w-full text-body-lg border border-gray-300 rounded-md p-2"
                        placeholder="Grandma's Apple Pie">
                </div>
                <div class="modal-card bg-on-background">
                    <p class="font-bold">
                        Description
                    </p>
                    <textarea name="description" class="w-full text-body-lg border border-gray-300 rounded-md p-2"
                        placeholder="A quick, cozy dessert.."></textarea>
                </div>
                <div class="modal-card bg-on-background">
                    <p class="font-bold">
                        Steps
                    </p>
                    {{-- TODO: Start again from here --}}
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
            </div>
        </div>
    </form>
@endsection
