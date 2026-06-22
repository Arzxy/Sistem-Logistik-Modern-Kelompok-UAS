@extends('layouts.dashboard')

@section('title', 'Manajemen Delivery')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Manajemen Delivery
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Kelola delivery paket di gudang Anda
            </p>

        </div>

    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="mt-4 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
            <i class='bx bx-check-circle text-green-500 text-xl'></i>
            <span class="text-sm text-green-700 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mt-4 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center gap-3">
            <i class='bx bx-error-circle text-red-500 text-xl'></i>
            <span class="text-sm text-red-700 font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- FILTER --}}
    <div class="bg-white border rounded-2xl p-5 mt-5">

        <form method="GET">

            <div class="flex flex-col lg:flex-row gap-4">

                <div class="flex-1">

                    <label class="text-sm text-gray-600 font-medium">
                        Cari Resi
                    </label>

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor resi..."
                        class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                </div>

                <div class="lg:w-56">

                    <label class="text-sm text-gray-600 font-medium">
                        Filter Status
                    </label>

                    <select name="status"
                        class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <option value="">Semua Status</option>

                        <option value="pending_pickup" {{ request('status') === 'pending_pickup' ? 'selected' : '' }}>
                            Pending Pickup
                        </option>

                        <option value="at_origin_warehouse" {{ request('status') === 'at_origin_warehouse' ? 'selected' : '' }}>
                            Di Gudang Origin
                        </option>

                        <option value="at_destination_warehouse" {{ request('status') === 'at_destination_warehouse' ? 'selected' : '' }}>
                            Di Gudang Tujuan
                        </option>

                    </select>

                </div>

                <div class="flex items-end gap-2">

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-xl text-sm font-medium">
                        <i class='bx bx-filter-alt mr-1'></i>
                        Filter
                    </button>

                    @if(request('search') || request('status'))
                        <a href="{{ route('agent.deliveries.index') }}"
                            class="bg-gray-100 hover:bg-gray-200 transition text-gray-600 px-4 py-3 rounded-xl text-sm">
                            Reset
                        </a>
                    @endif

                </div>

            </div>

        </form>

    </div>

    {{-- TABEL --}}
    <div class="bg-white rounded-2xl border overflow-hidden mt-5">

        {{-- HEADER --}}
        <div class="p-4 border-b flex items-center justify-between">

            <div>

                <h1 class="font-semibold">
                    List Paket
                </h1>

                <p class="text-sm text-gray-500 mt-1">

                    Total data:
                    {{ count($packages) }}

                </p>

            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px]">

                <thead class="bg-gray-50 border-b">

                    <tr class="text-xs text-gray-500 uppercase tracking-wide">

                        <th class="text-left px-4 py-3 font-medium">Resi</th>
                        <th class="text-left px-4 py-3 font-medium">Pengirim</th>
                        <th class="text-left px-4 py-3 font-medium">Penerima</th>
                        <th class="text-left px-4 py-3 font-medium">Rute</th>
                        <th class="text-left px-4 py-3 font-medium">Berat</th>
                        <th class="text-left px-4 py-3 font-medium">Status</th>
                        <th class="text-left px-4 py-3 font-medium">Aksi</th>

                    </tr>

                </thead>

                <tbody class="divide-y">

                    @forelse($packages as $package)

                        @php
                            $statusBadges = [
                                'pending_pickup' => ['bg' => 'bg-yellow-100 text-yellow-700', 'label' => 'Pending Pickup'],
                                'pending' => ['bg' => 'bg-yellow-100 text-yellow-700', 'label' => 'Pending'],
                                'assigned' => ['bg' => 'bg-blue-100 text-blue-700', 'label' => 'Assigned'],
                                'picked_up' => ['bg' => 'bg-orange-100 text-orange-700', 'label' => 'Picked Up'],
                                'at_origin_warehouse' => ['bg' => 'bg-indigo-100 text-indigo-700', 'label' => 'Gudang Origin'],
                                'in_transit' => ['bg' => 'bg-purple-100 text-purple-700', 'label' => 'In Transit'],
                                'at_destination_warehouse' => ['bg' => 'bg-cyan-100 text-cyan-700', 'label' => 'Gudang Tujuan'],
                                'out_for_delivery' => ['bg' => 'bg-green-100 text-green-700', 'label' => 'Out For Delivery'],
                                'delivered' => ['bg' => 'bg-emerald-100 text-emerald-700', 'label' => 'Delivered'],
                            ];
                            $badge = $statusBadges[$package['status']] ?? ['bg' => 'bg-gray-100 text-gray-700', 'label' => $package['status']];
                        @endphp

                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-4 py-4">
                                <div>
                                    <h1 class="font-semibold text-sm text-blue-600">
                                        {{ $package['resi_number'] }}
                                    </h1>
                                    <p class="text-xs text-gray-400 mt-0.5 capitalize">
                                        {{ $package['service_type'] }}
                                    </p>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-700">
                                {{ $package['sender_name'] }}
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-700">
                                {{ $package['receiver_name'] }}
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-600">
                                <span>{{ $package['sender_city'] }}</span>
                                <span class="mx-1 text-gray-400">→</span>
                                <span>{{ $package['receiver_city'] }}</span>
                            </td>

                            <td class="px-4 py-4 text-sm text-gray-700">
                                {{ $package['weight_kg'] }} kg
                            </td>

                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center gap-1 text-xs px-2.5 py-1.5 rounded-full font-medium {{ $badge['bg'] }}">
                                    {{ $badge['label'] }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center">
                                    <a href="{{ route('agent.deliveries.show', $package['id']) }}"
                                        class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                                        <i class='bx bx-show'></i>
                                    </a>
                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-16 text-gray-400">

                                <i class='bx bx-package text-5xl block mb-3'></i>
                                <p class="text-sm">Tidak ada paket yang perlu diproses</p>
                                <p class="text-xs mt-1 text-gray-300">Pastikan gudang Anda sudah terdaftar dan ada paket yang
                                    masuk</p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection