<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
	{
		/*
		|--------------------------------------------------------------------------
		| Package Summaries
		|--------------------------------------------------------------------------
		| Menyimpan status TERKINI tiap paket
		| Agar proses tracking tidak perlu scan seluruh histori log
		|--------------------------------------------------------------------------
		*/

		Schema::create('package_summaries', function (Blueprint $table) {

			$table->id();

			/*
			|--------------------------------------------------------------------------
			| Relasi Paket
			|--------------------------------------------------------------------------
			| Satu paket hanya memiliki satu summary
			*/
			$table->unsignedBigInteger('package_id')
				  ->unique();

			/*
			|--------------------------------------------------------------------------
			| Cache Tracking
			|--------------------------------------------------------------------------
			| Disimpan untuk mempercepat pencarian tracking berdasarkan resi
			*/
			$table->string('resi_number', 30)
				  ->unique();

			/*
			|--------------------------------------------------------------------------
			| Status Tracking Terakhir
			|--------------------------------------------------------------------------
			*/
			$table->string('last_status', 100);

			$table->string('last_location', 150)
				  ->nullable();

			$table->timestamp('last_updated');

			$table->timestamps();

			/*
			|--------------------------------------------------------------------------
			| Indexing
			|--------------------------------------------------------------------------
			*/
			$table->index('resi_number');
		});
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_summaries');
    }
};