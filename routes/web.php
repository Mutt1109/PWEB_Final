<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AdminTicketController;

/*
|--------------------------------------------------------------------------
| Web Routes — Sistem Pemesanan Tiket
|--------------------------------------------------------------------------
| Rute-rute utama aplikasi pemesanan tiket pertandingan.
*/

// Redirect halaman utama ke login
Route::get('/', fn() => redirect()->route('login'));

// ── RUTE AJAX (Komunikasi Asinkron) ─────────────────────────────────
// Pencarian pertandingan via Fetch API (tidak perlu login)
Route::post('/api/search', [SearchController::class, 'search'])->name('api.search');

// ── RUTE AUTH (hanya user yang sudah login) ─────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard: halaman utama user (lihat jadwal + booking tiket)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // CRUD Tiket (Create, Delete = di dashboard)
    Route::post('/tickets', [DashboardController::class, 'store'])->name('tickets.store');
    Route::delete('/tickets/{ticket}/cancel', [DashboardController::class, 'destroy'])->name('tickets.cancel');

    // ── ADMIN ONLY: Kelola Pertandingan & Tiket ─────────────────────────────
    Route::middleware('cek.admin')->group(function () {
        Route::resource('matches', MatchController::class);
        Route::get('/admin/tickets',         [AdminTicketController::class, 'index'])->name('admin.tickets.index');
        Route::put('/admin/tickets/{ticket}', [AdminTicketController::class, 'update'])->name('tickets.update');
        Route::delete('/admin/tickets/{ticket}', [AdminTicketController::class, 'destroy'])->name('tickets.destroy');
    });
});
