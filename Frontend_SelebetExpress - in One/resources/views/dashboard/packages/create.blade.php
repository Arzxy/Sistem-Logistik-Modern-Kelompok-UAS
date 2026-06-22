@extends('layouts.dashboard')

@section('title', 'Tambah Paket')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Tambah Paket
            </h1>

            <p class="text-sm text-gray-500 mt-1">

                Tambahkan data pengiriman paket baru
                SelebetExpress.

            </p>

        </div>

        <a href="/admin/packages"
            class="bg-white border hover:bg-gray-50 transition px-4 py-2 rounded-xl text-sm font-medium">

            Kembali

        </a>

    </div>

    {{-- FORM --}}
    <form action="/admin/packages" method="POST" class="mt-5 space-y-5">

        @csrf

        {{-- GRID --}}
        <div class="grid xl:grid-cols-2 gap-5">

            {{-- PENGIRIM --}}
            <div class="bg-white border rounded-2xl p-5">

                {{-- TITLE --}}
                <div class="flex items-center justify-between">

                    <div>

                        <h1 class="font-semibold text-gray-800">
                            Data Pengirim
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">
                            Informasi pengirim paket
                        </p>

                    </div>

                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">

                        <i class='bx bx-user'></i>

                    </div>

                </div>

                {{-- FORM --}}
                <div class="space-y-5 mt-6">

                    {{-- PHONE --}}
                    <div>

                        <label class="text-sm text-gray-600">
                            Nomor Handphone
                        </label>

                        <div class="flex gap-2 mt-2">

                            <input type="text" id="sender_phone" name="sender_phone" placeholder="08xxxxxxxxxx"
                                class="flex-1 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                            <button type="button" id="validateSender"
                                class="px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition">

                                Validate

                            </button>

                        </div>

                        <p id="senderValidationText" class="text-xs mt-2 hidden flex">
                        </p>

                    </div>

                    {{-- NAME --}}
                    <div>

                        <label class="text-sm text-gray-600">
                            Nama Pengirim
                        </label>

                        <input disabled type="text" id="sender_name" name="sender_name" placeholder="Masukkan nama pengirim"
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    {{-- KOTA --}}
                    <div>

                        <label class="text-sm text-gray-600">
                            Kota Asal
                        </label>

                        <input disabled type="text" id="sender_city" name="sender_city" placeholder="Masukkan kota asal"
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    {{-- GUDANG ASAL --}}
                    <div>

                        <label class="text-sm text-gray-600">
                            Gudang Asal
                        </label>

                        <select disabled id="origin_warehouse_id" name="origin_warehouse_id"
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                Pilih Gudang Asal
                            </option>

                            @foreach($warehouses as $warehouse)

                                <option value="{{ $warehouse['id'] }}">

                                    {{ $warehouse['name'] }}
                                    -
                                    {{ $warehouse['city'] }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- ADDRESS --}}
                    <div>

                        <label class="text-sm text-gray-600">
                            Alamat Asal
                        </label>

                        <textarea disabled id="sender_address" name="sender_address" rows="4"
                            placeholder="Masukkan alamat pickup..."
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

                    </div>

                    <p id="senderIDText" class="text-xs mt-2 flex">ID Pengirim: -</p>
                    <input type="hidden" id="sender_id" name="sender_id">

                </div>

            </div>

            {{-- PENERIMA --}}
            <div class="bg-white border rounded-2xl p-5">

                {{-- TITLE --}}
                <div class="flex items-center justify-between">

                    <div>

                        <h1 class="font-semibold text-gray-800">
                            Data Penerima
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">
                            Informasi penerima paket
                        </p>

                    </div>

                    <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">

                        <i class='bx bx-package'></i>

                    </div>

                </div>

                {{-- FORM --}}
                <div class="space-y-5 mt-6">

                    {{-- PHONE --}}
                    <div>

                        <label class="text-sm text-gray-600">
                            Nomor Handphone
                        </label>

                        <div class="flex gap-2 mt-2">

                            <input type="text" id="receiver_phone" name="receiver_phone" placeholder="08xxxxxxxxxx"
                                class="flex-1 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                            <button type="button" id="validateReceiver"
                                class="px-4 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition">

                                Validate

                            </button>

                        </div>

                        <p id="receiverValidationText" class="text-xs mt-2 hidden flex">
                        </p>

                    </div>

                    {{-- NAME --}}
                    <div>

                        <label class="text-sm text-gray-600">
                            Nama Penerima
                        </label>

                        <input disabled type="text" id="receiver_name" name="receiver_name"
                            placeholder="Masukkan nama penerima"
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    {{-- KOTA --}}
                    <div>

                        <label class="text-sm text-gray-600">
                            Kota Tujuan
                        </label>

                        <input disabled type="text" id="destination_city" name="destination_city"
                            placeholder="Masukkan kota tujuan"
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    {{-- GUDANG TUJUAN --}}
                    <div>

                        <label class="text-sm text-gray-600">
                            Gudang Tujuan
                        </label>

                        <select disabled id="destination_warehouse_id" name="destination_warehouse_id"
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                Pilih Gudang Tujuan
                            </option>

                            @foreach($warehouses as $warehouse)

                                <option value="{{ $warehouse['id'] }}">

                                    {{ $warehouse['name'] }}
                                    -
                                    {{ $warehouse['city'] }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- ADDRESS --}}
                    <div>

                        <label class="text-sm text-gray-600">
                            Alamat Tujuan
                        </label>

                        <textarea disabled id="destination_address" name="destination_address" rows="4"
                            placeholder="Masukkan alamat tujuan..."
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

                    </div>

                    <p id="receiverIDText" class="text-xs mt-2 flex">ID Penerima: -</p>
                    <input type="hidden" id="receiver_id" name="receiver_id">


                </div>

            </div>

        </div>

        {{-- DETAIL --}}
        <div class="bg-white border rounded-2xl p-5">

            {{-- TITLE --}}
            <div class="flex items-center justify-between">

                <div>

                    <h1 class="font-semibold text-gray-800">
                        Detail Paket
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Informasi detail pengiriman
                    </p>

                </div>

                <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">

                    <i class='bx bx-box'></i>

                </div>

            </div>

            {{-- FORM --}}
            <div class="grid lg:grid-cols-5 gap-5 mt-6">

                {{-- BERAT --}}
                <div>

                    <label class="text-sm text-gray-600">
                        Berat Paket (kg)
                    </label>

                    <input type="number" step="0.1" id="weight_kg" name="weight_kg" placeholder="Contoh: 2.5"
                        class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                </div>


                {{-- HEIGHT --}}
                <div>

                    <label class="text-sm text-gray-600">
                        Tinggi (cm)
                    </label>

                    <input type="number" step="0.1" id="height_cm" name="height_cm" placeholder="Contoh: 2.5"
                        class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                </div>


                {{-- LENGTH --}}
                <div>

                    <label class="text-sm text-gray-600">
                        Panjang (cm)
                    </label>

                    <input type="number" step="0.1" id="length_cm" name="length_cm" placeholder="Contoh: 2.5"
                        class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                </div>

                {{-- WIDTH --}}
                <div>

                    <label class="text-sm text-gray-600">
                        Lebar (cm)
                    </label>

                    <input type="number" step="0.1" id="width_cm" name="width_cm" placeholder="Contoh: 2.5"
                        class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                </div>

                {{-- SERVICE --}}
                <div>

                    <label class="text-sm text-gray-600">
                        Service Type
                    </label>

                    <select id="service_type" name="service_type"
                        class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <option value="">
                            Pilih Service
                        </option>

                        <option value="reguler">
                            Reguler
                        </option>

                        <option value="express">
                            Express
                        </option>

                        <option value="cargo">
                            Cargo
                        </option>

                    </select>

                </div>

            </div>

            {{-- ESTIMASI ONGKIR --}}
            <div class="mt-6">

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">

                    <div class="flex items-center justify-between">

                        <div>

                            <h1 class="font-semibold text-blue-700">
                                Estimasi Ongkir
                            </h1>

                            <p class="text-sm text-blue-500 mt-1" style="padding-right: 10px;">

                                Perhitungan otomatis berdasarkan
                                gudang & berat paket

                            </p>

                        </div>

                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">

                            <i class='bx bx-wallet'></i>

                        </div>

                    </div>

                    {{-- CONTENT --}}
                    <div class="grid lg:grid-cols-3 gap-4 mt-5">

                        {{-- BERAT --}}
                        <div class="bg-white rounded-xl border px-4 py-3">

                            <p class="text-xs text-gray-500">
                                Berat Dihitung
                            </p>

                            <h1 id="calculatedWeight" class="text-lg font-bold mt-1">

                                0 kg

                            </h1>

                        </div>

                        {{-- ESTIMASI --}}
                        <div class="bg-white rounded-xl border px-4 py-3">

                            <p class="text-xs text-gray-500">
                                Estimasi Ongkir
                            </p>

                            <h1 id="shippingCost" class="text-lg font-bold text-blue-600 mt-1">

                                Rp 0

                            </h1>

                            <input type="hidden" id="total_price" name="total_price">

                        </div>

                        {{-- ESTIMASI HARI --}}
                        <div class="bg-white rounded-xl border px-4 py-3">

                            <p class="text-xs text-gray-500">
                                Estimasi Pengiriman
                            </p>

                            <h1 id="estimationText" class="text-lg font-bold text-green-600 mt-1">

                                -

                            </h1>

                        </div>

                    </div>

                </div>

            </div>

            {{-- DESC --}}
            <div class="mt-5">

                <label class="text-sm text-gray-600">
                    Deskripsi Barang
                </label>

                <textarea name="description" rows="4" placeholder="Masukkan deskripsi barang..."
                    class="w-full mt-2 border rounded-xl px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

            </div>

        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-3">

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-xl text-sm font-medium shadow-lg shadow-blue-100">

                Simpan Paket

            </button>

        </div>

    </form>

    <script>

        /*
        |--------------------------------------------------------------------------
        | VALIDATE SENDER
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('validateSender')
            .addEventListener('click', async function () {

                const phone =
                    document.getElementById('sender_phone').value;

                const text =
                    document.getElementById('senderValidationText');

                if (!phone) return;

                const response = await fetch(
                    `/admin/packages/check-user/${phone}`
                );

                const result = await response.json();

                if (result.success) {

                    text.classList.remove('hidden');
                    text.classList.remove('text-red-500');

                    text.classList.add('text-green-600');

                    text.innerHTML =
                        'User ditemukan&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check size-4 text-success"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"></path><path d="m9 12 2 2 4-4"></path></svg>';

                    document.getElementById('sender_name').value = result.data.name ?? '';

                    document.getElementById('sender_city').value = result.data.city ?? '';

                    document.getElementById('sender_address').value = result.data.address ?? '';

                    document.getElementById('sender_id').value = result.data.id ?? '';
                    document.getElementById('senderIDText').innerText = 'ID Pengirim: ' + result.data.id;

                } else {

                    text.classList.remove('hidden');
                    text.classList.remove('text-green-600');

                    text.classList.add('text-red-500');

                    text.innerHTML =
                        'User tidak ditemukan ❌ (Otomatis akan dibuat)';

                }

                document.getElementById('sender_name').disabled = false;
                document.getElementById('sender_city').disabled = false;
                document.getElementById('origin_warehouse_id').disabled = false;
                document.getElementById('sender_address').disabled = false;

            });

        /*
        |--------------------------------------------------------------------------
        | VALIDATE RECEIVER
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('validateReceiver')
            .addEventListener('click', async function () {

                const phone =
                    document.getElementById('receiver_phone').value;

                const text =
                    document.getElementById('receiverValidationText');

                if (!phone) return;

                const response = await fetch(
                    `/admin/packages/check-user/${phone}`
                );

                const result = await response.json();

                if (result.success) {

                    text.classList.remove('hidden');
                    text.classList.remove('text-red-500');

                    text.classList.add('text-green-600');

                    text.innerHTML =
                        'User ditemukan&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check size-4 text-success"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"></path><path d="m9 12 2 2 4-4"></path></svg>';

                    document.getElementById('receiver_name').value = result.data.name ?? '';

                    document.getElementById('destination_city').value = result.data.city ?? '';

                    document.getElementById('destination_address').value = result.data.address ?? '';

                    document.getElementById('receiver_id').value = result.data.id ?? '';
                    document.getElementById('receiverIDText').innerText = 'ID Penerima: ' + result.data.id;

                } else {

                    text.classList.remove('hidden');
                    text.classList.remove('text-green-600');

                    text.classList.add('text-red-500');

                    text.innerHTML =
                        'User tidak ditemukan ❌ (Otomatis akan dibuat)';

                }

                document.getElementById('receiver_name').disabled = false;
                document.getElementById('destination_city').disabled = false;
                document.getElementById('destination_warehouse_id').disabled = false;
                document.getElementById('destination_address').disabled = false;

            });

    </script>

    <script>

        /*
        |--------------------------------------------------------------------------
        | CALCULATE SHIPPING
        |--------------------------------------------------------------------------
        */

        const calculateShipping = async () => {

            const originWarehouse =
                document.getElementById(
                    'origin_warehouse_id'
                ).value;

            const destinationWarehouse =
                document.getElementById(
                    'destination_warehouse_id'
                ).value;

            const serviceType =
                document.getElementById(
                    'service_type'
                ).value;

            const weight =
                document.getElementById(
                    'weight_kg'
                ).value;

            const length =
                document.getElementById(
                    'length_cm'
                ).value;

            const width =
                document.getElementById(
                    'width_cm'
                ).value;

            const height =
                document.getElementById(
                    'height_cm'
                ).value;

            /*
            |--------------------------------------------------------------------------
            | VALIDATION
            |--------------------------------------------------------------------------
            */

            if (
                !originWarehouse ||
                !destinationWarehouse ||
                !serviceType ||
                !weight
            ) {

                return;

            }

            /*
            |--------------------------------------------------------------------------
            | REQUEST
            |--------------------------------------------------------------------------
            */

            const response = await fetch(

                `/admin/packages/calculate-shipping?` +

                new URLSearchParams({

                    origin_warehouse_id:
                        originWarehouse,

                    destination_warehouse_id:
                        destinationWarehouse,

                    service_type:
                        serviceType,

                    weight_kg:
                        weight,

                    length_cm:
                        length,

                    width_cm:
                        width,

                    height_cm:
                        height

                })

            );

            const result = await response.json();

            /*
            |--------------------------------------------------------------------------
            | FAILED
            |--------------------------------------------------------------------------
            */

            if (!result.success) return;

            /*
            |--------------------------------------------------------------------------
            | UPDATE UI
            |--------------------------------------------------------------------------
            */

            document.getElementById(
                'calculatedWeight'
            ).innerHTML =
                result.final_weight + ' kg';

            document.getElementById(
                'shippingCost'
            ).innerHTML =
                'Rp ' + Number(result.shipping_cost).toLocaleString('id-ID');

            document.getElementById(
                'total_price'
            ).value = result.shipping_cost;

            document.getElementById(
                'estimationText'
            ).innerHTML =
                result.estimated_days + ' Hari';

        };

        /*
        |--------------------------------------------------------------------------
        | EVENT
        |--------------------------------------------------------------------------
        */

        [

            'origin_warehouse_id',
            'destination_warehouse_id',
            'service_type',
            'weight_kg',
            'length_cm',
            'width_cm',
            'height_cm'

        ].forEach(id => {

            document
                .getElementById(id)
                .addEventListener(
                    'change',
                    calculateShipping
                );

        });

    </script>

@endsection