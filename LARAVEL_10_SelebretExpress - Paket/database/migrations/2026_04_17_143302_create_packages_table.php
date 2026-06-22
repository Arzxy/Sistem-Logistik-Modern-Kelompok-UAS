<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
	{
		Schema::create('packages', function (Blueprint $table) {

			$table->id();

			// ── Nomor Resi ───────────────────────────────────────
			$table->string('resi_number', 20)->unique();

			// ── Pengirim & Penerima (L1) ─────────────────────────
			$table->unsignedBigInteger('sender_id');
			$table->unsignedBigInteger('receiver_id');

			// ── Gudang ───────────────────────────────────────────
			$table->unsignedBigInteger('origin_warehouse_id');
			$table->unsignedBigInteger('dest_warehouse_id');

			// ── Alamat ───────────────────────────────────────────
			$table->text('alamat_tujuan')->nullable();

			// ── Detail Paket ─────────────────────────────────────
			$table->decimal('weight_kg', 8, 2);

			$table->decimal('length_cm', 8, 2)->nullable();
			$table->decimal('width_cm', 8, 2)->nullable();
			$table->decimal('height_cm', 8, 2)->nullable();

			// volume = (p × l × t) / 6000
			$table->decimal('volume_weight_kg', 8, 2)->nullable();

			$table->text('description')->nullable();

			// ── Harga & Layanan ─────────────────────────────────
			$table->decimal('total_price', 12, 2)
				  ->default(0);

			// reguler | express | cargo
			$table->string('service_type', 20)
				  ->default('reguler');

			// ── Status Paket ────────────────────────────────────
			$table->enum('status', [

				// paket baru dibuat
				'pending_pickup',

				// sudah dijemput kurir
				'picked_up',

				// sudah sampai gudang asal
				'at_origin_warehouse',

				// sudah mendapat armada/pengiriman
				'assigned',

				// sedang perjalanan antar kota
				'in_transit',

				// sudah sampai gudang tujuan
				'at_destination_warehouse',

				// sedang diantar ke penerima
				'out_for_delivery',

				// selesai diterima
				'delivered',

				// dibatalkan
				'cancelled',

				// dikembalikan
				'returned',

			])->default('pending_pickup');

			// ── Relasi ke Armada (L4) ───────────────────────────
			$table->unsignedBigInteger('courier_id')->nullable();
			$table->unsignedBigInteger('delivery_id')->nullable();

			// ── Audit ───────────────────────────────────────────
			$table->unsignedBigInteger('created_by');

			$table->timestamps();

			// ── Index ───────────────────────────────────────────
			$table->index('status');
			$table->index('sender_id');
			$table->index('receiver_id');
			$table->index('courier_id');
			$table->index('created_at');
		});
	}

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};