@extends('layouts.dashboard')

@section('title', 'Dashboard Kasir')

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
            'cancelled'                => ['bg' => 'bg-red-100 text-red-700',         'label' => 'Cancelled'],
            'returned'                 => ['bg' => 'bg-gray-100 text-gray-600',       'label' => 'Returned'],
            'failed'                   => ['bg' => 'bg-red-100 text-red-700',         'label' => 'Failed'],
        ];
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">
                Monitor aktivitas pengiriman paket SelebetExpress.
            </p>
        </div>

        <a href="/admin/packages/create"
            class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded-xl text-sm font-medium">
            + Paket Baru
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
            <p class="text-sm text-gray-500">Total Paket</p>
            <div class="flex items-end justify-between mt-3">
                <h1 class="text-3xl font-bold">{{ $stats['total'] }}</h1>
            </div>
        </div>

        <div class="bg-white rounded-2xl border p-4">
            <p class="text-sm text-gray-500">Pending</p>
            <div class="flex items-end justify-between mt-3">
                <h1 class="text-3xl font-bold text-orange-500">{{ $stats['pending'] }}</h1>
                <span class="text-xs text-orange-500">Waiting</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border p-4">
            <p class="text-sm text-gray-500">Dalam Proses</p>
            <div class="flex items-end justify-between mt-3">
                <h1 class="text-3xl font-bold text-blue-600">{{ $stats['in_process'] }}</h1>
                <span class="text-xs text-blue-600">Delivery</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border p-4">
            <p class="text-sm text-gray-500">Delivered</p>
            <div class="flex items-end justify-between mt-3">
                <h1 class="text-3xl font-bold text-green-600">{{ $stats['delivered'] }}</h1>
                <span class="text-xs text-green-600">Success</span>
            </div>
        </div>

    </div>

    {{-- TABLE PAKET TERBARU --}}
    <div class="bg-white rounded-2xl border mt-5 overflow-hidden">

        <div class="p-4 border-b flex items-center justify-between">
            <div>
                <h1 class="font-semibold">Paket Terbaru</h1>
                <p class="text-sm text-gray-500 mt-1">Data pengiriman terbaru</p>
            </div>
            <a href="/admin/packages" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">

                <thead class="bg-gray-50">
                    <tr class="text-sm text-gray-500">
                        <th class="text-left px-5 py-4 font-medium">Resi</th>
                        <th class="text-left px-5 py-4 font-medium">Pengirim</th>
                        <th class="text-left px-5 py-4 font-medium">Berat</th>
                        <th class="text-left px-5 py-4 font-medium">Harga</th>
                        <th class="text-left px-5 py-4 font-medium">Status</th>
                        <th class="text-left px-5 py-4 font-medium">Tanggal</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($latestPackages as $pkg)
                        @php
                            $st = $statusMap[$pkg['status'] ?? ''] ?? ['bg' => 'bg-gray-100 text-gray-600', 'label' => $pkg['status'] ?? '-'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4">
                                <a href="/admin/packages/{{ $pkg['id'] }}"
                                    class="font-medium text-sm text-blue-600 hover:underline">
                                    {{ $pkg['resi_number'] }}
                                </a>
                                <p class="text-xs text-gray-400 mt-0.5">ID: {{ $pkg['id'] }}</p>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600">
                                {{ $pkg['sender']['name'] ?? $pkg['sender_id'] ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600">
                                {{ $pkg['weight_kg'] ?? 0 }} kg
                            </td>
                            <td class="px-5 py-4 text-sm font-medium text-gray-800">
                                Rp {{ number_format($pkg['total_price'] ?? 0) }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $st['bg'] }}">
                                    {{ $st['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($pkg['created_at'])->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500">
                                Belum ada data paket
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

@endsection
