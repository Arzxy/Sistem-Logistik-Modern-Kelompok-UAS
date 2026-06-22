@extends('layouts.dashboard')

@section('title', 'Dashboard Agen Gudang')

@section('content')

    @php
        $statusMap = [
            'pending_pickup'           => ['bg' => 'bg-yellow-100 text-yellow-700',   'label' => 'Pending Pickup'],
            'pending'                  => ['bg' => 'bg-yellow-100 text-yellow-700',   'label' => 'Pending'],
            'assigned'                 => ['bg' => 'bg-blue-100 text-blue-700',       'label' => 'Assigned'],
            'picked_up'                => ['bg' => 'bg-orange-100 text-orange-700',   'label' => 'Picked Up'],
            'at_origin_warehouse'      => ['bg' => 'bg-indigo-100 text-indigo-700',   'label' => 'Gudang Origin'],
            'in_transit'               => ['bg' => 'bg-purple-100 text-purple-700',   'label' => 'In Transit'],
            'at_destination_warehouse' => ['bg' => 'bg-cyan-100 text-cyan-700',       'label' => 'Gudang Tujuan'],
            'out_for_delivery'         => ['bg' => 'bg-green-100 text-green-700',     'label' => 'Out for Delivery'],
            'delivered'                => ['bg' => 'bg-emerald-100 text-emerald-700', 'label' => 'Delivered'],
        ];
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Agen</h1>
            <p class="text-sm text-gray-500 mt-1">
                <i class='bx bx-buildings mr-1'></i>{{ $agentWareName }}
            </p>
        </div>

        <a href="{{ route('agent.deliveries.index') }}"
            class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded-xl text-sm font-medium">
            Kelola Delivery
        </a>

    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="mt-4 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
            <i class='bx bx-check-circle text-green-500 text-xl'></i>
            <span class="text-sm text-green-700 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- STATS --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mt-5">

        <div class="bg-white rounded-2xl border p-4">
            <p class="text-sm text-gray-500">Perlu Aksi</p>
            <div class="flex items-end justify-between mt-3">
                <h1 class="text-3xl font-bold text-orange-500">{{ $stats['pending_action'] }}</h1>
                <span class="text-xs text-orange-500">Waiting</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border p-4">
            <p class="text-sm text-gray-500">Sedang Proses</p>
            <div class="flex items-end justify-between mt-3">
                <h1 class="text-3xl font-bold text-blue-600">{{ $stats['in_process'] }}</h1>
                <span class="text-xs text-blue-600">Delivery</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border p-4">
            <p class="text-sm text-gray-500">Terkirim</p>
            <div class="flex items-end justify-between mt-3">
                <h1 class="text-3xl font-bold text-green-600">{{ $stats['delivered'] }}</h1>
                <span class="text-xs text-green-600">Success</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border p-4">
            <p class="text-sm text-gray-500">Total Paket</p>
            <div class="flex items-end justify-between mt-3">
                <h1 class="text-3xl font-bold">{{ $stats['total'] }}</h1>
            </div>
        </div>

    </div>

    {{-- GRID BAWAH --}}
    <div class="grid xl:grid-cols-3 gap-5 mt-5">

        {{-- TABEL PAKET PERLU AKSI --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border overflow-hidden">

            <div class="p-4 border-b flex items-center justify-between">
                <div>
                    <h1 class="font-semibold">Paket Perlu Aksi</h1>
                    <p class="text-sm text-gray-500 mt-1">Paket yang menunggu penugasan kurir</p>
                </div>
                @if($stats['pending_action'] > 0)
                    <span class="bg-orange-100 text-orange-600 text-xs font-medium px-2.5 py-1 rounded-full">
                        {{ $stats['pending_action'] }} paket
                    </span>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[560px]">
                    <thead class="bg-gray-50">
                        <tr class="text-sm text-gray-500">
                            <th class="text-left px-5 py-3 font-medium">Resi</th>
                            <th class="text-left px-4 py-3 font-medium">Rute</th>
                            <th class="text-left px-4 py-3 font-medium">Status</th>
                            <th class="text-center px-4 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($packagesNeedAction as $pkg)
                            @php
                                $st = $statusMap[$pkg['status'] ?? ''] ?? ['bg' => 'bg-gray-100 text-gray-600', 'label' => $pkg['status'] ?? '-'];
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-sm text-blue-600">{{ $pkg['resi_number'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5 capitalize">{{ $pkg['service_type'] ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $pkg['origin_warehouse']['city'] ?? $pkg['origin_warehouse_id'] ?? '-' }}
                                    <span class="mx-1 text-gray-300">→</span>
                                    {{ $pkg['destination_warehouse']['city'] ?? $pkg['dest_warehouse_id'] ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $st['bg'] }}">
                                        {{ $st['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center">
                                        <a href="{{ route('agent.deliveries.show', $pkg['id']) }}"
                                            class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                                            <i class='bx bx-user-check'></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-gray-500">
                                    Semua paket sudah ditangani
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($stats['pending_action'] > 10)
                <div class="p-4 border-t text-center">
                    <a href="{{ route('agent.deliveries.index') }}" class="text-sm text-blue-600 hover:underline">
                        Lihat semua paket →
                    </a>
                </div>
            @endif

        </div>

        {{-- KOLOM KANAN --}}
        <div class="space-y-5">

            {{-- PAKET SEDANG BERJALAN --}}
            <div class="bg-white rounded-2xl border overflow-hidden">

                <div class="p-4 border-b flex items-center justify-between">
                    <div>
                        <h1 class="font-semibold">Sedang Berjalan</h1>
                        <p class="text-sm text-gray-500 mt-1">Kurir aktif bertugas</p>
                    </div>
                    <span class="text-xs text-blue-600 font-medium">{{ $stats['in_process'] }}</span>
                </div>

                <div class="divide-y">
                    @forelse($packagesInProcess as $pkg)
                        @php
                            $st = $statusMap[$pkg['status'] ?? ''] ?? ['bg' => 'bg-gray-100 text-gray-600', 'label' => $pkg['status'] ?? '-'];
                        @endphp
                        <div class="px-4 py-3 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $pkg['resi_number'] ?? '-' }}</p>
                                <span class="text-xs px-1.5 py-0.5 rounded-full {{ $st['bg'] }}">
                                    {{ $st['label'] }}
                                </span>
                            </div>
                            <a href="{{ route('agent.deliveries.show', $pkg['id']) }}"
                                class="w-8 h-8 rounded-xl bg-gray-100 text-gray-500 flex items-center justify-center hover:bg-blue-100 hover:text-blue-600 transition">
                                <i class='bx bx-link-external text-sm'></i>
                            </a>
                        </div>
                    @empty
                        <div class="px-4 py-8 text-center text-gray-500 text-sm">
                            Tidak ada delivery aktif
                        </div>
                    @endforelse
                </div>

            </div>

            {{-- RIWAYAT TERKIRIM --}}
            <div class="bg-white rounded-2xl border overflow-hidden">

                <div class="p-4 border-b flex items-center justify-between">
                    <div>
                        <h1 class="font-semibold">Riwayat Terkirim</h1>
                        <p class="text-sm text-gray-500 mt-1">Paket yang sudah selesai</p>
                    </div>
                    <a href="{{ route('agent.packages.history') }}"
                        class="text-xs text-blue-600 hover:underline">Lihat Semua</a>
                </div>

                <div class="divide-y">
                    @forelse($packagesHistory as $pkg)
                        <div class="px-4 py-3 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $pkg['resi_number'] ?? '-' }}</p>
                                @if(!empty($pkg['updated_at']))
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ date('d M Y', strtotime($pkg['updated_at'])) }}
                                    </p>
                                @endif
                            </div>
                            <span class="text-xs text-emerald-600 font-medium bg-emerald-50 px-2 py-0.5 rounded-full">
                                Delivered
                            </span>
                        </div>
                    @empty
                        <div class="px-4 py-8 text-center text-gray-500 text-sm">
                            Belum ada riwayat
                        </div>
                    @endforelse
                </div>

            </div>

        </div>

    </div>

@endsection
