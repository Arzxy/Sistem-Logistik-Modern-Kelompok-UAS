@extends('layouts.courier')

@section('title', 'Detail Delivery')

@section('content')

    @php

        $deliveryData = $delivery;
        $package = $delivery['package'] ?? [];
        $deliveryType = $deliveryData['delivery_type'] ?? 'pickup';
        $currentStatus = $deliveryData['status'] ?? 'assigned';
        $isCompleted = ($currentStatus === 'delivered');

        /*
        |--------------------------------------------------------------------------
        | KONFIGURASI TAMPILAN BERDASARKAN DELIVERY TYPE
        |--------------------------------------------------------------------------
        */
        $config = match ($deliveryType) {

            'pickup' => [
                'bg' => 'from-orange-500 to-orange-400',
                'title' => 'Pickup Paket',
                'subtitle' => 'Ambil paket dari kasir menuju gudang origin',
                'icon' => 'bx-package',
                'accent' => 'orange',
            ],

            'inter_warehouse' => [
                'bg' => 'from-blue-600 to-blue-500',
                'title' => 'Transit Antar Gudang',
                'subtitle' => 'Kirim paket dari gudang origin ke gudang tujuan',
                'icon' => 'bx-transfer-alt',
                'accent' => 'blue',
            ],

            'last_mile' => [
                'bg' => 'from-green-600 to-green-500',
                'title' => 'Antar ke Penerima',
                'subtitle' => 'Kirim paket langsung ke alamat pelanggan',
                'icon' => 'bx-home',
                'accent' => 'green',
            ],

            default => [
                'bg' => 'from-gray-600 to-gray-500',
                'title' => 'Delivery',
                'subtitle' => '-',
                'icon' => 'bx-package',
                'accent' => 'gray',
            ]

        };

        /*
        |--------------------------------------------------------------------------
        | STATUS OPTIONS BERDASARKAN DELIVERY TYPE DAN STATUS SAAT INI
        | ATURAN: kurir hanya bisa pindah ke status BERIKUTNYA
        | pickup:          assigned → picked_up → delivered
        | inter_warehouse: assigned → picked_up → in_transit → delivered
        | last_mile:       assigned → picked_up → out_for_delivery → delivered
        |--------------------------------------------------------------------------
        */

        $nextStatuses = [];

        if ($deliveryType === 'pickup') {

            if ($currentStatus === 'assigned') {
                $nextStatuses = [
                    ['value' => 'picked_up', 'label' => 'Paket Sudah Diambil dari Kasir (Picked Up)'],
                ];
            } elseif ($currentStatus === 'picked_up') {
                $nextStatuses = [
                    ['value' => 'delivered', 'label' => 'Paket Sampai di Gudang Origin (Delivered)'],
                ];
            }

        } elseif ($deliveryType === 'inter_warehouse') {

            if ($currentStatus === 'assigned') {
                $nextStatuses = [
                    ['value' => 'picked_up', 'label' => 'Paket Sudah Diambil dari Gudang (Picked Up)'],
                ];
            } elseif ($currentStatus === 'picked_up') {
                $nextStatuses = [
                    ['value' => 'in_transit', 'label' => 'Dalam Perjalanan Antar Gudang (In Transit)'],
                ];
            } elseif ($currentStatus === 'in_transit') {
                $nextStatuses = [
                    ['value' => 'delivered', 'label' => 'Tiba di Gudang Tujuan (Delivered)'],
                ];
            }

        } elseif ($deliveryType === 'last_mile') {

            if ($currentStatus === 'assigned') {
                $nextStatuses = [
                    ['value' => 'picked_up', 'label' => 'Paket Sudah Diambil dari Gudang (Picked Up)'],
                ];
            } elseif ($currentStatus === 'picked_up') {
                $nextStatuses = [
                    ['value' => 'out_for_delivery', 'label' => 'Menuju Rumah Penerima (Out for Delivery)'],
                ];
            } elseif ($currentStatus === 'out_for_delivery') {
                $nextStatuses = [
                    ['value' => 'delivered', 'label' => 'Paket Diterima Pelanggan (Delivered)'],
                ];
            }

        }

    @endphp

    @php
        $courierStatus = $courier['status'] ?? 'available';
        $isOnDuty = ($courierStatus === 'on_duty');
    @endphp

    {{-- WARNING: KURIR BELUM ON DUTY --}}
    @if(!$isOnDuty && !$isCompleted)
        <div class="mb-4 bg-amber-50 border border-amber-300 rounded-2xl p-4">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class='bx bx-error text-lg'></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-amber-800 text-sm">Kamu belum aktif bertugas</p>
                    <p class="text-xs text-amber-700 mt-0.5">Aktifkan status <strong>On Duty</strong> terlebih dahulu sebelum memperbarui status pengiriman.</p>
                    <a href="/courier/profile"
                        class="mt-2 inline-flex items-center gap-1.5 bg-red-500 hover:bg-amber-600 text-white text-xs font-semibold px-3 py-1.5 rounded-xl transition">
                        <i class='bx bx-cycling'></i>
                        Aktifkan On Duty
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- HERO CARD --}}
    <div class="bg-gradient-to-r {{ $config['bg'] }} rounded-3xl p-5 text-white">

        <div class="flex items-start gap-4">

            <div class="w-16 h-16 rounded-2xl bg-white/20 flex items-center justify-center">
                <i class='bx {{ $config['icon'] }} text-3xl'></i>
            </div>

            <div class="flex-1">

                <h1 class="text-2xl font-bold">
                    {{ $config['title'] }}
                </h1>

                <p class="text-sm opacity-90 mt-1">
                    {{ $config['subtitle'] }}
                </p>

                <div class="mt-3 inline-flex items-center gap-2 bg-white/20 rounded-full px-3 py-1">
                    <span class="text-xs font-medium capitalize">
                        Status: {{ str_replace('_', ' ', $currentStatus) }}
                    </span>
                </div>

            </div>

        </div>

    </div>

    {{-- INFO PAKET --}}
    <div class="bg-white border rounded-3xl p-5 mt-4">

        <h2 class="font-semibold text-gray-700 text-sm mb-4">Informasi Paket</h2>

        <div class="grid grid-cols-2 gap-4">

            <div>
                <p class="text-xs text-gray-400">Nomor Resi</p>
                <h1 class="font-bold text-blue-600 mt-1" style="word-wrap: break-word;">
                    {{ $package['resi_number'] ?? '-' }}
                </h1>
            </div>

            <div>
                <p class="text-xs text-gray-400">Status Delivery</p>
                <h1 class="font-semibold mt-1 capitalize">
                    {{ str_replace('_', ' ', $currentStatus) }}
                </h1>
            </div>

            <div>
                <p class="text-xs text-gray-400">Pengirim</p>
                <h1 class="font-semibold mt-1">
                    {{ $package['sender']['name'] ?? '-' }}
                </h1>
            </div>

            <div>
                <p class="text-xs text-gray-400">Penerima</p>
                <h1 class="font-semibold mt-1">
                    {{ $package['receiver']['name'] ?? '-' }}
                </h1>
            </div>

            <div>
                <p class="text-xs text-gray-400">Asal</p>
                <h1 class="font-semibold mt-1">
                    {{ $package['origin_warehouse']['city'] ?? '-' }}
                </h1>
            </div>

            <div>
                <p class="text-xs text-gray-400">Tujuan</p>
                <h1 class="font-semibold mt-1">
                    {{ $package['destination_warehouse']['city'] ?? '-' }}
                </h1>
            </div>

            <div>
                <p class="text-xs text-gray-400">Berat</p>
                <h1 class="font-semibold mt-1">
                    {{ $package['weight_kg'] ?? 0 }} kg
                </h1>
            </div>

            <div>
                <p class="text-xs text-gray-400">Total</p>
                <h1 class="font-bold text-blue-600 mt-1">
                    Rp {{ number_format($package['total_price'] ?? 0) }}
                </h1>
            </div>

        </div>

        @if(!empty($package['alamat_tujuan']))
            <div class="mt-4 pt-4 border-t">
                <p class="text-xs text-gray-400">Alamat Tujuan</p>
                <p class="text-sm font-medium text-gray-700 mt-1">{{ $package['alamat_tujuan'] }}</p>
            </div>
        @endif

    </div>

    {{-- GOOGLE MAPS & WHATSAPP BUTTONS --}}
    @php
        $alamat        = $package['alamat_tujuan'] ?? '';
        $destCity      = $package['destination_warehouse']['city'] ?? '';
        $mapQuery      = $alamat ?: $destCity;
        $mapsUrl       = 'https://www.google.com/maps/search/' . urlencode($mapQuery);

        $receiverName  = $package['receiver']['name'] ?? 'Penerima';
        $receiverPhone = $package['receiver']['phone'] ?? '';
        $waPhone       = preg_replace('/[^0-9]/', '', $receiverPhone);
        if (strpos($waPhone, '0') === 0) {
            $waPhone = '62' . substr($waPhone, 1);
        }
        $waUrl         = $waPhone ? 'https://wa.me/' . $waPhone . '?text=' . urlencode("Halo {$receiverName}, saya kurir Selebet Express ingin mengantarkan paket Anda.") : null;
        
        $isLastMile    = ($deliveryType === 'last_mile');
    @endphp

    <div class="grid grid-cols-1 gap-4 mt-4">
        @if($mapQuery)
            <div class="bg-white border rounded-3xl p-4 flex items-center gap-4 {{ !$isLastMile ? 'opacity-60' : '' }}">

                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                    <i class='bx bxs-map text-2xl'></i>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-400">Tujuan Pengiriman</p>
                    <p class="text-sm font-semibold text-gray-800 mt-0.5 truncate">
                        {{ $mapQuery }}
                    </p>
                </div>

                @if($isLastMile)
                    <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer"
                        class="flex-shrink-0 flex items-center gap-1.5 bg-red-500 hover:bg-red-600 transition text-white text-xs font-semibold px-4 py-2.5 rounded-2xl shadow-sm shadow-red-100">
                        <i class='bx bx-navigation text-sm'></i>
                        Maps
                    </a>
                @else
                    <button disabled
                        class="flex-shrink-0 flex items-center gap-1.5 bg-gray-200 text-gray-400 text-xs font-semibold px-4 py-2.5 rounded-2xl cursor-not-allowed">
                        <i class='bx bx-navigation text-sm'></i>
                        Maps
                    </button>
                @endif

            </div>
        @endif

        @if($waUrl)
            <div class="bg-white border rounded-3xl p-4 flex items-center gap-4 {{ !$isLastMile ? 'opacity-60' : '' }}">

                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <i class='bx bxl-whatsapp text-2xl'></i>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-400">Hubungi Penerima</p>
                    <p class="text-sm font-semibold text-gray-800 mt-0.5 truncate">
                        {{ $receiverName }} ({{ $receiverPhone }})
                    </p>
                </div>

                @if($isLastMile)
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                        class="flex-shrink-0 flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 transition text-white text-xs font-semibold px-4 py-2.5 rounded-2xl shadow-sm shadow-emerald-100">
                        <i class='bx bxl-whatsapp text-sm'></i>
                        WhatsApp
                    </a>
                @else
                    <button disabled
                        class="flex-shrink-0 flex items-center gap-1.5 bg-gray-200 text-gray-400 text-xs font-semibold px-4 py-2.5 rounded-2xl cursor-not-allowed">
                        <i class='bx bxl-whatsapp text-sm'></i>
                        WhatsApp
                    </button>
                @endif

            </div>
        @endif
    </div>


    {{-- UPDATE STATUS --}}

    <div class="bg-white border rounded-3xl p-5 mt-4">

        <h2 class="font-semibold text-gray-800 mb-1">Update Status</h2>
        <p class="text-xs text-gray-400 mb-4">Perbarui status sesuai kondisi aktual di lapangan</p>

        @if($isCompleted)

            <div class="bg-green-50 border border-green-200 rounded-2xl p-5 flex items-center gap-4">

                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i class='bx bx-check-double text-2xl'></i>
                </div>

                <div>
                    <h2 class="font-semibold text-green-700">Delivery Selesai</h2>
                    <p class="text-sm text-green-600 mt-0.5">Tugas pengiriman ini telah selesai.</p>
                </div>

            </div>

        @elseif(empty($nextStatuses))

            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 text-center text-sm text-gray-500">
                Tidak ada pembaruan status yang tersedia untuk kondisi saat ini.
            </div>

        @else

            <form action="/courier/packages/{{ $deliveryData['id'] }}/update-status" method="POST" class="space-y-4"
                @if(!$isOnDuty) id="form-update-disabled" @endif>

                @csrf
                <input type="hidden" name="delivery_type" value="{{ $deliveryType }}">

                {{-- STATUS PILIHAN --}}
                <div>
                    <label class="text-sm text-gray-600 font-medium">Update Status Ke</label>
                    <div class="mt-2 space-y-2">
                        @foreach($nextStatuses as $i => $opt)
                            <label class="flex items-center gap-3 border rounded-2xl p-4
                                {{ $isOnDuty ? 'cursor-pointer hover:bg-blue-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50' : 'opacity-50 cursor-not-allowed bg-gray-50' }}
                                transition">
                                <input type="radio" name="status" value="{{ $opt['value'] }}"
                                    {{ $i === 0 ? 'checked' : '' }}
                                    {{ !$isOnDuty ? 'disabled' : '' }}
                                    class="accent-blue-600">
                                <span class="text-sm text-gray-700">{{ $opt['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- LOKASI (hidden) --}}
                <div style="display: none;">
                    <input type="hidden" name="location" value="{{ $opt['label'] ?? '' }}">
                </div>

                {{-- CATATAN --}}
                <div>
                    <label class="text-sm text-gray-600 font-medium">Catatan (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Masukkan catatan jika perlu..."
                        {{ !$isOnDuty ? 'disabled' : '' }}
                        class="w-full mt-2 border rounded-2xl px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                        {{ !$isOnDuty ? 'bg-gray-50 opacity-50' : '' }}"></textarea>
                </div>

                @if($isOnDuty)
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 transition text-white py-3 rounded-2xl text-sm font-semibold">
                        <i class='bx bx-upload mr-1'></i>
                        Perbarui Status
                    </button>
                @else
                    <a href="/courier/profile"
                        class="w-full bg-amber-500 hover:bg-amber-600 transition text-white py-3 rounded-2xl text-sm font-semibold flex items-center justify-center gap-2">
                        <i class='bx bx-cycling'></i>
                        Aktifkan On Duty Dulu
                    </a>
                @endif

            </form>

        @endif

    </div>

    {{-- PROGRESS FLOW --}}
    <div class="bg-white border rounded-3xl p-5 mt-4">

        <h2 class="font-semibold text-gray-800 text-sm mb-4">Progress Delivery Ini</h2>

        @php
            $flows = [
                'pickup' => [
                    ['value' => 'assigned', 'label' => 'Ditugaskan'],
                    ['value' => 'picked_up', 'label' => 'Diambil dari Kasir'],
                    ['value' => 'delivered', 'label' => 'Sampai di Gudang Origin'],
                ],
                'inter_warehouse' => [
                    ['value' => 'assigned', 'label' => 'Ditugaskan'],
                    ['value' => 'picked_up', 'label' => 'Diambil dari Gudang'],
                    ['value' => 'in_transit', 'label' => 'Dalam Perjalanan'],
                    ['value' => 'delivered', 'label' => 'Tiba di Gudang Tujuan'],
                ],
                'last_mile' => [
                    ['value' => 'assigned', 'label' => 'Ditugaskan'],
                    ['value' => 'picked_up', 'label' => 'Diambil dari Gudang'],
                    ['value' => 'out_for_delivery', 'label' => 'Menuju Penerima'],
                    ['value' => 'delivered', 'label' => 'Diterima Pelanggan'],
                ],
            ];

            $activeFlow = $flows[$deliveryType] ?? $flows['pickup'];

            $statusIndex = array_search(
                $currentStatus,
                array_column($activeFlow, 'value')
            );
            if ($statusIndex === false)
                $statusIndex = 0;
        @endphp

        <div class="flex items-center">

            @foreach($activeFlow as $idx => $step)

                <div class="flex-1 flex flex-col items-center">

                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                                                                        @if($idx < $statusIndex)      bg-emerald-500 text-white
                                                                        @elseif($idx === $statusIndex) bg-blue-600 text-white
                                                                        @else                          bg-gray-100 text-gray-400
                                                                        @endif">

                        @if($idx < $statusIndex)
                            <i class='bx bx-check'></i>
                        @else
                            {{ $idx + 1 }}
                        @endif

                    </div>

                    <p class="text-[10px] text-center mt-1.5 leading-tight
                                                                        @if($idx === $statusIndex) text-blue-600 font-semibold
                                                                        @elseif($idx < $statusIndex) text-emerald-600
                                                                        @else text-gray-400
                                                                        @endif
                                                                        max-w-[60px]">
                        {{ $step['label'] }}
                    </p>

                </div>

                @if($idx < count($activeFlow) - 1)
                    <div class="flex-1 h-0.5 mb-5
                                                                                                @if($idx < $statusIndex) bg-emerald-400
                                                                                                @else bg-gray-200
                                                                                                @endif">
                    </div>
                @endif

            @endforeach

        </div>

    </div>

@endsection