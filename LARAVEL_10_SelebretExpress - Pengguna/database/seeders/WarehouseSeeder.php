<?php
namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
	{
		$warehouses = [

			// ── Gudang Jakarta ──────────────────────────────────
			[
				// Budi Santoso (agen Jakarta)
				'agent_id' => 3,
				'name'     => 'Gudang Jakarta Barat',
				'city'     => 'Jakarta',
				'address'  => 'Jl. Mangga Besar No. 10, Jakarta Barat',
				'phone'    => '02155550001',
				'is_active' => true,
			],

			// ── Gudang Bandung ─────────────────────────────────
			[
				// Siti Rahayu (agen Bandung)
				'agent_id' => 4,
				'name'     => 'Gudang Bandung Tengah',
				'city'     => 'Bandung',
				'address'  => 'Jl. Braga No. 5, Bandung',
				'phone'    => '02255550002',
				'is_active' => true,
			],
		];

		foreach ($warehouses as $warehouse) {
			Warehouse::create($warehouse);
		}
	}
}