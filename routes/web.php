<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/scan', [AbsensiController::class, 'index'])
    ->name('scan');

Route::post('/cari-mahasiswa', [AbsensiController::class, 'cariMahasiswa'])
    ->name('cari.mahasiswa');

Route::post('/simpan-absensi', [AbsensiController::class, 'simpanAbsensi'])
    ->name('simpan.absensi');

Route::get('/qr', function () {
    return view('qr');
})->name('qr');

Route::get('/mahasiswa', [AbsensiController::class, 'daftarMahasiswa'])
    ->name('mahasiswa');

    Route::get('/absensi', [AbsensiController::class, 'halamanAbsensi'])
    ->name('absensi');

    Route::get('/kehadiran', [AbsensiController::class, 'dataKehadiran'])
    ->name('kehadiran');

    Route::get('/laporan', [AbsensiController::class, 'laporan'])
    ->name('laporan');