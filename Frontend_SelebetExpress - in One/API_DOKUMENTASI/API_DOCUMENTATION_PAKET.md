# Layanan Paket (SelebretExpress)

Layanan Paket (L2) adalah salah satu microservice dalam ekosistem SelebretExpress yang bertanggung jawab mengelola data paket, penciptaan nomor resi otomatis, pelacakan status paket, dan integrasi pengiriman status ke Layanan Pelacakan (L5).

## Base URL
Default port berdasarkan file konfigurasi `.env`:
`http://127.0.0.1:8002`

---

# Aturan Umum & Spesifikasi Teknis

### 1. Sistem Autentikasi (Service-to-Service)
Sebagian besar endpoint internal dilindungi oleh middleware `VerifyServiceKey` (`service.key`). 
* **Header yang Wajib Dikirim:** `X-Service-Key`
* **Nilai Key:** Harus cocok dengan nilai `INTERNAL_SERVICE_KEY` di file `.env` (Default: `rahasia-internal-ekspedisi-2024`).
* **Format Response Jika Autentikasi Gagal (HTTP 403 Forbidden):**
```json
{
  "status": "error",
  "message": "Akses tidak diizinkan."
}
```

### 2. Standar Struktur Response API
#### Response Sukses (HTTP 200 / 201)
Format umum response sukses selalu memiliki properti `status` bernilai `"success"` dan data dibungkus di dalam key `"data"`.
```json
{
  "status": "success",
  "message": "Pesan sukses (opsional)",
  "data": { ... }
}
```

#### Response Gagal Validasi (HTTP 422 Unprocessable Entity)
Terjadi ketika payload request tidak memenuhi aturan validasi.
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "Pesan error detail."
    ]
  }
}
```

#### Response Gagal Menemukan Data (HTTP 404 Not Found)
Terjadi jika resource dengan ID atau parameter tertentu tidak ditemukan di database.
```json
{
  "message": "No query results for model [App\\Models\\Package] <id>"
}
```

---

# Database Schema: `packages`
Tabel utama yang digunakan untuk menyimpan data paket adalah `packages`. Struktur kolomnya adalah sebagai berikut:

| Nama Kolom | Tipe Data | Nullable? | Default | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `id` | BigInt (PK) | No | Auto Increment | ID unik paket |
| `resi_number` | String(20) | No | Unique | Nomor resi otomatis |
| `sender_id` | BigInt | No | - | Referensi ID Pengirim (L1) |
| `receiver_id` | BigInt | No | - | Referensi ID Penerima (L1) |
| `origin_warehouse_id`| BigInt | No | - | Referensi ID Gudang Asal |
| `dest_warehouse_id` | BigInt | No | - | Referensi ID Gudang Tujuan |
| `alamat_tujuan` | Text | Yes | NULL | Alamat pengiriman lengkap |
| `weight_kg` | Decimal(8,2) | No | - | Berat fisik paket |
| `length_cm` | Decimal(8,2) | Yes | NULL | Panjang paket |
| `width_cm` | Decimal(8,2) | Yes | NULL | Lebar paket |
| `height_cm` | Decimal(8,2) | Yes | NULL | Tinggi paket |
| `volume_weight_kg` | Decimal(8,2) | Yes | NULL | Berat volume: `(p * l * t) / 6000` |
| `description` | Text | Yes | NULL | Keterangan paket (e.g. barang pecah belah) |
| `total_price` | Decimal(12,2)| No | `0.00` | Total biaya ongkos kirim |
| `service_type` | String(20) | No | `'reguler'` | Tipe layanan: `reguler`, `express`, `cargo` |
| `status` | Enum | No | `'pending'` | Status paket (Lihat catatan inkonsistensi) |
| `courier_id` | BigInt | Yes | NULL | Referensi ID Kurir Pengirim (L4) |
| `delivery_id` | BigInt | Yes | NULL | Referensi ID Tugas Pengiriman (L4) |
| `created_by` | BigInt | No | - | ID user pembuat data |
| `created_at` | Timestamp | Yes | NULL | Waktu data dibuat |
| `updated_at` | Timestamp | Yes | NULL | Waktu data diperbarui |

---

# Endpoint List

## 1. Health Check

### Endpoint
`GET /api/health`

### Headers
*Tidak memerlukan autentikasi (Publik).*

### Request Body
*None (Tidak memerlukan request body).*

### Response Sukses (HTTP 200 OK)
```json
{
  "service": "Layanan Paket",
  "status": "ok",
  "db": "connected",
  "time": "2026-05-31T07:30:00.000000Z"
}
```

---

## 2. Lacak Paket Berdasarkan Nomor Resi

### Endpoint
`GET /api/packages/resi/{resi}`

### Headers
*Tidak memerlukan autentikasi (Publik, bisa diakses dari Frontend).*

### Route Parameters
* `resi` (string, required): Nomor resi paket (contoh: `EKS20260419A3F7B9`).

### Response Sukses (HTTP 200 OK)
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "resi_number": "EKS20260419A3F7B9",
    "sender_id": 1,
    "receiver_id": 2,
    "origin_warehouse_id": 1,
    "dest_warehouse_id": 2,
    "alamat_tujuan": "Jl. Melati Indah No. 12, RT 03/RW 07, Surabaya",
    "weight_kg": 2.5,
    "length_cm": 30,
    "width_cm": 20,
    "height_cm": 15,
    "volume_weight_kg": 1.5,
    "description": "Buku dan alat tulis",
    "total_price": 45000,
    "service_type": "reguler",
    "status": "delivered",
    "courier_id": 1,
    "delivery_id": 1,
    "created_by": 3,
    "created_at": "2026-05-31T07:30:00.000000Z",
    "updated_at": "2026-05-31T07:30:00.000000Z"
  }
}
```

### Response Gagal (HTTP 404 Not Found)
```json
{
  "message": "No query results for model [App\\Models\\Package]"
}
```

---

## 3. List Semua Paket dengan Filter & Pagination

### Endpoint
`GET /api/packages`

### Headers
* `X-Service-Key`: `rahasia-internal-ekspedisi-2024` (Wajib)

### Query Parameters
* `status` (string, optional): Menyaring paket berdasarkan status (contoh: `in_transit`).
* `courier_id` (integer, optional): Menyaring paket berdasarkan ID kurir.
* `sender_id` (integer, optional): Menyaring paket berdasarkan ID pengirim.
* `receiver_id` (integer, optional): Menyaring paket berdasarkan ID penerima.
* `origin_warehouse_id` (integer, optional): Menyaring paket berdasarkan ID gudang asal.
* `search` (string, optional): Melakukan pencarian sebagian (like) pada kolom `resi_number`.
* `date_from` (string/date YYYY-MM-DD, optional): Batas awal tanggal pembuatan paket.
* `date_to` (string/date YYYY-MM-DD, optional): Batas akhir tanggal pembuatan paket.
* `limit` (integer, optional, default: 20): Jumlah data per halaman.
* `page` (integer, optional): Nomor halaman pagination.

### Response Sukses (HTTP 200 OK)
```json
{
  "status": "success",
  "data": [
    {
      "id": 2,
      "resi_number": "EKS20260419D4H7B1",
      "sender_id": 4,
      "receiver_id": 5,
      "origin_warehouse_id": 1,
      "dest_warehouse_id": 3,
      "alamat_tujuan": "Jl. Sudirman No. 88, Jakarta Pusat",
      "weight_kg": 5,
      "length_cm": null,
      "width_cm": null,
      "height_cm": null,
      "volume_weight_kg": 3.2,
      "description": "Elektronik - handle with care",
      "total_price": 90000,
      "service_type": "express",
      "status": "in_transit",
      "courier_id": 1,
      "delivery_id": 1,
      "created_by": 3,
      "created_at": "2026-05-31T07:30:00.000000Z",
      "updated_at": "2026-05-31T07:30:00.000000Z"
    }
  ],
  "meta": {
    "total": 1,
    "current_page": 1,
    "last_page": 1
  }
}
```

---

## 4. Detail Paket Berdasarkan ID

### Endpoint
`GET /api/packages/{id}`

### Headers
* `X-Service-Key`: `rahasia-internal-ekspedisi-2024` (Wajib)

### Route Parameters
* `id` (integer, required): ID unik paket di database.

### Response Sukses (HTTP 200 OK)
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "resi_number": "EKS20260419A3F7B9",
    "sender_id": 1,
    "receiver_id": 2,
    "origin_warehouse_id": 1,
    "dest_warehouse_id": 2,
    "alamat_tujuan": "Jl. Melati Indah No. 12, RT 03/RW 07, Surabaya",
    "weight_kg": 2.5,
    "length_cm": 30,
    "width_cm": 20,
    "height_cm": 15,
    "volume_weight_kg": 1.5,
    "description": "Buku dan alat tulis",
    "total_price": 45000,
    "service_type": "reguler",
    "status": "delivered",
    "courier_id": 1,
    "delivery_id": 1,
    "created_by": 3,
    "created_at": "2026-05-31T07:30:00.000000Z",
    "updated_at": "2026-05-31T07:30:00.000000Z"
  }
}
```

### Response Gagal (HTTP 404 Not Found)
```json
{
  "message": "No query results for model [App\\Models\\Package] <id>"
}
```

---

## 5. Buat Paket Baru / Generate Resi

### Endpoint
`POST /api/packages`

### Headers
* `X-Service-Key`: `rahasia-internal-ekspedisi-2024` (Wajib)

### Request Body (JSON)
```json
{
  "sender_id": 1,
  "receiver_id": 2,
  "origin_warehouse_id": 1,
  "dest_warehouse_id": 2,
  "alamat_tujuan": "Jl. Melati Indah No. 12, Surabaya",
  "weight_kg": 2.5,
  "length_cm": 30,
  "width_cm": 20,
  "height_cm": 15,
  "description": "Buku tulis dan pensil",
  "total_price": 45000,
  "service_type": "reguler",
  "created_by": 3
}
```

### Aturan Validasi Request
* `sender_id` (integer, required)
* `receiver_id` (integer, required)
* `origin_warehouse_id` (integer, required)
* `dest_warehouse_id` (integer, required)
* `alamat_tujuan` (string, max:500, optional)
* `weight_kg` (numeric, min:0.1, required)
* `length_cm` (numeric, min:1, optional)
* `width_cm` (numeric, min:1, optional)
* `height_cm` (numeric, min:1, optional)
* `description` (string, max:500, optional)
* `total_price` (numeric, min:0, required)
* `service_type` (string, optional, must be: `reguler`, `express`, `cargo`)
* `created_by` (integer, required)

### Logika Internal
1. **Nomor Resi:** Otomatis digenerate menggunakan format `EKS` + `YYYYMMDD` + `6 digit random alfanumerik` (dijamin unik via DB loop check).
2. **Volume Weight:** Dihitung dari `(length_cm * width_cm * height_cm) / 6000`. (Penting: lihat bagian "Bug Kritis & Rekomendasi Perbaikan" mengenai pembagian ini).
3. **Notifikasi L5:** Mengirimkan riwayat log pelacakan awal ke Layanan Pelacakan (L5) via `TrackingNotifier::log` dengan payload:
   - `status`: `"pending"`
   - `location`: `"Gudang Asal #" . $origin_warehouse_id`
   - `notes`: `"Paket diterima di gudang, menunggu kurir."`

### Response Sukses (HTTP 201 Created)
```json
{
  "status": "success",
  "message": "Resi berhasil dibuat.",
  "data": {
    "id": 3,
    "resi_number": "EKS202605318F9C2B",
    "sender_id": 1,
    "receiver_id": 2,
    "origin_warehouse_id": 1,
    "dest_warehouse_id": 2,
    "alamat_tujuan": "Jl. Melati Indah No. 12, Surabaya",
    "weight_kg": 2.5,
    "length_cm": 30,
    "width_cm": 20,
    "height_cm": 15,
    "volume_weight_kg": 1.5,
    "description": "Buku tulis dan pensil",
    "total_price": 45000,
    "service_type": "reguler",
    "status": "pending_pickup",
    "courier_id": null,
    "delivery_id": null,
    "created_by": 3,
    "created_at": "2026-05-31T07:35:00.000000Z",
    "updated_at": "2026-05-31T07:35:00.000000Z"
  }
}
```

### Response Gagal Validasi (HTTP 422 Unprocessable Entity)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "weight_kg": [
      "The weight kg field is required."
    ],
    "service_type": [
      "The selected service type is invalid."
    ]
  }
}
```

---

## 6. Perbarui Status Paket

### Endpoint
`PATCH /api/packages/{id}/status`

### Keterangan
Endpoint ini dipanggil terutama oleh Layanan Armada (L4) saat kurir memperbarui status paket di lapangan.

### Headers
* `X-Service-Key`: `rahasia-internal-ekspedisi-2024` (Wajib)

### Route Parameters
* `id` (integer, required): ID unik paket di database.

### Request Body (JSON)
```json
{
  "status": "in_transit",
  "courier_id": 1,
  "delivery_id": 1,
  "location": "Hub Utama Surabaya",
  "notes": "Paket sedang dalam perjalanan menuju Hub tujuan.",
  "warehouse_id": 2
}
```

### Aturan Validasi Request
* `status` (string, required): Status paket yang baru.
* `courier_id` (integer, optional)
* `delivery_id` (integer, optional)
* `location` (string, optional): Lokasi saat ini (untuk diteruskan ke log L5).
* `notes` (string, optional): Catatan tambahan mengenai status (untuk diteruskan ke log L5).
* `warehouse_id` (integer, optional): ID gudang terkait (untuk diteruskan ke log L5).

### Logika Internal
1. Memperbarui field `status`, `courier_id`, dan `delivery_id` pada tabel `packages`.
2. Mengirim log pelacakan ke Layanan Pelacakan (L5) via `TrackingNotifier::log` dengan data lokasi, catatan, kurir, dan gudang yang diterima dari request.

### Response Sukses (HTTP 200 OK)
```json
{
  "status": "success",
  "message": "Status paket diperbarui.",
  "data": {
    "id": 1,
    "resi_number": "EKS20260419A3F7B9",
    "sender_id": 1,
    "receiver_id": 2,
    "origin_warehouse_id": 1,
    "dest_warehouse_id": 2,
    "alamat_tujuan": "Jl. Melati Indah No. 12, RT 03/RW 07, Surabaya",
    "weight_kg": 2.5,
    "length_cm": 30,
    "width_cm": 20,
    "height_cm": 15,
    "volume_weight_kg": 1.5,
    "description": "Buku dan alat tulis",
    "total_price": 45000,
    "service_type": "reguler",
    "status": "in_transit",
    "courier_id": 1,
    "delivery_id": 1,
    "created_by": 3,
    "created_at": "2026-05-31T07:30:00.000000Z",
    "updated_at": "2026-05-31T07:40:00.000000Z"
  }
}
```

---

## 7. Statistik Ringkasan (Dashboard)

### Endpoint
`GET /api/packages/stats`

### Headers
* `X-Service-Key`: `rahasia-internal-ekspedisi-2024` (Wajib)

### Response Sukses (HTTP 200 OK)
```json
{
  "status": "success",
  "data": {
    "counts": {
      "total": 3,
      "pending_pickup": 0,
      "picked_up": 0,
      "at_origin_warehouse": 0,
      "assigned": 0,
      "in_transit": 1,
      "at_destination_warehouse": 0,
      "out_for_delivery": 0,
      "delivered": 1,
      "cancelled": 0,
      "returned": 0
    },
    "total_revenue": 45000,
    "today": {
      "packages": 0,
      "revenue": 0
    }
  }
}
```

---

# Integrasi Layanan (Layanan Pelacakan L5)
Setiap kali status paket dibuat (`store`) atau diperbarui (`updateStatus`), service akan memanggil static helper `App\Services\TrackingNotifier::log(...)`.

* **Fungsi internal:** Mengirim HTTP POST secara asinkron (dilindungi dengan try-catch agar kegagalan L5 tidak menghentikan transaksi utama) ke endpoint:
  `${SERVICE_PELACAKAN}/api/tracking/log`
* **Headers yang dikirim:**
  - `X-Service-Key`: Diambil dari `env('INTERNAL_SERVICE_KEY')`
* **Payload JSON:**
```json
{
  "resi_number": "EKS20260419A3F7B9",
  "status": "status_value",
  "location": "location_value",
  "notes": "notes_value",
  "courier_id": 1,
  "warehouse_id": 2,
  "logged_at": "2026-05-31T07:40:00.000000Z"
}
```

---

# Temuan Masalah & Rekomendasi Perbaikan (Sangat Penting untuk AI Selanjutnya)

Selama analisis menyeluruh, ditemukan beberapa masalah logika dan arsitektur kritis yang harus segera diperbaiki sebelum melanjutkan pengembangan:

### ⚠️ 1. Konflik Urutan Route (Precedence Bug)
Pada `routes/api.php`, route untuk mengambil detail paket didefinisikan *sebelum* route statistik:
```php
Route::get('/packages/{id}',    [PackageController::class, 'show']);
...
Route::get('/packages/stats',   [PackageController::class, 'stats']);
```
**Akibat:** Request ke `GET /api/packages/stats` akan ditangkap oleh route `/packages/{id}` dengan nilai `{id} = "stats"`. Hal ini akan menyebabkan error `404 Not Found` dari controller karena database mencari record paket dengan ID `"stats"`.
**Solusi:** Pindahkan definisi route statistik ke atas detail paket, atau tambahkan regex constraint pada ID:
```php
Route::get('/packages/{id}', [PackageController::class, 'show'])->where('id', '[0-9]+');
```

### ⚠️ 2. Potensi Bug Null Pointer saat Menyimpan Paket
Pada `PackageController::store` baris ke-91:
```php
$volumeWeight = ($validated['length_cm'] * $validated['width_cm'] * $validated['height_cm']) / 6000;
```
Namun di aturan validasi request:
```php
'length_cm' => 'nullable|numeric|min:1',
'width_cm'  => 'nullable|numeric|min:1',
'height_cm' => 'nullable|numeric|min:1',
```
**Akibat:** Jika client mengirim request tanpa menyertakan `length_cm`, `width_cm`, atau `height_cm`, variabel kunci tersebut tidak akan masuk ke array `$validated`. Menjalankan perkalian langsung pada kunci yang tidak ada akan memicu error PHP `Warning: Undefined array key`.
**Solusi:** Gunakan static helper aman yang sudah disediakan di model `Package` (namun di-comment di controller):
```php
$volumeWeight = Package::calculateVolumeWeight(
    $request->input('length_cm'),
    $request->input('width_cm'),
    $request->input('height_cm')
);
```

### ⚠️ 3. Ketidakcocokan Enum Status (Database vs Controller)
Di file migrasi database `2026_04_17_143302_create_packages_table.php`, status didefinisikan sebagai:
`status` ENUM (`'pending'`, `'assigned'`, `'picked_up'`, `'in_transit'`, `'at_warehouse'`, `'out_for_delivery'`, `'delivered'`, `'returned'`, `'cancelled'`) dengan default `'pending'`.

Namun di controller:
- Saat `store`, status diisi `'pending_pickup'`.
- Saat `stats`, query melakukan perhitungan pada status `'pending_pickup'`, `'at_origin_warehouse'`, `'at_destination_warehouse'`.
- Seeder menggunakan `'pending'`, `'in_transit'`, `'delivered'`.

**Akibat:** Jika database menggunakan engine strict (seperti MySQL/PostgreSQL produksi), proses `store` akan langsung gagal/crash karena status `'pending_pickup'` tidak terdaftar di daftar ENUM migrasi database.
**Solusi:** Selaraskan daftar status di migrasi database dengan yang digunakan di controller, atau ubah tipe kolom database dari ENUM menjadi `VARCHAR(30)` agar lebih fleksibel untuk status-status baru.

### 🔒 4. Kebocoran Endpoint Internal (Security Gap)
Di file `routes/web.php` baris ke-30:
```php
Route::get('/api/packages', [PackageController::class, 'index']);
```
**Akibat:** Endpoint ini didaftarkan di route kelompok `web` tanpa middleware `service.key`. Hal ini memungkinkan pihak luar untuk mengakses seluruh list paket ekspedisi tanpa memerlukan header `X-Service-Key` jika mereka memanggilnya via routing web biasa.
**Solusi:** Hapus baris route tersebut dari `routes/web.php` karena endpoint `/api/packages` sudah didefinisikan dengan benar dan aman di `routes/api.php`.
