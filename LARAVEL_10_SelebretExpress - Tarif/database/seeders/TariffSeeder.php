<?php
namespace Database\Seeders;

use App\Models\Tariff;
use Illuminate\Database\Seeder;

class TariffSeeder extends Seeder
{
    public function run(): void
	{
		$cities = [

			// Pulau Jawa
			'Jakarta',
			'Bandung',
			'Bekasi',
			'Bogor',
			'Depok',
			'Tangerang',
			'Serang',
			'Cirebon',
			'Semarang',
			'Solo',
			'Yogyakarta',
			'Malang',
			'Surabaya',

			// Sumatera
			'Medan',
			'Padang',
			'Pekanbaru',
			'Batam',
			'Palembang',
			'Jambi',
			'Bengkulu',
			'Lampung',
			'Banda Aceh',

			// Kalimantan
			'Pontianak',
			'Palangkaraya',
			'Banjarmasin',
			'Samarinda',
			'Balikpapan',
			'Tarakan',

			// Sulawesi
			'Makassar',
			'Manado',
			'Palu',
			'Kendari',
			'Gorontalo',

			// Bali & Nusa Tenggara
			'Denpasar',
			'Mataram',
			'Kupang',

			// Papua & Maluku
			'Ambon',
			'Jayapura',
			'Sorong',
		];

		$tariffs = [];

		foreach ($cities as $origin) {

			foreach ($cities as $destination) {

				// Skip kalau kota sama
				if ($origin === $destination) {
					continue;
				}

				// Default tarif dasar
				$price = rand(10000, 35000);

				// Estimasi hari berdasarkan harga
				if ($price <= 15000) {
					$days = 1;
				} elseif ($price <= 22000) {
					$days = 2;
				} elseif ($price <= 28000) {
					$days = 3;
				} else {
					$days = 4;
				}

				$tariffs[] = [
					'origin_city'    => $origin,
					'dest_city'      => $destination,
					'price_per_kg'   => $price,
					'min_weight_kg'  => 1.00,
					'estimated_days' => $days,
					'is_active'      => true,
					'created_at'     => now(),
					'updated_at'     => now(),
				];
			}
		}

		Tariff::insert($tariffs);
	}
}