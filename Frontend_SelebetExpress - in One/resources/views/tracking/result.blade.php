@extends('layouts.public')

@section('content')

    @php
        $statusLabels = [
            'pending'                  => ['label' => 'Menunggu Pickup',      'icon' => 'bx-time-five',      'bg' => 'bg-yellow-50 text-yellow-700 border border-yellow-100',   'gradient' => 'from-yellow-400 to-amber-500'],
            'pending_pickup'           => ['label' => 'Menunggu Pickup',      'icon' => 'bx-time-five',      'bg' => 'bg-yellow-50 text-yellow-700 border border-yellow-100',   'gradient' => 'from-yellow-400 to-amber-500'],
            'assigned'                 => ['label' => 'Kurir Ditugaskan',     'icon' => 'bx-user-check',     'bg' => 'bg-blue-50 text-blue-700 border border-blue-100',       'gradient' => 'from-blue-500 to-indigo-600'],
            'picked_up'                => ['label' => 'Paket Diambil',        'icon' => 'bx-package',        'bg' => 'bg-orange-50 text-orange-700 border border-orange-100',   'gradient' => 'from-orange-500 to-red-600'],
            'at_origin_warehouse'      => ['label' => 'Tiba Gudang Origin',   'icon' => 'bx-building',       'bg' => 'bg-indigo-50 text-indigo-700 border border-indigo-100',   'gradient' => 'from-indigo-500 to-purple-600'],
            'in_transit'               => ['label' => 'Transit Antar Gudang', 'icon' => 'bx-transfer-alt',   'bg' => 'bg-purple-50 text-purple-700 border border-purple-100',   'gradient' => 'from-purple-500 to-pink-600'],
            'at_destination_warehouse' => ['label' => 'Tiba Gudang Tujuan',   'icon' => 'bx-building-house', 'bg' => 'bg-cyan-50 text-cyan-700 border border-cyan-100',       'gradient' => 'from-cyan-500 to-teal-600'],
            'out_for_delivery'         => ['label' => 'Dalam Pengiriman',     'icon' => 'bx-cycling',        'bg' => 'bg-green-50 text-green-700 border border-green-100',     'gradient' => 'from-green-500 to-emerald-600'],
            'delivered'                => ['label' => 'Terkirim',             'icon' => 'bx-check-circle',   'bg' => 'bg-emerald-50 text-emerald-700 border border-emerald-100', 'gradient' => 'from-emerald-500 to-teal-600'],
            'failed'                   => ['label' => 'Pengiriman Gagal',     'icon' => 'bx-x-circle',       'bg' => 'bg-red-50 text-red-700 border border-red-100',         'gradient' => 'from-red-500 to-rose-600'],
            'returned'                 => ['label' => 'Dikembalikan',         'icon' => 'bx-undo',           'bg' => 'bg-gray-50 text-gray-700 border border-gray-100',       'gradient' => 'from-gray-500 to-slate-600'],
            'cancelled'                => ['label' => 'Dibatalkan',           'icon' => 'bx-block',          'bg' => 'bg-red-50 text-red-700 border border-red-100',         'gradient' => 'from-red-500 to-rose-600'],
        ];

        $lastStatus = $tracking['last_status'] ?? '';
        $lastMeta = $statusLabels[$lastStatus] ?? [
            'label' => strtoupper(str_replace('_', ' ', $lastStatus)),
            'icon' => 'bx-info-circle',
            'bg' => 'bg-gray-50 text-gray-700 border border-gray-100',
            'gradient' => 'from-gray-500 to-slate-600'
        ];
    @endphp

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 py-24">

        {{-- BLUR --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-400/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-cyan-400/20 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">

            <div class="text-center text-white">

                <span class="bg-white/15 border border-white/20 backdrop-blur px-5 py-2 rounded-full text-sm">
                    Tracking Paket
                </span>

                <h1 class="mt-7 text-4xl md:text-5xl font-bold leading-tight">

                    Detail Pengiriman Paket

                </h1>

                <p class="mt-5 text-blue-100 max-w-2xl mx-auto leading-relaxed text-lg">

                    Informasi status dan perjalanan paket Anda
                    secara realtime melalui sistem tracking
                    SelebetExpress.

                </p>

            </div>

        </div>

    </section>

    {{-- CONTENT --}}
    <section class="-mt-14 relative z-20 pb-24">

        <div class="max-w-4xl mx-auto px-4">

            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

                {{-- TOP HEADER --}}
                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-5 border-b">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                        <div>

                            <p class="text-gray-500 text-xs">
                                Nomor Resi
                            </p>

                            <div class="flex items-center gap-3 mt-1.5">

                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                                    <i class='bx bx-package text-xl'></i>
                                </div>

                                <h1 class="text-xl md:text-2xl font-bold text-gray-800">
                                    {{ $tracking['resi_number'] }}
                                </h1>

                            </div>

                        </div>

                        <div>

                            <span class="inline-flex items-center gap-2 {{ $lastMeta['bg'] }} px-4 py-2.5 rounded-xl text-xs font-semibold shadow-sm capitalize">
                                <i class='bx {{ $lastMeta['icon'] }} text-base'></i>
                                {{ $lastMeta['label'] }}
                            </span>

                        </div>

                    </div>

                </div>

                {{-- SUMMARY --}}
                <div class="grid md:grid-cols-3 gap-4 p-6 border-b bg-gray-50/50">

                    {{-- STATUS --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        
                        <div>
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white bg-gradient-to-br {{ $lastMeta['gradient'] }}">
                                <i class='bx {{ $lastMeta['icon'] }} text-2xl'></i>
                            </div>

                            <p class="text-gray-500 mt-4 text-xs">
                                Status Terakhir
                            </p>

                            <h1 class="text-lg font-bold mt-1 leading-snug capitalize text-gray-800">
                                {{ $lastMeta['label'] }}
                            </h1>
                        </div>

                    </div>

                    {{-- LOCATION --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition">

                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                            <i class='bx bx-map text-2xl'></i>
                        </div>

                        <p class="text-gray-500 mt-4 text-xs">
                            Lokasi Terakhir
                        </p>

                        <h1 class="text-base font-bold mt-1 text-gray-800 leading-snug">
                            {{ $tracking['last_location'] ?? '-' }}
                        </h1>

                    </div>

                    {{-- UPDATED --}}
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition">

                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">
                            <i class='bx bx-time-five text-2xl'></i>
                        </div>

                        <p class="text-gray-500 mt-4 text-xs">
                            Update Terakhir
                        </p>

                        <h1 class="text-base font-bold mt-1 text-gray-800 leading-snug">
                            {{ \Carbon\Carbon::parse($tracking['last_updated'])->format('d M Y H:i') }}
                        </h1>

                    </div>

                </div>

                {{-- TIMELINE --}}
                <div class="p-6 md:p-8">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div>

                            <h1 class="text-2xl font-bold text-gray-800">
                                Riwayat Pengiriman
                            </h1>

                            <p class="text-sm text-gray-500 mt-1">
                                Perjalanan paket Anda secara realtime.
                            </p>

                        </div>

                        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-xl text-xs font-medium flex items-center gap-2 w-fit">
                            <i class='bx bx-git-branch'></i>
                            {{ count($tracking['logs']) }} Riwayat Tracking
                        </div>

                    </div>

                    {{-- TIMELINE CONTENT --}}
                    <div class="mt-8">

                        @foreach($tracking['logs'] as $index => $log)

                            @php
                                $logStatus = $log['status'] ?? '';
                                $logMeta = $statusLabels[$logStatus] ?? [
                                    'label' => strtoupper(str_replace('_', ' ', $logStatus)),
                                    'icon' => 'bx-info-circle',
                                    'bg' => 'bg-gray-100 text-gray-700',
                                    'gradient' => 'from-gray-500 to-slate-600'
                                ];
                            @endphp

                            <div class="relative pl-12 pb-8">

                                {{-- LINE --}}
                                @if(!$loop->last)

                                    <div class="absolute left-[19px] top-10 w-[2px] h-full bg-gradient-to-b from-blue-200 to-gray-200 rounded-full"></div>

                                @endif

                                {{-- DOT --}}
                                <div class="absolute left-0 top-1">

                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $logMeta['gradient'] }} flex items-center justify-center text-white shadow-md">
                                        <i class='bx {{ $logMeta['icon'] }} text-lg'></i>
                                    </div>

                                </div>

                                {{-- CONTENT --}}
                                <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl border border-gray-100 p-4 md:p-5 shadow-sm hover:shadow-md transition">

                                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

                                        <div>

                                            <div class="flex items-center gap-3">

                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $logMeta['bg'] }}">
                                                    <i class='bx {{ $logMeta['icon'] }} text-base'></i>
                                                </div>

                                                <div>

                                                    <h1 class="font-bold text-lg text-gray-800 capitalize">
                                                        {{ $logMeta['label'] }}
                                                    </h1>

                                                    <p class="text-gray-500 mt-1 text-xs flex items-center gap-2">
                                                        <i class='bx bx-map-pin'></i>
                                                        {{ $log['location'] ?? 'Lokasi tidak tersedia' }}
                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="bg-white border rounded-xl px-3 py-1.5 text-xs text-gray-500 flex items-center gap-1.5 w-fit">
                                            <i class='bx bx-time-five'></i>
                                            {{ $log['formatted_time'] }}
                                        </div>

                                    </div>

                                    @if(!empty($log['notes']))

                                        <div class="mt-4 bg-white border border-gray-100 rounded-xl p-4 flex gap-3">

                                            <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0">
                                                <i class='bx bx-note'></i>
                                            </div>

                                            <div>

                                                <p class="text-xs font-semibold text-gray-700">
                                                    Catatan Kurir
                                                </p>

                                                <p class="text-xs text-gray-600 leading-relaxed mt-1">
                                                    {{ $log['notes'] }}
                                                </p>

                                            </div>

                                        </div>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection