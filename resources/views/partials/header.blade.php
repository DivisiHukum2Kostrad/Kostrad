<nav class="bg-gradient-to-r from-green-900 via-green-800 to-green-900 shadow-lg fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-18 py-3">
            <div class="flex items-center space-x-4">
                <a href="{{ route('landing') }}" class="flex items-center space-x-4 focus:outline-none focus:ring-2 focus:ring-green-300" aria-label="Kembali ke Beranda">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
                        <svg class="w-7 h-7 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-lg">DivisiHukum2Kostrad</h1>
                        <p class="text-green-200 text-xs">SIPERKARA DIV-2</p>
                    </div>
                </a>
            </div>

            <!-- Desktop nav -->
            <div class="hidden md:flex space-x-8 items-center">
                <a href="{{ route('landing') }}" class="text-white hover:text-green-300 font-medium transition duration-300">Beranda</a>
                <a href="{{ route('perkara.public') }}" class="text-white hover:text-green-300 font-medium transition duration-300 {{ request()->routeIs('perkara.public') ? 'text-green-300 font-bold border-b-2 border-green-300' : '' }}">Data Perkara Publik</a>
                <a href="{{ request()->routeIs('landing') ? '#kontak' : route('landing') . '#kontak' }}" class="text-white hover:text-green-300 font-medium transition duration-300">Kontak</a>

                <div class="ml-4 flex items-center gap-3">
                    <a href="{{ request()->routeIs('landing') ? '#tentang' : route('landing') . '#tentang' }}" class="hidden lg:inline-block border-2 border-white text-white px-4 py-2 rounded-lg font-semibold hover:bg-white hover:text-green-900 transition">Pelajari Lebih Lanjut</a>
                </div>
            </div>

            <!-- Mobile menu button -->
            <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none" aria-controls="mobile-menu" aria-expanded="false">
                <!-- Hamburger -->
                <svg id="icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <!-- Close icon (hidden by default) -->
                <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile menu (hidden by default) -->
    <div id="mobile-menu" class="md:hidden hidden bg-gradient-to-r from-green-900 via-green-800 to-green-900 px-4 pt-2 pb-4">
        <div class="space-y-2">
            <a href="{{ route('landing') }}" class="block text-white hover:text-green-300 font-medium py-2">Beranda</a>
            <a href="{{ route('perkara.public') }}" class="block text-white hover:text-green-300 font-medium py-2">Data Perkara Publik</a>
                <a href="{{ request()->routeIs('landing') ? '#kontak' : route('landing') . '#kontak' }}" class="block text-white hover:text-green-300 font-medium py-2">Kontak</a>

            <div class="flex flex-col sm:flex-row gap-3 mt-2">
                <a href="{{ request()->routeIs('landing') ? '#tentang' : route('landing') . '#tentang' }}" class="border-2 border-white text-white px-4 py-2 rounded-lg font-semibold text-center">Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const mobileButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('icon-open');
            const iconClose = document.getElementById('icon-close');

            if (mobileButton && mobileMenu) {
                mobileButton.addEventListener('click', function() {
                    const expanded = mobileButton.getAttribute('aria-expanded') === 'true';
                    mobileButton.setAttribute('aria-expanded', String(!expanded));
                    mobileMenu.classList.toggle('hidden');
                    if (iconOpen && iconClose) {
                        iconOpen.classList.toggle('hidden');
                        iconClose.classList.toggle('hidden');
                    }
                });

                document.querySelectorAll('#mobile-menu a').forEach(el => {
                    el.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                        if (iconOpen && iconClose) {
                            iconOpen.classList.remove('hidden');
                            iconClose.classList.add('hidden');
                        }
                        mobileButton.setAttribute('aria-expanded', 'false');
                    });
                });
            }
        })();
    </script>
</nav>
