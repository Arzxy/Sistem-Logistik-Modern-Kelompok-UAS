@extends('layouts.dashboard')

@section('title', 'Detail Paket')

@section('content')

    @php
        // Status paket
        $packageStatus = $package['status'] ?? '';

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
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Detail Paket
            </h1>

            <p class="text-sm text-gray-500 mt-1">

                Informasi detail pengiriman paket
                {{ $package['resi_number'] }}

            </p>

        </div>

        <a href="/admin/packages"
            class="bg-white border hover:bg-gray-50 transition px-4 py-2 rounded-xl text-sm font-medium">

            Kembali

        </a>

    </div>

    {{-- GRID --}}
    <div class="grid xl:grid-cols-3 gap-5 mt-5">

        {{-- LEFT --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- PACKAGE --}}
            <div class="bg-white border rounded-2xl p-5">

                {{-- TITLE --}}
                <div class="flex items-center justify-between">

                    <div>

                        <h1 class="font-semibold text-gray-800">
                            Informasi Paket
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">

                            Data utama pengiriman paket

                        </p>

                    </div>

                    {{-- STATUS --}}
                    <span class="text-xs px-3 py-1.5 rounded-full font-medium capitalize {{ $badgeClass }}">
                        {{ str_replace('_', ' ', $packageStatus) }}
                    </span>

                </div>

                {{-- CONTENT --}}
                <div class="grid lg:grid-cols-2 gap-5 mt-6">

                    <div>

                        <p class="text-sm text-gray-500">
                            Nomor Resi
                        </p>

                        <h1 class="font-semibold mt-1">
                            {{ $package['resi_number'] }}
                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Service
                        </p>

                        <h1 class="font-semibold mt-1 capitalize">
                            {{ $package['service_type'] }}
                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Berat Paket
                        </p>

                        <h1 class="font-semibold mt-1">
                            {{ $package['weight_kg'] }} kg
                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Total Harga
                        </p>

                        <h1 class="font-semibold mt-1 text-blue-600">

                            Rp
                            {{ number_format($package['total_price']) }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Panjang
                        </p>

                        <h1 class="font-semibold mt-1">
                            {{ $package['length_cm'] ?? 0 }} cm
                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Lebar
                        </p>

                        <h1 class="font-semibold mt-1">
                            {{ $package['width_cm'] ?? 0 }} cm
                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Tinggi
                        </p>

                        <h1 class="font-semibold mt-1">
                            {{ $package['height_cm'] ?? 0 }} cm
                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Tanggal Dibuat
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ \Carbon\Carbon::parse($package['created_at'])->format('d M Y H:i') }}

                        </h1>

                    </div>

                </div>

                {{-- ALAMAT --}}
                <div class="mt-6">

                    <p class="text-sm text-gray-500">
                        Alamat Tujuan
                    </p>

                    <div class="bg-gray-50 rounded-xl p-4 mt-2 text-sm text-gray-700">

                        {{ $package['alamat_tujuan'] ?? '-' }}

                    </div>

                </div>

                {{-- DESCRIPTION --}}
                <div class="mt-6">

                    <p class="text-sm text-gray-500">
                        Deskripsi Barang
                    </p>

                    <div class="bg-gray-50 rounded-xl p-4 mt-2 text-sm text-gray-700">

                        {{ $package['description'] ?? '-' }}

                    </div>

                </div>

            </div>

            {{-- SENDER --}}
            <div class="bg-white border rounded-2xl p-5">

                <div class="flex items-center justify-between">

                    <div>

                        <h1 class="font-semibold text-gray-800">
                            Data Pengirim
                        </h1>

                    </div>

                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">

                        <i class='bx bx-user'></i>

                    </div>

                </div>

                <div class="grid lg:grid-cols-2 gap-5 mt-6">

                    <div>

                        <p class="text-sm text-gray-500">
                            Nama
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $package['sender']['name'] ?? '-' }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Nomor Handphone
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $package['sender']['phone'] ?? '-' }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Kota
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $package['sender']['city'] ?? '-' }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Gudang Asal
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $package['origin_warehouse']['name'] ?? '-' }}

                        </h1>

                    </div>

                </div>

            </div>

            {{-- RECEIVER --}}
            <div class="bg-white border rounded-2xl p-5">

                <div class="flex items-center justify-between">

                    <div>

                        <h1 class="font-semibold text-gray-800">
                            Data Penerima
                        </h1>

                    </div>

                    <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">

                        <i class='bx bx-package'></i>

                    </div>

                </div>

                <div class="grid lg:grid-cols-2 gap-5 mt-6">

                    <div>

                        <p class="text-sm text-gray-500">
                            Nama
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $package['receiver']['name'] ?? '-' }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Nomor Handphone
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $package['receiver']['phone'] ?? '-' }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Kota
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $package['receiver']['city'] ?? '-' }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Gudang Tujuan
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $package['destination_warehouse']['name'] ?? '-' }}

                        </h1>

                    </div>

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="space-y-5">

            {{-- ALUR PENGIRIMAN (mirip agent/deliveries/show) --}}
            <div class="bg-white border rounded-2xl p-5">

                <h2 class="font-semibold text-gray-800 text-sm mb-4">Alur Pengiriman</h2>

                @php
                    $currentStatus = $package['status'] ?? '';

                    $flowSteps = [
                        ['status' => ['pending_pickup', 'pending'], 'label' => 'Menunggu Pickup',      'icon' => 'bx-time-five'],
                        ['status' => ['assigned', 'picked_up'],    'label' => 'Pickup Berlangsung',   'icon' => 'bx-package'],
                        ['status' => ['at_origin_warehouse'],      'label' => 'Di Gudang Origin',     'icon' => 'bx-building'],
                        ['status' => ['in_transit'],               'label' => 'Transit Antar Gudang', 'icon' => 'bx-transfer-alt'],
                        ['status' => ['at_destination_warehouse'], 'label' => 'Di Gudang Tujuan',     'icon' => 'bx-building-house'],
                        ['status' => ['out_for_delivery'],         'label' => 'Dalam Pengiriman',     'icon' => 'bx-cycling'],
                        ['status' => ['delivered'],                'label' => 'Terkirim',             'icon' => 'bx-check-circle'],
                    ];

                    $statusOrder = [
                        'pending'                  => 0,
                        'pending_pickup'           => 0,
                        'assigned'                 => 1,
                        'picked_up'                => 1,
                        'at_origin_warehouse'      => 2,
                        'in_transit'               => 3,
                        'at_destination_warehouse' => 4,
                        'out_for_delivery'         => 5,
                        'delivered'                => 6,
                    ];
                    $currentOrder = $statusOrder[$currentStatus] ?? -1;
                @endphp

                <div class="space-y-3">

                    @foreach($flowSteps as $idx => $step)

                        @php
                            $stepOrder = $idx;
                            $isDone    = $currentOrder > $stepOrder;
                            $isCurrent = in_array($currentStatus, $step['status']);
                        @endphp

                        <div class="flex items-center gap-3">

                            {{-- DOT --}}
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

                            {{-- LABEL --}}
                            <span class="text-xs
                                                                                        @if($isDone)    text-gray-400 line-through
                                                                                        @elseif($isCurrent) text-blue-600 font-semibold
                                                                                        @else           text-gray-400
                                                                                        @endif">
                                {{ $step['label'] }}
                            </span>

                        </div>

                    @endforeach

                    {{-- STATUS GAGAL / BATAL --}}
                    @if(in_array($currentStatus, ['cancelled', 'returned', 'failed']))
                        <div class="mt-2 p-3 bg-red-50 rounded-xl border border-red-100 flex items-center gap-2">
                            <i class='bx bx-x-circle text-red-500 text-lg'></i>
                            <p class="text-xs text-red-600 font-medium capitalize">
                                Paket {{ str_replace('_', ' ', $currentStatus) }}
                            </p>
                        </div>
                    @endif

                </div>

            </div>

            {{-- INFO DIBUAT OLEH --}}
            <div class="bg-white border rounded-2xl p-5">

                <h2 class="font-semibold text-gray-800 text-sm mb-4">Informasi Tambahan</h2>

                <div class="space-y-3">

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Dibuat oleh</span>
                        <span class="text-xs font-medium text-gray-700">
                            {{ $package['pembuat']['name'] ?? 'Admin' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Tanggal Buat</span>
                        <span class="text-xs font-medium text-gray-700">
                            {{ \Carbon\Carbon::parse($package['created_at'])->format('d M Y H:i') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Gudang Origin</span>
                        <span class="text-xs font-medium text-gray-700">
                            {{ $package['origin_warehouse']['name'] ?? '-' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Gudang Tujuan</span>
                        <span class="text-xs font-medium text-gray-700">
                            {{ $package['destination_warehouse']['name'] ?? '-' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Jenis Layanan</span>
                        <span class="text-xs font-medium text-gray-700 capitalize">
                            {{ $package['service_type'] ?? '-' }}
                        </span>
                    </div>

                </div>

            </div>

            {{-- DETAIL PERJALANAN (Timeline dari L5) --}}
            <div class="bg-white border rounded-2xl p-5">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-gray-800 text-sm">Detail Perjalanan</h2>
                    @if(!empty($package['tracking_logs']))
                        <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-medium">
                            {{ count($package['tracking_logs']) }} event
                        </span>
                    @endif
                </div>

                @if(!empty($package['tracking_logs']))

                    @php
                        $logStatusLabels = [
                            'pending_pickup'           => ['label' => 'Menunggu Pickup',      'icon' => 'bx-time-five',      'cls' => 'bg-yellow-100 text-yellow-700'],
                            'pending'                  => ['label' => 'Menunggu',              'icon' => 'bx-time',           'cls' => 'bg-yellow-100 text-yellow-700'],
                            'assigned'                 => ['label' => 'Kurir Ditugaskan',     'icon' => 'bx-user-check',     'cls' => 'bg-blue-100 text-blue-700'],
                            'picked_up'                => ['label' => 'Paket Diambil',        'icon' => 'bx-package',        'cls' => 'bg-orange-100 text-orange-700'],
                            'at_origin_warehouse'      => ['label' => 'Tiba Gudang Origin',   'icon' => 'bx-building',       'cls' => 'bg-indigo-100 text-indigo-700'],
                            'in_transit'               => ['label' => 'Transit Antar Gudang', 'icon' => 'bx-transfer-alt',   'cls' => 'bg-purple-100 text-purple-700'],
                            'at_destination_warehouse' => ['label' => 'Tiba Gudang Tujuan',   'icon' => 'bx-building-house', 'cls' => 'bg-cyan-100 text-cyan-700'],
                            'out_for_delivery'         => ['label' => 'Dalam Pengiriman',     'icon' => 'bx-cycling',        'cls' => 'bg-green-100 text-green-700'],
                            'delivered'                => ['label' => 'Terkirim',             'icon' => 'bx-check-circle',   'cls' => 'bg-emerald-100 text-emerald-700'],
                            'failed'                   => ['label' => 'Pengiriman Gagal',     'icon' => 'bx-x-circle',       'cls' => 'bg-red-100 text-red-700'],
                            'returned'                 => ['label' => 'Dikembalikan',         'icon' => 'bx-undo',           'cls' => 'bg-gray-100 text-gray-600'],
                            'cancelled'                => ['label' => 'Dibatalkan',           'icon' => 'bx-block',          'cls' => 'bg-red-100 text-red-700'],
                        ];
                        // Gunakan logged_at atau created_at sebagai sort key
                        $sortedLogs = collect($package['tracking_logs'])->sortByDesc(fn($l) =>
                            $l['logged_at'] ?? $l['created_at'] ?? ''
                        );
                    @endphp

                    <div class="relative pl-6 border-l-2 border-gray-100 space-y-5">

                        @foreach($sortedLogs as $log)

                            @php
                                $ls    = $log['status'] ?? '';
                                $lmeta = $logStatusLabels[$ls] ?? [
                                    'label' => strtoupper(str_replace('_', ' ', $ls)),
                                    'icon'  => 'bx-info-circle',
                                    'cls'   => 'bg-gray-100 text-gray-600',
                                ];
                                $logTime = $log['logged_at'] ?? $log['created_at'] ?? null;
                            @endphp

                            <div class="relative">

                                {{-- Bullet --}}
                                <div class="absolute -left-[27px] top-1 w-5 h-5 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center">
                                    <i class='bx {{ $lmeta['icon'] }} text-[10px] text-gray-500'></i>
                                </div>

                                <div>

                                    <div class="flex items-start justify-between gap-2 flex-wrap">

                                        <span class="inline-flex items-center gap-1 text-xs {{ $lmeta['cls'] }} px-2 py-0.5 rounded-full font-medium">
                                            <i class='bx {{ $lmeta['icon'] }}'></i>
                                            {{ $lmeta['label'] }}
                                        </span>

                                        @if($logTime)
                                            <span class="text-xs text-gray-400">
                                                {{ \Carbon\Carbon::parse($logTime)->format('d M Y H:i') }}
                                            </span>
                                        @endif

                                    </div>

                                    @if(!empty($log['location']))
                                        <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                                            <i class='bx bx-map-pin'></i>
                                            {{ $log['location'] }}
                                        </p>
                                    @endif

                                    @if(!empty($log['notes']) || !empty($log['description']))
                                        <p class="text-xs bg-gray-50 border rounded-xl p-2 mt-2 text-gray-600 italic leading-relaxed">
                                            {{ $log['notes'] ?? $log['description'] }}
                                        </p>
                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="text-center py-8">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center mx-auto mb-3 text-amber-500">
                            <i class='bx bx-error-circle text-xl'></i>
                        </div>
                        <p class="text-sm text-gray-700 font-semibold">Resi Belum Terdaftar di Pelacakan</p>
                        <p class="text-xs text-gray-500 mt-1 max-w-[220px] mx-auto leading-relaxed">Sistem pelacakan belum memiliki data log. Anda dapat mendaftarkan paket ini secara manual.</p>

                        <form action="{{ route('packages.register-tracking', $package['id']) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition shadow-sm hover:shadow-md">
                                Hubungkan Pelacakan
                            </button>
                        </form>

                        {{-- Debug panel (hanya tampil saat APP_DEBUG=true) --}}
                        @if(config('app.debug'))
                            <div class="mt-6 text-left bg-yellow-50 border border-yellow-200 rounded-xl p-3">
                                <p class="text-xs font-semibold text-yellow-700 mb-1">🛠 Debug: tracking_logs raw</p>
                                <pre class="text-[10px] text-gray-600 overflow-x-auto">{{ json_encode($package['tracking_logs'] ?? 'null', JSON_PRETTY_PRINT) }}</pre>
                                <p class="text-[10px] text-gray-500 mt-2">Package ID: {{ $package['id'] }}</p>
                            </div>
                        @endif

                    </div>

                @endif

            </div>

        </div>

    </div>

@endsection