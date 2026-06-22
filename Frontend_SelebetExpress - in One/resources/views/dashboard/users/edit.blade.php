@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit User</h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui data pengguna SelebetExpress</p>
        </div>

        <a href="{{ route('users.index') }}"
            class="bg-white border hover:bg-gray-50 transition px-4 py-2 rounded-xl text-sm font-medium">
            Kembali
        </a>

    </div>

    {{-- FORM --}}
    <form action="{{ route('users.update', $user['id']) }}" method="POST" class="mt-5">

        @csrf
        @method('PUT')

        <div class="grid xl:grid-cols-3 gap-5">

            {{-- LEFT --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- INFORMASI USER --}}
                <div class="bg-white border rounded-2xl p-5">

                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="font-semibold text-gray-800">Informasi User</h1>
                            <p class="text-sm text-gray-500 mt-1">Update data pengguna sistem</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center">
                            <i class='bx bx-edit'></i>
                        </div>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-5 mt-6">

                        {{-- NAME --}}
                        <div>
                            <label class="text-sm text-gray-600">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user['name']) }}"
                                placeholder="Masukkan nama lengkap"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- PHONE --}}
                        <div>
                            <label class="text-sm text-gray-600">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $user['phone']) }}"
                                placeholder="08xxxxxxxxxx"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- CITY --}}
                        <div>
                            <label class="text-sm text-gray-600">Kota</label>
                            <input type="text" name="city" value="{{ old('city', $user['city']) }}"
                                placeholder="Masukkan kota"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- PASSWORD --}}
                        <div>
                            <label class="text-sm text-gray-600">Password Baru</label>
                            <input type="password" name="password"
                                placeholder="Kosongkan jika tidak diubah"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                    </div>

                </div>

                {{-- CARD INFO KURIR (muncul saat role = kurir) --}}
                @php
                    $currentWarehouseId = old('warehouse_id', $courier['warehouse_id'] ?? '');
                    $currentVehicleType = old('vehicle_type', $courier['vehicle_type'] ?? 'motor');
                    $currentVehiclePlate = old('vehicle_plate', $courier['vehicle_plate'] ?? $courier['vehicle_plate'] ?? '');
                    $isKurir = $user['role'] === 'kurir';
                @endphp

                <div id="card-kurir" class="bg-white border border-orange-200 rounded-2xl p-5 {{ $isKurir ? '' : 'hidden' }}">

                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h1 class="font-semibold text-gray-800">Info Kurir</h1>
                            <p class="text-sm text-gray-500 mt-1">Data kurir untuk sistem armada</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                            <i class='bx bx-cycling'></i>
                        </div>
                    </div>

                    @if($courier)
                        <div class="bg-orange-50 border border-orange-100 rounded-xl px-4 py-3 mb-4 flex items-center gap-2 text-sm text-orange-700">
                            <i class='bx bx-check-circle'></i>
                            Profil kurir sudah terdaftar di sistem armada. Perubahan di sini akan mengupdate data armada.
                        </div>
                    @endif

                    <div class="grid lg:grid-cols-2 gap-5">

                        {{-- GUDANG --}}
                        <div class="lg:col-span-2">
                            <label class="text-sm text-gray-600">Gudang Penempatan <span class="text-red-500">*</span></label>
                            <select name="warehouse_id"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <option value="">Pilih Gudang</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh['id'] }}" {{ $currentWarehouseId == $wh['id'] ? 'selected' : '' }}>
                                        {{ $wh['name'] }} — {{ $wh['city'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- VEHICLE TYPE --}}
                        <div>
                            <label class="text-sm text-gray-600">Jenis Kendaraan</label>
                            <select name="vehicle_type"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <option value="motor" {{ $currentVehicleType == 'motor' ? 'selected' : '' }}>Motor</option>
                                <option value="mobil" {{ $currentVehicleType == 'mobil' ? 'selected' : '' }}>Mobil</option>
                                <option value="van"   {{ $currentVehicleType == 'van'   ? 'selected' : '' }}>Van</option>
                                <option value="truck" {{ $currentVehicleType == 'truck' ? 'selected' : '' }}>Truck</option>
                            </select>
                        </div>

                        {{-- VEHICLE PLATE --}}
                        <div>
                            <label class="text-sm text-gray-600">Plat Nomor</label>
                            <input type="text" name="vehicle_plate" value="{{ $currentVehiclePlate }}"
                                placeholder="Contoh: B 1234 ABC"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                        </div>

                    </div>

                    <p class="text-xs text-orange-500 mt-4 flex items-center gap-1">
                        <i class='bx bx-info-circle'></i>
                        Data ini akan disimpan ke database armada sebagai profil kurir.
                    </p>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="space-y-5">

                {{-- USER INFO CARD --}}
                <div class="bg-white border rounded-2xl p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class='bx bx-user text-2xl'></i>
                        </div>
                        <div>
                            <h1 class="font-semibold text-gray-800">{{ $user['name'] }}</h1>
                            <p class="text-sm text-gray-500 capitalize mt-1">{{ $user['role'] }}</p>
                        </div>
                    </div>
                </div>

                {{-- ROLE --}}
                <div class="bg-white border rounded-2xl p-5">

                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="font-semibold text-gray-800">Role User</h1>
                            <p class="text-sm text-gray-500 mt-1">Atur hak akses pengguna</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <i class='bx bx-shield'></i>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="text-sm text-gray-600">Role</label>
                        <select id="role-select" name="role"
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="admin"    {{ $user['role'] == 'admin'    ? 'selected' : '' }}>Admin</option>
                            <option value="kasir"    {{ $user['role'] == 'kasir'    ? 'selected' : '' }}>Kasir</option>
                            <option value="agen"     {{ $user['role'] == 'agen'     ? 'selected' : '' }}>Agen</option>
                            <option value="kurir"    {{ $user['role'] == 'kurir'    ? 'selected' : '' }}>Kurir</option>
                            <option value="pengirim" {{ $user['role'] == 'pengirim' ? 'selected' : '' }}>Pengirim</option>
                            <option value="penerima" {{ $user['role'] == 'penerima' ? 'selected' : '' }}>Penerima</option>
                        </select>
                    </div>

                </div>

                {{-- STATUS --}}
                <div class="bg-white border rounded-2xl p-5">

                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="font-semibold text-gray-800">Status User</h1>
                            <p class="text-sm text-gray-500 mt-1">Atur status akun pengguna</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                            <i class='bx bx-check-shield'></i>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="text-sm text-gray-600">Status</label>
                        <select name="is_active"
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1" {{ $user['is_active'] ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$user['is_active'] ? 'selected' : '' }}>Nonactive</option>
                        </select>
                    </div>

                </div>

                {{-- BUTTON --}}
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 transition text-white py-3 rounded-xl text-sm font-medium shadow-lg shadow-blue-100">
                    Update User
                </button>

            </div>

        </div>

    </form>

    {{-- JS: Toggle card kurir --}}
    <script>
        const roleSelect = document.getElementById('role-select');
        const cardKurir  = document.getElementById('card-kurir');

        function toggleKurirCard() {
            if (roleSelect.value === 'kurir') {
                cardKurir.classList.remove('hidden');
            } else {
                cardKurir.classList.add('hidden');
            }
        }

        roleSelect.addEventListener('change', toggleKurirCard);
        toggleKurirCard();
    </script>

@endsection