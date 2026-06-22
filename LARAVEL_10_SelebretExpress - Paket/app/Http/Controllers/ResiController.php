<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResiController extends Controller
{
    public function index()
    {
        return view('resi_index');
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'no_resi' => 'required|string',
        ]);

        // Data dummy — ganti dengan query DB atau API JNE asli
        $resi = $this->getDummyResi($request->no_resi);

        return view('resi_cetak', compact('resi'));
    }

    private function getDummyResi(string $noResi): array
    {
        return [
            'no_resi'         => strtoupper($noResi),
            'tanggal_kirim'   => '19 April 2026',
            'layanan'         => 'YES (Yakin Esok Sampai)',
            'berat'           => '2 kg',
            'dimensi'         => '30 x 20 x 15 cm',
            'isi_paket'       => 'Elektronik',
            'keterangan'      => 'Fragile — Handle with Care',
            'jumlah_koli'     => 1,
            'total_koli'      => 1,
            'nilai_barang'    => 'Rp 1.500.000',
            'asuransi'        => 'Ya',
            'cod'             => 'Tidak',

            'pengirim' => [
                'nama'    => 'PT. Toko Online Sukses',
                'alamat'  => 'Jl. Sudirman No. 88, Lantai 5',
                'kota'    => 'Jakarta Pusat',
                'provinsi'=> 'DKI Jakarta',
                'kodepos' => '10220',
                'telp'    => '021-5551234',
            ],

            'penerima' => [
                'nama'    => 'Budi Santoso',
                'alamat'  => 'Jl. Melati Indah No. 12, RT 03/RW 07',
                'kota'    => 'Surabaya',
                'provinsi'=> 'Jawa Timur',
                'kodepos' => '60234',
                'telp'    => '081234567890',
            ],

            'biaya' => [
                'ongkir'   => 'Rp 85.000',
                'asuransi' => 'Rp 4.500',
                'total'    => 'Rp 89.500',
            ],

            'tracking' => [
                ['tanggal' => '19 Apr 2026 09:15', 'lokasi' => 'Jakarta Pusat', 'status' => 'Paket diterima di origin'],
                ['tanggal' => '19 Apr 2026 12:30', 'lokasi' => 'Jakarta Pusat', 'status' => 'Paket dalam proses sortir'],
                ['tanggal' => '19 Apr 2026 18:00', 'lokasi' => 'Hub Jakarta',   'status' => 'Dalam perjalanan ke tujuan'],
            ],
        ];
    }
}
