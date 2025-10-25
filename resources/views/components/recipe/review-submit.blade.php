<form class="flex-center flex-col space-between gap-2 w-full p-3" method="POST"
    action="{{ route('review.submit', ['slug' => $slug]) }}" x-data="{ rating: {{ old('rating', 0) }} }">
    @csrf
    <div class="flex justify-start gap-2 w-full">
        @auth
            <img class="rounded rounded-full size-13" src="{{ auth()->user()->profile->profile }}" alt="">
        @endauth
        <textarea name="review" required
            class="flex-1 rounded-lg px-4 py-2 text-body-lg bg-background focus:outline-none focus:ring-2 focus:ring-primary resize-y min-h-20"
            placeholder="Did you make this recipe? Leave a review!">{{ old('review') }}</textarea>

        <x-error-message names="review" />
    </div>
    <div class="flex justify-between space-between w-full">
        <div class="flex items-center gap-2 text-subtitle-lg font-semibold">
            <input type="hidden" name="rating" :value="rating">
            Your Rating:
            <div class="flex space-x-1">
                <template x-for="i in 5">
                    <button type="button" @click="rating = i" @keydown.enter.prevent="rating = i"
                        :aria-checked="rating === i" role="radio" class="focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            :class="rating >= i ? 'text-primary' : 'fill-black'" class="h-6 w-6 transition-colors">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.454a1 1 0 00-1.175 0l-3.38 2.454c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z" />
                        </svg>
                    </button>
                </template>
            </div>
        </div>
        <button
            class="flex-center justify-between button bg-tertiary text-black text-subtitle-md font-semibold rounded-full"
            :disabled="rating < 1">
            Post Review
        </button>
    </div>
</form>
