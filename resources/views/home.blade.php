@extends('layouts.app')

@section('content')
    {{-- inject initial data for Alpine --}}
    <script>
        window.__WELCOME_DATA__ = {
            carouselItems: @json($carouselItems ?? []),
            news: @json($news ?? []),
            events: @json($events ?? [])
        };
    </script>

    <!-- listen for global join event so navbar can open this modal -->
    <div class="max-w-7xl mx-auto" x-data="welcome()" x-cloak>
        <!-- Hero -->
        <section class="rounded-xl overflow-hidden shadow-lg mb-6">
            <div class="px-8 py-9 bg-gradient-to-r from-orange-400 to-green-400 text-white">
                <h1 class="text-4xl font-bold mb-2">Cook. Share. Inspire.</h1>
                <p class="max-w-2xl">FoodFusion brings home cooks and creators together — share recipes, learn new
                    techniques, and join events near you.</p>

                @guest
                    <!-- primary CTA -->
                    <div class="mt-5">
                        <button @click="toggleRegisterModal()"
                            class="inline-flex items-center gap-2 bg-white text-green-700 px-4 py-2 rounded-full font-semibold shadow">
                            Join Us
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                @endguest
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
                        <!-- sliding track -->
                        <div class="w-full overflow-hidden">
                            <div class="flex transition-transform duration-700 ease-in-out"
                                :style="'transform: translateX(-' + (carouselIndex * 100) + '%)'">
                                <template x-for="(item, idx) in carouselItems" :key="item.id">
                                    <div class="min-w-full p-6 flex flex-col md:flex-row items-center gap-6">
                                        <div
                                            class="w-full md:w-1/3 h-48 bg-gray-100 rounded flex items-center justify-center relative">
                                            <template x-if="item.img">
                                                <img :src="item.img" class="object-cover w-full h-full rounded"
                                                    alt="" @load="imageLoaded(idx)" />
                                            </template>
                                            <template x-if="!item.img">
                                                <span class="text-gray-400">Image</span>
                                            </template>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold" x-text="item.title"></h3>
                                            <p class="text-sm text-gray-500" x-text="item.date"></p>
                                            <p class="mt-2 text-gray-700">Discover this event and save your spot — workshops
                                                and tastings updated weekly.</p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="absolute left-3 top-1/2 -translate-y-1/2">
                            <button @click="prevSlide()" aria-label="Previous slide"
                                class="px-3 py-2 rounded-full bg-white shadow">‹</button>
                        </div>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                            <button @click="nextSlide()" aria-label="Next slide"
                                class="px-3 py-2 rounded-full bg-white shadow">›</button>
                        </div>
                    </div>
                </div>

                <!-- News feed -->
                <div class="bg-white rounded-xl shadow p-4 space-y-3">
                    <h2 class="font-bold text-lg">Featured Recipes & Trends</h2>
                    <template x-for="n in news" :key="n.id">
                        <a :href="n.slug ? ('/recipe/' + n.slug) : '/recipes'"
                            class="block border rounded p-3 flex items-start gap-3 hover:shadow-sm">
                            <div class="w-20 h-20 bg-gray-100 rounded flex items-center justify-center overflow-hidden">
                                <!-- placeholder square; if you have thumbnails in news, replace src -->
                                <img x-show="n.img" :src="n.hero_url" class="w-full h-full object-cover"
                                    alt="" />
                            </div>
                            <div>
                                <h3 class="font-semibold" x-text="n.title"></h3>
                                <p class="text-sm text-gray-600" x-text="n.excerpt"></p>
                            </div>
                        </a>
                    </template>
                </div>
            </main>

            <!-- Sidebar -->
            <aside class="space-y-6">
                <div class="bg-white rounded-xl shadow p-4">
                    <h3 class="font-semibold">Upcoming Events</h3>
                    <template x-if="events && events.length">
                        <ul class="mt-3 space-y-2 text-sm">
                            <template x-for="e in events" :key="e.id">
                                <li class="flex items-center justify-between">
                                    <span x-text="e.title"></span>
                                    <span class="text-gray-500 text-xs" x-text="e.date"></span>
                                </li>
                            </template>
                        </ul>
                    </template>

                    <template x-if="!events || events.length === 0">
                        <div class="mt-3 text-sm text-gray-500">Nothing to show.</div>
                    </template>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <h4 class="font-semibold">Follow Us</h4>
                    <div class="mt-3 flex gap-3">
                        <a href="https://www.facebook.com" target="_blank" rel="noopener" aria-label="Facebook"
                            class="button px-3 py-2 text-white" style="background-color:#1877F2;">
                            <span class="text-sm font-medium">Facebook</span>
                        </a>

                        <a href="#" target="_blank" rel="noopener" aria-label="Instagram"
                            class="button px-3 py-2 text-white"
                            style="background:linear-gradient(45deg,#f58529,#dd2a7b,#515bd4);">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5A4.5 4.5 0 1 1 7.5 12 4.5 4.5 0 0 1 12 7.5zm0 2A2.5 2.5 0 1 0 14.5 12 2.5 2.5 0 0 0 12 9.5zm4.8-3.8a1 1 0 1 1-1 1 1 1 0 0 1 1-1z" />
                            </svg>
                            <span class="text-sm font-medium">Instagram</span>
                        </a></svg>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-4 text-sm">
                    <a href="/privacy" class="block mb-2">Privacy Policy</a>
                    <a href="/cookies" class="block">Cookie Policy</a>
                </div>
            </aside>
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
    function welcome(initial = window.__WELCOME_DATA__ || {}) {
        return {

            // carousel (use initial data)
            carouselIndex: 0,
            carouselItems: initial.carouselItems || [],

            // track image loading per slide for spinner overlay
            imagesLoading: (initial.carouselItems || []).map(i => i.img ? true : false),

            imageLoaded(idx) {
                // mark this image as loaded (spinner hidden)
                this.imagesLoading[idx] = false;
            },

            nextSlide() {
                if (!this.carouselItems.length) return;
                this.carouselIndex = (this.carouselIndex + 1) % this.carouselItems.length;
            },
            prevSlide() {
                if (!this.carouselItems.length) return;
                this.carouselIndex = (this.carouselIndex - 1 + this.carouselItems.length) % this.carouselItems.length;
            },

            // news feed (from server)
            news: initial.news || [],

            // cookie consent
            showCookie: false,
            cookieKey: 'ff_cookie_accepted',

            // events (sidebar)
            events: initial.events || [],

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

            // cleanup
            destroy() {
                clearInterval(this._carouselInterval);
            }
        }
    }
</script>
