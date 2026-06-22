@extends('layouts.dashboard')

@section('title', 'Tambah Gudang')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Tambah Gudang
            </h1>

            <p class="text-sm text-gray-500 mt-1">

                Tambahkan gudang baru
                SelebetExpress

            </p>

        </div>

        <a href="{{ route('warehouses.index') }}"
            class="bg-white border hover:bg-gray-50 transition px-4 py-2 rounded-xl text-sm font-medium">

            Kembali

        </a>

    </div>

    {{-- GRID --}}
    <div class="grid xl:grid-cols-1 gap-5 mt-5">

        {{-- LEFT --}}
        <div class="xl:col-span-2">

            {{-- FORM --}}
            <div class="bg-white border rounded-2xl p-5">

                {{-- TITLE --}}
                <div class="flex items-center justify-between">

                    <div>

                        <h1 class="font-semibold text-gray-800">
                            Informasi Gudang
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">

                            Lengkapi data gudang baru

                        </p>

                    </div>

                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">

                        <i class='bx bx-buildings'></i>

                    </div>

                </div>

                {{-- FORM --}}
                <form action="{{ route('warehouses.store') }}" method="POST" class="mt-6">

                    @csrf

                    <div class="grid lg:grid-cols-2 gap-5">

                        {{-- NAME --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Nama Gudang
                            </label>

                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Contoh: Gudang Jakarta Barat"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                        </div>

                        {{-- CITY --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Kota
                            </label>

                            <input type="text" name="city" value="{{ old('city') }}" placeholder="Contoh: Jakarta"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                        </div>

                        {{-- PHONE --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Nomor Telepon
                            </label>

                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 02155550001"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                        </div>

                        {{-- AGENT --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Agen Pengelola
                            </label>

                            <select name="agent_id" class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                                <option value="">
                                    Pilih Agen
                                </option>

                                @foreach($agents as $agent)

                                    <option value="{{ $agent['id'] }}">

                                        {{ $agent['name'] }}
                                        -
                                        {{ $agent['city'] }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                    {{-- ADDRESS --}}
                    <div class="mt-5">

                        <label class="text-sm text-gray-600">
                            Alamat Gudang
                        </label>

                        <textarea name="address" rows="4" placeholder="Masukkan alamat lengkap gudang..."
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm resize-none">{{ old('address') }}</textarea>

                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-6 flex items-center gap-3">

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-3 rounded-xl text-sm font-medium">

                            Simpan Gudang

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection