@extends('layouts.public')

@section('content')

    {{-- HERO --}}
    <section class="bg-gradient-to-br from-blue-600 to-blue-800 py-20">

        <div class="max-w-7xl mx-auto px-4">

            <div class="text-center text-white">

                <span class="bg-white/20 px-4 py-2 rounded-full text-sm">
                    Hubungi SelebetExpress
                </span>

                <h1 class="mt-6 text-4xl md:text-5xl font-bold leading-tight">

                    Kami Siap Membantu
                    Kebutuhan Pengiriman Anda

                </h1>

                <p class="mt-5 text-blue-100 max-w-2xl mx-auto leading-relaxed">

                    Hubungi tim SelebetExpress untuk informasi layanan,
                    bantuan pengiriman, maupun pertanyaan lainnya.

                </p>

            </div>

        </div>

    </section>

    {{-- CONTACT CONTENT --}}
    <section class="-mt-10 relative z-20 pb-20">

        <div class="max-w-7xl mx-auto px-4">

            <div class="grid lg:grid-cols-3 gap-8">

                {{-- LEFT --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- EMAIL --}}
                    <div class="bg-white rounded-3xl p-7 shadow-sm border">

                        <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600">

                            <i class='bx bx-envelope text-3xl'></i>

                        </div>

                        <h1 class="text-2xl font-bold mt-5">
                            Email
                        </h1>

                        <p class="text-gray-500 mt-3">
                            Hubungi kami melalui email.
                        </p>

                        <p class="mt-5 font-semibold text-gray-800">
                            support@selebetexpress.com
                        </p>

                    </div>

                    {{-- PHONE --}}
                    <div class="bg-white rounded-3xl p-7 shadow-sm border">

                        <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-green-600">

                            <i class='bx bx-phone text-3xl'></i>

                        </div>

                        <h1 class="text-2xl font-bold mt-5">
                            Telepon
                        </h1>

                        <p class="text-gray-500 mt-3">
                            Layanan pelanggan aktif setiap hari.
                        </p>

                        <p class="mt-5 font-semibold text-gray-800">
                            +62 812-3456-7890
                        </p>

                    </div>

                    {{-- LOCATION --}}
                    <div class="bg-white rounded-3xl p-7 shadow-sm border">

                        <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600">

                            <i class='bx bx-map text-3xl'></i>

                        </div>

                        <h1 class="text-2xl font-bold mt-5">
                            Alamat
                        </h1>

                        <p class="text-gray-500 mt-3">
                            Kantor pusat SelebetExpress.
                        </p>

                        <p class="mt-5 font-semibold text-gray-800">
                            PERUM GRIYA MUKTI Purwakarta, Indonesia
                        </p>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="lg:col-span-2">

                    <div class="bg-white rounded-3xl shadow-sm border p-8 md:p-10">

                        <div>

                            <h1 class="text-3xl font-bold">
                                Kirim Pesan
                            </h1>

                            <p class="text-gray-500 mt-3">
                                Isi formulir berikut dan tim kami akan segera menghubungi Anda.
                            </p>

                        </div>

                        {{-- FORM --}}
                        <form onsubmit="sendMail(event)" class="mt-10 space-y-6">

                            {{-- ROW --}}
                            <div class="grid md:grid-cols-2 gap-6">

                                {{-- NAMA --}}
                                <div>

                                    <label class="font-medium text-sm text-gray-700">
                                        Nama Lengkap
                                    </label>

                                    <input type="text" id="name" placeholder="Masukkan nama lengkap"
                                        class="w-full mt-2 border border-gray-300 rounded-2xl px-5 py-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500">

                                </div>

                                {{-- EMAIL --}}
                                <div>

                                    <label class="font-medium text-sm text-gray-700">
                                        Email
                                    </label>

                                    <input type="email" id="email" placeholder="Masukkan email"
                                        class="w-full mt-2 border border-gray-300 rounded-2xl px-5 py-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500">

                                </div>

                            </div>

                            {{-- SUBJECT --}}
                            <div>

                                <label class="font-medium text-sm text-gray-700">
                                    Subjek
                                </label>

                                <input type="text" id="subject" placeholder="Masukkan subjek pesan"
                                    class="w-full mt-2 border border-gray-300 rounded-2xl px-5 py-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500">

                            </div>

                            {{-- MESSAGE --}}
                            <div>

                                <label class="font-medium text-sm text-gray-700">
                                    Pesan
                                </label>

                                <textarea rows="6" id="message" placeholder="Tulis pesan Anda..."
                                    class="w-full mt-2 border border-gray-300 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>

                            </div>

                            {{-- BUTTON --}}
                            <div>

                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 transition text-white px-8 py-3.5 rounded-2xl font-semibold">

                                    Kirim Pesan

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- SCRIPT --}}
    <script>

        function sendMail(event) {
            event.preventDefault();

            let name =
                document.getElementById('name').value;

            let email =
                document.getElementById('email').value;

            let subject =
                document.getElementById('subject').value;

            let message =
                document.getElementById('message').value;

            let body =
                `Nama: ${name}

    Email: ${email}

    Pesan:
    ${message}`;

            let gmailUrl =
                `https://mail.google.com/mail/?view=cm&fs=1&to=support@selebetexpress.com&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;

            window.open(
                gmailUrl,
                '_blank'
            );
        }

    </script>

@endsection