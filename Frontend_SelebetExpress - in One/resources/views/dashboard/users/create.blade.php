@extends('layouts.dashboard')

@section('title', 'Tambah User')

@section('content')

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah User</h1>
            <p class="text-sm text-gray-500 mt-1">Tambahkan pengguna baru ke sistem SelebetExpress</p>
        </div>

        <a href="{{ route('users.index') }}"
            class="bg-white border hover:bg-gray-50 transition px-4 py-2 rounded-xl text-sm font-medium">
            Kembali
        </a>

    </div>

    {{-- FORM --}}
    <form action="{{ route('users.store') }}" method="POST" class="mt-5">

        @csrf

        <div class="grid xl:grid-cols-3 gap-5">

            {{-- LEFT --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- INFORMASI USER --}}
                <div class="bg-white border rounded-2xl p-5">

                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="font-semibold text-gray-800">Informasi User</h1>
                            <p class="text-sm text-gray-500 mt-1">Lengkapi data pengguna baru</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class='bx bx-user'></i>
                        </div>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-5 mt-6">

                        {{-- NAME --}}
                        <div>
                            <label class="text-sm text-gray-600">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- PHONE --}}
                        <div>
                            <label class="text-sm text-gray-600">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                placeholder="08xxxxxxxxxx"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- PASSWORD --}}
                        <div>
                            <label class="text-sm text-gray-600">Password</label>
                            <input type="password" name="password"
                                placeholder="Masukkan password"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- CITY --}}
                        <div>
                            <label class="text-sm text-gray-600">Kota</label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                placeholder="Masukkan kota"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                    </div>

                </div>

                {{-- CARD INFO KURIR (muncul saat role = kurir) --}}
                <div id="card-kurir" class="bg-white border border-orange-200 rounded-2xl p-5 hidden">

                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h1 class="font-semibold text-gray-800">Info Kurir</h1>
                            <p class="text-sm text-gray-500 mt-1">Data kurir untuk sistem armada</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                            <i class='bx bx-cycling'></i>
                        </div>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-5">

                        {{-- GUDANG --}}
                        <div class="lg:col-span-2">
                            <label class="text-sm text-gray-600">Gudang Penempatan <span class="text-red-500">*</span></label>
                            <select name="warehouse_id"
                                class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <option value="">Pilih Gudang</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh['id'] }}" {{ old('warehouse_id') == $wh['id'] ? 'selected' : '' }}>
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
                                <option value="motor" {{ old('vehicle_type') == 'motor' ? 'selected' : '' }}>Motor</option>
                                <option value="mobil" {{ old('vehicle_type') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                                <option value="van"   {{ old('vehicle_type') == 'van'   ? 'selected' : '' }}>Van</option>
                                <option value="truck" {{ old('vehicle_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                            </select>
                        </div>

                        {{-- VEHICLE PLATE --}}
                        <div>
                            <label class="text-sm text-gray-600">Plat Nomor</label>
                            <input type="text" name="vehicle_plate" value="{{ old('vehicle_plate') }}"
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

                {{-- ROLE --}}
                <div class="bg-white border rounded-2xl p-5">

                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="font-semibold text-gray-800">Role User</h1>
                            <p class="text-sm text-gray-500 mt-1">Pilih hak akses user</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <i class='bx bx-shield'></i>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="text-sm text-gray-600">Role</label>
                        <select id="role-select" name="role"
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Role</option>
                            <option value="admin"    {{ old('role') == 'admin'    ? 'selected' : '' }}>Admin</option>
                            <option value="kasir"    {{ old('role') == 'kasir'    ? 'selected' : '' }}>Kasir</option>
                            <option value="agen"     {{ old('role') == 'agen'     ? 'selected' : '' }}>Agen</option>
                            <option value="kurir"    {{ old('role') == 'kurir'    ? 'selected' : '' }}>Kurir</option>
                            <option value="pengirim" {{ old('role') == 'pengirim' ? 'selected' : '' }}>Pengirim</option>
                            <option value="penerima" {{ old('role') == 'penerima' ? 'selected' : '' }}>Penerima</option>
                        </select>
                    </div>

                </div>

                {{-- STATUS --}}
                <div class="bg-white border rounded-2xl p-5">

                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="font-semibold text-gray-800">Status User</h1>
                            <p class="text-sm text-gray-500 mt-1">Atur status pengguna</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                            <i class='bx bx-check-shield'></i>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="text-sm text-gray-600">Status</label>
                        <select name="is_active"
                            class="w-full mt-2 border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1">Active</option>
                            <option value="0">Nonactive</option>
                        </select>
                    </div>

                </div>

                {{-- BUTTON --}}
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 transition text-white py-3 rounded-xl text-sm font-medium shadow-lg shadow-blue-100">
                    Simpan User
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
                cardKurir.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                cardKurir.classList.add('hidden');
            }
        }

        roleSelect.addEventListener('change', toggleKurirCard);
        // Run on load (for old() repopulation)
        toggleKurirCard();
    </script>

@endsection