@extends('layouts.dashboard')

@section('title', 'Detail Delivery')

@section('content')

    @php
        // Cari delivery aktif (belum delivered/cancelled)
        $activeDelivery = collect($deliveries)->first(function ($d) {
            return !in_array($d['status'], ['delivered', 'cancelled', 'failed', 'returned']);
        });

        // Status paket
        $packageStatus = $package['status'] ?? '';

        // Apakah paket sudah selesai total
        $isFullyDelivered = ($packageStatus === 'delivered');

        // canAssign datang dari AgentDeliveryService (otorisasi warehouse)
        // Default false jika tidak ada (backward compat)
        $canAssign = $canAssign ?? false;

        // Warna badge status
        $statusColors = [
            'pending_pickup' => 'bg-yellow-100 text-yellow-700',
            'pending' => 'bg-yellow-100 text-yellow-700',
            'assigned' => 'bg-blue-100 text-blue-700',
            'picked_up' => 'bg-orange-100 text-orange-700',
            'at_origin_warehouse' => 'bg-indigo-100 text-indigo-700',
            'in_transit' => 'bg-purple-100 text-purple-700',
            'at_destination_warehouse' => 'bg-cyan-100 text-cyan-700',
            'out_for_delivery' => 'bg-green-100 text-green-700',
            'delivered' => 'bg-emerald-100 text-emerald-700',
            'cancelled' => 'bg-red-100 text-red-700',
            'returned' => 'bg-gray-100 text-gray-700',
        ];
        $badgeClass = $statusColors[$packageStatus] ?? 'bg-gray-100 text-gray-700';

        // Konfirmasi gudang: hanya agen yang canAssign
        $canConfirmOrigin = ($packageStatus === 'picked_up' && $canAssign);

        // Pesan kontekstual jika agen tidak bisa assign
        $noAssignReason = '';
        if (!$canAssign && !$isFullyDelivered && !$activeDelivery) {
            if (in_array($packageStatus, ['in_transit', 'picked_up', 'out_for_delivery'])) {
                $noAssignReason = 'Paket sedang dalam perjalanan bersama kurir. Tunggu hingga kurir menyelesaikan tugasnya.';
            } elseif ($packageStatus === 'at_destination_warehouse') {
                $noAssignReason = 'Paket sudah di gudang tujuan. Hanya agen gudang tujuan yang dapat menugaskan kurir last-mile.';
            } elseif ($packageStatus === 'at_origin_warehouse') {
                $noAssignReason = 'Paket ini dikelola oleh agen gudang asal.';
            } else {
                $noAssignReason = 'Anda tidak memiliki akses untuk menugaskan kurir pada paket ini.';
            }
        }
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Detail Paket
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Monitoring dan assign courier delivery package
            </p>

        </div>

        <a href="{{ route('agent.deliveries.index') }}"
            class="inline-flex items-center gap-2 bg-white border hover:bg-gray-50 transition px-4 py-2 rounded-xl text-sm font-medium">
            <i class='bx bx-arrow-back'></i>
            Kembali
        </a>

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

    {{-- GRID UTAMA --}}
    <div class="grid xl:grid-cols-3 gap-5 mt-5">

        {{-- KOLOM KIRI (2/3) --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- INFORMASI PAKET --}}
            <div class="bg-white border rounded-2xl p-5">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="font-semibold text-gray-800">
                            Informasi Paket
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Detail paket yang perlu diproses
                        </p>

                    </div>

                    <span class="text-xs px-3 py-1.5 rounded-full font-medium capitalize {{ $badgeClass }}">
                        {{ str_replace('_', ' ', $packageStatus) }}
                    </span>

                </div>

                <div class="grid lg:grid-cols-2 gap-x-8 gap-y-5 mt-6">

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Nomor Resi</p>
                        <h1 class="font-bold text-blue-600 mt-1">{{ $package['resi_number'] ?? '-' }}</h1>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Service</p>
                        <h1 class="font-semibold mt-1 capitalize">{{ $package['service_type'] ?? '-' }}</h1>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Pengirim</p>
                        <h1 class="font-semibold mt-1">{{ $package['sender_name'] ?? '-' }}</h1>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Penerima</p>
                        <h1 class="font-semibold mt-1">{{ $package['receiver_name'] ?? '-' }}</h1>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Kota Asal (Gudang Origin)</p>
                        <h1 class="font-semibold mt-1">{{ $package['sender_city'] ?? '-' }}</h1>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Kota Tujuan (Gudang Dest)</p>
                        <h1 class="font-semibold mt-1">{{ $package['receiver_city'] ?? '-' }}</h1>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Berat</p>
                        <h1 class="font-semibold mt-1">{{ $package['weight_kg'] ?? 0 }} kg</h1>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Total Ongkos Kirim</p>
                        <h1 class="font-semibold mt-1 text-blue-600">Rp {{ number_format($package['total_price'] ?? 0) }}
                        </h1>
                    </div>

                    @if(!empty($package['alamat_tujuan']))
                        <div class="lg:col-span-2">
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Alamat Tujuan</p>
                            <h1 class="font-semibold mt-1">{{ $package['alamat_tujuan'] }}</h1>
                        </div>
                    @endif

                </div>

            </div>

            {{-- RIWAYAT DELIVERY --}}
            <div class="bg-white border rounded-2xl p-5">

                <div>
                    <h2 class="font-semibold text-gray-800">Riwayat Delivery</h2>
                    <p class="text-sm text-gray-500 mt-1">Seluruh history pengiriman package ini</p>
                </div>

                <div class="mt-5 space-y-3">

                    @forelse($deliveries as $delivery)

                        @php
                            $dType = $delivery['delivery_type'] ?? '';
                            $dStatus = $delivery['status'] ?? '';

                            $typeLabels = [
                                'pickup' => ['label' => 'Pickup', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'icon' => 'bx-package'],
                                'inter_warehouse' => ['label' => 'Antar Gudang', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'bx-transfer-alt'],
                                'last_mile' => ['label' => 'Last Mile', 'bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'bx-home'],
                            ];
                            $tConf = $typeLabels[$dType] ?? ['label' => $dType, 'bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-package'];

                            $dStatusColors = [
                                'assigned' => 'bg-blue-50 text-blue-600',
                                'picked_up' => 'bg-orange-50 text-orange-600',
                                'in_transit' => 'bg-purple-50 text-purple-600',
                                'out_for_delivery' => 'bg-green-50 text-green-600',
                                'delivered' => 'bg-emerald-50 text-emerald-600',
                                'failed' => 'bg-red-50 text-red-600',
                                'returned' => 'bg-gray-50 text-gray-600',
                            ];
                            $dBadge = $dStatusColors[$dStatus] ?? 'bg-gray-50 text-gray-600';
                        @endphp

                        <div class="border rounded-2xl p-4">

                            <div class="flex items-center justify-between gap-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="w-10 h-10 rounded-xl {{ $tConf['bg'] }} {{ $tConf['text'] }} flex items-center justify-center flex-shrink-0">
                                        <i class='bx {{ $tConf['icon'] }}'></i>
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-semibold text-sm text-gray-800">
                                                {{ $tConf['label'] }}
                                            </h3>
                                            <span class="text-xs px-2 py-0.5 rounded-full {{ $dBadge }} capitalize">
                                                {{ str_replace('_', ' ', $dStatus) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">
                                            Delivery #{{ $delivery['id'] }} ·
                                            Kurir: {{ $delivery['courier']['name'] ?? '-' }}
                                        </p>
                                        @if(!empty($delivery['current_location']))
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                <i class='bx bx-map-pin'></i> {{ $delivery['current_location'] }}
                                            </p>
                                        @endif
                                    </div>

                                </div>

                                <div class="text-right text-xs text-gray-400">
                                    @if(!empty($delivery['delivered_at']))
                                        <div>
                                            <span class="text-emerald-600 font-medium">Selesai</span>
                                            <br>{{ date('d/m/Y H:i', strtotime($delivery['delivered_at'])) }}
                                        </div>
                                    @elseif(!empty($delivery['assigned_at']))
                                        <div>
                                            Dibuat:<br>{{ date('d/m/Y H:i', strtotime($delivery['assigned_at'])) }}
                                        </div>
                                    @endif
                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-10 text-gray-400">
                            <i class='bx bx-package text-4xl'></i>
                            <p class="mt-2 text-sm">Belum ada delivery</p>
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

        {{-- KOLOM KANAN (1/3) --}}
        <div class="space-y-5">

            {{-- KONFIRMASI PAKET DI GUDANG --}}
            {{-- Tampil jika paket sudah picked_up (perlu dikonfirmasi masuk gudang origin) --}}
            @if($canConfirmOrigin)

                <div class="bg-white border border-indigo-200 rounded-2xl p-5">

                    <div class="flex items-center gap-3 mb-4">

                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <i class='bx bx-building-house'></i>
                        </div>

                        <div>
                            <h2 class="font-semibold text-gray-800 text-sm">
                                Konfirmasi Di Gudang Origin
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Paket sudah tiba di gudang asal
                            </p>
                        </div>

                    </div>

                    <div class="bg-indigo-50 rounded-xl p-3 mb-4 text-sm text-indigo-700">
                        Kurir pickup sudah mengantarkan paket ke gudang origin.
                        Konfirmasi penerimaan untuk memulai proses antar gudang.
                    </div>

                    <form action="{{ route('agent.deliveries.mark-at-warehouse', $package['id']) }}" method="POST">
                        @csrf
                        <input type="hidden" name="warehouse_type" value="origin">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 transition text-white py-3 rounded-xl text-sm font-medium">
                            <i class='bx bx-check-double mr-1'></i>
                            Konfirmasi Paket di Gudang Origin
                        </button>
                    </form>

                </div>

            @endif

            {{-- STATUS AKTIF: Kurir sedang bertugas --}}
            @if($activeDelivery)

                <div class="bg-white border rounded-2xl p-5">

                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center">
                            <i class='bx bx-time'></i>
                        </div>
                        <div>
                            <h2 class="font-semibold text-gray-800 text-sm">Delivery Sedang Berjalan</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Kurir aktif bertugas</p>
                        </div>
                    </div>

                    <div class="bg-yellow-50 rounded-xl p-4 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Courier</span>
                            <span class="font-medium text-gray-800">{{ $activeDelivery['courier']['name'] ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Jenis</span>
                            <span
                                class="font-medium capitalize">{{ str_replace('_', ' ', $activeDelivery['delivery_type'] ?? '-') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span
                                class="font-medium text-yellow-700 capitalize">{{ str_replace('_', ' ', $activeDelivery['status'] ?? '-') }}</span>
                        </div>
                        @if(!empty($activeDelivery['current_location']))
                            <div class="flex justify-between">
                                <span class="text-gray-500">Lokasi</span>
                                <span class="font-medium text-gray-700">{{ $activeDelivery['current_location'] }}</span>
                            </div>
                        @endif
                    </div>

                </div>

            @elseif($isFullyDelivered)

                {{-- PAKET SUDAH TERKIRIM --}}
                <div class="bg-white border border-emerald-200 rounded-2xl p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <i class='bx bx-check-double text-2xl'></i>
                        </div>
                        <div>
                            <h2 class="font-semibold text-emerald-700">Delivery Selesai</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Paket sudah diterima oleh pelanggan</p>
                        </div>
                    </div>
                </div>

            @elseif($canAssign)

                {{-- FORM ASSIGN COURIER — hanya untuk agen yang berwenang --}}
                <div class="bg-white border rounded-2xl p-5">

                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="font-semibold text-gray-800">Assign Courier</h2>
                            <p class="text-sm text-gray-500 mt-1">Tugaskan courier untuk tahap ini</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class='bx bx-car'></i>
                        </div>
                    </div>

                    @if(empty($couriers) || count($couriers) === 0)
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center text-sm text-gray-500">
                            <i class='bx bx-user-x text-3xl text-gray-300 mb-2 block'></i>
                            Tidak ada courier tersedia di gudang ini saat ini.
                        </div>
                    @else

                        <form action="{{ route('agent.deliveries.assign') }}" method="POST" class="space-y-4">
                            @csrf

                            <input type="hidden" name="package_id" value="{{ $package['id'] }}">
                            <input type="hidden" name="origin_warehouse_id" value="{{ $package['origin_warehouse_id'] }}">
                            <input type="hidden" name="destination_warehouse_id" value="{{ $package['dest_warehouse_id'] }}">

                            {{-- DELIVERY TYPE — otomatis sesuai status paket --}}
                            <div>
                                <label class="text-sm text-gray-600 font-medium">Jenis Delivery</label>

                                @if(in_array($packageStatus, ['pending_pickup', 'pending']))
                                    <input type="hidden" name="delivery_type" value="pickup">
                                    <div
                                        class="mt-2 flex items-center gap-3 border rounded-xl px-4 py-3 bg-orange-50 border-orange-200">
                                        <div>
                                            <p class="text-sm font-medium text-orange-700">Pickup</p>
                                            <p class="text-xs text-orange-600">Ambil paket dari kasir → Gudang Origin</p>
                                        </div>
                                    </div>
                                @elseif($packageStatus === 'at_origin_warehouse')
                                    <input type="hidden" name="delivery_type" value="inter_warehouse">
                                    <div class="mt-2 flex items-center gap-3 border rounded-xl px-4 py-3 bg-blue-50 border-blue-200">
                                        <div>
                                            <p class="text-sm font-medium text-blue-700">Antar Gudang</p>
                                            <p class="text-xs text-blue-600">Gudang Origin → Gudang Tujuan</p>
                                        </div>
                                    </div>
                                @elseif($packageStatus === 'at_destination_warehouse')
                                    <input type="hidden" name="delivery_type" value="last_mile">
                                    <div class="mt-2 flex items-center gap-3 border rounded-xl px-4 py-3 bg-green-50 border-green-200">
                                        <div>
                                            <p class="text-sm font-medium text-green-700">Last Mile</p>
                                            <p class="text-xs text-green-600">Gudang Tujuan → Alamat Penerima</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- PILIH COURIER --}}
                            <div>
                                <label class="text-sm text-gray-600 font-medium">Pilih Courier</label>
                                <select name="courier_id"
                                    class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach($couriers as $courier)
                                        <option value="{{ $courier['id'] }}">
                                            {{ $courier['name'] }}
                                            ({{ $courier['vehicle_type'] ?? 'motor' }})
                                            - {{ $courier['phone'] ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 transition text-white py-3 rounded-xl text-sm font-semibold">
                                <i class='bx bx-user-check mr-1'></i>
                                Assign Courier
                            </button>

                        </form>

                    @endif

                </div>

            @else

                {{-- TIDAK BERWENANG: tampilkan info kontekstual --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-5">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class='bx bx-info-circle text-xl'></i>
                        </div>
                        <div>
                            <h2 class="font-semibold text-gray-700 text-sm">Tidak Perlu Aksi</h2>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                {{ $noAssignReason ?: 'Paket ini tidak memerlukan aksi dari gudang Anda saat ini.' }}
                            </p>
                        </div>
                    </div>
                </div>

            @endif

            {{-- FLOW DIAGRAM SINGKAT --}}
            <div class="bg-white border rounded-2xl p-5">

                <h2 class="font-semibold text-gray-800 text-sm mb-4">Alur Pengiriman</h2>

                <div class="space-y-3">

                    @php
                        $flowSteps = [
                            ['status' => ['pending_pickup', 'pending'], 'label' => 'Menunggu Pickup', 'icon' => 'bx-time-five', 'color' => 'orange'],
                            ['status' => ['picked_up', 'assigned'], 'label' => 'Pickup Berlangsung', 'icon' => 'bx-package', 'color' => 'orange'],
                            ['status' => ['at_origin_warehouse'], 'label' => 'Di Gudang Origin', 'icon' => 'bx-building', 'color' => 'indigo'],
                            ['status' => ['in_transit'], 'label' => 'Transit Antar Gudang', 'icon' => 'bx-transfer-alt', 'color' => 'purple'],
                            ['status' => ['at_destination_warehouse'], 'label' => 'Di Gudang Tujuan', 'icon' => 'bx-building-house', 'color' => 'cyan'],
                            ['status' => ['out_for_delivery'], 'label' => 'Dalam Pengiriman', 'icon' => 'bx-cycling', 'color' => 'green'],
                            ['status' => ['delivered'], 'label' => 'Terkirim', 'icon' => 'bx-check-circle', 'color' => 'emerald'],
                        ];

                        $statusOrder = [
                            'pending' => 0,
                            'pending_pickup' => 0,
                            'assigned' => 1,
                            'picked_up' => 1,
                            'at_origin_warehouse' => 2,
                            'in_transit' => 3,
                            'at_destination_warehouse' => 4,
                            'out_for_delivery' => 5,
                            'delivered' => 6,
                        ];
                        $currentOrder = $statusOrder[$packageStatus] ?? -1;
                    @endphp

                    @foreach($flowSteps as $idx => $step)

                        @php
                            $stepOrder = $idx;
                            $isDone = $currentOrder > $stepOrder;
                            $isCurrent = in_array($packageStatus, $step['status']);
                        @endphp

                        <div class="flex items-center gap-3">

                            <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 text-xs
                                                                @if($isDone)    bg-emerald-100 text-emerald-600
                                                                @elseif($isCurrent) bg-blue-500 text-white
                                                                @else           bg-gray-100 text-gray-400
                                                                @endif">

                                @if($isDone)
                                    <i class='bx bx-check'></i>
                                @else
                                    <i class='bx {{ $step['icon'] }}'></i>
                                @endif

                            </div>

                            <span class="text-xs
                                                                @if($isDone) text-gray-400 line-through
                                                                @elseif($isCurrent) text-blue-600 font-semibold
                                                                @else text-gray-400
                                                                @endif">
                                {{ $step['label'] }}
                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

@endsection