@extends('layouts.courier')

@section('title', 'Paket Saya')

@section('content')

    {{-- HEADER --}}
    <div class="mb-5">

        <h1 class="text-xl font-bold text-gray-800">
            Delivery Saya
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Daftar tugas pengiriman courier
        </p>

    </div>

    {{-- LIST --}}
    <div class="space-y-4">

        @forelse($deliveries as $delivery)

            @php

                $package =
                    $delivery['package']
                    ?? [];

                $typeConfig = match ($delivery['delivery_type']) {

                    'pickup' => [
                        'bg' => 'bg-orange-100',
                        'text' => 'text-orange-700',
                        'icon' => 'bx-package',
                        'title' => 'Pickup Paket',
                        'desc' => 'Ambil paket dari kasir'
                    ],

                    'inter_warehouse' => [
                        'bg' => 'bg-blue-100',
                        'text' => 'text-blue-700',
                        'icon' => 'bx-transfer-alt',
                        'title' => 'Transit Antar Gudang',
                        'desc' => 'Kirim paket ke gudang tujuan'
                    ],

                    'last_mile' => [
                        'bg' => 'bg-green-100',
                        'text' => 'text-green-700',
                        'icon' => 'bx-home',
                        'title' => 'Antar ke Pelanggan',
                        'desc' => 'Kirim paket ke alamat penerima'
                    ],

                    default => [
                        'bg' => 'bg-gray-100',
                        'text' => 'text-gray-700',
                        'icon' => 'bx-package',
                        'title' => 'Delivery',
                        'desc' => '-'
                    ]

                };

            @endphp

            <a href="/courier/packages/{{ $delivery['id'] }}"
                class="block bg-white border rounded-3xl p-5 active:scale-[0.98] transition">

                {{-- TOP --}}
                <div class="flex items-start justify-between gap-4">

                    <div class="flex items-start gap-4">

                        <div
                            class="w-14 h-14 rounded-2xl {{ $typeConfig['bg'] }} {{ $typeConfig['text'] }} flex items-center justify-center">

                            <i class='bx {{ $typeConfig['icon'] }} text-2xl'></i>

                        </div>

                        <div>

                            <h1 class="font-bold text-gray-800">

                                {{ $typeConfig['title'] }}

                            </h1>

                            <p class="text-xs text-gray-500 mt-1">

                                {{ $typeConfig['desc'] }}

                            </p>

                        </div>

                    </div>

                    <span class="text-xs px-3 py-1 rounded-full bg-blue-100 text-blue-700 capitalize">

                        {{ str_replace('_', ' ', $delivery['status']) }}

                    </span>

                </div>

                {{-- RESI --}}
                <div class="mt-5">

                    <p class="text-xs text-gray-500">
                        Nomor Resi
                    </p>

                    <h1 class="font-bold text-blue-600 mt-1">

                        {{ $package['resi_number'] ?? '-' }}

                    </h1>

                </div>

                {{-- ROUTE --}}
                <div class="grid grid-cols-2 gap-3 mt-4">

                    <div class="bg-gray-50 rounded-2xl p-3">

                        <p class="text-[11px] text-gray-500">
                            Asal
                        </p>

                        <h1 class="font-semibold text-sm mt-1">

                            {{ $package['origin_warehouse']['city'] ?? '-' }}

                        </h1>

                    </div>

                    <div class="bg-gray-50 rounded-2xl p-3">

                        <p class="text-[11px] text-gray-500">
                            Tujuan
                        </p>

                        <h1 class="font-semibold text-sm mt-1">

                            {{ $package['destination_warehouse']['city'] ?? '-' }}

                        </h1>

                    </div>

                </div>

                {{-- BOTTOM --}}
                <div class="flex items-center justify-between mt-4">

                    <div>

                        <p class="text-xs text-gray-500">
                            Berat
                        </p>

                        <h1 class="font-semibold text-sm mt-1">

                            {{ $package['weight_kg'] ?? 0 }} kg

                        </h1>

                    </div>

                    <div class="text-right">

                        <p class="text-xs text-gray-500">
                            Total
                        </p>

                        <h1 class="font-bold text-blue-600 mt-1">

                            Rp {{ number_format($package['total_price'] ?? 0) }}

                        </h1>

                    </div>

                </div>

            </a>

        @empty

            <div class="bg-white border rounded-3xl p-10 text-center">

                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto">

                    <i class='bx bx-package text-5xl text-gray-300'></i>

                </div>

                <h1 class="font-semibold text-gray-700 mt-5">

                    Tidak ada delivery

                </h1>

                <p class="text-sm text-gray-500 mt-2">

                    Belum ada tugas pengiriman

                </p>

            </div>

        @endforelse

    </div>

@endsection