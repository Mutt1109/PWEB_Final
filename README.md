# IKL Ticket Hub

IKL Ticket Hub adalah platform web untuk melihat jadwal dan memesan tiket pertandingan. Sistem ini memfasilitasi interaksi yang lancar antara pengguna yang ingin memesan tiket dan admin yang mengelola pertandingan serta pesanan.

## Fitur Utama
- Autentikasi & Otorisasi Pengguna (Pengguna biasa & Admin)
- Dasbor Pribadi Pengguna (Pemesanan & Pembatalan Tiket)
- Pencarian Pertandingan Asinkron (AJAX)
- Pembuatan Kode Booking Otomatis
- Manajemen Pertandingan (Admin)
- Manajemen Pemesanan Tiket (Admin)

## Tumpukan Teknologi (Technology Stack)
- **Kerangka Kerja Backend**: Laravel 13 (PHP)
- **Basis Data**: MySQL / SQLite
- **Frontend**: HTML5, Tailwind CSS, Alpine.js, Blade Templates
- **Build Tool**: Vite

## Persyaratan Sistem
Pastikan server atau lingkungan pengembangan lokal Anda memenuhi persyaratan berikut:
- PHP >= 8.3
- Composer
- Node.js dan NPM
- Basis Data (MySQL / MariaDB / SQLite)

## Panduan Instalasi
Ikuti langkah-langkah berikut untuk mengatur proyek ini secara lokal:

1. **Kloning Repositori**: Kloning proyek ke dalam direktori server lokal Anda.
2. **Instalasi Dependensi PHP**: Arahkan terminal ke dalam direktori proyek dan jalankan Composer.
   ```bash
   composer install
   ```
3. **Instalasi Dependensi Frontend**: Instal dependensi Node.js.
   ```bash
   npm install
   ```
4. **Konfigurasi Lingkungan (Environment)**: Salin berkas `.env.example` menjadi `.env` lalu sesuaikan konfigurasi basis data Anda.
   ```bash
   cp .env.example .env
   ```
5. **Hasilkan Kunci Aplikasi**: Buat kunci aplikasi yang aman untuk Laravel.
   ```bash
   php artisan key:generate
   ```
6. **Jalankan Migrasi Basis Data**: Buat tabel dan jalankan *seeder*.
   ```bash
   php artisan migrate --seed
   ```
7. **Jalankan Aplikasi**: Mulai server backend dan frontend secara bersamaan.
   Buka dua terminal terpisah:
   ```bash
   php artisan serve
   ```
   ```bash
   npm run dev
   ```

Aplikasi dapat diakses melalui peramban pada alamat `http://localhost:8000`.

## Sorotan Struktur Direktori
- `app/Http/Controllers/`: Berisi logika bisnis aplikasi (contohnya, `MatchController.php`, `AdminTicketController.php`, `DashboardController.php`).
- `app/Models/`: Model Eloquent ORM yang merepresentasikan tabel basis data (`IklMatch.php`, `Ticket.php`, `User.php`).
- `resources/views/`: Templat antarmuka (Blade + Tailwind).
- `routes/web.php`: Mendefinisikan jalur (*routes*) web dan perlindungan *middleware*.
