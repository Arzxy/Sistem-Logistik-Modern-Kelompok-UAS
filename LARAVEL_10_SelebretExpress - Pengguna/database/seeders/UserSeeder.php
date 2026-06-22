<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
	{
		$users = [

			// ── Admin ───────────────────────────────────────────
			[
				'name'      => 'Admin Ekspedisi',
				'phone'     => '1',
				'email'     => 'admin@ekspedisi.com',
				'password'  => Hash::make('admin123'),
				'role'      => 'admin',
				'city'      => 'Jakarta',
				'is_active' => true,
			],

			// ── Kasir ───────────────────────────────────────────
			[
				'name'      => 'Ani',
				'phone'     => '2',
				'email'     => 'kasir@ekspedisi.com',
				'password'  => Hash::make('kasir123'),
				'role'      => 'kasir',
				'city'      => 'Jakarta',
				'is_active' => true,
			],

			// ── Agen Jakarta ───────────────────────────────────
			[
				'name'      => 'Budi Santoso',
				'phone'     => '3',
				'password'  => Hash::make('agen123'),
				'role'      => 'agen',
				'city'      => 'Jakarta',
				'address'   => 'Jl. Mangga Besar No. 10',
				'is_active' => true,
			],

			// ── Agen Bandung ───────────────────────────────────
			[
				'name'      => 'Siti Rahayu',
				'phone'     => '4',
				'password'  => Hash::make('agen123'),
				'role'      => 'agen',
				'city'      => 'Bandung',
				'address'   => 'Jl. Braga No. 5',
				'is_active' => true,
			],

			// ── Kurir Jakarta ──────────────────────────────────
			[
				'name'      => 'Dodi Kuswara',
				'phone'     => '5',
				'password'  => Hash::make('kurir123'),
				'role'      => 'kurir',
				'city'      => 'Jakarta',
				'is_active' => true,
			],

			// ── Kurir Jakarta ──────────────────────────────────
			[
				'name'      => 'Dandi Santoloyo',
				'phone'     => '6',
				'password'  => Hash::make('kurir123'),
				'role'      => 'kurir',
				'city'      => 'Jakarta',
				'is_active' => true,
			],

			// ── Kurir Bandung ──────────────────────────────────
			[
				'name'      => 'Eko Prasetyo',
				'phone'     => '7',
				'password'  => Hash::make('kurir123'),
				'role'      => 'kurir',
				'city'      => 'Bandung',
				'is_active' => true,
			],

			// ── Kurir Bandung ──────────────────────────────────
			[
				'name'      => 'Susakoyonto',
				'phone'     => '8',
				'password'  => Hash::make('kurir123'),
				'role'      => 'kurir',
				'city'      => 'Bandung',
				'is_active' => true,
			],
		];

		foreach ($users as $user) {
			User::create($user);
		}
	}
}