@extends('layouts.dashboard')

@section('title', 'Detail User')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Detail User
            </h1>

            <p class="text-sm text-gray-500 mt-1">

                Informasi lengkap pengguna
                SelebetExpress

            </p>

        </div>

        <a href="{{ route('users.index') }}"
            class="bg-white border hover:bg-gray-50 transition px-4 py-2 rounded-xl text-sm font-medium">

            Kembali

        </a>

    </div>

    {{-- GRID --}}
    <div class="grid xl:grid-cols-3 gap-5 mt-5">

        {{-- LEFT --}}
        <div class="xl:col-span-2">

            {{-- USER --}}
            <div class="bg-white border rounded-2xl p-5">

                {{-- TITLE --}}
                <div class="flex items-center justify-between">

                    <div>

                        <h1 class="font-semibold text-gray-800">
                            Informasi User
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">

                            Detail data pengguna

                        </p>

                    </div>

                    @if($user['is_active'])

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
                            Nama Lengkap
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $user['name'] }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Nomor Telepon
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $user['phone'] }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Kota
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $user['city'] }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Role
                        </p>

                        <h1 class="font-semibold mt-1 capitalize">

                            {{ $user['role'] }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            User ID
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $user['id'] }}

                        </h1>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Status
                        </p>

                        <h1 class="font-semibold mt-1">

                            {{ $user['is_active'] ? 'Active' : 'Nonactive' }}

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

                    <div class="w-24 h-24 rounded-3xl bg-blue-100 text-blue-600 flex items-center justify-center">

                        <i class='bx bx-user text-5xl'></i>

                    </div>

                    <h1 class="font-bold text-xl mt-4">

                        {{ $user['name'] }}

                    </h1>

                    <p class="text-sm text-gray-500 capitalize mt-1">

                        {{ $user['role'] }}

                    </p>

                </div>

            </div>

            {{-- ACTION --}}
            <div class="bg-white border rounded-2xl p-5">

                <h1 class="font-semibold text-gray-800">
                    Action
                </h1>

                <div class="mt-5 space-y-3">

                    <a href="{{ route('users.edit', $user['id']) }}"
                        class="w-full flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600 transition text-white py-3 rounded-xl text-sm font-medium">

                        <i class='bx bx-edit'></i>

                        Edit User

                    </a>

                </div>

            </div>

        </div>

    </div>

@endsection