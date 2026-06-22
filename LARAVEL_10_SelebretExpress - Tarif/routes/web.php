<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Route untuk tampilan (view / blade)
|
*/

// Halaman utama (default Laravel welcome)
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Layanan Tarif sudah berjalan',
    ], 200);
});