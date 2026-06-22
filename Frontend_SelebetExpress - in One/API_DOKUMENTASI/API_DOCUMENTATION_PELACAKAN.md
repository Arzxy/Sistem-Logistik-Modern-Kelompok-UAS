# Layanan Pelacakan (Tracking Service)

Layanan ini adalah bagian dari sistem backend SelebretExpress yang bertanggung jawab untuk mencatat histori pelacakan paket dan menyediakan status terkini dari setiap paket (resi). Layanan ini dirancang menggunakan arsitektur mikroservis berkolaborasi dengan layanan lain (Layanan Paket/L2, Layanan Armada/L4, dll.).

## Base URL
http://127.0.0.1:8005

---

# Aturan Umum & Arsitektur Kode

### 1. Database & Model
Proyek ini memiliki dua tabel utama di database `db_pelacakan`:
*   **`tracking_logs`** ([TrackingLog.php](file:///c:/xampp/htdocs/LARAVEL_10_SelebretExpress%20-%20Pelacakan/app/Models/TrackingLog.php)): Menyimpan seluruh riwayat/log perjalanan paket dari berbagai layanan (L2, L4, dll.).
*   **`package_summaries`** ([PackageSummary.php](file:///c:/xampp/htdocs/LARAVEL_10_SelebretExpress%20-%20Pelacakan/app/Models/PackageSummary.php)): Menyimpan *cache* status terakhir paket untuk pencarian cepat (sehingga sistem tidak perlu melakukan scan seluruh baris log perjalanan setiap kali melacak resi).

### 2. Mekanisme Sinkronisasi Status Terkini
Setiap kali ada log tracking baru masuk melalui `POST /api/tracking-logs`, sistem akan memanggil metode statis `PackageSummary::updateFromLog($log, $resiNumber)` yang secara otomatis membuat baru atau memperbarui status terakhir paket di tabel `package_summaries`.

### 3. Skema Database
#### Tabel `tracking_logs`
*   `id` (BigInt, PK, Auto Increment)
*   `package_id` (BigInt, Index) -> ID Paket dari Layanan Paket (L2)
*   `courier_id` (BigInt, Nullable, Index) -> ID Kurir dari Layanan Armada (L4)
*   `warehouse_id` (BigInt, Nullable, Index) -> ID Gudang
*   `status` (String max 100, Index) -> Status perjalanan (misal: "Paket dibuat", "Paket sedang dikirim")
*   `location` (String max 150, Nullable) -> Lokasi fisik kejadian
*   `notes` (Text, Nullable) -> Catatan tambahan
*   `source_service` (String max 10, Default: 'L2') -> Layanan pengirim log (misal: 'L2', 'L4')
*   `logged_at` (Timestamp) -> Waktu kejadian asli
*   `timestamps` (`created_at`, `updated_at`)

#### Tabel `package_summaries`
*   `id` (BigInt, PK, Auto Increment)
*   `package_id` (BigInt, Unique) -> Hubungan 1-ke-1 dengan paket
*   `resi_number` (String max 30, Unique, Index) -> Nomor resi untuk pencarian cepat
*   `last_status` (String max 100) -> Duplikasi status terbaru dari log terakhir
*   `last_location` (String max 150, Nullable) -> Duplikasi lokasi terbaru dari log terakhir
*   `last_updated` (Timestamp) -> Waktu log terakhir
*   `timestamps` (`created_at`, `updated_at`)

### 4. Authentication & Middleware
Layanan ini memiliki dua lapis keamanan:
*   **Public API**: Endpoint publik (Health Check, Lacak Resi, dan Ringkasan) dapat diakses tanpa autentikasi apa pun.
*   **Internal Service API**: Endpoint yang memanipulasi data (`POST`, `DELETE`, dll.) wajib menyertakan middleware `service.auth` ([InternalServiceAuth.php](file:///c:/xampp/htdocs/LARAVEL_10_SelebretExpress%20-%20Pelacakan/app/Http/Middleware/InternalServiceAuth.php)).
    *   **Header Wajib**: `X-Service-Key`
    *   Nilai header harus cocok dengan nilai `INTERNAL_SERVICE_KEY` yang terdefinisi di file `.env` server.
    *   Jika salah atau tidak ada, sistem akan mengembalikan status `401 Unauthorized`.

---

# Standar Response JSON

### Response Sukses
Semua response sukses mengembalikan field `status` dengan nilai `"success"` dan payload di dalam field `data`.
```json
{
  "status": "success",
  "data": { ... }
}
```
*Catatan: Beberapa endpoint list menyertakan tambahan field `"total"` untuk menghitung jumlah item.*

### Response Gagal (Not Found / Error Bisnis)
Jika data tidak ditemukan atau terjadi kesalahan logika aplikasi, sistem mengembalikan status `404` atau `400` dengan format:
```json
{
  "status": "error",
  "message": "Pesan deskripsi kesalahan"
}
```

### Response Gagal (Akses Tidak Sah - 401)
Jika header `X-Service-Key` tidak valid atau hilang pada endpoint internal:
```json
{
  "status": "error",
  "message": "Unauthorized. Service key tidak valid."
}
```

### Response Gagal (Validasi Request - 422)
Jika data request body tidak memenuhi aturan validasi:
```json
{
  "message": "The package id field is required. (and other errors)",
  "errors": {
    "package_id": [
      "The package id field is required."
    ]
  }
}
```

---

# Endpoint List

## 1. Health Check

### Endpoint
GET /api/health

### Headers
Tidak ada header wajib.

### Query Parameters
Tidak ada.

### Response Body (200 OK)
```json
{
  "service": "Layanan Pelacakan",
  "status": "ok",
  "port": "8005",
  "db_tables": {
    "tracking_logs": 24,
    "package_summaries": 10
  },
  "time": "2026-05-31T14:32:05.000000Z"
}
```

---

## 2. Get Public Tracking Summaries (Ringkasan Status Semua Paket)

### Endpoint
GET /api/tracking/summaries

### Headers
Tidak ada header wajib.

### Query Parameters
*   `status` (string, optional) -> memfilter status terakhir paket (pencarian parsial `like`).

### Response Body (200 OK)
```json
{
  "status": "success",
  "total": 2,
  "data": [
    {
      "id": 1,
      "package_id": 12,
      "resi_number": "SLBXT10001",
      "last_status": "Paket dikirim dari Gudang Surabaya",
      "last_location": "Surabaya",
      "last_updated": "2026-05-31T10:00:00.000000Z",
      "created_at": "2026-05-31T09:00:00.000000Z",
      "updated_at": "2026-05-31T10:00:00.000000Z"
    },
    {
      "id": 2,
      "package_id": 13,
      "resi_number": "SLBXT10002",
      "last_status": "Paket sedang diantar kurir",
      "last_location": "Sidoarjo",
      "last_updated": "2026-05-31T11:15:00.000000Z",
      "created_at": "2026-05-31T09:30:00.000000Z",
      "updated_at": "2026-05-31T11:15:00.000000Z"
    }
  ]
}
```

---

## 3. Lacak Status Paket Berdasarkan Resi (Public Tracking)

### Endpoint
GET /api/tracking/{resi}

### Headers
Tidak ada header wajib.

### URL Parameters
*   `resi` (string, required) -> Nomor resi paket yang dicari.

### Response Body (200 OK - Resi Ditemukan)
```json
{
  "status": "success",
  "data": {
    "resi_number": "SLBXT10001",
    "package_id": 12,
    "last_status": "Paket dikirim dari Gudang Surabaya",
    "last_location": "Surabaya",
    "last_updated": "2026-05-31T10:00:00.000000Z",
    "logs": [
      {
        "id": 1,
        "status": "Paket Dibuat",
        "location": "Gudang Jakarta",
        "notes": "Barang dalam kondisi baik",
        "source_service": "L2",
        "logged_at": "2026-05-31T09:00:00.000000Z",
        "formatted_time": "31 May 2026, 16:00"
      },
      {
        "id": 2,
        "status": "Paket dikirim dari Gudang Surabaya",
        "location": "Surabaya",
        "notes": "Diproses armada pengiriman cepat",
        "source_service": "L4",
        "logged_at": "2026-05-31T10:00:00.000000Z",
        "formatted_time": "31 May 2026, 17:00"
      }
    ]
  }
}
```

### Response Body (404 Not Found - Resi Tidak Terdaftar)
```json
{
  "status": "error",
  "message": "Resi 'SLBXT99999' tidak ditemukan."
}
```

---

## 4. Tambah Log Pelacakan Baru (Internal Service Only)

### Endpoint
POST /api/tracking-logs

### Headers
*   `X-Service-Key`: `[Isi dengan INTERNAL_SERVICE_KEY]` (wajib)
*   `Content-Type`: `application/json`

### Request Body
```json
{
  "package_id": 12,
  "resi_number": "SLBXT10001",
  "courier_id": 4,
  "warehouse_id": 2,
  "status": "Paket sedang diantar kurir",
  "location": "Sidoarjo",
  "notes": "Kurir: Budi, No HP: 081234567890",
  "source_service": "L4",
  "logged_at": "2026-05-31 11:15:00"
}
```

### Validasi Request Body (Request Validation)
*   `package_id` : `required|integer`
*   `resi_number` : `required|string|max:30`
*   `courier_id` : `nullable|integer`
*   `warehouse_id` : `nullable|integer`
*   `status` : `required|string|max:100`
*   `location` : `nullable|string|max:150`
*   `notes` : `nullable|string`
*   `source_service` : `nullable|string|max:10` (Default jika kosong: `'UNKNOWN'`)
*   `logged_at` : `nullable|date` (Default jika kosong: waktu saat ini/`now()`)

### Response Body (201 Created - Berhasil)
```json
{
  "status": "success",
  "message": "Log tracking berhasil dicatat.",
  "data": {
    "package_id": 12,
    "resi_number": "SLBXT10001",
    "courier_id": 4,
    "warehouse_id": 2,
    "status": "Paket sedang diantar kurir",
    "location": "Sidoarjo",
    "notes": "Kurir: Budi, No HP: 081234567890",
    "source_service": "L4",
    "logged_at": "2026-05-31T11:15:00.000000Z",
    "updated_at": "2026-05-31T11:16:00.000000Z",
    "created_at": "2026-05-31T11:16:00.000000Z",
    "id": 3
  }
}
```

---

## 5. Lihat Semua Log Pelacakan (Internal Service Only)

### Endpoint
GET /api/tracking-logs

### Headers
*   `X-Service-Key`: `[Isi dengan INTERNAL_SERVICE_KEY]` (wajib)

### Query Parameters
*   `date` (string format `YYYY-MM-DD`, optional) -> Memfilter log berdasarkan tanggal kejadian `logged_at`.
*   `source_service` (string, optional) -> Memfilter log berdasarkan servis pengirim (misal: `L2` atau `L4`).
*   `package_id` (integer, optional) -> Memfilter log berdasarkan package_id tertentu.

*Catatan: Response dibatasi maksimum 200 baris teratas demi performa, diurutkan menurun (`DESC`) berdasarkan `logged_at`.*

### Response Body (200 OK)
```json
{
  "status": "success",
  "total": 1,
  "data": [
    {
      "id": 3,
      "package_id": 12,
      "courier_id": 4,
      "warehouse_id": 2,
      "status": "Paket sedang diantar kurir",
      "location": "Sidoarjo",
      "notes": "Kurir: Budi, No HP: 081234567890",
      "source_service": "L4",
      "logged_at": "2026-05-31T11:15:00.000000Z",
      "created_at": "2026-05-31T11:16:00.000000Z",
      "updated_at": "2026-05-31T11:16:00.000000Z"
    }
  ]
}
```

---

## 6. Lacak Log Berdasarkan ID Paket (Internal Service Only)

### Endpoint
GET /api/tracking/package/{packageId}

### Headers
*   `X-Service-Key`: `[Isi dengan INTERNAL_SERVICE_KEY]` (wajib)

### URL Parameters
*   `packageId` (integer, required) -> ID Paket yang log perjalanannya ingin ditarik lengkap.

### Response Body (200 OK - Log Ditemukan)
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "package_id": 12,
      "courier_id": null,
      "warehouse_id": null,
      "status": "Paket Dibuat",
      "location": "Gudang Jakarta",
      "notes": "Barang dalam kondisi baik",
      "source_service": "L2",
      "logged_at": "2026-05-31T09:00:00.000000Z",
      "created_at": "2026-05-31T09:00:00.000000Z",
      "updated_at": "2026-05-31T09:00:00.000000Z"
    },
    {
      "id": 3,
      "package_id": 12,
      "courier_id": 4,
      "warehouse_id": 2,
      "status": "Paket sedang diantar kurir",
      "location": "Sidoarjo",
      "notes": "Kurir: Budi, No HP: 081234567890",
      "source_service": "L4",
      "logged_at": "2026-05-31T11:15:00.000000Z",
      "created_at": "2026-05-31T11:16:00.000000Z",
      "updated_at": "2026-05-31T11:16:00.000000Z"
    }
  ]
}
```

### Response Body (404 Not Found - ID Paket Tidak Memiliki Log)
```json
{
  "status": "error",
  "message": "Tidak ada log untuk package_id 999."
}
```

---

## 7. Hapus Log Pelacakan (Internal Service Only)

### Endpoint
DELETE /api/tracking-logs/{id}

### Headers
*   `X-Service-Key`: `[Isi dengan INTERNAL_SERVICE_KEY]` (wajib)

### URL Parameters
*   `id` (integer, required) -> ID Log Pelacakan (`tracking_logs.id`) yang ingin dihapus.

### Response Body (200 OK)
```json
{
  "status": "success",
  "message": "Log berhasil dihapus."
}
```
