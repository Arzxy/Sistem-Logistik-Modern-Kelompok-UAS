@extends('layouts.dashboard')

@section('title', 'Edit Tarif')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Edit Tarif
            </h1>

            <p class="text-sm text-gray-500 mt-1">

                Perbarui data tarif pengiriman
                SelebetExpress

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
                            Form Edit Tarif
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">

                            Perbarui informasi tarif pengiriman

                        </p>

                    </div>

                    <div class="w-12 h-12 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center">

                        <i class='bx bx-edit text-2xl'></i>

                    </div>

                </div>

                {{-- FORM --}}
                <form action="{{ route('tariffs.update', $tariff['id']) }}" method="POST" class="mt-6">

                    @csrf
                    @method('PUT')

                    <div class="grid lg:grid-cols-2 gap-5">

                        {{-- ORIGIN --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Kota Asal
                            </label>

                            <div class="relative mt-2">

                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">

                                    <i class='bx bx-map'></i>

                                </div>

                                <input type="text" value="{{ $tariff['origin_city'] }}" disabled
                                    class="w-full border rounded-xl pl-11 pr-4 py-3 text-sm bg-gray-100">

                            </div>

                        </div>

                        {{-- DEST --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Kota Tujuan
                            </label>

                            <div class="relative mt-2">

                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">

                                    <i class='bx bx-location-plus'></i>

                                </div>

                                <input type="text" value="{{ $tariff['dest_city'] }}" disabled
                                    class="w-full border rounded-xl pl-11 pr-4 py-3 text-sm bg-gray-100">

                            </div>

                        </div>

                        {{-- PRICE --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Harga per Kg
                            </label>

                            <div class="relative mt-2">

                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">

                                    <i class='bx bx-wallet'></i>

                                </div>

                                <input type="number" name="price_per_kg" value="{{ $tariff['price_per_kg'] }}"
                                    class="w-full border rounded-xl pl-11 pr-4 py-3 text-sm">

                            </div>

                        </div>

                        {{-- MIN WEIGHT --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Minimal Berat (Kg)
                            </label>

                            <div class="relative mt-2">

                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">

                                    <i class='bx bx-package'></i>

                                </div>

                                <input type="number" name="min_weight_kg" value="{{ $tariff['min_weight_kg'] }}"
                                    class="w-full border rounded-xl pl-11 pr-4 py-3 text-sm">

                            </div>

                        </div>

                        {{-- ESTIMATION --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Estimasi Hari
                            </label>

                            <div class="relative mt-2">

                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">

                                    <i class='bx bx-time'></i>

                                </div>

                                <input type="number" name="estimated_days" value="{{ $tariff['estimated_days'] }}"
                                    class="w-full border rounded-xl pl-11 pr-4 py-3 text-sm">

                            </div>

                        </div>

                        {{-- STATUS --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Status Tarif
                            </label>

                            <select name="is_active" class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                                <option value="1" {{ $tariff['is_active'] ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0" {{ !$tariff['is_active'] ? 'selected' : '' }}>
                                    Nonactive
                                </option>

                            </select>

                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-6 flex items-center gap-3">

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-3 rounded-xl text-sm font-medium">

                            Update Tarif

                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="space-y-5">

            {{-- INFO --}}
            <div class="bg-white border rounded-2xl p-5">

                <h1 class="font-semibold text-gray-800">
                    Informasi Tarif
                </h1>

                <div class="mt-5 space-y-4">

                    <div>

                        <p class="text-sm text-gray-500">
                            Rute Pengiriman
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $tariff['origin_city'] }}
                            →
                            {{ $tariff['dest_city'] }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Harga Saat Ini
                        </p>

                        <h1 class="font-semibold mt-1 text-blue-600">

                            Rp {{ number_format($tariff['price_per_kg']) }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Estimasi
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $tariff['estimated_days'] }} Hari

                        </h1>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection