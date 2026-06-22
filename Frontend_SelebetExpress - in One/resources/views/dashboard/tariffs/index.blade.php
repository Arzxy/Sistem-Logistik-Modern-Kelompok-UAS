@extends('layouts.dashboard')

@section('title', 'Tarif Pengiriman')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Tarif Pengiriman
            </h1>

            <p class="text-sm text-gray-500 mt-1">

                Kelola tarif pengiriman SelebetExpress

            </p>

        </div>

        {{-- ACTION --}}
        <div class="flex items-center gap-3">

            <a href="{{ route('tariffs.create') }}"
                class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded-xl text-sm font-medium">

                + Tarif Baru

            </a>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border overflow-hidden mt-5">

        {{-- HEADER --}}
        <div class="p-4 border-b flex items-center justify-between">

            <div>

                <h1 class="font-semibold">
                    List Tarif
                </h1>

                <p class="text-sm text-gray-500 mt-1">

                    Total data:
                    {{ count($tariffs) }}

                </p>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px]">

                <thead class="bg-gray-50">

                    <tr class="text-sm text-gray-500">

                        <th class="text-left px-4 py-3 font-medium">
                            Rute
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Harga / Kg
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Minimal Berat
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Estimasi
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

                    @forelse($tariffs as $tariff)

                        <tr class="hover:bg-gray-50 transition">

                            {{-- ROUTE --}}
                            <td class="px-4 py-3">

                                <div>

                                    <h1 class="font-semibold text-sm text-gray-800">

                                        {{ $tariff['origin_city'] }}

                                        <span class="mx-1 text-gray-400">
                                            →
                                        </span>

                                        {{ $tariff['dest_city'] }}

                                    </h1>

                                    <p class="text-xs text-gray-500 mt-1">

                                        ID:
                                        {{ $tariff['id'] }}

                                    </p>

                                </div>

                            </td>

                            {{-- PRICE --}}
                            <td class="px-4 py-3">

                                <div>

                                    <h1 class="font-semibold text-blue-600 text-sm">

                                        Rp {{ number_format($tariff['price_per_kg']) }}

                                    </h1>

                                    <p class="text-xs text-gray-500 mt-1">

                                        per kilogram

                                    </p>

                                </div>

                            </td>

                            {{-- MIN WEIGHT --}}
                            <td class="px-4 py-3 text-sm text-gray-700">

                                {{ $tariff['min_weight_kg'] }} Kg

                            </td>

                            {{-- ESTIMATION --}}
                            <td class="px-4 py-3">

                                <span class="bg-blue-100 text-blue-700 text-xs px-2.5 py-1 rounded-full">

                                    {{ $tariff['estimated_days'] }} Hari

                                </span>

                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-3">

                                @if($tariff['is_active'])

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

                                {{ \Carbon\Carbon::parse($tariff['created_at'])->format('d M Y') }}

                            </td>

                            {{-- ACTION --}}
                            <td class="px-4 py-3">

                                <div class="flex items-center justify-center gap-2">

                                    {{-- DETAIL --}}
                                    <a href="{{ route('tariffs.show', $tariff['id']) }}"
                                        class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">

                                        <i class='bx bx-show'></i>

                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('tariffs.edit', $tariff['id']) }}"
                                        class="w-10 h-10 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center hover:bg-yellow-500 hover:text-white transition">

                                        <i class='bx bx-edit'></i>

                                    </a>

                                    {{-- DELETE --}}
                                    <button type="button"
                                        onclick="openDeleteModal('{{ route('tariffs.destroy', $tariff['id']) }}')"
                                        class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition">

                                        <i class='bx bx-trash'></i>

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center py-12 text-gray-500">

                                Belum ada data tarif

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- DELETE MODAL --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">

        <div class="bg-white rounded-3xl w-full max-w-md p-6 animate-fadeIn">

            {{-- ICON --}}
            <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">

                <i class='bx bx-trash text-3xl'></i>

            </div>

            {{-- TITLE --}}
            <div class="text-center mt-5">

                <h1 class="text-xl font-bold text-gray-800">
                    Hapus Tarif?
                </h1>

                <p class="text-sm text-gray-500 mt-2 leading-relaxed">

                    Data tarif akan dihapus permanen
                    dan tidak dapat dikembalikan lagi.

                </p>

            </div>

            {{-- FORM --}}
            <form id="deleteForm" method="POST" class="mt-6">

                @csrf
                @method('DELETE')

                <div class="grid grid-cols-2 gap-3">

                    {{-- CANCEL --}}
                    <button type="button" onclick="closeDeleteModal()"
                        class="w-full border border-gray-300 hover:bg-gray-100 transition py-3 rounded-2xl text-sm font-medium">

                        Batal

                    </button>

                    {{-- DELETE --}}
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 transition text-white py-3 rounded-2xl text-sm font-medium">

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