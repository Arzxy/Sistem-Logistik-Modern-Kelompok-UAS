<nav class="bg-white shadow-sm sticky top-0 z-50 border-b">

    <div class="max-w-7xl mx-auto px-4">

        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="/">
                <h1 class="font-bold text-blue-600 text-2xl leading-tight">SelebetExpress</h1>
                <p class="text-[10px] text-gray-400 leading-tight">Logistics Management</p>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-8 font-medium">

                <a href="/" class="hover:text-blue-600 transition">Beranda</a>

                <a href="/tentang-kami" class="hover:text-blue-600 transition">Tentang Kami</a>

                <a href="/layanan" class="hover:text-blue-600 transition">Layanan</a>

                <a href="/tracking" class="hover:text-blue-600 transition">Lacak Pengiriman</a>

                <a href="/kontak-kami" class="hover:text-blue-600 transition">Kontak Kami</a>

                <a href="/status" class="hover:text-blue-600 transition">Status Server</a>

                <a href="/login"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded-full transition">
                    Login
                </a>

            </div>

            {{-- Mobile Hamburger Button --}}
            <button id="navbar-toggle" class="md:hidden p-2 rounded-lg border border-gray-200"
                    onclick="toggleMobileMenu()" aria-label="Toggle menu">
                <svg id="icon-open" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hidden" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>

    </div>

    {{-- Mobile Menu — hidden by default --}}
    <div id="mobile-menu" class="md:hidden bg-white border-t hidden">

        <div class="flex flex-col p-4 gap-4 font-medium">

            <a href="/" class="hover:text-blue-600">Beranda</a>

            <a href="/tentang-kami" class="hover:text-blue-600">Tentang Kami</a>

            <a href="/layanan" class="hover:text-blue-600">Layanan</a>

            <a href="/tracking" class="hover:text-blue-600">Lacak Pengiriman</a>

            <a href="/kontak-kami" class="hover:text-blue-600">Kontak Kami</a>

            <a href="/status" class="hover:text-blue-600">Status Server</a>

            <a href="/login"
               class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                Login
            </a>

        </div>

    </div>

</nav>

<script>
    function toggleMobileMenu() {
        var menu      = document.getElementById('mobile-menu');
        var iconOpen  = document.getElementById('icon-open');
        var iconClose = document.getElementById('icon-close');

        var isOpen = !menu.classList.contains('hidden');

        if (isOpen) {
            menu.classList.add('hidden');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
        } else {
            menu.classList.remove('hidden');
            iconOpen.classList.add('hidden');
            iconClose.classList.remove('hidden');
        }
    }
</script>