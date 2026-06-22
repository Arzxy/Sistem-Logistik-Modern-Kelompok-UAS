<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
	{
		/*
		|--------------------------------------------------------------------------
		| Tariffs
		|--------------------------------------------------------------------------
		| Menyimpan tarif pengiriman antar kota
		|--------------------------------------------------------------------------
		*/

		Schema::create('tariffs', function (Blueprint $table) {

			$table->id();

			/*
			|--------------------------------------------------------------------------
			| Rute Pengiriman
			|--------------------------------------------------------------------------
			*/
			$table->string('origin_city', 60);

			$table->string('dest_city', 60);

			/*
			|--------------------------------------------------------------------------
			| Tarif
			|--------------------------------------------------------------------------
			*/
			$table->decimal('price_per_kg', 10, 2);

			// minimal berat yang dikenakan tarif
			$table->decimal('min_weight_kg', 5, 2)
				  ->default(1.00);

			/*
			|--------------------------------------------------------------------------
			| Estimasi Pengiriman
			|--------------------------------------------------------------------------
			*/
			$table->tinyInteger('estimated_days')
				  ->default(1);

			/*
			|--------------------------------------------------------------------------
			| Status Tarif
			|--------------------------------------------------------------------------
			*/
			$table->boolean('is_active')
				  ->default(true);

			$table->timestamps();

			/*
			|--------------------------------------------------------------------------
			| Constraint
			|--------------------------------------------------------------------------
			| Kombinasi kota asal dan tujuan tidak boleh duplikat
			*/
			$table->unique([
				'origin_city',
				'dest_city'
			]);

			/*
			|--------------------------------------------------------------------------
			| Indexing
			|--------------------------------------------------------------------------
			*/
			$table->index('origin_city');

			$table->index('dest_city');

			$table->index('is_active');
		});
	}

    public function down(): void
    {
        Schema::dropIfExists('tariffs');
    }
};