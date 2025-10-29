@extends('layouts.app', ['title' => 'Contact Us'])

@section('content')
    <div class="max-w-3xl mx-auto py-12" x-data="{ sending: false }">
        <h1 class="text-2xl font-semibold mb-4">Contact Us</h1>
        <p class="text-sm text-muted mb-6">Have a question, recipe request or feedback? Send us a message and we'll
            respond as soon as possible.</p>

        {{-- Success --}}
        @if (session('success'))
            <div class="mb-4 p-3 rounded bg-green-50 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('contact.submit') }}"
            @submit.prevent="if(!sending){ sending = true; $el.submit() }" novalidate>
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="name" class="block text-sm text-muted mb-1">Name</label>
                    <input id="name" name="name" value="{{ old('name') }}" required
                        class="input w-full px-3 py-2 rounded border bg-white" />
                </div>

                <div>
                    <label for="email" class="block text-sm text-muted mb-1">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                        class="input w-full px-3 py-2 rounded border bg-white" />
                </div>
            </div>

            <div class="mb-4">
                <label for="subject" class="block text-sm text-muted mb-1">Subject (optional)</label>
                <input id="subject" name="subject" value="{{ old('subject') }}"
                    class="input w-full px-3 py-2 rounded border bg-white" />
            </div>

            <div class="mb-4">
                <label for="message" class="block text-sm text-muted mb-1">Message</label>
                <textarea id="message" name="message" rows="6" required class="input w-full px-3 py-2 rounded border bg-white">{{ old('message') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="button bg-primary text-white px-4 py-2 rounded disabled:opacity-60"
                    :disabled="sending">
                    <template x-if="sending">
                        <svg class="animate-spin w-4 h-4 inline-block mr-2" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                        </svg>
                    </template>
                    <span x-text="sending ? 'Sending…' : 'Send Message'"></span>
                </button>

                <button type="button" class="px-4 py-2 rounded border"
                    @click="() => { $refs.form && $refs.form.reset(); }">
                    Reset
                </button>
            </div>
        </form>
    </div>
@endsection
