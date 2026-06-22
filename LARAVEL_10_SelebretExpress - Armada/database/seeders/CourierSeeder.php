<?php
namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    public function run(): void
	{
		// user_id harus sesuai dengan ID user di database pengguna (L1)
		// Pastikan role kurir sudah ada

		$couriers = [
			[
				'user_id' => 5,
				'warehouse_id' => 1,
				'name' => 'Dodi Kuswara',
				'phone' => '5',
				'vehicle_type' => 'motor',
				'vehicle_plate' => 'B 5678 ABC',
				'status' => 'available',
			],
			[
				'user_id' => 6,
				'warehouse_id' => 1,
				'name' => 'Dandi Santoloyo',
				'phone' => '6',
				'vehicle_type' => 'mobil',
				'vehicle_plate' => 'B 9012 DEF',
				'status' => 'available',
			],
			[
				'user_id' => 7,
				'warehouse_id' => 2,
				'name' => 'Eko Prasetyo',
				'phone' => '7',
				'vehicle_type' => 'motor',
				'vehicle_plate' => 'D 1953 GSP',
				'status' => 'available',
			],
			[
				'user_id' => 8,
				'warehouse_id' => 2,
				'name' => 'Susakoyonto',
				'phone' => '8',
				'vehicle_type' => 'mobil',
				'vehicle_plate' => 'D 1194 OXE',
				'status' => 'available',
			],
		];

		foreach ($couriers as $courier) {
			Courier::create($courier);
		}
	}
}