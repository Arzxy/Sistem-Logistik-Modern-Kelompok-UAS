<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - SelebetExpress</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- BOXICONS --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body
    class="bg-gray-100 min-h-screen flex items-center justify-center px-5 py-5 relative overflow-y-auto overflow-x-hidden">

    {{-- BACKGROUND --}}
    <div class="absolute inset-0 -z-10 overflow-hidden">

        {{-- BLUR --}}
        <div
            class="absolute top-[-100px] left-[-100px] w-[300px] h-[300px] bg-blue-300 rounded-full blur-3xl opacity-30">
        </div>

        <div
            class="absolute bottom-[-120px] right-[-120px] w-[350px] h-[350px] bg-blue-500 rounded-full blur-3xl opacity-20">
        </div>

        {{-- GRID --}}
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#dbeafe_1px,transparent_1px),linear-gradient(to_bottom,#dbeafe_1px,transparent_1px)] bg-[size:40px_40px] opacity-30">
        </div>

    </div>

    {{-- CONTAINER --}}
    <div
        class="max-w-4xl w-full grid lg:grid-cols-2 bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-white/40">

        {{-- LEFT --}}
        <div class="hidden lg:flex bg-gradient-to-br from-blue-600 to-blue-800 p-9 text-white flex-col justify-between">

            <div>

                <h1 class="text-3xl font-bold leading-tight">

                    SelebetExpress
                    Internal System

                </h1>

                <p class="mt-5 text-blue-100 leading-relaxed text-sm">

                    Sistem internal logistik modern
                    untuk pengelolaan pengiriman,
                    tracking, armada, dan distribusi paket.

                </p>

            </div>

            <div class="space-y-4">

                {{-- ITEM --}}
                <div class="flex items-center gap-4">

                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">

                        <i class='bx bx-package text-xl'></i>

                    </div>

                    <p class="text-sm">
                        Monitoring Paket Realtime
                    </p>

                </div>

                {{-- ITEM --}}
                <div class="flex items-center gap-4">

                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">

                        <i class='bx bx-car text-xl'></i>

                    </div>

                    <p class="text-sm">
                        Manajemen Armada & Kurir
                    </p>

                </div>

                {{-- ITEM --}}
                <div class="flex items-center gap-4">

                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">

                        <i class='bx bx-shield-quarter text-xl'></i>

                    </div>

                    <p class="text-sm">
                        Sistem Aman & Terintegrasi
                    </p>

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="p-7 md:p-9 flex items-center">

            <div class="max-w-sm mx-auto w-full">

                {{-- HEADER --}}
                <div class="text-center">

                    <div
                        class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto shadow-sm text-blue-600">

                        <i class='bx bx-lock-alt text-3xl'></i>

                    </div>

                    <h1 class="text-3xl font-bold mt-5">
                        Login Internal
                    </h1>

                    <p class="text-gray-500 mt-3 text-sm leading-relaxed">

                        Masuk untuk mengakses dashboard
                        SelebetExpress

                    </p>

                </div>

                {{-- FORM --}}
                <form action="{{ route('login.process') }}" method="POST" class="mt-8 space-y-5">

                    @csrf

                    {{-- PHONE --}}
                    <div>

                        <label class="text-sm font-medium text-gray-700">
                            Nomor Telepon
                        </label>

                        <div class="relative mt-2">

                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">

                                <i class='bx bx-phone text-xl'></i>

                            </div>

                            <input type="text" name="phone" placeholder="Masukkan nomor telepon"
                                class="w-full border border-gray-300 rounded-2xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">

                        </div>

                    </div>

                    {{-- PASSWORD --}}
                    <div>

                        <label class="text-sm font-medium text-gray-700">
                            Password
                        </label>

                        <div class="relative mt-2">

                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">

                                <i class='bx bx-lock-alt text-xl'></i>

                            </div>

                            <input type="password" name="password" placeholder="Masukkan password"
                                class="w-full border border-gray-300 rounded-2xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">

                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 transition text-white py-3 rounded-2xl font-semibold shadow-lg shadow-blue-100">

                        Login

                    </button>

                </form>

                {{-- BACK --}}
                <div class="text-center mt-6">

                    <a href="/" class="text-blue-600 hover:underline text-sm">

                        ← Kembali ke Beranda

                    </a>

                </div>

            </div>

        </div>

    </div>

    {{-- TOAST --}}
    @if(session('error'))

        <script>

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000
            })

        </script>

    @endif

</body>

</html>