@extends('layouts.dashboard')

@section('title', 'Detail Tarif')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Detail Tarif
            </h1>

            <p class="text-sm text-gray-500 mt-1">

                Informasi lengkap tarif pengiriman

            </p>

        </div>

        <a href="{{ route('tariffs.index') }}"
            class="bg-white border hover:bg-gray-50 transition px-4 py-2 rounded-xl text-sm font-medium">

            Kembali

        </a>

    </div>

    {{-- GRID --}}
    <div class="grid xl:grid-cols-3 gap-5 mt-5">

        {{-- LEFT --}}
        <div class="xl:col-span-2">

            <div class="bg-white border rounded-2xl p-5">

                {{-- TITLE --}}
                <div class="flex items-center justify-between">

                    <div>

                        <h1 class="font-semibold text-gray-800">
                            Informasi Tarif
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">

                            Detail tarif pengiriman

                        </p>

                    </div>

                    @if($tariff['is_active'])

                        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">

                            Active

                        </span>

                    @else

                        <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full">

                            Nonactive

                        </span>

                    @endif

                </div>

                {{-- CONTENT --}}
                <div class="grid lg:grid-cols-2 gap-5 mt-6">

                    <div>

                        <p class="text-sm text-gray-500">
                            Kota Asal
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $tariff['origin_city'] }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Kota Tujuan
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $tariff['dest_city'] }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Harga per Kg
                        </p>

                        <h1 class="font-semibold mt-1 text-blue-600">

                            Rp {{ number_format($tariff['price_per_kg']) }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Minimal Berat
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $tariff['min_weight_kg'] }} Kg

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Estimasi Pengiriman
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $tariff['estimated_days'] }} Hari

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Status
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $tariff['is_active'] ? 'Active' : 'Nonactive' }}

                        </h1>

                    </div>

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="space-y-5">

            {{-- PROFILE --}}
            <div class="bg-white border rounded-2xl p-5">

                <div class="flex flex-col items-center text-center">

                    <div
                        class="w-24 h-24 rounded-3xl bg-blue-100 text-blue-600 flex items-center justify-center">

                        <i class='bx bx-wallet text-5xl'></i>

                    </div>

                    <h1 class="font-bold text-xl mt-4">

                        Rp {{ number_format($tariff['price_per_kg']) }}

                    </h1>

                    <p class="text-sm text-gray-500 mt-1">

                        Tarif per kilogram

                    </p>

                </div>

            </div>

            {{-- ACTION --}}
            <div class="bg-white border rounded-2xl p-5">

                <h1 class="font-semibold text-gray-800">
                    Action
                </h1>

                <div class="mt-5 space-y-3">

                    <a href="{{ route('tariffs.edit', $tariff['id']) }}"
                        class="w-full flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600 transition text-white py-3 rounded-xl text-sm font-medium">

                        <i class='bx bx-edit'></i>

                        Edit Tarif

                    </a>

                </div>

            </div>

        </div>

    </div>

@endsection