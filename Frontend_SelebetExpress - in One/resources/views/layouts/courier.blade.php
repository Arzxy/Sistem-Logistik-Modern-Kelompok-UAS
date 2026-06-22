<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Courier App</title>

    @vite('resources/css/app.css')

    {{-- BOXICONS --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-gray-100">

    {{-- MOBILE CONTAINER --}}
    <div class="max-w-md mx-auto min-h-screen bg-gray-100 pb-28">

        {{-- TOPBAR --}}
        <div class="bg-white border-b sticky top-0 z-50">

            <div class="px-5 py-4 flex items-center justify-between">

                <div>
                    <h1 class="font-bold text-lg text-gray-800">SelebetExpress</h1>
                    <p class="text-xs text-gray-500">Courier App</p>
                </div>

                {{-- Avatar → ke halaman profile --}}
                <a href="/courier/profile"
                    class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 transition">
                    <i class='bx bx-user text-xl'></i>
                </a>

            </div>

        </div>

        {{-- CONTENT --}}
        <div class="p-4">

            @yield('content')

        </div>

    </div>

    {{-- BOTTOM NAVIGATION --}}
    <div class="fixed bottom-0 left-0 right-0 z-50">

        <div class="max-w-md mx-auto bg-white border-t shadow-lg">

            <div class="grid grid-cols-3">

                {{-- PAKET (aktif) --}}
                <a href="/courier/packages"
                    class="flex flex-col items-center justify-center py-3 transition
                        {{ request()->is('courier/packages*') && !request()->is('courier/history*') ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
                    <i class='bx bx-package text-2xl'></i>
                    <span class="text-xs mt-1">Paket</span>
                </a>

                {{-- RIWAYAT --}}
                <a href="/courier/history"
                    class="flex flex-col items-center justify-center py-3 transition
                        {{ request()->is('courier/history*') ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
                    <i class='bx bx-history text-2xl'></i>
                    <span class="text-xs mt-1">Riwayat</span>
                </a>

                {{-- PROFIL --}}
                <a href="/courier/profile"
                    class="flex flex-col items-center justify-center py-3 transition
                        {{ request()->is('courier/profile*') ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
                    <i class='bx bx-user text-2xl'></i>
                    <span class="text-xs mt-1">Profil</span>
                </a>

            </div>

        </div>

    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            })
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 4000
            })
        </script>
    @endif

</body>

</html>