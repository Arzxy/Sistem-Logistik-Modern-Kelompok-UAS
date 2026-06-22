@extends('layouts.dashboard')

@section('title', 'Tambah Tarif')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Tambah Tarif
            </h1>

            <p class="text-sm text-gray-500 mt-1">

                Tambahkan tarif pengiriman baru
                SelebetExpress

            </p>

        </div>

        <a href="{{ route('tariffs.index') }}"
            class="bg-white border hover:bg-gray-50 transition px-4 py-2 rounded-xl text-sm font-medium">

            Kembali

        </a>

    </div>

    {{-- GRID --}}
    <div class="grid xl:grid-cols-1 gap-5 mt-5">

        {{-- LEFT --}}
        <div class="xl:col-span-2">

            <div class="bg-white border rounded-2xl p-5">

                {{-- TITLE --}}
                <div class="flex items-center justify-between">

                    <div>

                        <h1 class="font-semibold text-gray-800">
                            Form Tarif
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">

                            Lengkapi data tarif pengiriman

                        </p>

                    </div>

                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">

                        <i class='bx bx-wallet text-2xl'></i>

                    </div>

                </div>

                {{-- FORM --}}
                <form action="{{ route('tariffs.store') }}" method="POST" class="mt-6">

                    @csrf

                    <div class="grid lg:grid-cols-2 gap-5">

                        {{-- ORIGIN --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Kota Asal
                            </label>

                            <input type="text" name="origin_city" value="{{ old('origin_city') }}"
                                placeholder="Contoh : Purwakarta" class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                        </div>

                        {{-- DEST --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Kota Tujuan
                            </label>

                            <input type="text" name="dest_city" value="{{ old('dest_city') }}"
                                placeholder="Contoh : Bandung" class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                        </div>

                        {{-- PRICE --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Harga per Kg
                            </label>

                            <input type="number" name="price_per_kg" value="{{ old('price_per_kg') }}" placeholder="5000"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                        </div>

                        {{-- MIN WEIGHT --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Minimal Berat
                            </label>

                            <input type="number" name="min_weight_kg" value="{{ old('min_weight_kg') }}" placeholder="1"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                        </div>

                        {{-- ESTIMATION --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Estimasi Hari
                            </label>

                            <input type="number" name="estimated_days" value="{{ old('estimated_days') }}" placeholder="2"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-6">

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-3 rounded-xl text-sm font-medium">

                            Simpan Tarif

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection