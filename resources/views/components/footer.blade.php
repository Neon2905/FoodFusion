<footer class="w-full bg-navbar-gray border-t border-[rgba(191,191,191,0.5)] py-8 px-6 md:px-12">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        {{-- Brand --}}
        <div class="flex flex-col items-start gap-2">
            <a href="{{ route('home') }}"
                class="flex items-center no-underline gap-0 text-display-sm drop-shadow-[0_4px_4px_rgba(0,0,0,.5)]">
                <span class="text-primary">Food</span><span class="text-accent">Fusion</span>
            </a>
            <p class="text-sm text-muted">Discover. Cook. Share.</p>
        </div>

        {{-- Links --}}
        <nav aria-label="Footer navigation" class="w-full md:w-auto">
            <ul class="flex flex-wrap gap-3 md:gap-6 items-start">
                <li><a href="/community" class="text-sm hover:underline">Community</a></li>
                <li><a href="/recipes" class="text-sm hover:underline">Recipes</a></li>
                <li><a href="/about" class="text-sm hover:underline">About</a></li>
                <li><a href="/privacy" class="text-sm hover:underline">Privacy</a></li>
                <li><a href="/contact" class="text-sm hover:underline">Contact</a></li>
            </ul>
        </nav>

        {{-- Social & small subscribe --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="flex gap-3">
                <a href="https://twitter.com" target="_blank" rel="noopener noreferrer"
                    class="text-muted hover:text-primary" aria-label="Twitter">
                    <!-- Twitter -->
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path
                            d="M22 5.92c-.66.29-1.37.49-2.12.58a3.7 3.7 0 001.62-2.05 7.32 7.32 0 01-2.33.9A3.66 3.66 0 0016.15 4c-2 0-3.62 1.84-3.16 3.84A10.4 10.4 0 013 4.77a3.66 3.66 0 001.13 4.88c-.53 0-1.03-.16-1.47-.4v.04c0 1.82 1.26 3.33 2.93 3.68a3.68 3.68 0 01-1.46.06c.41 1.27 1.6 2.2 3.01 2.23A7.35 7.35 0 012 19.54 10.36 10.36 0 008.29 21c6.29 0 9.73-5.3 9.73-9.89v-.45A6.96 6.96 0 0022 5.92z" />
                    </svg>
                </a>
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                    class="text-muted hover:text-primary" aria-label="Instagram">
                    <!-- Instagram -->
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path
                            d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm5 6.5A4.5 4.5 0 1016.5 13 4.51 4.51 0 0012 8.5zm5.5-.9a1.1 1.1 0 11-1.1 1.1 1.1 1.1 0 011.1-1.1z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </a>
                <a href="mailto:hello@foodfusion.example" class="text-muted hover:text-primary" aria-label="Email">
                    <!-- Mail -->
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path
                            d="M2 6.5A2.5 2.5 0 014.5 4h15A2.5 2.5 0 0122 6.5v11A2.5 2.5 0 0119.5 20h-15A2.5 2.5 0 012 17.5zM20.5 6l-8.25 5.4a1 1 0 01-1.05 0L3.5 6h17z" />
                    </svg>
                </a>
            </div>

            <form class="flex items-center gap-2" action="#" method="POST"
                onsubmit="event.preventDefault(); /* TODO: wire up subscribe */">
                <label for="footer-email" class="sr-only">Subscribe</label>
                <input id="footer-email" type="email" placeholder="Your email"
                    class="px-3 py-2 rounded-full border border-[rgba(191,191,191,0.5)] bg-white text-sm focus:outline-none" />
                <button type="submit" class="px-3 py-2 bg-accent text-white rounded-full text-sm">Subscribe</button>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto mt-6 text-center text-sm text-muted">
        <span>&copy; {{ date('Y') }} FoodFusion. All rights reserved.</span>
    </div>
</footer>
