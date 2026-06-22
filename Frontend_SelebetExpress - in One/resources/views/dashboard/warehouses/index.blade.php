@extends('layouts.dashboard')

@section('title', 'Kelola Gudang')

@section('content')

    @php

        $filteredWarehouses = collect($warehouses)

            ->when(request('city'), function ($query) {

                return $query->filter(function ($warehouse) {

                    return strtolower($warehouse['city']) ==
                        strtolower(request('city'));

                });

            })

            ->when(request('search'), function ($query) {

                return $query->filter(function ($warehouse) {

                    return str_contains(
                        strtolower($warehouse['name']),
                        strtolower(request('search'))
                    )

                        ||

                        str_contains(
                            strtolower($warehouse['city']),
                            strtolower(request('search'))
                        );

                });

            });

    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Kelola Gudang
            </h1>

            <p class="text-sm text-gray-500 mt-1">

                Kelola seluruh gudang SelebetExpress

            </p>

        </div>

        <a href="{{ route('warehouses.create') }}"
            class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-2xl text-sm font-medium">

            Tambah Gudang

        </a>

    </div>

    {{-- FILTER --}}
    <div class="bg-white border rounded-2xl p-5 mt-5">

        <form method="GET">

            <div class="flex flex-col lg:flex-row gap-4">

                {{-- SEARCH --}}
                <div class="flex-1">

                    <label class="text-sm text-gray-600">
                        Cari Gudang
                    </label>

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama gudang"
                        class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                </div>

                {{-- CITY --}}
                <div class="lg:w-64">

                    <label class="text-sm text-gray-600">
                        Filter Kota
                    </label>

                    <input type="text" name="city" value="{{ request('city') }}" placeholder="Contoh: Jakarta"
                        class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                </div>

                {{-- BUTTON --}}
                <div class="flex items-end gap-2">

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-xl text-sm font-medium">

                        Filter

                    </button>

                    <a href="{{ route('warehouses.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 transition px-5 py-3 rounded-xl text-sm font-medium">

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border overflow-hidden mt-5">

        {{-- HEADER --}}
        <div class="p-4 border-b flex items-center justify-between">

            <div>

                <h1 class="font-semibold">
                    List Gudang
                </h1>

                <p class="text-sm text-gray-500 mt-1">

                    Total data:
                    {{ count($filteredWarehouses) }}

                </p>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px]">

                <thead class="bg-gray-50">

                    <tr class="text-sm text-gray-500">

                        <th class="text-left px-4 py-3 font-medium">
                            Gudang
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Kota
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Agen
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Telepon Kantor
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Status
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Tanggal
                        </th>

                        <th class="text-center px-4 py-3 font-medium">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y">

                    @forelse($filteredWarehouses as $warehouse)

                        <tr class="hover:bg-gray-50 transition">

                            {{-- NAME --}}
                            <td class="px-4 py-3">

                                <div>

                                    <a href="{{ route('warehouses.show', $warehouse['id']) }}"
                                        class="font-semibold text-blue-600 hover:underline">

                                        {{ $warehouse['name'] }}

                                    </a>

                                    <p class="text-xs text-gray-500 mt-1">

                                        ID:
                                        {{ $warehouse['id'] }}

                                    </p>

                                </div>

                            </td>

                            {{-- CITY --}}
                            <td class="px-4 py-3 text-sm text-gray-700">

                                {{ $warehouse['city'] }}

                            </td>

                            {{-- AGENT --}}
                            <td class="px-4 py-3">

                                <div>

                                    <h1 class="text-sm font-medium text-gray-800">

                                        {{ $warehouse['agent']['name'] ?? '-' }}

                                    </h1>

                                    <p class="text-xs text-gray-500 mt-1">

                                        Telp: {{ $warehouse['agent']['phone'] ?? '-' }}

                                    </p>

                                </div>

                            </td>

                            {{-- PHONE --}}
                            <td class="px-4 py-3 text-sm text-gray-700">

                                {{ $warehouse['phone'] ?? '-' }}

                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-3">

                                @if($warehouse['is_active'])

                                    <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full">

                                        Active

                                    </span>

                                @else

                                    <span class="bg-red-100 text-red-700 text-xs px-2.5 py-1 rounded-full">

                                        Nonactive

                                    </span>

                                @endif

                            </td>

                            {{-- DATE --}}
                            <td class="px-4 py-3 text-sm text-gray-600">

                                {{ \Carbon\Carbon::parse($warehouse['created_at'])->format('d M Y') }}

                            </td>

                            {{-- ACTION --}}
                            <td class="px-4 py-3">

                                <div class="flex items-center justify-center gap-2">

                                    {{-- DETAIL --}}
                                    <a href="{{ route('warehouses.show', $warehouse['id']) }}"
                                        class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">

                                        <i class='bx bx-show'></i>

                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('warehouses.edit', $warehouse['id']) }}"
                                        class="w-10 h-10 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center hover:bg-yellow-500 hover:text-white transition">

                                        <i class='bx bx-edit'></i>

                                    </a>

                                    {{-- DELETE --}}
                                    <button type="button"
                                        onclick="openDeleteModal('{{ route('warehouses.destroy', $warehouse['id']) }}')"
                                        class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition">

                                        <i class='bx bx-trash'></i>

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center py-12 text-gray-500">

                                Belum ada data gudang

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- DELETE MODAL --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">

        <div class="bg-white rounded-3xl w-full max-w-md p-6">

            {{-- ICON --}}
            <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">

                <i class='bx bx-trash text-3xl'></i>

            </div>

            {{-- TITLE --}}
            <div class="text-center mt-5">

                <h1 class="text-xl font-bold text-gray-800">
                    Hapus Gudang?
                </h1>

                <p class="text-sm text-gray-500 mt-2">

                    Gudang akan dinonaktifkan
                    dari sistem SelebetExpress.

                </p>

            </div>

            {{-- FORM --}}
            <form id="deleteForm" method="POST" class="mt-6">

                @csrf
                @method('DELETE')

                <div class="grid grid-cols-2 gap-3">

                    <button type="button" onclick="closeDeleteModal()"
                        class="border border-gray-300 hover:bg-gray-100 transition py-3 rounded-2xl text-sm font-medium">

                        Batal

                    </button>

                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 transition text-white py-3 rounded-2xl text-sm font-medium">

                        Ya, Hapus

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- SCRIPT --}}
    <script>

        function openDeleteModal(actionUrl) {

            document
                .getElementById('deleteForm')
                .action = actionUrl;

            document
                .getElementById('deleteModal')
                .classList
                .remove('hidden');

            document
                .getElementById('deleteModal')
                .classList
                .add('flex');

        }

        function closeDeleteModal() {

            document
                .getElementById('deleteModal')
                .classList
                .add('hidden');

            document
                .getElementById('deleteModal')
                .classList
                .remove('flex');

        }

    </script>

@endsection