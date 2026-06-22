<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

/**
 * Trait HandlesServiceErrors
 *
 * Membungkus semua HTTP call ke backend service dengan try/catch.
 * Jika server sedang mati (cURL error 7 / Connection refused / timeout),
 * exception ditangkap dan session('error') diisi dengan nama layanan
 * yang bermasalah — sehingga SweetAlert2 di layout otomatis menampilkan toast.
 */
trait HandlesServiceErrors
{
    /**
     * Nama-nama layanan berdasarkan env key.
     * Digunakan untuk pesan error yang ramah pengguna.
     */
    private array $serviceNames = [
        'SERVICE_PENGGUNA'  => 'Layanan Pengguna',
        'SERVICE_PAKET'     => 'Layanan Paket',
        'SERVICE_TARIF'     => 'Layanan Tarif',
        'SERVICE_ARMADA'    => 'Layanan Armada',
        'SERVICE_PELACAKAN' => 'Layanan Pelacakan',
    ];

    /**
     * Tentukan nama layanan berdasarkan URL yang dipanggil.
     */
    private function resolveServiceName(string $url): string
    {
        foreach ($this->serviceNames as $envKey => $label) {
            $serviceUrl = rtrim(env($envKey, ''), '/');
            if ($serviceUrl && str_starts_with($url, $serviceUrl)) {
                return $label;
            }
        }
        return 'Layanan Backend';
    }

    /**
     * Jalankan HTTP request dengan penanganan error koneksi.
     *
     * @param  callable  $callback   Fungsi yang melakukan Http::get/post/put/delete
     * @param  string    $url        URL tujuan (untuk identifikasi nama layanan)
     * @param  mixed     $fallback   Nilai yang dikembalikan saat error (default null)
     * @return mixed
     */
    protected function safeRequest(callable $callback, string $url, mixed $fallback = null): mixed
    {
        try {
            return $callback();

        } catch (ConnectionException $e) {
            // cURL error 7: Connection refused, server mati, dsb.
            $serviceName = $this->resolveServiceName($url);
            session()->flash('error', $serviceName . ' sedang tidak dapat dijangkau. Coba beberapa saat lagi.');
            return $fallback;

        } catch (\Illuminate\Http\Client\RequestException $e) {
            $serviceName = $this->resolveServiceName($url);
            session()->flash('error', $serviceName . ' mengembalikan respons error. Silakan coba lagi.');
            return $fallback;

        } catch (\Exception $e) {
            // Tangkap semua exception tak terduga
            $serviceName = $this->resolveServiceName($url);
            session()->flash('error', 'Terjadi kesalahan pada ' . $serviceName . '. Silakan coba lagi.');
            return $fallback;
        }
    }

    /**
     * Shortcut: safeRequest yang langsung kembalikan Http::Response atau null.
     * Gunakan ini untuk satu kali HTTP call yang hasilnya perlu dicek.
     */
    protected function safeHttp(callable $callback, string $url): ?\Illuminate\Http\Client\Response
    {
        return $this->safeRequest($callback, $url, null);
    }
}
