<?php

use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\ResiController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Layanan Paket sudah berjalan',
    ], 200);
});*/

Route::get('/', [ResiController::class, 'index'])->name('resi.index');
Route::post('/cetak', [ResiController::class, 'cetak'])->name('resi.cetak');

Route::get('/api/packages', [PackageController::class, 'index']);
