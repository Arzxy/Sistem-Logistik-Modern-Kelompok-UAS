<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
	{
		/*
		|--------------------------------------------------------------------------
		| Warehouses
		|--------------------------------------------------------------------------
		| Menyimpan data gudang / agen distribusi
		|--------------------------------------------------------------------------
		*/

		Schema::create('warehouses', function (Blueprint $table) {

			$table->id();

			/*
			|--------------------------------------------------------------------------
			| Penanggung Jawab Gudang
			|--------------------------------------------------------------------------
			*/
			$table->foreignId('agent_id')
				  ->constrained('users')
				  ->restrictOnDelete();

			/*
			|--------------------------------------------------------------------------
			| Informasi Gudang
			|--------------------------------------------------------------------------
			*/
			$table->string('name', 100);

			$table->string('city', 60);

			$table->text('address');

			$table->string('phone', 20)
				  ->nullable();

			/*
			|--------------------------------------------------------------------------
			| Status Gudang
			|--------------------------------------------------------------------------
			*/
			$table->boolean('is_active')
				  ->default(true);

			$table->timestamps();

			/*
			|--------------------------------------------------------------------------
			| Indexing
			|--------------------------------------------------------------------------
			*/
			$table->index('agent_id');

			$table->index('city');

			$table->index('is_active');
		});
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
};