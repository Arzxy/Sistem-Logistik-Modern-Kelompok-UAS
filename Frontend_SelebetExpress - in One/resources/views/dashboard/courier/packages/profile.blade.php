@extends('layouts.courier')

@section('title', 'Profil Saya')

@section('content')

    @php
        $statusConfig = [
            'available' => [
                'label'     => 'Available',
                'desc'      => 'Siap menerima tugas baru',
                'bg'        => 'bg-emerald-500',
                'badgeBg'   => 'bg-emerald-100',
                'badgeText' => 'text-emerald-700',
                'icon'      => 'bx-check-circle',
            ],
            'on_duty' => [
                'label'     => 'On Duty',
                'desc'      => 'Sedang dalam perjalanan',
                'bg'        => 'bg-blue-600',
                'badgeBg'   => 'bg-blue-100',
                'badgeText' => 'text-blue-700',
                'icon'      => 'bx-cycling',
            ],
            'offline' => [
                'label'     => 'Offline',
                'desc'      => 'Tidak aktif',
                'bg'        => 'bg-gray-400',
                'badgeBg'   => 'bg-gray-100',
                'badgeText' => 'text-gray-600',
                'icon'      => 'bx-power-off',
            ],
        ];

        $currentStatus = $courier['status'] ?? 'offline';
        $sc = $statusConfig[$currentStatus] ?? $statusConfig['offline'];
    @endphp

    {{-- HERO PROFILE --}}
    <div class="bg-gradient-to-br from-blue-600 to-blue-500 rounded-3xl p-6 text-white">

        <div class="flex items-center gap-4">

            <div class="w-20 h-20 rounded-2xl bg-white/20 flex items-center justify-center">
                <i class='bx bx-user text-4xl'></i>
            </div>

            <div class="flex-1">

                <h1 class="text-xl font-bold">{{ $courier['name'] ?? $user['name'] ?? '-' }}</h1>

                <p class="text-sm opacity-80 mt-0.5">{{ $courier['phone'] ?? $user['phone'] ?? '-' }}</p>

                <div class="mt-2 inline-flex items-center gap-1.5 bg-white/20 rounded-full px-3 py-1">
                    <i class='bx {{ $sc['icon'] }} text-sm'></i>
                    <span class="text-xs font-semibold">{{ $sc['label'] }}</span>
                </div>

            </div>

        </div>

    </div>

    {{-- INFO KURIR --}}
    <div class="bg-white border rounded-3xl p-5 mt-4 space-y-4">

        <h2 class="font-semibold text-gray-800">Informasi Kurir</h2>

        <div class="grid grid-cols-2 gap-4">

            <div>
                <p class="text-xs text-gray-400">Jenis Kendaraan</p>
                <p class="font-semibold text-sm mt-1 capitalize">{{ $courier['vehicle_type'] ?? '-' }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-400">Plat Nomor</p>
                <p class="font-semibold text-sm mt-1">{{ $courier['vehicle_plate'] ?? '-' }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-400">Total Pengiriman</p>
                <p class="font-bold text-blue-600 text-lg mt-1">{{ $courier['total_deliveries'] ?? 0 }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-400">Delivery Aktif</p>
                <p class="font-bold text-orange-500 text-lg mt-1">{{ $courier['active_deliveries_count'] ?? 0 }}</p>
            </div>

        </div>

    </div>

    {{-- TOGGLE STATUS --}}
    <div class="bg-white border rounded-3xl p-5 mt-4">

        <h2 class="font-semibold text-gray-800 mb-1">Status Kerja</h2>
        <p class="text-xs text-gray-500 mb-4">{{ $sc['desc'] }}</p>

        {{-- Status Badge --}}
        <div class="flex items-center gap-3 mb-5">
            <div class="w-3 h-3 rounded-full {{ $sc['bg'] }}
                @if($currentStatus === 'on_duty') animate-pulse @endif">
            </div>
            <span class="text-sm font-semibold {{ $sc['badgeText'] }}">{{ $sc['label'] }}</span>
        </div>

        {{-- Tombol toggle --}}
        <div class="grid grid-cols-2 gap-3">

            {{-- Tombol AVAILABLE --}}
            <form action="{{ route('courier.status') }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="available">
                <button type="submit"
                    class="w-full py-3 rounded-2xl text-sm font-semibold transition flex items-center justify-center gap-2
                        {{ $currentStatus === 'available'
                            ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-100'
                            : 'bg-gray-100 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600' }}">
                    <i class='bx bx-check-circle text-lg'></i>
                    Available
                </button>
            </form>

            {{-- Tombol ON DUTY --}}
            <form action="{{ route('courier.status') }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="on_duty">
                <button type="submit"
                    class="w-full py-3 rounded-2xl text-sm font-semibold transition flex items-center justify-center gap-2
                        {{ $currentStatus === 'on_duty'
                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-100'
                            : 'bg-gray-100 text-gray-500 hover:bg-blue-50 hover:text-blue-600' }}">
                    <i class='bx bx-cycling text-lg'></i>
                    On Duty
                </button>
            </form>

        </div>

        <p class="text-[11px] text-gray-400 mt-3 text-center">
            Status <strong>Available</strong> = gudang bisa berikan tugas baru.<br>
            Status <strong>On Duty</strong> = kamu sedang bertugas di lapangan.
        </p>

    </div>

    {{-- QUICK LINKS --}}
    <div class="grid grid-cols-2 gap-3 mt-4">

        <a href="/courier/packages"
            class="bg-white border rounded-2xl p-4 flex items-center gap-3 hover:bg-blue-50 transition">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                <i class='bx bx-package text-xl'></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Paket</p>
                <p class="text-sm font-semibold text-gray-800">Aktif</p>
            </div>
        </a>

        <a href="/courier/history"
            class="bg-white border rounded-2xl p-4 flex items-center gap-3 hover:bg-green-50 transition">
            <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                <i class='bx bx-history text-xl'></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Riwayat</p>
                <p class="text-sm font-semibold text-gray-800">Selesai</p>
            </div>
        </a>

    </div>

    {{-- LOGOUT --}}
    <div class="mt-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full bg-white border border-red-200 text-red-500 hover:bg-red-50 transition py-3 rounded-2xl text-sm font-semibold flex items-center justify-center gap-2">
                <i class='bx bx-log-out text-lg'></i>
                Keluar dari Akun
            </button>
        </form>
    </div>

@endsection
