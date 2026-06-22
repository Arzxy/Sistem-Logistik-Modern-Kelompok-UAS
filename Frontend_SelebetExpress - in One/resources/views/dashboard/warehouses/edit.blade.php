@extends('layouts.dashboard')

@section('title', 'Edit Gudang')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Edit Gudang
            </h1>

            <p class="text-sm text-gray-500 mt-1">

                Perbarui data gudang
                SelebetExpress

            </p>

        </div>

        <a href="{{ route('warehouses.index') }}"
            class="bg-white border hover:bg-gray-50 transition px-4 py-2 rounded-xl text-sm font-medium">

            Kembali

        </a>

    </div>

    {{-- GRID --}}
    <div class="grid xl:grid-cols-3 gap-5 mt-5">

        {{-- LEFT --}}
        <div class="xl:col-span-2">

            {{-- FORM --}}
            <div class="bg-white border rounded-2xl p-5">

                {{-- TITLE --}}
                <div class="flex items-center justify-between">

                    <div>

                        <h1 class="font-semibold text-gray-800">
                            Form Edit Gudang
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">

                            Update informasi gudang

                        </p>

                    </div>

                    <div
                        class="w-10 h-10 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center">

                        <i class='bx bx-edit'></i>

                    </div>

                </div>

                {{-- FORM --}}
                <form action="{{ route('warehouses.update', $warehouse['id']) }}"
                    method="POST"
                    class="mt-6">

                    @csrf
                    @method('PUT')

                    <div class="grid lg:grid-cols-2 gap-5">

                        {{-- NAME --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Nama Gudang
                            </label>

                            <input type="text"
                                name="name"
                                value="{{ $warehouse['name'] }}"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                        </div>

                        {{-- CITY --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Kota
                            </label>

                            <input type="text"
                                name="city"
                                value="{{ $warehouse['city'] }}"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                        </div>

                        {{-- PHONE --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Nomor Telepon
                            </label>

                            <input type="text"
                                name="phone"
                                value="{{ $warehouse['phone'] }}"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                        </div>

                        {{-- STATUS --}}
                        <div>

                            <label class="text-sm text-gray-600">
                                Status
                            </label>

                            <select name="is_active"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                                <option value="1"
                                    {{ $warehouse['is_active'] ? 'selected' : '' }}>

                                    Active

                                </option>

                                <option value="0"
                                    {{ !$warehouse['is_active'] ? 'selected' : '' }}>

                                    Nonactive

                                </option>

                            </select>

                        </div>

                        {{-- AGENT --}}
                        <div class="lg:col-span-2">

                            <label class="text-sm text-gray-600">
                                Agen Pengelola
                            </label>

                            <select name="agent_id"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                                <option value="">
                                    Pilih Agen
                                </option>

                                @foreach($agents as $agent)

                                    <option value="{{ $agent['id'] }}"
                                        {{ $warehouse['agent_id'] == $agent['id'] ? 'selected' : '' }}>

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

                        <textarea name="address"
                            rows="4"
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm resize-none">{{ $warehouse['address'] }}</textarea>

                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-6 flex items-center gap-3">

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-3 rounded-xl text-sm font-medium">

                            Update Gudang

                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="space-y-5">

            {{-- INFO --}}
            <div class="bg-white border rounded-2xl p-5">

                <div class="flex flex-col items-center text-center">

                    <div
                        class="w-24 h-24 rounded-3xl bg-yellow-100 text-yellow-600 flex items-center justify-center">

                        <i class='bx bx-buildings text-5xl'></i>

                    </div>

                    <h1 class="font-bold text-xl mt-4">

                        {{ $warehouse['name'] }}

                    </h1>

                    <p class="text-sm text-gray-500 mt-1">

                        {{ $warehouse['city'] }}

                    </p>

                </div>

            </div>

        </div>

    </div>

@endsection