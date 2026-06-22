<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();

            // FK ke database pengguna
            // Tidak menggunakan foreign key karena beda database/service
            $table->unsignedBigInteger('user_id')->unique();

            // ID warehouse asal / tujuan kurir
            $table->unsignedBigInteger('warehouse_id');

            // Data kurir
            $table->string('name', 100)->nullable();
            $table->string('phone', 20)->nullable();

            // Jenis kendaraan
            // motor | mobil | truck
            $table->string('vehicle_type', 30)
                  ->default('motor');

            // Plat kendaraan
            $table->string('vehicle_plate', 15)->nullable();

            // Status kurir
            // available = siap antar
            // on_duty  = sedang mengantar
            // off_duty = tidak aktif
            $table->enum('status', [
                'available',
                'on_duty',
                'off_duty'
            ])->default('available');

            // Terakhir aktif
            $table->timestamp('last_active_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexing
            |--------------------------------------------------------------------------
            */
            $table->index('status');
            $table->index('warehouse_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};