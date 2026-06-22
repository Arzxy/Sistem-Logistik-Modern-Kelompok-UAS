@php
    $role = session('user.role', 'Error');
    $userName = session('user.name', 'Error');

    // Initial huruf avatar
    $initial = strtoupper(substr($userName, 0, 1));

    // Warna avatar per role
    $avatarColor = match ($role) {
        'admin' => 'bg-blue-600',
        'kasir' => 'bg-emerald-600',
        'agen' => 'bg-indigo-600',
        'kurir' => 'bg-orange-500',
        default => 'bg-gray-500',
    };

    // Badge label role
    $roleLabel = match ($role) {
        'admin' => 'Administrator',
        'kasir' => 'Kasir',
        'agen' => 'Agen Gudang',
        'kurir' => 'Kurir',
        default => ucfirst($role),
    };
@endphp

{{-- OVERLAY --}}
<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden">
</div>

{{-- SIDEBAR --}}
<aside id="sidebar"
    class="fixed top-0 left-0 w-64 h-screen bg-white border-r z-50 transform -translate-x-full lg:translate-x-0 transition duration-300 flex flex-col">

    {{-- LOGO --}}
    <div class="h-16 flex items-center justify-between px-5 border-b">

        <div class="flex items-center gap-3">

            <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                SE
            </div>

            <div>
                <h1 class="font-bold text-gray-800 text-sm leading-tight">SelebetExpress</h1>
                <p class="text-[10px] text-gray-400 leading-tight">Logistics Management</p>
            </div>

        </div>

        {{-- CLOSE MOBILE --}}
        <button id="closeSidebar" class="lg:hidden text-2xl text-gray-400 hover:text-gray-700">
            <i class='bx bx-x'></i>
        </button>

    </div>

    {{-- MENU --}}
    <div class="flex-1 p-3 overflow-y-auto space-y-1">

        {{-- ===================== ADMIN ===================== --}}
        @if($role === 'admin')

            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 py-2">
                Main Menu
            </p>

            <a href="/admin/dashboard"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->is('admin/dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 transition' }}">
                <i class='bx bx-grid-alt text-lg'></i>
                Dashboard
            </a>

            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 pt-4 pb-2">
                Manajemen
            </p>

            <a href="/admin/packages"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->is('admin/packages') || request()->is('admin/packages/*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 transition' }}">
                <i class='bx bx-package text-lg'></i>
                Manajemen Paket
            </a>

            <a href="/admin/users"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 transition' }}">
                <i class='bx bx-user text-lg'></i>
                Kelola User
            </a>

            <a href="/admin/warehouses"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->is('admin/warehouses') || request()->is('admin/warehouses/*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 transition' }}">
                <i class='bx bx-buildings text-lg'></i>
                Kelola Gudang
            </a>

            <a href="/admin/tariffs"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->is('admin/tariffs') || request()->is('admin/tariffs/*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 transition' }}">
                <i class='bx bx-money text-lg'></i>
                Kelola Tarif
            </a>

            <div class="border-t my-3"></div>

            <a href="/tracking"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 hover:bg-gray-100 transition">
                <i class='bx bx-map text-lg'></i>
                Lacak Paket
            </a>

            {{-- ===================== KASIR ===================== --}}
        @elseif($role === 'kasir')

            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 py-2">
                Main Menu
            </p>

            <a href="/kasir/dashboard"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->is('kasir/dashboard') ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 transition' }}">
                <i class='bx bx-grid-alt text-lg'></i>
                Dashboard
            </a>

            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 pt-4 pb-2">
                Transaksi
            </p>

            <a href="/admin/packages"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->is('admin/packages') ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 transition' }}">
                <i class='bx bx-package text-lg'></i>
                Manajemen Paket
            </a>

            <a href="/admin/packages/create"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->is('admin/packages/create') ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 transition' }}">
                <i class='bx bx-plus-circle text-lg'></i>
                Input Paket Baru
            </a>

            <a href="/kasir/packages/history"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->is('kasir/packages/history') ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 transition' }}">
                <i class='bx bx-history text-lg'></i>
                Riwayat Paket
            </a>

            <div class="border-t my-3"></div>

            <a href="/tracking"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 hover:bg-gray-100 transition">
                <i class='bx bx-map text-lg'></i>
                Lacak Paket
            </a>

            {{-- ===================== AGEN ===================== --}}
        @elseif($role === 'agen')

            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 py-2">
                Main Menu
            </p>

            <a href="/agent/dashboard"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->is('agent/dashboard') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 transition' }}">
                <i class='bx bx-grid-alt text-lg'></i>
                Dashboard
            </a>

            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 pt-4 pb-2">
                Operasional
            </p>

            <a href="/agent/deliveries"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->is('agent/deliveries') || request()->is('agent/deliveries/*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 transition' }}">
                <i class='bx bx-car text-lg'></i>
                Manajemen Delivery
            </a>

            <a href="/agent/packages/history"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->is('agent/packages/history') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-gray-600 hover:bg-gray-100 transition' }}">
                <i class='bx bx-history text-lg'></i>
                Riwayat Paket
            </a>

            <div class="border-t my-3"></div>

            <a href="/tracking"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 hover:bg-gray-100 transition">
                <i class='bx bx-map text-lg'></i>
                Lacak Paket
            </a>

            {{-- ===================== FALLBACK (semua menu) ===================== --}}
        @else

            <a href="/admin/dashboard"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 hover:bg-gray-100 transition">
                <i class='bx bx-grid-alt text-lg'></i>
                Dashboard
            </a>

        @endif

    </div>

    {{-- USER INFO --}}
    <div class="border-t p-4">

        <div class="flex items-center gap-3">

            <div
                class="w-10 h-10 rounded-full {{ $avatarColor }} flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ $initial }}
            </div>

            <div class="flex-1 min-w-0">

                <h1 class="font-semibold text-sm text-gray-800 truncate">
                    {{ $userName }}
                </h1>

                <span class="text-xs text-gray-400">{{ $roleLabel }}</span>

            </div>

        </div>

        {{-- LOGOUT --}}
        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button
                class="w-full flex items-center justify-center gap-2 text-sm text-red-500 hover:text-red-600 hover:bg-red-50 transition py-2 rounded-xl">
                <i class='bx bx-log-out'></i>
                Keluar dari Akun
            </button>
        </form>

    </div>

</aside>