<nav class="h-16 bg-white border-b px-5 flex items-center justify-between">

    {{-- LEFT --}}
    <div class="flex items-center gap-4">

        {{-- MOBILE MENU --}}
        <button id="openSidebar" class="lg:hidden text-2xl text-gray-700">

            <i class='bx bx-menu'></i>

        </button>

    </div>

    {{-- RIGHT --}}
    <div class="flex items-center gap-3">

        {{-- NOTIFICATION --}}
        <button
            class="relative w-11 h-11 rounded-xl border bg-white hover:bg-gray-50 transition flex items-center justify-center">

            <i class='bx bx-bell text-xl text-gray-700'></i>

            {{-- DOT --}}
            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full">
            </span>

        </button>

    </div>

</nav>