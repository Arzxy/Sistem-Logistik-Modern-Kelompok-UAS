@extends('layouts.dashboard')

@section('title', 'Kelola User')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Kelola User
            </h1>

            <p class="text-sm text-gray-500 mt-1">

                Kelola seluruh pengguna SelebetExpress

            </p>

        </div>

        {{-- ACTION --}}
        <div class="flex items-center gap-3">

            <a href="{{ route('users.create') }}"
                class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded-xl text-sm font-medium">

                + User Baru

            </a>

        </div>

    </div>

    {{-- FILTER --}}
    <div class="bg-white border rounded-2xl p-5 mt-5">

        <form method="GET">

            <div class="flex flex-col lg:flex-row gap-4">

                {{-- SEARCH --}}
                <div class="flex-1">

                    <label class="text-sm text-gray-600">
                        Cari User
                    </label>

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama / nomor telepon..." class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                </div>

                {{-- ROLE --}}
                <div class="lg:w-64">

                    <label class="text-sm text-gray-600">
                        Filter Role
                    </label>

                    <select name="role" class="w-full mt-2 border rounded-xl px-4 py-3 text-sm">

                        <option value="">
                            Semua Role
                        </option>

                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="kasir" {{ request('role') == 'kasir' ? 'selected' : '' }}>
                            Kasir
                        </option>

                        <option value="agen" {{ request('role') == 'agen' ? 'selected' : '' }}>
                            Agen
                        </option>

                        <option value="kurir" {{ request('role') == 'kurir' ? 'selected' : '' }}>
                            Kurir
                        </option>

                        <option value="pengirim" {{ request('role') == 'pengirim' ? 'selected' : '' }}>
                            Pengirim
                        </option>

                        <option value="penerima" {{ request('role') == 'penerima' ? 'selected' : '' }}>
                            Penerima
                        </option>

                    </select>

                </div>

                {{-- BUTTON --}}
                <div class="flex items-end gap-2">

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-xl text-sm font-medium">

                        Filter

                    </button>

                    <a href="{{ url()->current() }}"
                        class="bg-gray-100 hover:bg-gray-200 transition px-5 py-3 rounded-xl text-sm font-medium">

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

    {{-- FILTERED --}}
    @php

        $filteredUsers = collect($users)

            ->when(request('role'), function ($query) {

                return $query->where(
                    'role',
                    request('role')
                );

            })

            ->when(request('search'), function ($query) {

                return $query->filter(function ($user) {

                    return str_contains(
                        strtolower($user['name']),
                        strtolower(request('search'))
                    )

                        ||

                        str_contains(
                            strtolower($user['phone']),
                            strtolower(request('search'))
                        );

                });

            });

    @endphp

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border overflow-hidden mt-5">

        {{-- HEADER --}}
        <div class="p-4 border-b flex items-center justify-between">

            <div>

                <h1 class="font-semibold">
                    List User
                </h1>

                <p class="text-sm text-gray-500 mt-1">

                    Total data:
                    {{ count($filteredUsers) }}

                </p>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px]">

                <thead class="bg-gray-50">

                    <tr class="text-sm text-gray-500">

                        <th class="text-left px-4 py-3 font-medium">
                            User
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Phone
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Email
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Role
                        </th>

                        <th class="text-left px-4 py-3 font-medium">
                            Kota
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

                    @forelse($filteredUsers as $user)

                        @php

                            $roleColor = match ($user['role']) {

                                'admin' => 'bg-red-100 text-red-700',
                                'kasir' => 'bg-blue-100 text-blue-700',
                                'agen' => 'bg-indigo-100 text-indigo-700',
                                'kurir' => 'bg-orange-100 text-orange-700',
                                'pengirim' => 'bg-green-100 text-green-700',
                                default => 'bg-gray-100 text-gray-700'

                            };

                        @endphp

                        <tr class="hover:bg-gray-50 transition">

                            {{-- USER --}}
                            <td class="px-4 py-3">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="w-11 h-11 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">

                                        <i class='bx bx-user text-xl'></i>

                                    </div>

                                    <div>

                                        <h1 class="font-semibold text-sm text-gray-800">

                                            {{ $user['name'] }}

                                        </h1>

                                        <p class="text-xs text-gray-500 mt-1">

                                            ID:
                                            {{ $user['id'] }}

                                        </p>

                                    </div>

                                </div>

                            </td>

                            {{-- PHONE --}}
                            <td class="px-4 py-3 text-sm text-gray-700">

                                {{ $user['phone'] }}

                            </td>

                            {{-- EMAIL --}}
                            <td class="px-4 py-3 text-sm text-gray-700">

                                {{ $user['email'] ?? '-' }}

                            </td>

                            {{-- ROLE --}}
                            <td class="px-4 py-3">

                                <span class="capitalize text-xs px-2.5 py-1 rounded-full {{ $roleColor }}">

                                    {{ $user['role'] }}

                                </span>

                            </td>

                            {{-- CITY --}}
                            <td class="px-4 py-3 text-sm text-gray-700">

                                {{ $user['city'] ?? '-' }}

                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-3">

                                @if($user['is_active'])

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

                                {{ \Carbon\Carbon::parse($user['created_at'])->format('d M Y') }}

                            </td>

                            {{-- ACTION --}}
                            <td class="px-4 py-3">

                                <div class="flex items-center justify-center gap-2">

                                    {{-- DETAIL --}}
                                    <a href="{{ route('users.show', $user['id']) }}"
                                        class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">

                                        <i class='bx bx-show'></i>

                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('users.edit', $user['id']) }}"
                                        class="w-10 h-10 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center hover:bg-yellow-500 hover:text-white transition">

                                        <i class='bx bx-edit'></i>

                                    </a>

                                    {{-- DELETE --}}
                                    <button type="button"
                                        onclick="openDeleteModal('{{ route('users.destroy', $user['id']) }}', '{{ $user['name'] }}')"
                                        class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition">

                                        <i class='bx bx-trash'></i>

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center py-12 text-gray-500">

                                Belum ada data user

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
                    Hapus User?
                </h1>

                <p class="text-sm text-gray-500 mt-2 leading-relaxed">

                    User
                    <span id="deleteUserName" class="font-semibold text-gray-700"></span>
                    akan dihapus permanen
                    dan tidak dapat dikembalikan.

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

        function openDeleteModal(actionUrl, userName) {

            document
                .getElementById('deleteForm')
                .action = actionUrl;

            document
                .getElementById('deleteUserName')
                .innerText = userName;

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