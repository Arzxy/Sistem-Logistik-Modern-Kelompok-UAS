@extends('layouts.dashboard')

@section('title', 'Manajemen Paket')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Manajemen Paket
            </h1>

            <p class="text-sm text-gray-500 mt-1">

                Monitoring seluruh data pengiriman paket
                SelebetExpress secara realtime.

            </p>

        </div>

        {{-- ACTION --}}
        <div class="flex items-center gap-3">

            <a href="/admin/packages/create"
                class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded-xl text-sm font-medium">

                + Paket Baru

            </a>

        </div>

    </div>

    {{-- FILTER --}}
    <div class="bg-white border rounded-2xl p-5 mt-5">

        <form method="GET">

            <div class="flex flex-col lg:flex-row gap-4">

                {{-- SEARCH --}}
                <div class="flex-1">

                    <label class="text-sm text-gray-600">
                        Cari Resi
                    </label>

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor resi..."
                        class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                </div>

                {{-- ROLE --}}
                <div class="lg:w-64">

                    <label class="text-sm text-gray-600">
                        Filter Status
                    </label>

                    <select name="status"
                        class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <option value="">Semua Status</option>

                        <option value="pending_pickup" {{ request('status') == 'pending_pickup' ? 'selected' : '' }}>Pending
                            Pickup</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                        <option value="at_origin_warehouse" {{ request('status') == 'at_origin_warehouse' ? 'selected' : '' }}>Di Gudang Origin</option>
                        <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit
                        </option>
                        <option value="at_destination_warehouse" {{ request('status') == 'at_destination_warehouse' ? 'selected' : '' }}>Di Gudang Tujuan</option>
                        <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>Out
                            for Delivery</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>

                    </select>

                </div>

                {{-- BUTTON --}}
                <div class="flex items-end gap-2">

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-xl text-sm font-medium">

                        Filter

                    </button>

                    <a href="{{ url()->current() }}"
                        class="bg-gray-100 hover:bg-gray-200 transition px-5 py-3 rounded-xl text-sm font-medium">

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

    {{-- TABLE --}}
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

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px]">

                <thead class="bg-gray-50">

                    <tr class="text-sm text-gray-500">

                        <th class="text-left px-4 py-3 font-medium">
                            Resi
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Pengirim
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Penerima
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Tujuan
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Berat
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Service
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Harga
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Status
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Tanggal
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y">

                    @forelse($packages as $package)

                        <tr class="hover:bg-gray-50 transition">

                            {{-- RESI --}}
                            <td class="px-4 py-3">

                                <div>

                                    <a href="/admin/packages/{{ $package['id'] }}"
                                        class="font-semibold text-sm text-blue-600 hover:underline">

                                        {{ $package['resi_number'] }}

                                    </a>

                                    <p class="text-xs text-gray-500 mt-1">

                                        ID:
                                        {{ $package['id'] }}

                                    </p>

                                </div>

                            </td>

                            {{-- SENDER --}}
                            <td class="px-4 py-3">

                                <div>

                                    <h1 class="text-sm font-medium text-gray-800">

                                        {{ $package['sender']['name'] ?? '-' }}

                                    </h1>

                                    <p class="text-xs text-gray-500 mt-1">

                                        Kota: {{ $package['sender']['city'] ?? '-' }}

                                    </p>

                                </div>

                            </td>

                            {{-- RECEIVER --}}
                            <td class="px-4 py-3">

                                <div>

                                    <h1 class="text-sm font-medium text-gray-800">

                                        {{ $package['receiver']['name'] ?? '-' }}

                                    </h1>

                                    <p class="text-xs text-gray-500 mt-1">

                                        Kota: {{ $package['receiver']['city'] ?? '-' }}

                                    </p>

                                </div>

                            </td>

                            {{-- CITY --}}
                            <td class="px-5 py-4 text-sm text-gray-700">

                                {{ $package['sender_city'] ?? '-' }} → {{ $package['receiver_city'] ?? '-' }}

                            </td>

                            {{-- WEIGHT --}}
                            <td class="px-4 py-3 text-sm text-gray-700">

                                {{ $package['weight_kg'] ?? 0 }} kg

                            </td>

                            {{-- SERVICE --}}
                            <td class="px-4 py-3">

                                <span class="bg-blue-100 text-blue-700 text-xs px-2.5 py-1 rounded-full capitalize">

                                    {{ $package['service_type'] ?? '-' }}

                                </span>

                            </td>

                            {{-- PRICE --}}
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">

                                Rp
                                {{ number_format($package['total_price'] ?? 0) }}

                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-3">
                                @php
                                    $statusMap = [
                                        'pending_pickup' => ['bg' => 'bg-yellow-100 text-yellow-700', 'label' => 'Pending Pickup'],
                                        'pending' => ['bg' => 'bg-yellow-100 text-yellow-700', 'label' => 'Pending'],
                                        'assigned' => ['bg' => 'bg-blue-100 text-blue-700', 'label' => 'Assigned'],
                                        'picked_up' => ['bg' => 'bg-orange-100 text-orange-700', 'label' => 'Picked Up'],
                                        'at_origin_warehouse' => ['bg' => 'bg-indigo-100 text-indigo-700', 'label' => 'Gudang Origin'],
                                        'in_transit' => ['bg' => 'bg-purple-100 text-purple-700', 'label' => 'In Transit'],
                                        'at_destination_warehouse' => ['bg' => 'bg-cyan-100 text-cyan-700', 'label' => 'Gudang Tujuan'],
                                        'out_for_delivery' => ['bg' => 'bg-green-100 text-green-700', 'label' => 'Out for Delivery'],
                                        'delivered' => ['bg' => 'bg-emerald-100 text-emerald-700', 'label' => 'Delivered'],
                                        'cancelled' => ['bg' => 'bg-red-100 text-red-700', 'label' => 'Cancelled'],
                                        'returned' => ['bg' => 'bg-gray-100 text-gray-600', 'label' => 'Returned'],
                                        'failed' => ['bg' => 'bg-red-100 text-red-700', 'label' => 'Failed'],
                                    ];
                                    $st = $statusMap[$package['status'] ?? ''] ?? ['bg' => 'bg-gray-100 text-gray-600', 'label' => $package['status'] ?? '-'];
                                @endphp
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $st['bg'] }}">
                                    {{ $st['label'] }}
                                </span>
                            </td>

                            {{-- DATE --}}
                            <td class="px-4 py-3 text-sm text-gray-600">

                                {{ \Carbon\Carbon::parse($package['created_at'])->format('d M Y') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center py-12 text-gray-500">

                                Belum ada data paket

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection