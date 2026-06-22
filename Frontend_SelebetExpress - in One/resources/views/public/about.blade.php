@extends('layouts.public')

@section('content')

    {{-- HERO --}}
    <section class="bg-gradient-to-br from-blue-600 to-blue-800 py-24">

        <div class="max-w-7xl mx-auto px-4">

            <div class="text-center text-white">

                <span class="bg-white/20 px-4 py-2 rounded-full text-sm">
                    Tentang SelebetExpress
                </span>

                <h1 class="mt-6 text-5xl font-bold leading-tight">

                    Solusi Logistik Modern
                    untuk Pengiriman Cepat
                    & Aman

                </h1>

                <p class="mt-6 text-lg text-blue-100 max-w-3xl mx-auto leading-relaxed">

                    SelebetExpress hadir sebagai platform logistik berbasis
                    distributed system yang membantu proses pengiriman paket
                    secara cepat, aman, transparan, dan realtime tracking.

                </p>

            </div>

        </div>

    </section>

    {{-- ABOUT --}}
    <section class="py-24 bg-gray-50">

        <div class="max-w-7xl mx-auto px-4">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                {{-- IMAGE --}}
                <div>

                    <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1200&auto=format&fit=crop"
                        class="rounded-3xl shadow-xl w-full object-cover" style="height: 500px;">

                </div>

                {{-- TEXT --}}
                <div>

                    <span class="text-blue-600 font-semibold">
                        Siapa Kami?
                    </span>

                    <h1 class="text-4xl font-bold mt-4 leading-tight">

                        Membantu Pengiriman
                        Paket Menjadi Lebih
                        Cepat & Efisien

                    </h1>

                    <p class="mt-6 text-gray-600 leading-relaxed text-lg">

                        SelebetExpress dibangun untuk memberikan
                        pengalaman pengiriman modern dengan sistem
                        tracking realtime dan distribusi cepat.

                    </p>

                    <p class="mt-4 text-gray-600 leading-relaxed text-lg">

                        Dengan dukungan teknologi microservices
                        dan distributed system, kami mampu
                        mengelola pengiriman secara efisien
                        dan transparan.

                    </p>

                    {{-- STATS --}}
                    <div class="grid grid-cols-2 gap-6 mt-10">

                        <div class="bg-white rounded-2xl p-6 shadow-sm border">

                            <h1 class="text-3xl font-bold text-blue-600">
                                10K+
                            </h1>

                            <p class="text-gray-500 mt-2">
                                Paket Terkirim
                            </p>

                        </div>

                        <div class="bg-white rounded-2xl p-6 shadow-sm border">

                            <h1 class="text-3xl font-bold text-blue-600">
                                25+
                            </h1>

                            <p class="text-gray-500 mt-2">
                                Kota Operasional
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- VISI MISI --}}
    <section class="py-20 bg-white">

        <div class="max-w-5xl mx-auto px-4">

            <div class="text-center">

                <h1 class="text-4xl font-bold">
                    Visi & Misi
                </h1>

                <p class="text-gray-500 mt-4">
                    Komitmen kami dalam memberikan layanan terbaik.
                </p>

            </div>

            <div class="grid md:grid-cols-2 gap-6 mt-12">

                {{-- VISI --}}
                <div class="bg-gray-50 rounded-3xl p-8 border shadow-sm">

                    <div class="flex items-center gap-4">

                        <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600">

                            <i class='bx bx-target-lock text-3xl'></i>

                        </div>

                        <h1 class="text-2xl font-bold">
                            Visi
                        </h1>

                    </div>

                    <p class="mt-6 text-gray-600 leading-relaxed">

                        Menjadi layanan logistik modern terpercaya
                        dengan pengiriman cepat, aman,
                        dan transparan di seluruh Indonesia.

                    </p>

                </div>

                {{-- MISI --}}
                <div class="bg-gray-50 rounded-3xl p-8 border shadow-sm">

                    <div class="flex items-center gap-4">

                        <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-green-600">

                            <i class='bx bx-trip text-3xl'></i>

                        </div>

                        <h1 class="text-2xl font-bold">
                            Misi
                        </h1>

                    </div>

                    <ul class="mt-6 space-y-3 text-gray-600">

                        <li>
                            • Memberikan pengiriman cepat & aman.
                        </li>

                        <li>
                            • Mengembangkan tracking realtime modern.
                        </li>

                        <li>
                            • Menjaga kualitas layanan pelanggan.
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </section>

@endsection