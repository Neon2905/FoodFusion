@extends('layouts.app')

@section('content')
    {{-- TODO: Consider to abstract/extract alpineJS --}}
    <form action="{{ route('recipes.create') }}" method="POST" enctype="multipart/form-data" class="flex-col w-full"
        x-data="createRecipe()" x-cloak>
        @csrf

        {{-- Main Title --}}
        <div class="card w-full flex-col p-0 overflow-hidden gap-0">
            <div class="py-7 px-6 gap-3 text-white bg-gradient-to-r from-primary to-accent">
                <span class="font-highlight font-semibold text-display-sm">Cook a Recipe</span>
                <p>Share your best dishes with community!</p>
            </div>
            <div class="px-6 py-4 text-body-md font-semibold text-muted">
                Complete the form below to share your recipe with FoodFusion.
            </div>
        </div>

        @if ($errors->any())
            <div class="card w-full p-4 bg-red-100 text-red-800 mb-5">
                <h3 class="font-bold mb-2">There were some problems with your input:</h3>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex md:flex-row flex-col w-full gap-3 pt-5">
            {{-- Left side: Main inputs --}}
            <div class="flex flex-col w-full gap-3">
                <div class="card ">
                    <p class="font-bold">
                        Title
                    </p>
                    <input type="text" required name="title" class="w-full input-box"
                        placeholder="Grandma's Apple Pie">
                    <x-error-message names="title" />
                </div>

                {{-- media uploader --}}
                <div class="card flex flex-col gap-2">
                    <p class="font-bold">
                        Media
                    </p>
                    <x-media-uploader class="rounded border border-gray" />
                </div>

                {{-- description --}}
                <div class="card ">
                    <p class="font-bold">
                        Description
                    </p>
                    <textarea required name="description" class="w-full input-box" placeholder="A quick, cozy dessert.."></textarea>
                    <x-error-message names="description" />
                </div>

                <div class="flex w-full gap-3">
                    <div class="card flex-row  max-w-70">
                        <div class="flex flex-col gap-3">
                            <p class="font-bold">
                                Prep Time
                            </p>
                            <input type="number" min="0" name="prep_time" class="w-full input-box"
                                placeholder="10 mins" inputmode="numeric" pattern="[0-9]*">
                        </div>
                        <div class="flex flex-col gap-3">
                            <p class="font-bold">
                                Cook Time
                            </p>
                            <input type="number" min="0" name="cook_time" class="w-full input-box"
                                placeholder="30 mins" inputmode="numeric" pattern="[0-9]*">
                        </div>
                    </div>
                    <div class="card  w-80">
                        <p class="font-bold">
                            Difficulty
                        </p>
                        <input required type="text" name="difficulty" class="w-full input-box"
                            placeholder="Hard - Medium">
                        <x-error-message names="difficulty" />
                    </div>
                    <div class="card  w-full">
                        <p class="font-bold">
                            Meal Type
                        </p>
                        <input required type="text" name="meal_type" class="w-full input-box"
                            placeholder="Breakfast - Lunch - Dinner">
                        <x-error-message names="meal_type" />
                    </div>
                </div>

                {{-- ingredients --}}
                <div class="card ">
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
                                <input required type="text" :name="`ingredients[${index}][quantity]`"
                                    x-model="ingredient.quantity" class="w-20 input-box" placeholder="1">
                                <input required type="text" :name="`ingredients[${index}][unit]`"
                                    x-model="ingredient.unit" class="w-20 input-box" placeholder="cup">
                                <input required type="text" :name="`ingredients[${index}][name]`"
                                    x-model="ingredient.name" class="w-full input-box" placeholder="All-purpose flour">
                                <div x-show="ingredients.length > 1" class="flex-center pt-3">
                                    <button type="button" class="clickable" @click="removeIngredient(index)"
                                        title="Remove ingredient">
                                        <x-css-trash class="text-error" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <x-error-message names="ingredients.*" />
                </div>

                {{-- steps --}}
                <div class="card ">
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
                                <textarea required :name="`steps[${index}][instruction]`" x-model="step.instruction" rows="3"
                                    class="w-full input-box" placeholder="Do something..."></textarea>
                                <input type="hidden" :name="`steps[${index}][step_order]`" :value="index + 1" />
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
                                    <button x-show="steps.length > 1" type="button" class="clickable"
                                        @click="removeStep(index)" title="Remove step">
                                        <x-css-trash class="text-error" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <x-error-message names="steps.*" />
                </div>

                {{-- tips --}}
                <div class="card ">
                    <div class="flex flex-row w-full justify-between items-center">
                        <p class="font-bold">
                            Tips
                        </p>
                        <button type="button" class="clickable" @click="addTip()">
                            <x-feathericon-plus-circle class="text-accent" />
                        </button>
                    </div>
                    <template x-for="(tip, index) in tips" :key="index">
                        <div x-transition class="mb-3">
                            <div class="flex items-start gap-3">
                                <textarea :name="`tips[${index}][content]`" x-model="tip.content" rows="1" class="w-full input-box"
                                    placeholder="Give a tip"></textarea>
                                <input type="hidden" :name="`tips[${index}]`" :value="index" />
                                <div class="flex-center gap-2">
                                    <div class="flex flex-col gap-2">
                                        <button type="button" class="rounded-full size-min clickable"
                                            @click="moveTipUp(index)" title="Move up"
                                            style="background:var(--color-primary); color:white">
                                            <x-bi-arrow-up-short class="size-4" />
                                        </button>
                                        <button type="button" class="rounded-full size-min clickable"
                                            @click="moveTipDown(index)" title="Move down"
                                            style="background:var(--color-primary); color:white">
                                            <x-bi-arrow-down-short class="size-4" />
                                        </button>
                                    </div>
                                    <button x-show="tips.length > 1" type="button" class="clickable"
                                        @click="removeTip(index)" title="Remove tip">
                                        <x-css-trash class="text-error" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Right side: Additional inputs --}}
            <div class="flex flex-col gap-3 md:w-1/3 w-full">
                <div class="card">
                    <p class="font-bold">
                        Tags
                    </p>
                    <x-tag-input name="tags" placeholder="Add a tag" />
                </div>
                <div class="card">
                    <p class="font-semibold">
                        Servings
                    </p>
                    <div class="flex items-center gap-1">
                        <button type="button" class="button p-0 rounded-full bg-primary"
                            @click="servings = Math.max(1, (parseInt(servings) || 1) - 1)">
                            <x-bi-dash class="size-5" />
                        </button>
                        <input required type="number" min="1" name="servings" x-model="servings"
                            class="input-box py-1 text-center w-16" placeholder="4" inputmode="numeric"
                            pattern="[0-9]*">
                        <button type="button" class="button p-0 rounded-full bg-primary"
                            @click="servings = (parseInt(servings) || 0) + 1">
                            <x-bi-plus class="size-5" />
                        </button>
                    </div>
                    <x-error-message names="servings" />
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
                <div class="card">
                    <button type="submit" class="button bg-accent">Publish Recipe</button>
                    <button type="button" class="button bg-gray" @click="cancel()">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

<script>
    function createRecipe() {
        return {
            title: '',
            description: '',
            prep_time: '',
            cook_time: '',
            difficulty: '',
            meal_type: '',

            servings: '',
            ingredients: [{
                quantity: '',
                unit: '',
                name: ''
            }],
            steps: [{
                title: '',
                instruction: ''
            }],
            tips: [''],
            tags: [],
            tagInput: '', // TODO
            addIngredient() {
                this.ingredients.push({
                    quantity: '',
                    unit: '',
                    name: ''
                })
            },
            removeIngredient(i) {
                if (this.ingredients.length <= 1) return;
                this.ingredients.splice(i, 1)
            },
            addStep() {
                this.steps.push({
                    title: '',
                    instruction: ''
                })
            },
            removeStep(i) {
                if (this.steps.length <= 1) return;
                this.steps.splice(i, 1)
            },
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

            addTip() {
                this.tips.push({
                    content: ''
                })
            },
            removeTip(i) {
                if (this.tips.length <= 1) return;
                this.tips.splice(i, 1)
            },
            moveTipUp(i) {
                if (i === 0) return;
                const a = this.tips[i - 1];
                this.tips.splice(i - 1, 2, this.tips[i], a)
            },
            moveTipDown(i) {
                if (i === this.tips.length - 1) return;
                const a = this.tips[i + 1];
                this.tips.splice(i, 2, a, this.tips[i])
            },
            addTag() {
                const t = this.tagInput && this.tagInput.trim();
                if (!t) return;
                if (!this.tags.includes(t)) this.tags.push(t);
                this.tagInput = ''
            },
            nutritionLabels: {
                calories: 'Calories',
                fat: 'Fat',
                carbs: 'Carbs',
                protein: 'Protein',
                fiber: 'Fiber',
                sugar: 'Sugar'
            },
            nutritions: {
                calories: '',
                fat: '',
                carbs: '',
                protein: '',
                fiber: '',
                sugar: ''
            },

            cancel() {
                if (confirm('This will discard any unsaved data. Are you sure you want to leave?'))
                    (typeof route !== 'undefined' ? route.back() : window.history.back());
            }
        }
    }
</script>
