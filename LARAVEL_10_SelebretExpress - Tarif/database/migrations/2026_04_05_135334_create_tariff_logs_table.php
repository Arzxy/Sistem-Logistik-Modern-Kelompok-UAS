<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
	{
		/*
		|--------------------------------------------------------------------------
		| Tariff Logs
		|--------------------------------------------------------------------------
		| Menyimpan histori perubahan tarif pengiriman
		|--------------------------------------------------------------------------
		*/

		Schema::create('tariff_logs', function (Blueprint $table) {

			$table->id();

			/*
			|--------------------------------------------------------------------------
			| Relasi Tarif
			|--------------------------------------------------------------------------
			*/
			$table->foreignId('tariff_id')
				  ->constrained('tariffs')
				  ->cascadeOnDelete();

			/*
			|--------------------------------------------------------------------------
			| Perubahan Tarif
			|--------------------------------------------------------------------------
			*/
			$table->decimal('old_price', 10, 2);

			$table->decimal('new_price', 10, 2);

			/*
			|--------------------------------------------------------------------------
			| Audit Perubahan
			|--------------------------------------------------------------------------
			| changed_by berasal dari layanan pengguna (L1)
			*/
			$table->unsignedBigInteger('changed_by');

			$table->timestamp('changed_at');

			$table->timestamps();

			/*
			|--------------------------------------------------------------------------
			| Indexing
			|--------------------------------------------------------------------------
			*/
			$table->index('tariff_id');

			$table->index('changed_by');

			$table->index('changed_at');
		});
	}

    public function down(): void
    {
        Schema::dropIfExists('tariff_logs');
    }
};