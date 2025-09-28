{{-- resources/views/reviews/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow rounded-lg">
        <h1 class="text-2xl font-bold mb-6">Leave a Review</h1>

        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('test.post') }}" method="POST" x-data="{ rating: {{ old('rating', 0) }} }">
            @csrf

            {{-- Review Text --}}
            <div class="mb-4">
                <label for="review" class="block text-sm font-medium text-gray-700 mb-1">Review</label>
                <textarea id="review" name="review" rows="4"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Write your review...">{{ old('review') }}</textarea>
                @error('review')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Rating --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                <input type="hidden" name="rating" :value="rating">

                <div class="flex space-x-1">
                    <template x-for="i in 5">
                        <button type="button" @click="rating = i" @keydown.enter.prevent="rating = i"
                            :aria-checked="rating === i" role="radio" class="focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                :class="rating >= i ? 'text-yellow-400' : 'text-gray-300'"
                                class="h-8 w-8 transition-colors">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1
                                                   1 0 00.95.69h3.462c.969 0 1.371 1.24.588
                                                   1.81l-2.8 2.034a1 1 0 00-.364
                                                   1.118l1.07 3.292c.3.921-.755
                                                   1.688-1.54 1.118l-2.8-2.034a1 1 0
                                                   00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1
                                                   1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1
                                                   1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </button>
                    </template>
                </div>

                @error('rating')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div>
                <button type="submit"
                    class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-white font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Submit Review
                </button>
            </div>
        </form>
    </div>
@endsection
