# IKL Ticket Hub — Sistem Pemesanan Tiket Pertandingan

Aplikasi web dinamis untuk pemesanan tiket pertandingan esports, dibangun menggunakan **Laravel** (PHP Framework) dengan **MySQL** sebagai database.

## Deskripsi Sistem

IKL Ticket Hub adalah sistem booking tiket pertandingan sederhana. User dapat mendaftar, login, melihat jadwal pertandingan, dan memesan tiket secara online. Pembayaran dilakukan di lokasi (bukan online). Admin dapat mengelola data pertandingan melalui halaman khusus.

## Fitur Utama

### 1. Frontend (HTML & CSS) — 10%
- Menggunakan **HTML5 semantik** (`<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<footer>`)
- Styling menggunakan **Tailwind CSS** (CDN) — responsif dan rapi
- 4 halaman: Login, Register, Dashboard, Kelola Pertandingan (Admin)

### 2. JavaScript & DOM Manipulation — 10%
- **Validasi form client-side** pada semua form (login, register, booking, pertandingan)
- **Estimasi harga real-time** saat user memilih pertandingan dan jumlah tiket
- **DOM manipulation** untuk menampilkan error dan hasil pencarian

### 3. Backend PHP & CRUD Database — 30%
- Framework **Laravel** dengan arsitektur MVC
- **CRUD lengkap** pada data tiket (Create, Read, Update status, Delete)
- **CRUD lengkap** pada data pertandingan oleh Admin
- Database **MySQL** dengan relasi tabel (users, matches, tickets)

### 4. Cookies & Session (Autentikasi) — 20%
- **Login & Logout** menggunakan session Laravel
- Halaman dashboard **terproteksi** — hanya user yang login bisa mengakses
- Halaman kelola pertandingan **hanya bisa diakses Admin** (middleware)
- Session di-regenerate saat login dan di-invalidate saat logout

### 5. Komunikasi Asinkron (AJAX/JSON) — 15%
- **Live search** pertandingan menggunakan **Fetch API**
- Request **POST** dikirim ke server, response **JSON** ditampilkan tanpa reload halaman
- Dilengkapi **loading indicator** dan **error handling**

### 6. Kualitas Kode & Dokumentasi — 5%
- Kode terstruktur dengan komentar yang jelas
- Penamaan variabel dan fungsi yang deskriptif
- README lengkap dengan penjelasan fitur

## Teknologi yang Digunakan

| Teknologi | Keterangan |
|-----------|-----------|
| PHP 8.3 | Bahasa pemrograman backend |
| Laravel 13 | Framework PHP |
| MySQL | Database (via Laragon) |
| Tailwind CSS | Framework CSS (CDN) |
| JavaScript | Validasi & AJAX |
| Fetch API | Komunikasi asinkron |

## Cara Instalasi & Menjalankan

### Prasyarat
- **Laragon** (sudah termasuk PHP, MySQL, Apache)
- **Composer** (sudah termasuk di Laragon)

### Langkah-langkah

```bash
# 1. Masuk ke folder project
cd d:\Kulyeahh\PWEB\PWEB_AKHIR

# 2. Install dependencies PHP
composer install

# 3. Buat database (pastikan Laragon sudah nyala)
mysql -u root -e "CREATE DATABASE IF NOT EXISTS tiket_ikl"

# 4. Jalankan migration dan seeder
php artisan migrate:fresh --seed

# 5. Jalankan server
php artisan serve
```

Buka browser: **http://localhost:8000**

## Akun Default

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@gmail.com | 1234 |
| **User** | user@gmail.com | 1234 |

## Struktur Halaman

| Halaman | URL | Akses | Keterangan |
|---------|-----|-------|-----------|
| Login | `/login` | Publik | Halaman login + validasi JS |
| Register | `/register` | Publik | Halaman registrasi + validasi JS |
| Dashboard | `/dashboard` | User | Booking tiket, AJAX search, tiket user (view only) |
| Kelola Pertandingan | `/matches` | Admin | CRUD data pertandingan |
| Kelola Pesanan | `/admin/tickets` | Admin | Kelola dan ubah status pesanan tiket seluruh user |

## Struktur Database

### Tabel `users`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | bigint | Primary key |
| name | varchar | Nama user |
| email | varchar | Email (unique) |
| role | varchar | 'admin' atau 'user' |
| password | varchar | Password (hashed) |

### Tabel `matches`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | bigint | Primary key |
| tim_tamu | varchar | Nama tim tamu |
| tim_kandang | varchar | Nama tim kandang |
| tanggal | date | Tanggal pertandingan |
| jam | varchar | Jam pertandingan |
| venue | varchar | Lokasi venue |
| harga | decimal | Harga per tiket |
| kapasitas | integer | Kapasitas kursi |
| aktif | boolean | Status aktif |

### Tabel `tickets`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | bigint | Primary key |
| match_id | bigint | FK ke tabel matches |
| user_id | bigint | FK ke tabel users |
| nama_pemesan | varchar | Nama pemesan |
| email | varchar | Email pemesan |
| no_hp | varchar | No. HP (opsional) |
| jumlah | integer | Jumlah tiket |
| total_harga | decimal | Total harga |
| status | enum | Aktif / Terpakai / Dibatalkan |
| kode_booking | varchar | Kode booking unik |
