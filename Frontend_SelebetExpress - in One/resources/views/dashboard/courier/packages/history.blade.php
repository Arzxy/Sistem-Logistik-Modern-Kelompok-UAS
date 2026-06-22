@extends('layouts.courier')

@section('title', 'Riwayat Pengiriman')

@section('content')

    {{-- HEADER --}}
    <div class="mb-5">
        <h1 class="text-xl font-bold text-gray-800">Riwayat Pengiriman</h1>
        <p class="text-sm text-gray-500 mt-1">Semua paket yang sudah berhasil dikirim</p>
    </div>

    {{-- SUMMARY BADGE --}}
    @if(count($deliveries) > 0)
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 mb-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class='bx bx-check-double text-emerald-500 text-lg'></i>
                <span class="text-sm text-emerald-700 font-medium">Total Selesai</span>
            </div>
            <span class="bg-emerald-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                {{ count($deliveries) }}
            </span>
        </div>
    @endif

    {{-- LIST --}}
    <div class="space-y-3">

        @forelse($deliveries as $delivery)

            @php
                $package = $delivery['package'] ?? [];

                $typeConfig = match ($delivery['delivery_type'] ?? 'pickup') {
                    'pickup'          => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'icon' => 'bx-package',       'title' => 'Pickup'],
                    'inter_warehouse' => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'icon' => 'bx-transfer-alt',  'title' => 'Transit Gudang'],
                    'last_mile'       => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'icon' => 'bx-home',          'title' => 'Last Mile'],
                    default           => ['bg' => 'bg-gray-100',   'text' => 'text-gray-700',   'icon' => 'bx-package',       'title' => 'Delivery'],
                };
            @endphp

            <a href="/courier/packages/{{ $delivery['id'] }}"
                class="block bg-white border rounded-3xl p-4 active:scale-[0.98] transition">

                <div class="flex items-center justify-between gap-3">

                    {{-- Icon + Info --}}
                    <div class="flex items-center gap-3">

                        <div class="w-11 h-11 rounded-2xl {{ $typeConfig['bg'] }} {{ $typeConfig['text'] }} flex items-center justify-center flex-shrink-0">
                            <i class='bx {{ $typeConfig["icon"] }} text-xl'></i>
                        </div>

                        <div>
                            <p class="font-bold text-sm text-gray-800">
                                {{ $package['resi_number'] ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $typeConfig['title'] }}
                                @if(!empty($delivery['updated_at']))
                                    · {{ \Carbon\Carbon::parse($delivery['updated_at'])->format('d M Y') }}
                                @endif
                            </p>
                        </div>

                    </div>

                    {{-- Badge Delivered --}}
                    <span class="flex-shrink-0 text-xs px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 font-medium">
                        ✓ Delivered
                    </span>

                </div>

                {{-- Route --}}
                <div class="flex items-center gap-2 mt-3 px-1">
                    <span class="text-xs text-gray-500 truncate">
                        {{ $package['origin_warehouse']['city'] ?? '-' }}
                    </span>
                    <i class='bx bx-right-arrow-alt text-gray-400 text-sm flex-shrink-0'></i>
                    <span class="text-xs text-gray-500 truncate">
                        {{ $package['destination_warehouse']['city'] ?? '-' }}
                    </span>
                </div>

            </a>

        @empty

            <div class="bg-white border rounded-3xl p-10 text-center">

                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto">
                    <i class='bx bx-history text-5xl text-gray-300'></i>
                </div>

                <h1 class="font-semibold text-gray-700 mt-5">Belum ada riwayat</h1>

                <p class="text-sm text-gray-500 mt-2">
                    Riwayat pengiriman yang berhasil akan muncul di sini
                </p>

                <a href="/courier/packages"
                    class="mt-5 inline-block bg-blue-600 text-white text-sm px-5 py-2.5 rounded-2xl font-medium">
                    Lihat Paket Aktif
                </a>

            </div>

        @endforelse

    </div>

@endsection
