@extends('layouts.public')

@section('content')

    {{-- HERO --}}
    <section class="bg-gradient-to-br from-blue-600 to-blue-800 py-24">

        <div class="max-w-7xl mx-auto px-4">

            <div class="text-center text-white">

                <span class="bg-white/20 px-4 py-2 rounded-full text-sm">
                    Layanan SelebetExpress
                </span>

                <h1 class="mt-6 text-5xl font-bold">

                    Pilihan Layanan
                    Pengiriman Terbaik

                </h1>

                <p class="mt-6 text-lg text-blue-100 max-w-3xl mx-auto leading-relaxed">

                    Kami menyediakan berbagai layanan pengiriman
                    untuk kebutuhan personal maupun bisnis
                    dengan sistem tracking realtime modern.

                </p>

            </div>

        </div>

    </section>

    {{-- SERVICES --}}
    <section class="py-24 bg-gray-50">

        <div class="max-w-7xl mx-auto px-4">

            <div class="grid lg:grid-cols-3 gap-8">

                {{-- REGULER --}}
                <div class="bg-white rounded-3xl p-10 shadow-sm border hover:shadow-xl transition duration-300">

                    <div class="w-20 h-20 bg-blue-100 rounded-3xl flex items-center justify-center text-blue-600">

                        <i class='bx bx-package text-5xl'></i>

                    </div>

                    <h1 class="text-3xl font-bold mt-8">
                        Reguler
                    </h1>

                    <p class="mt-5 text-gray-600 leading-relaxed">

                        Layanan pengiriman standar
                        dengan harga ekonomis untuk
                        kebutuhan sehari-hari.

                    </p>

                    <div class="mt-8 space-y-4">

                        <div class="flex items-center gap-3">

                            <i class='bx bx-check-circle text-green-500 text-xl'></i>

                            <p>Harga lebih hemat</p>

                        </div>

                        <div class="flex items-center gap-3">

                            <i class='bx bx-check-circle text-green-500 text-xl'></i>

                            <p>Tracking realtime</p>

                        </div>

                        <div class="flex items-center gap-3">

                            <i class='bx bx-check-circle text-green-500 text-xl'></i>

                            <p>Cocok untuk paket umum</p>

                        </div>

                    </div>

                    <div class="mt-10">

                        <div class="bg-gray-100 rounded-2xl px-5 py-4">

                            <p class="text-gray-500 text-sm">
                                Estimasi Pengiriman
                            </p>

                            <h1 class="text-2xl font-bold mt-1">
                                2 - 5 Hari
                            </h1>

                        </div>

                    </div>

                </div>

                {{-- EXPRESS --}}
                <div class="bg-blue-600 text-white rounded-3xl p-10 shadow-xl relative overflow-hidden">

                    {{-- BADGE --}}
                    <div class="absolute top-5 right-5 bg-white text-blue-600 text-sm font-semibold px-4 py-2 rounded-full">

                        POPULER

                    </div>

                    <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center text-white">

                        <i class='bx bx-bolt-circle text-5xl'></i>

                    </div>

                    <h1 class="text-3xl font-bold mt-8">
                        Express
                    </h1>

                    <p class="mt-5 text-blue-100 leading-relaxed">

                        Pengiriman prioritas tinggi
                        dengan waktu lebih cepat
                        dan penanganan khusus.

                    </p>

                    <div class="mt-8 space-y-4">

                        <div class="flex items-center gap-3">

                            <i class='bx bx-check-circle text-xl'></i>

                            <p>Prioritas pengiriman</p>

                        </div>

                        <div class="flex items-center gap-3">

                            <i class='bx bx-check-circle text-xl'></i>

                            <p>Tracking realtime</p>

                        </div>

                        <div class="flex items-center gap-3">

                            <i class='bx bx-check-circle text-xl'></i>

                            <p>Pengiriman super cepat</p>

                        </div>

                    </div>

                    <div class="mt-10">

                        <div class="bg-white/10 rounded-2xl px-5 py-4">

                            <p class="text-blue-100 text-sm">
                                Estimasi Pengiriman
                            </p>

                            <h1 class="text-2xl font-bold mt-1">
                                1 - 2 Hari
                            </h1>

                        </div>

                    </div>

                </div>

                {{-- CARGO --}}
                <div class="bg-white rounded-3xl p-10 shadow-sm border hover:shadow-xl transition duration-300">

                    <div class="w-20 h-20 bg-orange-100 rounded-3xl flex items-center justify-center text-orange-600">

                        <i class='bx bx-package text-5xl'></i>

                    </div>

                    <h1 class="text-3xl font-bold mt-8">
                        Cargo
                    </h1>

                    <p class="mt-5 text-gray-600 leading-relaxed">

                        Pengiriman barang besar,
                        berat, dan kebutuhan
                        logistik skala besar.

                    </p>

                    <div class="mt-8 space-y-4">

                        <div class="flex items-center gap-3">

                            <i class='bx bx-check-circle text-green-500 text-xl'></i>

                            <p>Kapasitas besar</p>

                        </div>

                        <div class="flex items-center gap-3">

                            <i class='bx bx-check-circle text-green-500 text-xl'></i>

                            <p>Harga kompetitif</p>

                        </div>

                        <div class="flex items-center gap-3">

                            <i class='bx bx-check-circle text-green-500 text-xl'></i>

                            <p>Cocok untuk bisnis</p>

                        </div>

                    </div>

                    <div class="mt-10">

                        <div class="bg-gray-100 rounded-2xl px-5 py-4">

                            <p class="text-gray-500 text-sm">
                                Estimasi Pengiriman
                            </p>

                            <h1 class="text-2xl font-bold mt-1">
                                3 - 7 Hari
                            </h1>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- CTA --}}
    <section class="py-24 bg-white">

        <div class="max-w-5xl mx-auto px-4">

            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl p-12 text-center text-white">

                <h1 class="text-4xl font-bold">

                    Siap Mengirim Paket Anda?

                </h1>

                <p class="mt-5 text-blue-100 text-lg max-w-2xl mx-auto">

                    Gunakan layanan SelebetExpress
                    untuk pengalaman pengiriman cepat,
                    aman, dan terpercaya.

                </p>

                <div class="mt-10 flex flex-wrap justify-center gap-4">

                    <a href="/tracking"
                        class="bg-white text-blue-700 font-semibold px-7 py-4 rounded-2xl hover:bg-gray-100 transition">

                        Lacak Paket

                    </a>

                    <a href="/kontak-kami"
                        class="border border-white/30 px-7 py-4 rounded-2xl hover:bg-white/10 transition">

                        Hubungi Kami

                    </a>

                </div>

            </div>

        </div>

    </section>

@endsection