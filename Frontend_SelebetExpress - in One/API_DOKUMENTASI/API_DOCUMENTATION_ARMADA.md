# Layanan Armada (Service Armada - L4)

## Base URL
http://127.0.0.1:8004

---

# Arsitektur & Aturan Layanan

Layanan Armada (L4) adalah bagian dari ekosistem microservice ekspedisi. Layanan ini bertanggung jawab atas pengelolaan kurir (couriers) dan penugasan pengiriman (deliveries).

### 1. Inter-Service Authentication
Hampir seluruh endpoint API pada layanan ini dilindungi oleh middleware `service.key` (terkecuali `/api/health`). 
Setiap request ke endpoint yang dilindungi wajib menyertakan HTTP Header berikut:
- **Header**: `X-Service-Key`
- **Value**: Diambil dari `INTERNAL_SERVICE_KEY` pada file `.env` (contoh: `rahasia-internal-ekspedisi-2024`).

Jika Header tidak valid atau kosong, server akan mengembalikan:
- **HTTP Status**: `401 Unauthorized`
- **JSON Response**:
```json
{
  "status": "error",
  "message": "Akses ditolak. Service key tidak valid."
}
```

### 2. Integrasi & Notifikasi Outgoing (L5 Pelacakan)
Setiap kali terjadi perubahan status delivery (baik saat baru dibuat/assigned, maupun diupdate statusnya oleh kurir), Layanan Armada **wajib** mengirimkan notifikasi log status ke **L5 Pelacakan** (Service Pelacakan) menggunakan class `App\Services\TrackingNotifier`.
- **Endpoint L5**: `POST {SERVICE_PELACAKAN}/api/tracking/log`
- **Headers**: `X-Service-Key` dengan value internal key.
- **Body**:
```json
{
  "package_id": 12,
  "courier_id": 5,
  "status": "assigned",
  "location": "Gudang asal",
  "notes": "Kurir Eko Prasetyo ditugaskan.",
  "logged_at": "2026-05-31T07:22:28+07:00"
}
```

### 3. Struktur Umum JSON Response
- **Respons Sukses**:
  Selalu mengembalikan HTTP Status `200 OK` (atau `201 Created` untuk pembuatan data) dengan format:
  ```json
  {
    "status": "success",
    "message": "Pesan informasi (opsional)",
    "data": { ... } // Dapat berupa objek, array, atau paginated object
  }
  ```
- **Respons Gagal**:
  - **Kesalahan Validasi (422 Unprocessable Content)**:
    Menggunakan format standar Laravel:
    ```json
    {
      "message": "Pesan error validasi utama",
      "errors": {
        "nama_field": [
          "Penjelasan error detail"
        ]
      }
    }
    ```
  - **Kesalahan Data / Bisnis Logic (422 Unprocessable Content)**:
    ```json
    {
      "status": "error",
      "message": "Penjelasan kegagalan proses."
    }
    ```
  - **Data Tidak Ditemukan (404 Not Found)**:
    ```json
    {
      "status": "error",
      "message": "Delivery untuk paket ini belum ada." // atau default Exception handler
    }
    ```

---

# Database Schema & Models

### 1. Model `Courier` (Table: `couriers`)
Digunakan untuk menyimpan informasi kurir yang bertugas di armada ekspedisi.
- `id` (unsigned big integer, Primary Key)
- `user_id` (unsigned big integer, Unique) — Menghubungkan kurir dengan data user di L1 (db_pengguna), tidak memiliki foreign key constraint fisik karena beda database.
- `warehouse_id` (unsigned big integer) — Gudang asal / tugas kurir.
- `name` (string max 100, nullable) — Nama kurir.
- `phone` (string max 20, nullable, Unique) — Nomor telepon kurir.
- `vehicle_type` (string max 30, default: `motor`) — Jenis kendaraan. Opsi valid: `motor`, `mobil`, `truck`.
- `vehicle_plate` (string max 15, nullable) — Nomor plat kendaraan.
- `status` (enum, default: `available`) — Status aktif kurir. Opsi valid: `available` (tersedia), `on_duty` (sedang mengantar), `off_duty` (tidak aktif).
- `last_active_at` (timestamp, nullable) — Waktu aktivitas terakhir kurir.
- `created_at` & `updated_at` (timestamps)
- `deleted_at` (soft deletes)

### 2. Model `Delivery` (Table: `deliveries`)
Digunakan untuk menyimpan riwayat penugasan kurir ke suatu paket (package_id).
- `id` (unsigned big integer, Primary Key)
- `package_id` (unsigned big integer, Unique) — ID paket dari L2 (database paket), tanpa constraint fisik. Satu paket hanya bisa memiliki satu delivery record aktif.
- `courier_id` (foreign key to `couriers.id`, restrict on delete) — ID kurir yang bertugas.
- `origin_warehouse_id` (unsigned big integer) — Gudang asal pengiriman.
- `dest_warehouse_id` (unsigned big integer) — Gudang/alamat tujuan.
- `delivery_type` (string max 50) — Tipe pengiriman (contoh: `instant`, `regular`, `cargo`).
- `status` (enum, default: `assigned`) — Status pengiriman saat ini. Opsi valid:
  - `assigned` (kurir ditugaskan)
  - `picked_up` (paket diambil)
  - `in_transit` (sedang dikirim)
  - `out_for_delivery` (kurir menuju alamat penerima)
  - `delivered` (diterima sukses)
  - `failed` (gagal terkirim)
  - `returned` (dikembalikan)
- `current_location` (string max 150, nullable) — Lokasi paket saat ini.
- `notes` (text, nullable) — Catatan pengiriman.
- `assigned_at` (timestamp, nullable) — Waktu penugasan kurir.
- `picked_up_at` (timestamp, nullable) — Waktu kurir mengambil paket.
- `delivered_at` (timestamp, nullable) — Waktu paket berhasil diterima (`delivered`).
- `created_at` & `updated_at` (timestamps)

---

# Endpoint List

## 1. Health Check

### Endpoint
GET /api/health

### Headers
*Tidak memerlukan headers X-Service-Key*

### Request Body
*None*

### Response Success
**HTTP Status**: 200 OK
```json
{
  "service": "Layanan Armada",
  "status": "ok",
  "time": "2026-05-31T14:21:49.000000+07:00"
}
```

---

## 2. List Couriers

### Endpoint
GET /api/couriers

### Headers
- `X-Service-Key`: `rahasia-internal-ekspedisi-2024`

### Request Query Parameters
- `status` (optional): Filter berdasarkan status kurir (`available`, `on_duty`, `off_duty`).
- `search` (optional): Pencarian nama kurir atau nomor HP (`LIKE` pencarian).

### Request Body
*None*

### Response Success
**HTTP Status**: 200 OK
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "user_id": 4,
      "warehouse_id": 1,
      "name": "Dodi Kuswara",
      "phone": "084444444444",
      "vehicle_type": "motor",
      "vehicle_plate": "B 5678 ABC",
      "status": "available",
      "last_active_at": null,
      "created_at": "2026-05-31T07:22:28.000000Z",
      "updated_at": "2026-05-31T07:22:28.000000Z",
      "deleted_at": null,
      "total_deliveries": 0,
      "active_deliveries_count": 0
    }
  ]
}
```

---

## 3. Register Courier

### Endpoint
POST /api/couriers

### Headers
- `X-Service-Key`: `rahasia-internal-ekspedisi-2024`

### Request Body
```json
{
  "user_id": 10,
  "warehouse_id": 2,
  "name": "Budi Santoso",
  "phone": "08123456789",
  "vehicle_type": "motor",
  "vehicle_plate": "B 1234 CD"
}
```

### Request Validation Rules
- `user_id` (required, integer, unique:couriers,user_id)
- `warehouse_id` (required, integer)
- `name` (required, string, max:100)
- `phone` (required, string, max:20, unique:couriers,phone)
- `vehicle_type` (nullable, string, in:motor,mobil,truck)
- `vehicle_plate` (nullable, string, max:15)

### Response Success
**HTTP Status**: 201 Created
```json
{
  "status": "success",
  "message": "Kurir berhasil didaftarkan.",
  "data": {
    "user_id": 10,
    "warehouse_id": 2,
    "name": "Budi Santoso",
    "phone": "08123456789",
    "vehicle_type": "motor",
    "vehicle_plate": "B 1234 CD",
    "status": "available",
    "updated_at": "2026-05-31T07:22:28.000000Z",
    "created_at": "2026-05-31T07:22:28.000000Z",
    "id": 3
  }
}
```

### Response Failure (Validation Error)
**HTTP Status**: 422 Unprocessable Content
```json
{
  "message": "The user id has already been taken.",
  "errors": {
    "user_id": [
      "The user id has already been taken."
    ]
  }
}
```

---

## 4. Get Courier Detail

### Endpoint
GET /api/couriers/{id}

### Headers
- `X-Service-Key`: `rahasia-internal-ekspedisi-2024`

### Request Body
*None*

### Response Success
*Mengembalikan data detail kurir dan memuat relasi 10 delivery terakhirnya.*
**HTTP Status**: 200 OK
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "user_id": 4,
    "warehouse_id": 1,
    "name": "Dodi Kuswara",
    "phone": "084444444444",
    "vehicle_type": "motor",
    "vehicle_plate": "B 5678 ABC",
    "status": "available",
    "last_active_at": null,
    "created_at": "2026-05-31T07:22:28.000000Z",
    "updated_at": "2026-05-31T07:22:28.000000Z",
    "deleted_at": null,
    "deliveries": []
  }
}
```

### Response Failure (Not Found)
**HTTP Status**: 404 Not Found
```json
{
  "message": "No query results for model [App\\Models\\Courier] 99"
}
```

---

## 5. Update Courier

### Endpoint
PUT /api/couriers/{id}

### Headers
- `X-Service-Key`: `rahasia-internal-ekspedisi-2024`

### Request Body
```json
{
  "warehouse_id": 2,
  "vehicle_type": "mobil",
  "vehicle_plate": "B 9999 XYZ",
  "status": "off_duty"
}
```

### Request Validation Rules
- `warehouse_id` (sometimes, integer)
- `vehicle_type` (sometimes, string, in:motor,mobil,truck)
- `vehicle_plate` (sometimes, string, max:15)
- `status` (sometimes, string)

### Response Success
**HTTP Status**: 200 OK
```json
{
  "status": "success",
  "message": "Data kurir diupdate.",
  "data": {
    "id": 1,
    "user_id": 4,
    "warehouse_id": 2,
    "name": "Dodi Kuswara",
    "phone": "084444444444",
    "vehicle_type": "mobil",
    "vehicle_plate": "B 9999 XYZ",
    "status": "off_duty",
    "last_active_at": null,
    "created_at": "2026-05-31T07:22:28.000000Z",
    "updated_at": "2026-05-31T07:23:45.000000Z",
    "deleted_at": null
  }
}
```

---

## 6. Deactivate Courier (Soft Delete)

### Endpoint
DELETE /api/couriers/{id}

### Headers
- `X-Service-Key`: `rahasia-internal-ekspedisi-2024`

### Request Body
*None*

### Response Success
**HTTP Status**: 200 OK
```json
{
  "status": "success",
  "message": "Kurir dinonaktifkan."
}
```

### Response Failure (Active Delivery Lock)
*Kurir tidak bisa dihapus jika sedang memproses delivery aktif.*
**HTTP Status**: 422 Unprocessable Content
```json
{
  "status": "error",
  "message": "Kurir masih memiliki pengiriman aktif. Selesaikan dulu sebelum menonaktifkan."
}
```

---

## 7. Courier Delivery History

### Endpoint
GET /api/couriers/{id}/deliveries

### Headers
- `X-Service-Key`: `rahasia-internal-ekspedisi-2024`

### Request Query Parameters
- `status` (optional): Filter berdasarkan status delivery kurir.

### Request Body
*None*

### Response Success
*Mengembalikan paginated list pengiriman dari kurir terkait.*
**HTTP Status**: 200 OK
```json
{
  "status": "success",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "package_id": 1001,
        "courier_id": 1,
        "origin_warehouse_id": 1,
        "dest_warehouse_id": 2,
        "delivery_type": "instant",
        "status": "delivered",
        "current_location": "Rumah Penerima",
        "notes": "Diterima oleh Bpk. Budi",
        "assigned_at": "2026-05-31T07:30:00.000000Z",
        "picked_up_at": "2026-05-31T07:45:00.000000Z",
        "delivered_at": "2026-05-31T08:15:00.000000Z",
        "created_at": "2026-05-31T07:30:00.000000Z",
        "updated_at": "2026-05-31T08:15:00.000000Z"
      }
    ],
    "first_page_url": "http://127.0.0.1:8004/api/couriers/1/deliveries?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://127.0.0.1:8004/api/couriers/1/deliveries?page=1",
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http://127.0.0.1:8004/api/couriers/1/deliveries?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": null,
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "next_page_url": null,
    "path": "http://127.0.0.1:8004/api/couriers/1/deliveries",
    "per_page": 20,
    "prev_page_url": null,
    "to": 1,
    "total": 1
  }
}
```

---

## 8. Get Single Delivery by Package ID

### Endpoint
GET /api/deliveries/by-package/{packageId}

### Headers
- `X-Service-Key`: `rahasia-internal-ekspedisi-2024`

### Request Body
*None*

### Response Success
**HTTP Status**: 200 OK
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "package_id": 1001,
    "courier_id": 1,
    "origin_warehouse_id": 1,
    "dest_warehouse_id": 2,
    "delivery_type": "instant",
    "status": "assigned",
    "current_location": "Gudang asal",
    "notes": "Kurir Dodi Kuswara ditugaskan.",
    "assigned_at": "2026-05-31T07:30:00.000000Z",
    "picked_up_at": null,
    "delivered_at": null,
    "created_at": "2026-05-31T07:30:00.000000Z",
    "updated_at": "2026-05-31T07:30:00.000000Z",
    "courier": {
      "id": 1,
      "user_id": 4,
      "warehouse_id": 1,
      "name": "Dodi Kuswara",
      "phone": "084444444444",
      "vehicle_type": "motor",
      "vehicle_plate": "B 5678 ABC",
      "status": "available",
      "last_active_at": null,
      "created_at": "2026-05-31T07:22:28.000000Z",
      "updated_at": "2026-05-31T07:22:28.000000Z",
      "deleted_at": null
    }
  }
}
```

### Response Failure (Not Found)
**HTTP Status**: 404 Not Found
```json
{
  "status": "error",
  "message": "Delivery untuk paket ini belum ada."
}
```

---

## 9. Get All Deliveries by Package ID

### Endpoint
GET /api/deliveries/package/{packageId}

### Headers
- `X-Service-Key`: `rahasia-internal-ekspedisi-2024`

### Request Body
*None*

### Response Success
*Mengembalikan koleksi / array dari seluruh data delivery yang diasosiasikan dengan packageId tersebut.*
**HTTP Status**: 200 OK
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "package_id": 1001,
      "courier_id": 1,
      "origin_warehouse_id": 1,
      "dest_warehouse_id": 2,
      "delivery_type": "instant",
      "status": "assigned",
      "current_location": "Gudang asal",
      "notes": "Kurir Dodi Kuswara ditugaskan.",
      "assigned_at": "2026-05-31T07:30:00.000000Z",
      "picked_up_at": null,
      "delivered_at": null,
      "created_at": "2026-05-31T07:30:00.000000Z",
      "updated_at": "2026-05-31T07:30:00.000000Z",
      "courier": {
        "id": 1,
        "user_id": 4,
        "warehouse_id": 1,
        "name": "Dodi Kuswara",
        "phone": "084444444444",
        "vehicle_type": "motor",
        "vehicle_plate": "B 5678 ABC",
        "status": "available",
        "last_active_at": null,
        "created_at": "2026-05-31T07:22:28.000000Z",
        "updated_at": "2026-05-31T07:22:28.000000Z",
        "deleted_at": null
      }
    }
  ]
}
```

---

## 10. List Deliveries

### Endpoint
GET /api/deliveries

### Headers
- `X-Service-Key`: `rahasia-internal-ekspedisi-2024`

### Request Query Parameters
- `courier_id` (optional): Filter berdasarkan ID Kurir.
- `status` (optional): Filter berdasarkan status delivery (`assigned`, `picked_up`, `in_transit`, etc.).
- `package_id` (optional): Filter berdasarkan ID Paket.

### Request Body
*None*

### Response Success
*Mengembalikan paginated list dari seluruh data delivery yang ada.*
**HTTP Status**: 200 OK
```json
{
  "status": "success",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "package_id": 1001,
        "courier_id": 1,
        "origin_warehouse_id": 1,
        "dest_warehouse_id": 2,
        "delivery_type": "instant",
        "status": "assigned",
        "current_location": "Gudang asal",
        "notes": "Kurir Dodi Kuswara ditugaskan.",
        "assigned_at": "2026-05-31T07:30:00.000000Z",
        "picked_up_at": null,
        "delivered_at": null,
        "created_at": "2026-05-31T07:30:00.000000Z",
        "updated_at": "2026-05-31T07:30:00.000000Z",
        "courier": {
          "id": 1,
          "user_id": 4,
          "name": "Dodi Kuswara"
        }
      }
    ],
    ...
  }
}
```

---

## 11. Get Delivery Detail

### Endpoint
GET /api/deliveries/{id}

### Headers
- `X-Service-Key`: `rahasia-internal-ekspedisi-2024`

### Request Body
*None*

### Response Success
**HTTP Status**: 200 OK
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "package_id": 1001,
    "courier_id": 1,
    "origin_warehouse_id": 1,
    "dest_warehouse_id": 2,
    "delivery_type": "instant",
    "status": "assigned",
    "current_location": "Gudang asal",
    "notes": "Kurir Dodi Kuswara ditugaskan.",
    "assigned_at": "2026-05-31T07:30:00.000000Z",
    "picked_up_at": null,
    "delivered_at": null,
    "created_at": "2026-05-31T07:30:00.000000Z",
    "updated_at": "2026-05-31T07:30:00.000000Z",
    "courier": {
      "id": 1,
      "user_id": 4,
      "warehouse_id": 1,
      "name": "Dodi Kuswara",
      "phone": "084444444444",
      "vehicle_type": "motor",
      "vehicle_plate": "B 5678 ABC",
      "status": "available",
      "last_active_at": null,
      "created_at": "2026-05-31T07:22:28.000000Z",
      "updated_at": "2026-05-31T07:22:28.000000Z",
      "deleted_at": null
    }
  }
}
```

---

## 12. Assign Courier (Create Delivery)

### Endpoint
POST /api/deliveries

### Headers
- `X-Service-Key`: `rahasia-internal-ekspedisi-2024`

### Request Body
```json
{
  "package_id": 1001,
  "courier_id": 1,
  "origin_warehouse_id": 1,
  "dest_warehouse_id": 2,
  "delivery_type": "instant",
  "current_location": "Gudang Asal Utama",
  "notes": "Paket pecah belah, harap hati-hati."
}
```

### Request Validation Rules
- `package_id` (required, integer, unique:deliveries,package_id)
- `courier_id` (required, integer, exists:couriers,id)
- `origin_warehouse_id` (required, integer)
- `dest_warehouse_id` (required, integer)
- `delivery_type` (required, string)
- `current_location` (nullable, string, max:150)
- `notes` (nullable, string)

### Response Success
*Membuat data pengiriman baru, mengupdate status pengantaran, dan memicu notifikasi `TrackingNotifier` ke L5.*
**HTTP Status**: 201 Created
```json
{
  "status": "success",
  "message": "Kurir berhasil ditugaskan ke paket.",
  "data": {
    "package_id": 1001,
    "courier_id": 1,
    "origin_warehouse_id": 1,
    "dest_warehouse_id": 2,
    "delivery_type": "instant",
    "current_location": "Gudang Asal Utama",
    "notes": "Paket pecah belah, harap hati-hati.",
    "status": "assigned",
    "assigned_at": "2026-05-31T07:30:00.000000Z",
    "created_at": "2026-05-31T07:30:00.000000Z",
    "updated_at": "2026-05-31T07:30:00.000000Z",
    "id": 1,
    "courier": {
      "id": 1,
      "user_id": 4,
      "warehouse_id": 1,
      "name": "Dodi Kuswara",
      "phone": "084444444444",
      "vehicle_type": "motor",
      "vehicle_plate": "B 5678 ABC",
      "status": "available",
      "last_active_at": null,
      "created_at": "2026-05-31T07:22:28.000000Z",
      "updated_at": "2026-05-31T07:22:28.000000Z",
      "deleted_at": null
    }
  }
}
```

### Response Failure (Validation Error - Package ID Already Assigned)
**HTTP Status**: 422 Unprocessable Content
```json
{
  "message": "The package id has already been taken.",
  "errors": {
    "package_id": [
      "The package id has already been taken."
    ]
  }
}
```

---

## 13. Update Delivery Status

### Endpoint
PATCH /api/deliveries/{id}/status

### Headers
- `X-Service-Key`: `rahasia-internal-ekspedisi-2024`

### Request Body
```json
{
  "delivery_type": "instant",
  "status": "in_transit",
  "location": "Simpang Lima Semarang",
  "notes": "Paket sedang dalam perjalanan darat."
}
```

### Request Validation Rules
- `delivery_type` (required, string)
- `status` (required, string)
- `location` (nullable, string, max:150)
- `notes` (nullable, string)

### Alur Bisnis pada Proses ini:
1. **Pengisian Timestamp Otomatis**:
   - Jika status di-update ke `picked_up`, sistem mengisi `picked_up_at = now()`.
   - Jika status di-update ke `delivered`, sistem mengisi `delivered_at = now()`.
2. **Update Status Kurir**:
   - Jika status baru bernilai terminal (`delivered`, `failed`, `returned`), status kurir akan kembali diset menjadi `available`.
   - Jika status masih berjalan (seperti `picked_up`, `in_transit`, `out_for_delivery`), status kurir diset/dipertahankan menjadi `on_duty`.
   - `last_active_at` milik kurir diperbarui ke waktu sekarang.
3. **Kirim Tracking Log ke L5**:
   - Menggunakan `TrackingNotifier` untuk mengirim log perubahan status ke Service Pelacakan (L5).

### Response Success
**HTTP Status**: 200 OK
```json
{
  "status": "success",
  "message": "Status pengiriman diupdate.",
  "data": {
    "id": 1,
    "package_id": 1001,
    "courier_id": 1,
    "origin_warehouse_id": 1,
    "dest_warehouse_id": 2,
    "delivery_type": "instant",
    "status": "in_transit",
    "current_location": "Simpang Lima Semarang",
    "notes": "Paket sedang dalam perjalanan darat.",
    "assigned_at": "2026-05-31T07:30:00.000000Z",
    "picked_up_at": "2026-05-31T07:45:00.000000Z",
    "delivered_at": null,
    "created_at": "2026-05-31T07:30:00.000000Z",
    "updated_at": "2026-05-31T07:50:00.000000Z",
    "courier": {
      "id": 1,
      "user_id": 4,
      "warehouse_id": 1,
      "name": "Dodi Kuswara",
      "phone": "084444444444",
      "vehicle_type": "motor",
      "vehicle_plate": "B 5678 ABC",
      "status": "on_duty",
      "last_active_at": "2026-05-31T07:50:00.000000Z",
      "created_at": "2026-05-31T07:22:28.000000Z",
      "updated_at": "2026-05-31T07:50:00.000000Z",
      "deleted_at": null
    }
  }
}
```

### Response Failure (Delivery Already Finished)
*Pengiriman yang sudah mencapai status akhir (terminal) tidak dapat diperbarui lagi.*
**HTTP Status**: 422 Unprocessable Content
```json
{
  "status": "error",
  "message": "Pengiriman sudah selesai, status tidak bisa diubah."
}
```
