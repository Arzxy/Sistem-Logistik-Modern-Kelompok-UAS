# 🚚 SelebetExpress — Sistem Logistik Modern

> Sistem manajemen logistik berbasis arsitektur **microservice** yang dibangun dengan Laravel 10. Mencakup pengelolaan paket, manajemen kurir & armada, perhitungan tarif, pelacakan pengiriman, serta autentikasi pengguna — semua terintegrasi melalui satu frontend terpusat.

---

## 📦 Daftar Repositori

Proyek ini terdiri dari **6 repositori terpisah** (1 frontend + 5 backend service):

| Repositori | Deskripsi | Database | Port Default |
|---|---|---|---|
| `Frontend_SelebetExpress` | Frontend terpusat (Laravel Blade + Tailwind) | — | 8000 |
| `LARAVEL_10_SelebretExpress-Pengguna` | Autentikasi & manajemen pengguna | `db_pengguna` | 8001 |
| `LARAVEL_10_SelebretExpress-Paket` | Manajemen paket pengiriman | `db_paket` | 8002 |
| `LARAVEL_10_SelebretExpress-Tarif` | Perhitungan tarif & ongkos kirim | `db_tarif` | 8003 |
| `LARAVEL_10_SelebretExpress-Armada` | Manajemen kurir & armada kendaraan | `db_armada` | 8004 |
| `LARAVEL_10_SelebretExpress-Pelacakan` | Tracking & histori pengiriman | `db_pelacakan` | 8005 |

---

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────────────┐
│              Frontend SelebetExpress                │
│         (Laravel 10 · Blade · Tailwind CSS)         │
│                                                     │
│  Role: Admin · Kasir · Agen · Kurir                 │
└──────────────────────┬──────────────────────────────┘
                       │ HTTP (internal API key)
          ┌────────────┼────────────┐
          ▼            ▼            ▼
   ┌──────────┐  ┌──────────┐  ┌──────────┐
   │Pengguna  │  │  Paket   │  │  Tarif   │
   │ :8001    │  │  :8002   │  │  :8003   │
   └──────────┘  └──────────┘  └──────────┘
          ┌────────────┐
          ▼            ▼
   ┌──────────┐  ┌──────────────┐
   │  Armada  │  │  Pelacakan   │
   │  :8004   │  │    :8005     │
   └──────────┘  └──────────────┘
```

---

## ✨ Fitur Utama

### 🖥️ Frontend
- **Dashboard Admin** — monitoring paket, user, tarif, gudang
- **Dashboard Kasir** — buat paket baru, riwayat transaksi
- **Dashboard Agen** — assign kurir, konfirmasi paket tiba di gudang
- **Tampilan Kurir** — lihat daftar tugas, update status pengiriman, navigasi Google Maps, hubungi penerima via WhatsApp
- **Halaman Status Server** — pantau uptime semua layanan backend secara real-time dengan auto-refresh 30 detik
- **Tracking Publik** — lacak status paket tanpa login
- **Toast Notifikasi** — notifikasi SweetAlert2 untuk semua aksi & error koneksi backend

### 🔧 Backend Services

| Service | Fitur Utama |
|---|---|
| **Pengguna** | Register/Login, JWT token, manajemen user & role (admin, kasir, agen, kurir) |
| **Paket** | CRUD paket, generate nomor resi, assign gudang asal & tujuan |
| **Tarif** | Kalkulasi ongkir berdasarkan berat & rute antar gudang |
| **Armada** | Manajemen kurir, status kurir (available/on_duty), assign delivery, update status pengiriman |
| **Pelacakan** | Histori tracking per resi, logging setiap perubahan status paket |

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|---|---|
| Backend Framework | Laravel 10 (PHP 8.1+) |
| Frontend Styling | Tailwind CSS v3 |
| Build Tool | Vite |
| HTTP Client | Guzzle / Laravel HTTP Facade |
| Autentikasi | Laravel Sanctum (token-based) |
| Database | MySQL 8 |
| Notifikasi | SweetAlert2 |
| Icon | Boxicons 2 |
| Tunnel (dev) | ngrok |

---

## ⚡ Cara Menjalankan

### Prasyarat
- PHP >= 8.1
- Composer
- Node.js >= 18 & npm
- MySQL 8
- XAMPP / Laragon (opsional)

---

### 1. Clone semua repositori

```bash
git clone https://github.com/Arzxy/Frontend_SelebetExpress.git
git clone https://github.com/Arzxy/LARAVEL_10_SelebretExpress-Pengguna.git
git clone https://github.com/Arzxy/LARAVEL_10_SelebretExpress-Paket.git
git clone https://github.com/Arzxy/LARAVEL_10_SelebretExpress-Tarif.git
git clone https://github.com/Arzxy/LARAVEL_10_SelebretExpress-Armada.git
git clone https://github.com/Arzxy/LARAVEL_10_SelebretExpress-Pelacakan.git
```

---

### 2. Setup masing-masing Backend Service

Lakukan langkah berikut untuk **setiap** repositori backend:

```bash
# Masuk ke folder backend (contoh: Pengguna)
cd LARAVEL_10_SelebretExpress-Pengguna

# Install dependensi
composer install

# Salin .env
cp .env.example .env

# Generate app key
php artisan key:generate

# Konfigurasi database di .env
# DB_DATABASE=db_pengguna  (sesuaikan per service)
# DB_USERNAME=root
# DB_PASSWORD=

# Migrasi & seed database
php artisan migrate --seed

# Jalankan server (sesuaikan port per service)
php artisan serve --port=8001   # Pengguna
php artisan serve --port=8002   # Paket
php artisan serve --port=8003   # Tarif
php artisan serve --port=8004   # Armada
php artisan serve --port=8005   # Pelacakan
```

> 💡 Gunakan `run-all.bat` yang tersedia di root `htdocs` untuk menjalankan semua service sekaligus.

---

### 3. Setup Frontend

```bash
cd Frontend_SelebetExpress

# Install dependensi PHP
composer install

# Install dependensi Node
npm install

# Salin dan konfigurasi .env.test.dev (kalo emang masih percobaan)
cp .env.test.dev .env
php artisan key:generate
```

Edit `.env` dan isi URL masing-masing service:

```env
APP_URL=http://localhost:8000

INTERNAL_SERVICE_KEY=rahasia-internal-ekspedisi-2024

SERVICE_PENGGUNA=http://127.0.0.1:8001
SERVICE_PAKET=http://127.0.0.1:8002
SERVICE_TARIF=http://127.0.0.1:8003
SERVICE_ARMADA=http://127.0.0.1:8004
SERVICE_PELACAKAN=http://127.0.0.1:8005
```

```bash
# Jalankan frontend
php artisan serve --port=8000

# Jalankan Vite (mode development)
npm run dev
```

---

### 4. Akses Aplikasi

Buka browser dan kunjungi `http://localhost:8000`

| Halaman | URL |
|---|---|
| Beranda | `/` |
| Login | `/login` |
| Lacak Paket | `/tracking` |
| Status Server | `/status` |
| Dashboard Admin | `/admin/dashboard` |
| Dashboard Kasir | `/kasir/dashboard` |
| Dashboard Agen | `/agent/dashboard` |
| Tampilan Kurir | `/courier/packages` |

---

## 🌐 Deployment dengan ngrok

Untuk akses dari perangkat lain di jaringan berbeda:

```bash
# Build frontend
npm run build

# Jalankan server
php artisan serve --port=8000

# Buka tunnel (terminal lain)
ngrok http 8000
```

Update `.env` frontend dengan URL ngrok:

```env
APP_URL=https://xxxx.ngrok-free.app
ASSET_URL=https://xxxx.ngrok-free.app
```

---

## 📁 Struktur Folder Frontend

```
Frontend_SelebetExpress/
├── app/
│   ├── Http/Controllers/       # Controller per fitur
│   └── Services/               # Service layer (HTTP call ke backend)
│       ├── HandlesServiceErrors.php  # Trait global error handling
│       ├── AuthService.php
│       ├── PackageService.php
│       └── ...
├── resources/
│   └── views/
│       ├── layouts/            # Layout: dashboard, courier, public
│       ├── auth/               # Halaman login
│       ├── public/             # Halaman publik (home, tracking, dsb)
│       ├── dashboard/          # Halaman admin/kasir/agen/courier
│       ├── components/         # Komponen reusable (navbar, sidebar)
│       └── status.blade.php    # Monitoring status server
└── routes/
    └── web.php
```

---

## 👥 Role & Hak Akses

| Role | Akses |
|---|---|
| **Admin** | Semua fitur: paket, user, tarif, gudang, assign kurir |
| **Kasir** | Buat paket baru, lihat riwayat transaksi |
| **Agen** | Assign kurir ke paket, konfirmasi paket tiba di gudang |
| **Kurir** | Lihat tugas pengiriman, update status, navigasi Maps & WA |

---

## 🔌 API Endpoints Utama

### Layanan Pengguna
| Method | Endpoint | Keterangan |
|---|---|---|
| POST | `/api/auth/login` | Login pengguna |
| GET | `/api/users` | Daftar semua pengguna |
| POST | `/api/users` | Tambah pengguna baru |
| DELETE | `/api/users/{id}` | Hapus pengguna |
| GET | `/api/health` | Health check |

### Layanan Paket
| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/packages` | Daftar paket |
| POST | `/api/packages` | Buat paket baru |
| GET | `/api/packages/{id}` | Detail paket |
| PATCH | `/api/packages/{id}/status` | Update status paket |

### Layanan Armada
| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/couriers` | Daftar kurir |
| GET | `/api/deliveries` | Daftar delivery |
| POST | `/api/deliveries` | Buat delivery baru |
| PATCH | `/api/deliveries/{id}/status` | Update status delivery |
| PATCH | `/api/couriers/{id}/status` | Toggle on_duty/available |

### Layanan Tarif
| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/tariffs` | Daftar tarif |
| POST | `/api/tariffs/calculate` | Hitung ongkir |

### Layanan Pelacakan
| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/tracking/{resi}` | Lacak paket by resi |
| POST | `/api/tracking` | Tambah log tracking |

---

## 🤝 Kontribusi

1. Fork repositori
2. Buat branch baru: `git checkout -b fitur/nama-fitur`
3. Commit: `git commit -m "feat: deskripsi perubahan"`
4. Push: `git push origin fitur/nama-fitur`
5. Buat Pull Request

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademis (Tugas Akhir / UAS). Lisensi MIT.

---

<div align="center">
  <strong>SelebetExpress</strong> — Sistem Logistik Modern Kelompok UAS<br>
  Dibangun dengan ❤️ menggunakan Laravel 10
</div>
