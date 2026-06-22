@extends('layouts.public')

@section('content')

    {{-- HERO SECTION --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-600 to-blue-800">

        <div class="max-w-7xl mx-auto px-4 py-20 lg:py-28">

            <div class="grid lg:grid-cols-2 gap-14 items-center">

                {{-- LEFT --}}
                <div class="text-white">

                    <span class="bg-white/20 text-sm px-4 py-2 rounded-full">
                        Sistem Logistik Modern
                    </span>

                    <h1 class="mt-6 text-5xl lg:text-6xl font-bold leading-tight">

                        Kirim Paket
                        Lebih Cepat,
                        Aman &
                        Real-Time

                    </h1>

                    <p class="mt-6 text-lg text-blue-100 leading-relaxed">

                        SelebetExpress hadir sebagai solusi layanan
                        logistik modern dengan tracking realtime,
                        pengiriman cepat, dan keamanan paket terpercaya.

                    </p>

                    <div class="mt-10 flex flex-wrap gap-4">

                        <a href="/tracking"
                            class="bg-white text-blue-700 font-semibold px-7 py-4 rounded-2xl hover:bg-gray-100 transition">

                            Lacak Paket

                        </a>

                        <a href="/layanan"
                            class="border border-white/40 text-white px-7 py-4 rounded-2xl hover:bg-white/10 transition">

                            Lihat Layanan

                        </a>

                    </div>

                    {{-- STATS --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-14">

                        <div>
                            <h1 class="text-3xl font-bold">
                                10K+
                            </h1>

                            <p class="text-blue-100 mt-1">
                                Paket Terkirim
                            </p>
                        </div>

                        <div>
                            <h1 class="text-3xl font-bold">
                                150+
                            </h1>

                            <p class="text-blue-100 mt-1">
                                Kurir Aktif
                            </p>
                        </div>

                        <div>
                            <h1 class="text-3xl font-bold">
                                25+
                            </h1>

                            <p class="text-blue-100 mt-1">
                                Kota
                            </p>
                        </div>

                        <div>
                            <h1 class="text-3xl font-bold">
                                99%
                            </h1>

                            <p class="text-blue-100 mt-1">
                                Paket Sampai
                            </p>
                        </div>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="relative">

                    <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=1200&auto=format&fit=crop"
                        class="rounded-3xl shadow-2xl w-full object-cover">

                    {{-- FLOATING CARD --}}
                    <div class="absolute -bottom-6 -left-6 bg-white p-5 rounded-2xl shadow-xl hidden md:block">

                        <div class="flex items-center gap-4">

                            <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-green-600">

                                <i class='bx bx-package text-3xl'></i>

                            </div>

                            <div>
                                <p class="text-gray-500 text-sm">
                                    Paket Hari Ini
                                </p>

                                <h1 class="text-2xl font-bold">
                                    1,250+
                                </h1>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- QUICK TRACKING --}}
    <section class="-mt-10 relative z-20">

        <div class="max-w-5xl mx-auto px-4">

            <div class="bg-white rounded-3xl shadow-xl p-8">

                <div class="text-center">

                    <h1 class="text-3xl font-bold">
                        Lacak Pengiriman Anda
                    </h1>

                    <p class="text-gray-500 mt-3">
                        Masukkan nomor resi untuk melihat status paket terbaru
                    </p>

                </div>

                <form action="{{ route('tracking.track') }}" method="POST" class="mt-8">

                    @csrf

                    <div class="flex flex-col md:flex-row gap-4">

                        <input type="text" name="resi" placeholder="Masukkan nomor resi..."
                            class="w-full border border-gray-300 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <button
                            class="bg-blue-600 hover:bg-blue-700 transition text-white px-8 py-4 rounded-2xl font-semibold">

                            Lacak

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </section>

    {{-- KEUNGGULAN --}}
    <section class="py-24">

        <div class="max-w-7xl mx-auto px-4">

            <div class="text-center">

                <h1 class="text-4xl font-bold">
                    Kenapa Memilih Kami?
                </h1>

                <p class="text-gray-500 mt-4 max-w-2xl mx-auto">
                    Kami memberikan layanan pengiriman modern
                    dengan sistem distribusi yang cepat,
                    aman, dan transparan.
                </p>

            </div>

            <div class="grid md:grid-cols-3 gap-8 mt-16">

                {{-- CARD 1 --}}
                <div class="bg-white rounded-3xl p-8 shadow-sm border">

                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600">

                        <i class='bx bx-bolt-circle text-4xl'></i>

                    </div>

                    <h1 class="text-2xl font-bold mt-6">
                        Pengiriman Cepat
                    </h1>

                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Sistem distribusi modern memungkinkan
                        paket sampai lebih cepat dan efisien.
                    </p>

                </div>

                {{-- CARD 2 --}}
                <div class="bg-white rounded-3xl p-8 shadow-sm border">

                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center text-green-600">

                        <i class='bx bx-shield-quarter text-4xl'></i>

                    </div>

                    <h1 class="text-2xl font-bold mt-6">
                        Aman & Terpercaya
                    </h1>

                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Paket Anda dipantau secara realtime
                        dengan sistem tracking terintegrasi.
                    </p>

                </div>

                {{-- CARD 3 --}}
                <div class="bg-white rounded-3xl p-8 shadow-sm border">

                    <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600">

                        <i class='bx bx-map-pin text-4xl'></i>

                    </div>

                    <h1 class="text-2xl font-bold mt-6">
                        Tracking Realtime
                    </h1>

                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Pantau lokasi paket kapan saja
                        secara realtime dan transparan.
                    </p>

                </div>

            </div>

        </div>

    </section>

@endsection