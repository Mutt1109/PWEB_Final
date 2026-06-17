<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes — Login, Register, Logout
|--------------------------------------------------------------------------
| Rute autentikasi sederhana: login, register, dan logout.
*/

// Rute untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('login',    [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login',   [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register',[RegisteredUserController::class, 'store']);
});

// Rute untuk yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
