<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
	{
		/*
		|--------------------------------------------------------------------------
		| Users
		|--------------------------------------------------------------------------
		| Menyimpan seluruh data pengguna sistem:
		| - admin
		| - kasir
		| - agen
		| - kurir
		| - pengirim
		| - penerima
		|--------------------------------------------------------------------------
		*/

		Schema::create('users', function (Blueprint $table) {

			$table->id();

			/*
			|--------------------------------------------------------------------------
			| Informasi Pengguna
			|--------------------------------------------------------------------------
			*/
			$table->string('name', 100);

			$table->string('phone', 20)
				  ->unique();

			$table->string('email', 100)
				  ->nullable()
				  ->unique();

			/*
			|--------------------------------------------------------------------------
			| Authentication
			|--------------------------------------------------------------------------
			| Password hanya digunakan untuk role yang dapat login
			*/
			$table->string('password')
				  ->nullable();

			/*
			|--------------------------------------------------------------------------
			| Role User
			|--------------------------------------------------------------------------
			*/
			$table->enum('role', [
				'admin',
				'kasir',
				'agen',
				'kurir',
				'pengirim',
				'penerima',
			]);

			/*
			|--------------------------------------------------------------------------
			| Alamat
			|--------------------------------------------------------------------------
			*/
			$table->string('address')
				  ->nullable();

			$table->string('city', 60);

			/*
			|--------------------------------------------------------------------------
			| Status User
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
			$table->index('role');

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
        Schema::dropIfExists('users');
    }
};