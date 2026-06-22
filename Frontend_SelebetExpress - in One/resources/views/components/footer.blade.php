<footer class="bg-white border-t mt-20">

    <div class="max-w-7xl mx-auto px-4 py-14">

        <div class="grid md:grid-cols-3 gap-10">

            {{-- Logo --}}
            <div>

                <h1 class="text-2xl font-bold text-blue-600">
                    SelebetExpress
                </h1>

                <p class="mt-4 text-gray-600 leading-relaxed">
                    Solusi layanan logistik modern dengan sistem
                    tracking realtime, pengiriman cepat,
                    dan keamanan paket terpercaya.
                </p>

            </div>

            {{-- Navigation --}}
            <div>

                <h1 class="font-bold text-lg">
                    Navigasi
                </h1>

                <div class="mt-4 flex flex-col gap-3 text-gray-600">

                    <a href="/" class="hover:text-blue-600 transition">
                        Beranda
                    </a>

                    <a href="/tentang-kami" class="hover:text-blue-600 transition">
                        Tentang Kami
                    </a>

                    <a href="/layanan" class="hover:text-blue-600 transition">
                        Layanan
                    </a>

                    <a href="/tracking" class="hover:text-blue-600 transition">
                        Lacak Pengiriman
                    </a>

                    <a href="/status" class="hover:text-blue-600 transition">
                        Status Server
                    </a>

                </div>

            </div>

            {{-- Contact --}}
            <div>

                <h1 class="font-bold text-lg">
                    Kontak Kami
                </h1>

                <div class="mt-4 space-y-4 text-gray-600">

                    {{-- ADDRESS --}}
                    <div class="flex items-start gap-3">

                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">

                            <i class='bx bx-map'></i>

                        </div>

                        <div>

                            <h1 class="font-medium text-gray-800">
                                Alamat
                            </h1>

                            <p class="text-sm text-gray-500 mt-1">

                            </p>PERUM GRIYA MUKTI Purwakarta, Indonesia

                        </div>

                    </div>

                    {{-- PHONE --}}
                    <div class="flex items-start gap-3">

                        <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">

                            <i class='bx bx-phone'></i>

                        </div>

                        <div>

                            <h1 class="font-medium text-gray-800">
                                Telepon
                            </h1>

                            <p class="text-sm text-gray-500 mt-1">
                                +62 812-3456-7890
                            </p>

                        </div>

                    </div>

                    {{-- EMAIL --}}
                    <div class="flex items-start gap-3">

                        <div
                            class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">

                            <i class='bx bx-envelope'></i>

                        </div>

                        <div>

                            <h1 class="font-medium text-gray-800">
                                Email
                            </h1>

                            <p class="text-sm text-gray-500 mt-1">
                                support@selebetexpress.com
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Bottom Footer --}}
        <div class="border-t mt-10 pt-6 text-center text-gray-500 text-sm">

            © {{ date('Y') }} SelebetExpress.
            All rights reserved.

        </div>

    </div>

</footer>