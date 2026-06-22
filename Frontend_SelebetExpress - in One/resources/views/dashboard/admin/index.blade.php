@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Dashboard
            </h1>

            <p class="text-sm text-gray-500 mt-1">

                Monitor aktivitas pengiriman
                SelebetExpress secara realtime.

            </p>

        </div>

        {{-- ACTION --}}

        {{-- ACTION --}}
        <div class="flex items-center gap-3">

            <a href="/admin/packages/create"
                class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded-xl text-sm font-medium">

                + Paket Baru

            </a>

        </div>

    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mt-5">

        {{-- CARD --}}
        <div class="bg-white rounded-2xl border p-4">

            <p class="text-sm text-gray-500">
                Total Paket
            </p>

            <div class="flex items-end justify-between mt-3">

                <h1 class="text-3xl font-bold">

                    {{ $stats['total_packages'] }}

                </h1>

            </div>

        </div>

        {{-- CARD --}}
        <div class="bg-white rounded-2xl border p-4">

            <p class="text-sm text-gray-500">
                Pending
            </p>

            <div class="flex items-end justify-between mt-3">

                <h1 class="text-3xl font-bold text-orange-500">

                    {{ $stats['pending_packages'] }}

                </h1>

                <span class="text-xs text-orange-500">
                    Waiting
                </span>

            </div>

        </div>

        {{-- CARD --}}
        <div class="bg-white rounded-2xl border p-4">

            <p class="text-sm text-gray-500">
                In Transit
            </p>

            <div class="flex items-end justify-between mt-3">

                <h1 class="text-3xl font-bold text-blue-600">

                    {{ $stats['transit_packages'] }}

                </h1>

                <span class="text-xs text-blue-600">
                    Delivery
                </span>

            </div>

        </div>

        {{-- CARD --}}
        <div class="bg-white rounded-2xl border p-4">

            <p class="text-sm text-gray-500">
                Delivered
            </p>

            <div class="flex items-end justify-between mt-3">

                <h1 class="text-3xl font-bold text-green-600">

                    {{ $stats['delivered_packages'] }}

                </h1>

                <span class="text-xs text-green-600">
                    Success
                </span>

            </div>

        </div>

    </div>

    {{-- CHARTS --}}
    <div class="grid xl:grid-cols-3 gap-4 mt-5">

        {{-- CHART --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border p-5">

            <div class="flex items-center justify-between mb-5">

                <div>

                    <h1 class="font-semibold">
                        Statistik Pengiriman
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Aktivitas paket terbaru
                    </p>

                </div>

            </div>
            <div class="h-[300px] w-full">
                <canvas id="shipmentChart"></canvas>
            </div>
        </div>

        {{-- STATUS --}}
        <div class="bg-white rounded-2xl border p-5">

            <h1 class="font-semibold">
                Status Armada
            </h1>

            <div class="space-y-5 mt-6">

                {{-- ITEM --}}
                <div class="flex items-center justify-between">

                    <div>

                        <p class="font-medium">
                            Kurir Tersedia
                        </p>

                        <p class="text-sm text-gray-500">
                            Ready delivery
                        </p>

                    </div>

                    <h1 class="text-2xl font-bold text-green-600">

                        {{ $stats['available_couriers'] }}

                    </h1>

                </div>

                {{-- ITEM --}}
                <div class="flex items-center justify-between">

                    <div>

                        <p class="font-medium">
                            Sedang Bertugas
                        </p>

                        <p class="text-sm text-gray-500">
                            Active courier
                        </p>

                    </div>

                    <h1 class="text-2xl font-bold text-blue-600">

                        {{ $stats['active_couriers'] }}

                    </h1>

                </div>

            </div>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border mt-5 overflow-hidden">

        {{-- HEADER --}}
        <div class="p-4 border-b flex items-center justify-between">

            <div>

                <h1 class="font-semibold">
                    Paket Terbaru
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Data pengiriman terbaru
                </p>

            </div>

            <a href="/admin/packages" class="text-sm text-blue-600 hover:underline">

                Lihat Semua

            </a>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50">

                    <tr class="text-sm text-gray-500">

                        <th class="text-left px-5 py-4 font-medium">
                            Resi
                        </th>

                        <th class="text-left px-5 py-4 font-medium">
                            Tujuan
                        </th>

                        <th class="text-left px-5 py-4 font-medium">
                            Berat
                        </th>

                        <th class="text-left px-5 py-4 font-medium">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y">

                    @forelse($packages as $package)

                        <tr class="hover:bg-gray-50 transition">

                            {{-- RESI --}}
                            <td class="px-5 py-4">

                                <div>

                                    <a href="/admin/packages/{{ $package['id'] }}"
                                        class="font-medium text-sm text-blue-600 hover:underline">

                                        {{ $package['resi_number'] }}

                                    </a>

                                    <p class="text-xs text-gray-500 mt-1">

                                        ID: {{ $package['id'] }}

                                    </p>

                                </div>

                            </td>

                            {{-- CITY --}}
                            <td class="px-5 py-4 text-sm text-gray-600">

                                {{ $package['sender_city'] ?? '-' }} → {{ $package['receiver_city'] ?? '-' }}

                            </td>

                            {{-- WEIGHT --}}
                            <td class="px-5 py-4 text-sm text-gray-600">

                                {{ $package['weight_kg'] }} kg

                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-3">
                                @php
                                    $adminStatusMap = [
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
                                    $ast = $adminStatusMap[$package['status'] ?? ''] ?? ['bg' => 'bg-gray-100 text-gray-600', 'label' => $package['status'] ?? '-'];
                                @endphp
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $ast['bg'] }}">
                                    {{ $ast['label'] }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="text-center py-10 text-gray-500">

                                Belum ada data paket

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- CHART --}}
    <script>

        window.addEventListener('load', function () {

            const canvas = document.getElementById('shipmentChart');

            if (!canvas) return;

            const ctx = canvas.getContext('2d');

            new Chart(ctx, {

                type: 'line',

                data: {

                    labels: [
                        'Pending',
                        'Transit',
                        'Delivered'
                    ],

                    datasets: [{

                        label: 'Jumlah Paket',

                        data: [

                                                                                                                                                        {{ $stats['pending_packages'] }},
                                                                                                                                                        {{ $stats['transit_packages'] }},
                            {{ $stats['delivered_packages'] }}

                        ],

                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',

                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,

                        pointBackgroundColor: '#2563eb',
                        pointRadius: 5

                    }]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {
                            display: false
                        }

                    },

                    scales: {

                        y: {

                            beginAtZero: true,

                            ticks: {

                                precision: 0

                            }

                        }

                    }

                }

            });

        });

    </script>

@endsection