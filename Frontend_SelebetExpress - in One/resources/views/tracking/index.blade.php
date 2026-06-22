@extends('layouts.public')

@section('content')

  {{-- HERO --}}
  <section class="bg-gradient-to-br from-blue-600 to-blue-800 py-20">

    <div class="max-w-7xl mx-auto px-4">

      <div class="text-center text-white">

        <span class="bg-white/20 px-4 py-2 rounded-full text-sm">
          Tracking Paket Realtime
        </span>

        <h1 class="mt-6 text-4xl md:text-5xl font-bold leading-tight">

          Lacak Paket Anda
          dengan Mudah

        </h1>

        <p class="mt-5 text-base md:text-lg text-blue-100 max-w-2xl mx-auto leading-relaxed">

          Pantau perjalanan dan status paket Anda
          secara realtime melalui sistem tracking
          modern SelebetExpress.

        </p>

      </div>

    </div>

  </section>

  {{-- TRACKING FORM --}}
  <section class="-mt-10 relative z-20">

    <div class="max-w-4xl mx-auto px-4">

      <div class="bg-white rounded-3xl shadow-xl border p-8 md:p-10">

        <div class="text-center">

          <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mx-auto">

            <i class='bx bx-package text-4xl'></i>

          </div>

          <h1 class="text-3xl font-bold mt-5">
            Lacak Pengiriman
          </h1>

          <p class="text-gray-500 mt-3">
            Masukkan nomor resi untuk melihat status paket terbaru.
          </p>

        </div>

        {{-- FORM --}}
        <form action="{{ route('tracking.track') }}" method="POST" class="mt-8">

          @csrf

          <div class="flex flex-col md:flex-row gap-3">

            <input type="text" name="resi" placeholder="Contoh : EKS20260523ABC123"
              class="w-full border border-gray-300 rounded-2xl px-5 py-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <button
              class="bg-blue-600 hover:bg-blue-700 transition text-white px-7 py-3.5 rounded-2xl font-semibold whitespace-nowrap">

              Lacak

            </button>

          </div>

        </form>

        {{-- MINI INFO --}}
        <div class="grid md:grid-cols-3 gap-5 mt-10">

          {{-- CARD --}}
          <div class="bg-gray-50 rounded-2xl p-5 border">

            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">

              <i class='bx bx-bolt-circle text-2xl'></i>

            </div>

            <h1 class="text-lg font-bold mt-4">
              Realtime
            </h1>

            <p class="text-gray-600 mt-2 text-sm leading-relaxed">

              Pantau status paket secara realtime.

            </p>

          </div>

          {{-- CARD --}}
          <div class="bg-gray-50 rounded-2xl p-5 border">

            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">

              <i class='bx bx-shield-quarter text-2xl'></i>

            </div>

            <h1 class="text-lg font-bold mt-4">
              Aman
            </h1>

            <p class="text-gray-600 mt-2 text-sm leading-relaxed">

              Riwayat pengiriman tersimpan aman.

            </p>

          </div>

          {{-- CARD --}}
          <div class="bg-gray-50 rounded-2xl p-5 border">

            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">

              <i class='bx bx-car text-2xl'></i>

            </div>

            <h1 class="text-lg font-bold mt-4">
              Cepat
            </h1>

            <p class="text-gray-600 mt-2 text-sm leading-relaxed">

              Pengiriman lebih cepat & efisien.

            </p>

          </div>

        </div>

      </div>

    </div>

  </section>

  {{-- STEPS --}}
  <section class="py-20 bg-gray-50">

    <div class="max-w-7xl mx-auto px-4">

      <div class="text-center">

        <h1 class="text-3xl font-bold">
          Cara Melacak Paket
        </h1>

        <p class="text-gray-500 mt-3">
          Tracking paket cepat dan mudah.
        </p>

      </div>

      <div class="grid md:grid-cols-3 gap-6 mt-14">

        {{-- STEP --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border text-center">

          <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mx-auto">

            <i class='bx bx-search-alt text-3xl'></i>

          </div>

          <h1 class="text-xl font-bold mt-5">
            Input Resi
          </h1>

          <p class="text-gray-600 mt-3 leading-relaxed">

            Masukkan nomor resi paket Anda.

          </p>

        </div>

        {{-- STEP --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border text-center">

          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-green-600 mx-auto">

            <i class='bx bx-loader-circle text-3xl'></i>

          </div>

          <h1 class="text-xl font-bold mt-5">
            Sistem Memproses
          </h1>

          <p class="text-gray-600 mt-3 leading-relaxed">

            Sistem mencari data tracking paket.

          </p>

        </div>

        {{-- STEP --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border text-center">

          <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 mx-auto">

            <i class='bx bx-map-pin text-3xl'></i>

          </div>

          <h1 class="text-xl font-bold mt-5">
            Lihat Status
          </h1>

          <p class="text-gray-600 mt-3 leading-relaxed">

            Pantau perjalanan paket realtime.

          </p>

        </div>

      </div>

    </div>

  </section>

@endsection