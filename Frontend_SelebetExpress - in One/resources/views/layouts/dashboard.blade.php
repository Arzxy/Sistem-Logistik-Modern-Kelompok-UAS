<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- BOXICONS --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        @include('components.sidebar')

        {{-- MAIN --}}
        <div class="flex-1 lg:ml-64">

            {{-- NAVBAR --}}
            @include('components.navbar-dashboard')

            {{-- CONTENT --}}
            <main class="p-5">

                @yield('content')

            </main>

        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- SIDEBAR SCRIPT --}}
    <script>

        const sidebar = document.getElementById('sidebar')
        const overlay = document.getElementById('sidebarOverlay')

        const openSidebar = document.getElementById('openSidebar')
        const closeSidebar = document.getElementById('closeSidebar')

        openSidebar.addEventListener('click', () => {

            sidebar.classList.remove('-translate-x-full')
            overlay.classList.remove('hidden')

        })

        closeSidebar.addEventListener('click', () => {

            sidebar.classList.add('-translate-x-full')
            overlay.classList.add('hidden')

        })

        overlay.addEventListener('click', () => {

            sidebar.classList.add('-translate-x-full')
            overlay.classList.add('hidden')

        })

    </script>

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