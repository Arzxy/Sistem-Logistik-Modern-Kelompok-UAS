@extends('layouts.dashboard')

@section('title', 'Riwayat Paket')

@section('content')

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

        // Client-side filter
        $filtered = collect($allPackages)
            ->when(request('search'), fn($c) => $c->filter(
                fn($p) =>
                str_contains(strtolower($p['resi_number'] ?? ''), strtolower(request('search')))
            ))
            ->when(request('status'), fn($c) => $c->filter(
                fn($p) =>
                ($p['status'] ?? '') === request('status')
            ));
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Paket</h1>
            <p class="text-sm text-gray-500 mt-1">Seluruh histori pengiriman paket</p>
        </div>

    </div>

    {{-- FILTER --}}
    <div class="bg-white border rounded-2xl p-5 mt-5">
        <form method="GET">
            <div class="flex flex-col lg:flex-row gap-4">

                <div class="flex-1">
                    <label class="text-sm text-gray-600">Cari Resi</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor resi..."
                        class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="lg:w-64">
                    <label class="text-sm text-gray-600">Filter Status</label>
                    <select name="status"
                        class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        @foreach($statusMap as $key => $val)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                {{ $val['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-xl text-sm font-medium">
                        Filter
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ url()->current() }}"
                            class="bg-gray-100 hover:bg-gray-200 transition px-5 py-3 rounded-xl text-sm font-medium">
                            Reset
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border overflow-hidden mt-5">

        <div class="p-4 border-b flex items-center justify-between">
            <div>
                <h1 class="font-semibold">List Paket</h1>
                <p class="text-sm text-gray-500 mt-1">Total data: {{ count($filtered) }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">

                <thead class="bg-gray-50">
                    <tr class="text-sm text-gray-500">
                        <th class="text-left px-4 py-3 font-medium">Resi</th>
                        <th class="text-left px-4 py-3 font-medium">Pengirim</th>
                        <th class="text-left px-4 py-3 font-medium">Penerima</th>
                        <th class="text-left px-4 py-3 font-medium">Berat</th>
                        <th class="text-left px-4 py-3 font-medium">Harga</th>
                        <th class="text-left px-4 py-3 font-medium">Status</th>
                        <th class="text-left px-4 py-3 font-medium">Tanggal</th>
                        <th class="text-center px-4 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($filtered as $pkg)
                        @php
                            $st = $statusMap[$pkg['status'] ?? ''] ?? ['bg' => 'bg-gray-100 text-gray-600', 'label' => $pkg['status'] ?? '-'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <a href="/admin/packages/{{ $pkg['id'] }}"
                                    class="font-semibold text-sm text-blue-600 hover:underline">
                                    {{ $pkg['resi_number'] }}
                                </a>
                                <p class="text-xs text-gray-400 mt-0.5">ID: {{ $pkg['id'] }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $pkg['sender']['name'] ?? $pkg['sender_id'] ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $pkg['receiver']['name'] ?? $pkg['receiver_id'] ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $pkg['weight_kg'] ?? 0 }} kg</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                Rp {{ number_format($pkg['total_price'] ?? 0) }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $st['bg'] }}">
                                    {{ $st['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($pkg['created_at'])->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center">
                                    <a href="/admin/packages/{{ $pkg['id'] }}"
                                        class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                                        <i class='bx bx-show'></i>
                                    </a>
                                </div>
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