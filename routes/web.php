<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Sewa Lapangan Mini GOR Syafea
|--------------------------------------------------------------------------
| Di sini Anda mendaftarkan rute web untuk aplikasi Anda.
| Rute-rute ini dimuat oleh RouteServiceProvider dalam grup "web".
|
*/

// Rute Pengunjung & Pelanggan (Home, Jadwal, Form Pemesanan)
Route::get('/', [BookingController::class, 'index'])->name('booking.index');
Route::get('/pemesanan', [BookingController::class, 'create'])->name('booking.create');
Route::post('/pemesanan', [BookingController::class, 'store'])->name('booking.store');

// Rute Admin Panel (Verifikasi Pembayaran)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('/verify/{id}', [AdminController::class, 'verify'])->name('verify');
});

// Rute Portal Pemilik (Laporan Rekap Keuangan & Grafik Omzet)
Route::prefix('owner')->name('owner.')->group(function () {
    Route::get('/', [OwnerController::class, 'index'])->name('index');
});

