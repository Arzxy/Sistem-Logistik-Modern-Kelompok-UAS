# Layanan Tarif (Tariff Service)

## Base URL
`http://127.0.0.1:8003`

---

# Arsitektur Database & Hubungan Model (Database Architecture)

Layanan ini mengelola data tarif pengiriman barang antarkota dan mencatat log perubahan harga tarif tersebut.

## 1. Skema Tabel (`database/migrations`)

### Tabel `tariffs`
Tabel ini menyimpan data tarif pengiriman dasar untuk rute spesifik.
*   `id` (BigInt, Primary Key, Auto Increment, Unsigned)
*   `origin_city` (Varchar(60), Not Null): Kota asal pengiriman.
*   `dest_city` (Varchar(60), Not Null): Kota tujuan pengiriman.
*   `price_per_kg` (Decimal(10,2), Not Null): Biaya per kilogram (dalam IDR).
*   `min_weight_kg` (Decimal(5,2), Default: `1.00`): Berat minimum pengiriman yang dikenakan tarif.
*   `estimated_days` (TinyInt, Default: `1`): Estimasi durasi pengiriman dalam satuan hari.
*   `is_active` (Boolean, Default: `true`): Status aktif rute tarif.
*   `created_at` & `updated_at` (Timestamp, Nullable)

**Indeks & Kendala (Constraints):**
*   **Unique Constraint**: `['origin_city', 'dest_city']` -> Kombinasi kota asal dan tujuan tidak boleh duplikat.
*   **Indexes**:
    *   Index pada kolom `origin_city` (untuk optimasi query filter asal).
    *   Index pada kolom `dest_city` (untuk optimasi query filter tujuan).
    *   Index pada kolom `is_active` (untuk optimasi query filter tarif aktif).

### Tabel `tariff_logs`
Tabel ini mencatat riwayat perubahan harga (`price_per_kg`) pada tabel `tariffs`.
*   `id` (BigInt, Primary Key, Auto Increment, Unsigned)
*   `tariff_id` (BigInt, Foreign Key, Unsigned): Menghubungkan ke `tariffs.id` dengan relasi `cascadeOnDelete`.
*   `old_price` (Decimal(10,2), Not Null): Harga per kg sebelum perubahan.
*   `new_price` (Decimal(10,2), Not Null): Harga per kg setelah perubahan.
*   `changed_by` (BigInt, Unsigned): ID admin yang melakukan perubahan (diambil dari database eksternal Layanan Pengguna - L1).
*   `changed_at` (Timestamp, Not Null): Waktu eksekusi perubahan harga.
*   `created_at` & `updated_at` (Timestamp, Nullable)

**Indeks & Kendala (Constraints):**
*   **Foreign Key**: `tariff_id` mengarah ke `tariffs(id)` dengan `onDelete('cascade')`.
*   **Indexes**:
    *   Index pada kolom `tariff_id`.
    *   Index pada kolom `changed_by`.
    *   Index pada kolom `changed_at`.

---

## 2. Hubungan Model (`app/Models`)

### Model `Tariff` (`app/Models/Tariff.php`)
*   **Properti Terproteksi `$fillable`**: `origin_city`, `dest_city`, `price_per_kg`, `min_weight_kg`, `estimated_days`, `is_active`.
*   **Properti Terproteksi `$casts`**:
    *   `price_per_kg` => `float`
    *   `min_weight_kg` => `float`
    *   `is_active` => `boolean`
    *   `estimated_days` => `integer`
*   **Relasi**:
    *   `logs()` (`hasMany(TariffLog::class)`): Menghubungkan satu tarif ke banyak log perubahan harganya.
*   **Query Scope**:
    *   `scopeActive($query)`: Menyederhanakan query untuk hanya mengambil tarif dengan `is_active = true`.

### Model `TariffLog` (`app/Models/TariffLog.php`)
*   **Properti Terproteksi `$fillable`**: `tariff_id`, `old_price`, `new_price`, `changed_by`, `changed_at`.
*   **Properti Terproteksi `$casts`**:
    *   `old_price` => `float`
    *   `new_price` => `float`
    *   `changed_at` => `datetime`
*   **Relasi**:
    *   `tariff()` (`belongsTo(Tariff::class)`): Menghubungkan log kembali ke data tarif induknya.

---

# Spesifikasi Arsitektur Kode & Aturan Bisnis

### 1. Controller & Service Layer
*   Layanan ini menggunakan arsitektur MVC standar Laravel.
*   Tidak ada Service Layer terpisah (`app/Services` kosong). Logika perhitungan tarif dan pencatatan log perubahan harga diimplementasikan langsung di dalam **`TariffController`** menggunakan Eloquent ORM.

### 2. Validasi Permintaan (Request Validation)
*   Validasi data input dilakukan secara inline langsung di dalam method Controller menggunakan `$request->validate([...])`.
*   Jika validasi gagal, Laravel secara otomatis melempar `ValidationException` yang menghasilkan respon JSON dengan status code `422` (jika header request menyertakan `Accept: application/json`).

### 3. Autentikasi & Otorisasi (Authentication & Authorization)
*   **Arsitektur Microservice**: Layanan ini bertindak sebagai layanan independen. Autentikasi pengguna/admin utama dikelola secara eksternal oleh Layanan Pengguna (L1 - Port 8001).
*   **Keamanan Route**: Semua route di dalam `routes/api.php` saat ini **tidak diproteksi** menggunakan middleware auth bawaan Laravel (seperti Sanctum/Passport) pada level routing.
*   **Otorisasi Perubahan Data**: Perubahan harga tarif memerlukan parameter wajib `changed_by` (ID Admin dari L1) yang dikirimkan melalui request body, yang kemudian disimpan di tabel `tariff_logs`.
*   **Middleware Terbengkalai**: Terdapat middleware `UserAccess` (`app/Http/Middleware/UserAccess.php`) di dalam codebase, tetapi middleware ini tidak didaftarkan ataupun digunakan pada route API apa pun.

### 4. Struktur Respon JSON (Response Format)

#### Respon Sukses Standar:
Respon sukses menggunakan status HTTP `200 OK` atau `201 Created` dengan struktur pembungkus utama:
```json
{
  "status": "success",
  "message": "Pesan deskriptif (opsional)",
  "data": { ... } // Berisi objek atau array data hasil operasi
}
```

#### Respon Gagal Standar (Kustom Controller):
Respon gagal yang ditangani secara manual oleh Controller menghasilkan status HTTP `404 Not Found` atau `422 Unprocessable Entity` dengan struktur:
```json
{
  "status": "error",
  "message": "Pesan kegagalan operasi"
}
```

#### Respon Gagal Otomatis Laravel (Validation Exception):
Jika validasi gagal, Laravel mengembalikan struktur JSON bawaan dengan status code `422`:
```json
{
  "message": "Pesan error pertama.",
  "errors": {
    "nama_field": [
      "Detail pesan kesalahan untuk field tersebut."
    ]
  }
}
```

#### Respon Gagal Otomatis Laravel (Model Not Found Exception):
Jika data dengan ID tertentu tidak ditemukan oleh `findOrFail()`, Laravel mengembalikan status code `404`:
```json
{
  "message": "No query results for model [App\\Models\\Tariff] {id}"
}
```

---

# Endpoint List

## 1. Health Check

Mengetahui status hidup/tidurnya Layanan Tarif.

*   **Endpoint**: `GET /api/health`
*   **Authentication**: None
*   **Headers**:
    *   `Accept: application/json`

### Request Body
None

### Response Success (200 OK)
```json
{
  "service": "Layanan Tarif",
  "status": "ok",
  "time": "2026-05-31T07:45:30.000000Z"
}
```

---

## 2. Calculate Shipping Rate (Kalkulasi Ongkir)

Menghitung biaya pengiriman berdasarkan kota asal, kota tujuan, dan berat barang aktual. Dipanggil oleh Layanan Paket (L2) dan Frontend.

*   **Endpoint**: `GET /api/tariffs/calculate`
*   **Authentication**: None
*   **Headers**:
    *   `Accept: application/json`

### Query Parameters
| Parameter | Tipe Data | Status | Deskripsi |
| :--- | :--- | :--- | :--- |
| `origin` | String | Required | Nama kota asal pengiriman (contoh: "Jakarta") |
| `dest` | String | Required | Nama kota tujuan pengiriman (contoh: "Bandung") |
| `weight` | Numeric | Required | Berat barang aktual dalam kg (minimal `0.1`) |

### Aturan Perhitungan:
1.  Sistem mencari tarif aktif (`is_active = true`) berdasarkan kecocokan `origin_city` dan `dest_city`.
2.  Perbandingan berat chargeable: `chargeable_weight = max(weight_aktual, min_weight_kg)`. Jika berat aktual di bawah berat minimal tarif, tarif minimal yang akan dihitung.
3.  Total harga dihitung dengan rumus: `total_price = chargeable_weight * price_per_kg` (dibulatkan 2 angka di belakang koma).

### Response Success (200 OK)
```json
{
  "status": "success",
  "data": {
    "origin": "Jakarta",
    "destination": "Bandung",
    "actual_weight_kg": 0.5,
    "chargeable_kg": 1.0,
    "price_per_kg": 12000,
    "total_price": 12000.0,
    "estimated_days": 1,
    "currency": "IDR"
  }
}
```

### Response Failure - Route Not Found (404 Not Found)
Terjadi jika rute kota asal dan tujuan tidak terdaftar atau dalam status tidak aktif.
```json
{
  "status": "error",
  "message": "Tarif untuk rute Jakarta - Jayapura tidak ditemukan."
}
```

### Response Failure - Validation Error (422 Unprocessable Entity)
Terjadi jika query parameter tidak lengkap atau bernilai tidak valid.
```json
{
  "message": "The weight field is required.",
  "errors": {
    "weight": [
      "The weight field is required."
    ]
  }
}
```

---

## 3. Get All Tariffs (List Semua Tarif)

Mengambil daftar seluruh tarif pengiriman. Dapat difilter berdasarkan kota asal dan kota tujuan.

*   **Endpoint**: `GET /api/tariffs`
*   **Authentication**: None
*   **Headers**:
    *   `Accept: application/json`

### Query Parameters
| Parameter | Tipe Data | Status | Deskripsi |
| :--- | :--- | :--- | :--- |
| `origin` | String | Optional | Memfilter berdasarkan nama kota asal |
| `dest` | String | Optional | Memfilter berdasarkan nama kota tujuan |

### Response Success (200 OK)
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "origin_city": "Jakarta",
      "dest_city": "Bandung",
      "price_per_kg": 12000,
      "min_weight_kg": 1.0,
      "estimated_days": 1,
      "is_active": true,
      "created_at": "2026-04-05T13:54:10.000000Z",
      "updated_at": "2026-04-05T13:54:10.000000Z"
    },
    {
      "id": 2,
      "origin_city": "Jakarta",
      "dest_city": "Surabaya",
      "price_per_kg": 18000,
      "min_weight_kg": 1.0,
      "estimated_days": 2,
      "is_active": true,
      "created_at": "2026-04-05T13:54:10.000000Z",
      "updated_at": "2026-04-05T13:54:10.000000Z"
    }
  ]
}
```

---

## 4. Get Tariff Detail (Detail Tarif Spesifik)

Mengambil rincian informasi satu tarif beserta seluruh riwayat log perubahan harganya.

*   **Endpoint**: `GET /api/tariffs/{id}`
*   **Authentication**: None
*   **Headers**:
    *   `Accept: application/json`

### Path Parameters
*   `id` (Integer, Required): ID unik dari tarif.

### Response Success (200 OK)
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "origin_city": "Jakarta",
    "dest_city": "Bandung",
    "price_per_kg": 12000,
    "min_weight_kg": 1.0,
    "estimated_days": 1,
    "is_active": true,
    "created_at": "2026-04-05T13:54:10.000000Z",
    "updated_at": "2026-04-05T13:54:10.000000Z",
    "logs": [
      {
        "id": 1,
        "tariff_id": 1,
        "old_price": 10000,
        "new_price": 12000,
        "changed_by": 3,
        "changed_at": "2026-05-10T08:00:00.000000Z",
        "created_at": "2026-05-10T08:00:00.000000Z",
        "updated_at": "2026-05-10T08:00:00.000000Z"
      }
    ]
  }
}
```

### Response Failure (404 Not Found)
```json
{
  "message": "No query results for model [App\\Models\\Tariff] 999"
}
```

---

## 5. Create Tariff (Tambah Tarif Baru)

Membuat data tarif pengiriman rute baru.

*   **Endpoint**: `POST /api/tariffs`
*   **Authentication**: None (Logika otentikasi diurus L1)
*   **Headers**:
    *   `Accept: application/json`
    *   `Content-Type: application/json`

### Request Body
```json
{
  "origin_city": "Surabaya",
  "dest_city": "Malang",
  "price_per_kg": 9500,
  "min_weight_kg": 1.5,
  "estimated_days": 1
}
```

### Aturan Validasi Request
*   `origin_city` (Required, String, Max 60 karakter)
*   `dest_city` (Required, String, Max 60 karakter)
*   `price_per_kg` (Required, Numeric, Min `0`)
*   `min_weight_kg` (Optional, Numeric, Min `0`)
*   `estimated_days` (Optional, Integer, Min `1`)

### Response Success (201 Created)
```json
{
  "status": "success",
  "message": "Tarif berhasil dibuat.",
  "data": {
    "origin_city": "Surabaya",
    "dest_city": "Malang",
    "price_per_kg": 9500,
    "min_weight_kg": 1.5,
    "estimated_days": 1,
    "id": 11,
    "updated_at": "2026-05-31T07:45:30.000000Z",
    "created_at": "2026-05-31T07:45:30.000000Z"
  }
}
```

### Response Failure - Duplikat Rute (422 Unprocessable Entity)
Terjadi jika rute asal-tujuan yang diinput sudah terdaftar sebelumnya di database.
```json
{
  "status": "error",
  "message": "Tarif untuk rute ini sudah ada."
}
```

---

## 6. Update Tariff (Ubah Data Tarif)

Mengubah tarif pengiriman yang ada. Jika nominal `price_per_kg` diubah, riwayat perubahan harga otomatis disimpan ke tabel `tariff_logs`.

*   **Endpoint**: `PUT /api/tariffs/{id}`
*   **Authentication**: None
*   **Headers**:
    *   `Accept: application/json`
    *   `Content-Type: application/json`

### Path Parameters
*   `id` (Integer, Required): ID unik dari tarif yang akan diubah.

### Request Body
```json
{
  "price_per_kg": 13500,
  "min_weight_kg": 1.0,
  "estimated_days": 1,
  "is_active": true,
  "changed_by": 5
}
```

### Aturan Validasi Request
*   `price_per_kg` (Optional/Sometimes, Numeric, Min `0`)
*   `min_weight_kg` (Optional/Sometimes, Numeric, Min `0`)
*   `estimated_days` (Optional/Sometimes, Integer, Min `1`)
*   `is_active` (Optional/Sometimes, Boolean)
*   `changed_by` (Required, Integer): ID Admin dari L1 yang melakukan perubahan (wajib disertakan untuk pencatatan log).

### Response Success (200 OK)
```json
{
  "status": "success",
  "message": "Tarif berhasil diupdate.",
  "data": {
    "id": 1,
    "origin_city": "Jakarta",
    "dest_city": "Bandung",
    "price_per_kg": 13500,
    "min_weight_kg": 1.0,
    "estimated_days": 1,
    "is_active": true,
    "created_at": "2026-04-05T13:54:10.000000Z",
    "updated_at": "2026-05-31T07:46:12.000000Z"
  }
}
```

---

## 7. Delete Tariff (Hapus Tarif)

Menghapus rute tarif dari database secara permanen.

*   **Endpoint**: `DELETE /api/tariffs/{id}`
*   **Authentication**: None
*   **Headers**:
    *   `Accept: application/json`

### Path Parameters
*   `id` (Integer, Required): ID unik dari tarif yang akan dihapus.

### Catatan Kode:
Di dalam controller terdapat baris yang dinonaktifkan:
`//$tariff->update(['is_active' => false]); // soft deactivate, bukan hapus`
Sebagai gantinya, baris kode aktif yang berjalan adalah `$tariff->delete();`. Sehingga memanggil endpoint ini akan langsung menghapus data dari database secara permanen (Hard Delete) dan secara cascade menghapus semua data log terkait di tabel `tariff_logs`.

### Response Success (200 OK)
```json
{
  "status": "success",
  "message": "Tarif dinonaktifkan."
}
```

---

## 8. Get Tariff Price Log (Daftar Log Perubahan Harga)

Mengambil daftar log perubahan harga khusus untuk tarif spesifik.

*   **Endpoint**: `GET /api/tariffs/{id}/logs`
*   **Authentication**: None
*   **Headers**:
    *   `Accept: application/json`

### Path Parameters
*   `id` (Integer, Required): ID unik dari tarif.

### Response Success (200 OK)
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "tariff_id": 1,
      "old_price": 12000,
      "new_price": 13500,
      "changed_by": 5,
      "changed_at": "2026-05-31T07:46:12.000000Z",
      "created_at": "2026-05-31T07:46:12.000000Z",
      "updated_at": "2026-05-31T07:46:12.000000Z"
    }
  ]
}
```

---

## 9. Bulk Insert (BANYAK DETAIL / BROKEN ROUTE)

Route ini disediakan untuk melakukan import tarif dalam jumlah besar sekaligus oleh admin.

> [!WARNING]
> **BUG / UNIMPLEMENTED ENDPOINT:**
> Route ini dideklarasikan di `routes/api.php` mengarah ke method `bulk` di `TariffController`:
> `Route::post('/tariffs/bulk', [TariffController::class, 'bulk']);`
> Namun, method `bulk` **belum diimplementasikan** sama sekali di dalam file `app/Http/Controllers/Api/TariffController.php`.
> Memanggil endpoint ini saat ini akan memicu error `500 Internal Server Error` (BadMethodCallException).

*   **Endpoint**: `POST /api/tariffs/bulk`
*   **Authentication**: None
*   **Headers**:
    *   `Accept: application/json`
    *   `Content-Type: application/json`

### Rencana Request Body (Referensi bagi Pengembang Berikutnya)
```json
{
  "changed_by": 5,
  "tariffs": [
    {
      "origin_city": "Semarang",
      "dest_city": "Solo",
      "price_per_kg": 8000,
      "min_weight_kg": 1.0,
      "estimated_days": 1
    },
    {
      "origin_city": "Yogyakarta",
      "dest_city": "Solo",
      "price_per_kg": 7500,
      "min_weight_kg": 1.0,
      "estimated_days": 1
    }
  ]
}
```
