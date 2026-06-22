<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relasi Paket
            |--------------------------------------------------------------------------
            | package_id berasal dari service/database paket (L2)
            | Tidak menggunakan foreign key karena beda database/service
            */
            $table->unsignedBigInteger('package_id');

            /*
            |--------------------------------------------------------------------------
            | Relasi Internal Armada
            |--------------------------------------------------------------------------
            */
            $table->foreignId('courier_id')
                  ->constrained('couriers')
                  ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Warehouse
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('origin_warehouse_id');
            $table->unsignedBigInteger('dest_warehouse_id');

            /*
            |--------------------------------------------------------------------------
            | Jenis Pengiriman
            |--------------------------------------------------------------------------
            */
            $table->enum('delivery_type', [
                'pickup',
                'inter_warehouse',
                'last_mile',
            ])->default('pickup');

            /*
            |--------------------------------------------------------------------------
            | Status Pengiriman
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'assigned',
                'picked_up',
                'in_transit',
                'out_for_delivery',
                'delivered',
                'failed',
                'returned',
            ])->default('assigned');

            /*
            |--------------------------------------------------------------------------
            | Lokasi & Catatan
            |--------------------------------------------------------------------------
            */
            $table->string('current_location', 150)->nullable();
            $table->text('notes')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamp Aktivitas
            |--------------------------------------------------------------------------
            */
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexing
            |--------------------------------------------------------------------------
            */
            $table->index('status');
            $table->index('courier_id');
            $table->index('origin_warehouse_id');
            $table->index('dest_warehouse_id');

            // Optimasi pencarian tracking
            $table->index(['courier_id', 'status']);

            // Optimasi warehouse tracking
            $table->index([
                'origin_warehouse_id',
                'dest_warehouse_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};