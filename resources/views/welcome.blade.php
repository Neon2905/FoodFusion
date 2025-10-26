@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="welcome()" x-cloak>
        <!-- Hero -->
        <section class="rounded-xl overflow-hidden shadow-lg mb-6">
            <div class="px-8 py-9 bg-gradient-to-r from-orange-400 to-green-400 text-white">
                <h1 class="text-4xl font-bold mb-2">Cook. Share. Inspire.</h1>
                <p class="max-w-2xl">FoodFusion brings home cooks and creators together — share recipes, learn new
                    techniques, and join events near you.</p>
            </div>
            <div class="px-6 py-4 bg-white">
                <p class="text-sm text-gray-600">Join our community to save favorites, publish recipes, and attend live
                    workshops.</p>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main column -->
            <main class="lg:col-span-2 space-y-6">
                <!-- Featured carousel -->
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="relative overflow-hidden rounded-md">
                        <template x-for="(item, idx) in carouselItems" :key="item.id">
                            <div x-show="carouselIndex === idx" x-transition
                                class="p-6 flex flex-col md:flex-row items-center gap-6">
                                <div class="w-full md:w-1/3 h-48 bg-gray-100 rounded flex items-center justify-center">
                                    <span class="text-gray-400">Image</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold" x-text="item.title"></h3>
                                    <p class="text-sm text-gray-500" x-text="item.date"></p>
                                    <p class="mt-2 text-gray-700">Discover this event and save your spot — workshops and
                                        tastings updated weekly.</p>
                                </div>
                            </div>
                        </template>

                        <div class="absolute left-3 top-1/2 -translate-y-1/2">
                            <button @click="prevSlide()" class="px-3 py-2 rounded-full bg-white shadow">‹</button>
                        </div>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                            <button @click="nextSlide()" class="px-3 py-2 rounded-full bg-white shadow">›</button>
                        </div>
                    </div>
                </div>

                <!-- News feed -->
                <div class="bg-white rounded-xl shadow p-4 space-y-3">
                    <h2 class="font-bold text-lg">Featured Recipes & Trends</h2>
                    <template x-for="n in news" :key="n.id">
                        <article class="border rounded p-3 flex items-start gap-3">
                            <div class="w-20 h-20 bg-gray-100 rounded flex items-center justify-center">Img</div>
                            <div>
                                <h3 class="font-semibold" x-text="n.title"></h3>
                                <p class="text-sm text-gray-600" x-text="n.excerpt"></p>
                            </div>
                        </article>
                    </template>
                </div>
            </main>

            <!-- Sidebar -->
            <aside class="space-y-6">
                <div class="bg-white rounded-xl shadow p-4">
                    <h3 class="font-semibold">Upcoming Events</h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <template x-for="e in carouselItems" :key="e.id">
                            <li class="flex items-center justify-between">
                                <span x-text="e.title"></span>
                                <span class="text-gray-500 text-xs" x-text="e.date"></span>
                            </li>
                        </template>
                    </ul>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <h4 class="font-semibold">Follow Us</h4>
                    <div class="mt-3 flex gap-3">
                        <a href="#" class="px-3 py-2 rounded border">Twitter</a>
                        <a href="#" class="px-3 py-2 rounded border">Instagram</a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-4 text-sm">
                    <a href="/privacy" class="block mb-2">Privacy Policy</a>
                    <a href="/cookies" class="block">Cookie Policy</a>
                </div>
            </aside>
        </div>

        <!-- Join modal (uses normal form POST to /register for reliability) -->
        <div x-show="showJoin" class="fixed inset-0 z-40 flex items-center justify-center bg-black/40" x-transition>
            <div @click.away="closeJoin()" class="bg-white rounded-xl w-full max-w-xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Join FoodFusion</h3>
                    <button @click="closeJoin()" class="text-gray-500">✕</button>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <input name="first_name" placeholder="First name" class="input p-2 rounded" required>
                        <input name="last_name" placeholder="Last name" class="input p-2 rounded">
                        <input name="email" type="email" placeholder="Email" class="input p-2 rounded" required>
                        <input name="password" type="password" placeholder="Password" class="input p-2 rounded" required>
                    </div>

                    <div class="mt-4 flex justify-end gap-3">
                        <button type="button" @click="closeJoin()" class="px-4 py-2 rounded border">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded bg-green-500 text-white">Create account</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cookie consent -->
        <div x-show="showCookie" class="fixed bottom-4 right-4 z-50 bg-white rounded-lg shadow p-4 w-80" x-transition>
            <div class="text-sm text-gray-700">
                We use cookies to improve your experience. By continuing, you agree to our <a href="/privacy"
                    class="underline text-primary">privacy policy</a>.
            </div>
            <div class="mt-3 flex justify-end gap-2">
                <button @click="acceptCookies()" class="px-3 py-1 rounded bg-green-500 text-white">Accept</button>
                <a href="/cookies" class="px-3 py-1 rounded border">Learn more</a>
            </div>
        </div>
    </div>
@endsection

<script>
    function welcome() {
        return {
            // modal / form
            showJoin: false,
            joinForm: { first_name: '', last_name: '', email: '', password: '' },

            // carousel
            carouselIndex: 0,
            carouselItems: [
                { id: 1, title: 'Farm-to-Table Workshop', date: '2025-11-09', img: null },
                { id: 2, title: 'Sourdough Basics', date: '2025-12-02', img: null },
                { id: 3, title: 'Holiday Feast Ideas', date: '2026-01-15', img: null }
            ],
            nextSlide() {
                this.carouselIndex = (this.carouselIndex + 1) % this.carouselItems.length;
            },
            prevSlide() {
                this.carouselIndex = (this.carouselIndex - 1 + this.carouselItems.length) % this.carouselItems.length;
            },

            // news feed (placeholder items — swap with server data)
            news: [
                { id: 1, title: 'Featured: Lemon Roast Chicken', excerpt: 'A bright, simple roast that steals the show.' },
                { id: 2, title: 'Trend: Fusion Breakfast Bowls', excerpt: 'Mix global flavors for a nutrient-packed morning.' },
                { id: 3, title: 'How-to: Crispy Skillet Potatoes', excerpt: 'A step-by-step to perfect crispiness.' }
            ],

            // cookie consent
            showCookie: false,
            cookieKey: 'ff_cookie_accepted',

            init() {
                // cookie banner
                this.showCookie = !localStorage.getItem(this.cookieKey);

                // autoplay carousel (gentle)
                this._carouselInterval = setInterval(() => this.nextSlide(), 6000);
            },

            acceptCookies() {
                localStorage.setItem(this.cookieKey, '1');
                this.showCookie = false;
            },

            // helpers for join modal
            openJoin() { this.showJoin = true; },
            closeJoin() { this.showJoin = false; },

            // optional AJAX submit fallback (if you want to submit via fetch)
            async submitJoinForm(e) {
                // If the form uses regular server POST (action + method + @csrf), remove this handler.
                // This function demonstrates AJAX submission and simple client-side validation.
                e.preventDefault();
                const payload = { ...this.joinForm };
                if (!payload.email || !payload.password) {
                    alert('Please provide email and password.');
                    return;
                }

                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    const res = await fetch('{{ url('') }}/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify(payload)
                    });
                    if (res.ok) {
                        // success - redirect or close modal
                        this.closeJoin();
                        window.location.reload();
                    } else {
                        const json = await res.json().catch(() => ({}));
                        alert(json.message || 'Registration failed');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Network error');
                }
            },

            // cleanup
            destroy() {
                clearInterval(this._carouselInterval);
            }
        }
    }
</script>
