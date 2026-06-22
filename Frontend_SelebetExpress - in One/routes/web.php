<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
     AuthController,
     TrackingController,
     AdminDashboardController,
     AdminPackageController,
     CourierPackageController,
     TariffController,
     UserController,
     WarehouseController,
     AgentDeliveryController,
     AgentDashboardController,
     KasirDashboardController,
     ServiceStatusController,
};


// ------------ public tampilan -------------

Route::get('/', fn() => view('public.home'));

Route::get('/tentang-kami', fn() => view('public.about'));

Route::get('/layanan', fn() => view('public.services'));

Route::get('/kontak-kami', fn() => view('public.contact'));

Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/tracking', [TrackingController::class, 'track'])->name('tracking.track');

Route::get('/status',     [ServiceStatusController::class, 'index'])->name('status.index');
Route::get('/status/api', [ServiceStatusController::class, 'api'])->name('status.api');

// ------------ ------------- -------------

// ------------ auth login tampilan -------------

Route::get('/login', [AuthController::class, 'index'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ------------ ------------- -------------

// ------------ admin  tampilan -------------

Route::middleware('auth.check')->group(function () {

     /*
     |--------------------------------------------------------------------------
     | PACKAGES
     |--------------------------------------------------------------------------
     */

     Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);

     Route::get('/admin/packages', [AdminPackageController::class, 'index']);

     Route::get('/admin/packages/create', [AdminPackageController::class, 'create']);

     Route::post('/admin/packages', [AdminPackageController::class, 'store']);

     Route::get('/admin/packages/check-user/{phone}', [AdminPackageController::class, 'checkUser']);

     Route::get('/admin/packages/calculate-shipping', [AdminPackageController::class, 'calculateShipping']);

     Route::get('/admin/packages/{id}', [AdminPackageController::class, 'show']);

     Route::post('/admin/packages/{id}/register-tracking', [AdminPackageController::class, 'registerTracking'])->name('packages.register-tracking');

     // Route::post('/admin/packages/{id}/assign-courier', [AdminPackageController::class, 'assignCourier']);

     // Route::post('/admin/packages/{id}/update-delivery', [AdminPackageController::class, 'updateDeliveryStatus']);

     /*
     |--------------------------------------------------------------------------
     | COURIER
     |--------------------------------------------------------------------------
     */

     Route::prefix('courier')->group(function () {

          // Deliveries (paket aktif)
          Route::get('/packages',                          [CourierPackageController::class, 'index']);
          Route::get('/packages/{id}',                    [CourierPackageController::class, 'show']);
          Route::post('/packages/{id}/update-status',     [CourierPackageController::class, 'updateStatus']);

          // History (paket selesai)
          Route::get('/history',                          [CourierPackageController::class, 'history'])->name('courier.history');

          // Profile
          Route::get('/profile',                          [CourierPackageController::class, 'profile'])->name('courier.profile');

          // Toggle status kurir (on_duty ↔ available)
          Route::post('/status',                          [CourierPackageController::class, 'updateCourierStatus'])->name('courier.status');

     });

     /*
     |--------------------------------------------------------------------------
     | TARIFF
     |--------------------------------------------------------------------------
     */

     Route::get('/admin/tariffs', [TariffController::class, 'index'])->name('tariffs.index');

     Route::get('/admin/tariffs/create', [TariffController::class, 'create'])->name('tariffs.create');

     Route::post('/admin/tariffs', [TariffController::class, 'store'])->name('tariffs.store');

     Route::get('/admin/tariffs/{id}', [TariffController::class, 'show'])->name('tariffs.show');

     Route::get('/admin/tariffs/{id}/edit', [TariffController::class, 'edit'])->name('tariffs.edit');

     Route::put('/admin/tariffs/{id}', [TariffController::class, 'update'])->name('tariffs.update');

     Route::delete('/admin/tariffs/{id}', [TariffController::class, 'destroy'])->name('tariffs.destroy');

     /*
     |--------------------------------------------------------------------------
     | USER
     |--------------------------------------------------------------------------
     */

     Route::resource('/admin/users', UserController::class);

     /*
     |--------------------------------------------------------------------------
     | WAREHOUSES
     |--------------------------------------------------------------------------
     */

     Route::resource('/admin/warehouses', WarehouseController::class);

     /*
     |--------------------------------------------------------------------------
     | AGENT DELIVERIES & DASHBOARD
     |--------------------------------------------------------------------------
     */

     /*
     |--------------------------------------------------------------------------
     | KASIR
     |--------------------------------------------------------------------------
     */

     Route::prefix('kasir')->group(function () {

          Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('kasir.dashboard');

          Route::get('/packages/history', [KasirDashboardController::class, 'history'])->name('kasir.packages.history');

     });

     /*
     |--------------------------------------------------------------------------
     | AGENT DELIVERIES & DASHBOARD
     |--------------------------------------------------------------------------
     */

     Route::prefix('agent')->group(function () {

          // Dashboard
          Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('agent.dashboard');

          // Riwayat paket
          Route::get('/packages/history', [AgentDashboardController::class, 'history'])->name('agent.packages.history');

          // Delivery management
          Route::get('/deliveries', [AgentDeliveryController::class, 'index'])->name('agent.deliveries.index');

          Route::post('/deliveries/assign', [AgentDeliveryController::class, 'assignCourier'])->name('agent.deliveries.assign');

          Route::get('/deliveries/{id}', [AgentDeliveryController::class, 'show'])->name('agent.deliveries.show');

          Route::post('/deliveries/{packageId}/mark-at-warehouse', [AgentDeliveryController::class, 'markAtWarehouse'])->name('agent.deliveries.mark-at-warehouse');

     });

});