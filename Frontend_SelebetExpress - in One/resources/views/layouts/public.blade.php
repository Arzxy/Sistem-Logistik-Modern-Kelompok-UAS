<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>SelebetExpress</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  
  {{-- BOXICONS --}}
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-50 text-gray-800">

  @include('components.navbar')

  <main>
    @yield('content')
  </main>

  @include('components.footer')

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