<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
	{
		/*
		|--------------------------------------------------------------------------
		| Tracking Logs
		|--------------------------------------------------------------------------
		| Menyimpan histori perjalanan/status paket
		| Data berasal dari berbagai service:
		| - L1 = Pengguna
		| - L2 = Paket
		| - L4 = Armada
		|--------------------------------------------------------------------------
		*/

		Schema::create('tracking_logs', function (Blueprint $table) {

			$table->id();

			/*
			|--------------------------------------------------------------------------
			| Relasi Antar Service
			|--------------------------------------------------------------------------
			| Tidak memakai foreign key karena beda database/service
			*/
			$table->unsignedBigInteger('package_id');

			$table->unsignedBigInteger('courier_id')
				  ->nullable();

			$table->unsignedBigInteger('warehouse_id')
				  ->nullable();

			/*
			|--------------------------------------------------------------------------
			| Informasi Tracking
			|--------------------------------------------------------------------------
			*/
			$table->string('status', 100);

			// lokasi kejadian
			$table->string('location', 150)
				  ->nullable();

			// catatan tambahan tracking
			$table->text('notes')
				  ->nullable();

			/*
			|--------------------------------------------------------------------------
			| Source Service
			|--------------------------------------------------------------------------
			| contoh:
			| L1 = pengguna
			| L2 = paket
			| L4 = armada
			*/
			$table->string('source_service', 10)
				  ->default('L2');

			/*
			|--------------------------------------------------------------------------
			| Waktu Kejadian Asli
			|--------------------------------------------------------------------------
			*/
			$table->timestamp('logged_at');

			$table->timestamps();

			/*
			|--------------------------------------------------------------------------
			| Indexing
			|--------------------------------------------------------------------------
			*/

			// pencarian histori per paket
			$table->index('package_id');

			// optimasi sorting histori tracking
			$table->index([
				'package_id',
				'logged_at'
			]);

			// filter status
			$table->index('status');

			// tracking berdasarkan kurir
			$table->index('courier_id');

			// tracking berdasarkan warehouse
			$table->index('warehouse_id');
		});
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracking_logs');
    }
};