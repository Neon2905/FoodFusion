@extends('layouts.app')

@section('content')
    <form action="{{ route('recipes.create') }}" method="POST" enctype="multipart/form-data" class="flex-col w-full"
        x-data="{
            title: '',
            description: '',
            servings: '',
            ingredients: [{ quantity: '', unit: '', name: '' }],
            steps: [{ title: '', content: '' }],
            tags: [],
            tips: [],
            tagInput: '',
            imagePreviews: [], // array of data URLs
            addIngredient() { this.ingredients.push({ quantity: '', unit: '', name: '' }) },
            removeIngredient(i) { this.ingredients.splice(i, 1) },
            addStep() { this.steps.push({ title: '', content: '' }) },
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
            nutritionLabels: { calories: 'Calories', fat: 'Fat', carbs: 'Carbs', protein: 'Protein', fiber: 'Fiber', sugar: 'Sugar' },
            nutritions: { calories: '', fat: '', carbs: '', protein: '', fiber: '', sugar: '' },
        }" x-cloak>
        @csrf

        {{-- Main Title --}}
        <div class="modal-card w-full flex-col p-0 overflow-hidden gap-0">
            <div class="py-7 px-6 gap-3 text-white bg-gradient-to-r from-primary to-accent">
                <span class="font-highlight font-semibold text-display-sm">Cook a Recipe</span>
                <p>Share your best dishes with community!</p>
            </div>
            <div class="px-6 py-4 text-body-md font-semibold text-muted">
                Complete the form below to share your recipe with FoodFusion.
            </div>
        </div>

        <div class="flex md:flex-row flex-col w-full gap-3 pt-5">
            {{-- Left side: Main inputs --}}
            <div class="flex flex-col w-full gap-3">
                <div class="modal-card bg-on-background">
                    <p class="font-bold">
                        Title
                    </p>
                    <input type="text" name="title" class="w-full input-box" placeholder="Grandma's Apple Pie"
                        x-model="title">
                </div>
                <div class="modal-card bg-on-background">
                    <p class="font-bold">
                        Description
                    </p>
                    <textarea name="description" class="w-full input-box" placeholder="A quick, cozy dessert.."></textarea>
                </div>

                {{-- ingredients --}}
                <div class="modal-card bg-on-background">
                    <div class="flex flex-row w-full justify-between items-center">
                        <p class="font-bold">
                            Ingredients
                        </p>
                        <button type="button" class="clickable" @click="addIngredient()">
                            <x-feathericon-plus-circle class="text-accent" />
                        </button>
                    </div>
                    <template x-for="(ingredient, index) in ingredients" :key="index">
                        <div x-transition class="mb-3">
                            <div class="flex items-start gap-3">
                                <input type="text" :name="`ingredients[${index}][quantity]`"
                                    x-model="ingredient.quantity" class="w-20 input-box" placeholder="1">
                                <input type="text" :name="`ingredients[${index}][unit]`" x-model="ingredient.unit"
                                    class="w-20 input-box" placeholder="cup">
                                <input type="text" :name="`ingredients[${index}][name]`" x-model="ingredient.name"
                                    class="w-full input-box" placeholder="All-purpose flour">
                                <div class="flex-center pt-3">
                                    <button type="button" class="clickable" @click="removeIngredient(index)"
                                        title="Remove ingredient">
                                        <x-css-trash class="text-error" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- steps --}}
                <div class="modal-card bg-on-background">
                    <div class="flex flex-row w-full justify-between items-center">
                        <p class="font-bold">
                            Steps
                        </p>
                        <button type="button" class="clickable" @click="addStep()">
                            <x-feathericon-plus-circle class="text-accent" />
                        </button>
                    </div>
                    <template x-for="(step, index) in steps" :key="index">
                        <div x-transition class="mb-3">
                            <div class="flex items-start gap-3">
                                <input type="text" :name="`steps[${index}][title]`" x-model="step.title"
                                    class="w-20 input-box" :placeholder="`Step ${index + 1}`">
                                <textarea :name="`steps[${index}][content]`" x-model="step.content" rows="3" class="w-full input-box"
                                    placeholder="Do something..."></textarea>
                                <input type="hidden" :name="`steps[${index}][order]`" :value="index" />
                                <div class="flex-center pt-3 gap-2">
                                    <div class="flex flex-col gap-4">
                                        <button type="button" class="rounded-full size-min clickable"
                                            @click="moveStepUp(index)" title="Move up"
                                            style="background:var(--color-primary); color:white">
                                            <x-bi-arrow-up-short class="size-5" />
                                        </button>
                                        <button type="button" class="rounded-full size-min clickable"
                                            @click="moveStepDown(index)" title="Move down"
                                            style="background:var(--color-primary); color:white">
                                            <x-bi-arrow-down-short class="size-5" />
                                        </button>
                                    </div>
                                    <button type="button" class="clickable" @click="removeStep(index)" title="Remove step">
                                        <x-css-trash class="text-error" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Right side: Additional inputs --}}
            <div class="flex flex-col md:w-1/3 w-full">
                <div class="modal-card">
                    <p class="font-semibold">
                        Servings
                    </p>
                    <div class="flex items-center gap-1">
                        <button type="button" class="button p-0 rounded-full bg-primary"
                            @click="servings = Math.max(1, (parseInt(servings) || 1) - 1)">
                            <x-bi-dash class="size-5" />
                        </button>
                        <input type="number" min="1" name="servings" x-model="servings"
                            class="input-box py-1 text-center w-16" placeholder="4" inputmode="numeric"
                            pattern="[0-9]*">
                        <button type="button" class="button p-0 rounded-full bg-primary"
                            @click="servings = (parseInt(servings) || 0) + 1">
                            <x-bi-plus class="size-5" />
                        </button>
                    </div>
                    <p class="font-semibold">
                        Nutritional Facts
                    </p>

                    {{-- Nutrition inputs --}}
                    <div class="flex flex-col gap-2">
                        <input type="number" class="input-box" placeholder="Calories" x-model="nutritions.calories"
                            name="nutritions[calories]" inputmode="numeric" pattern="[0-9]*">
                        <input type="number" class="input-box" placeholder="Fat (g)" x-model="nutritions.fat"
                            name="nutritions[fat]" inputmode="numeric" pattern="[0-9]*">
                        <input type="number" class="input-box" placeholder="Carbs (g)" x-model="nutritions.carbs"
                            name="nutritions[carbs]" inputmode="numeric" pattern="[0-9]*">
                        <input type="number" class="input-box" placeholder="Protein (g)" x-model="nutritions.protein"
                            name="nutritions[protein]" inputmode="numeric" pattern="[0-9]*">
                        <input type="number" class="input-box" placeholder="Fiber (g)" x-model="nutritions.fiber"
                            name="nutritions[fiber]" inputmode="numeric" pattern="[0-9]*">
                        <input type="number" class="input-box" placeholder="Suger (g)" x-model="nutritions.sugar"
                            name="nutritions[sugar]" inputmode="numeric" pattern="[0-9]*">
                    </div>

                    <p class="font-semibold">
                        Nutrition per serving
                    </p>

                    {{-- per serving summary --}}
                    <div class="flex flex-col">
                        <template x-for="nutrition in nutritions">
                            <div class="flex justify-between border-b border-gray-200 py-1">
                                <span x-text="nutritionLabels[nutrition[0]]"></span>
                                <span
                                    x-text="nutrition[1] ? nutrition[1] + (nutrition[0] === 'calories' ? '' : ' g') : '-'"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-center modal-card">
            <button type="submit" class="btn btn-primary w-full">
                <x-feathericon-plus class="size-5 mr-2" />
                Share Recipe
            </button>
        </div>
    </form>
@endsection
