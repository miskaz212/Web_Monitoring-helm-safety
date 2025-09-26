<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PekerjaController;

// Route default langsung ke halaman index pekerjas
Route::get('/', [PekerjaController::class, 'index'])->name('home');

// Resource route pekerjas (tanpa show)
Route::resource('pekerjas', PekerjaController::class)->except(['show']);

// Kirim notifikasi Telegram
Route::post('/pekerjas/{id}/kirim-telegram', [PekerjaController::class, 'kirimTelegram'])
    ->name('pekerjas.kirimTelegram');

// Riwayat pekerjas
Route::get('/pekerjas/riwayat', [PekerjaController::class, 'riwayat'])
    ->name('pekerjas.riwayat');

    Route::get('/pekerjas/latest', [PekerjaController::class, 'latest'])->name('pekerjas.latest');
Route::post('/pekerjas/{id}/kirim-manual', [PekerjaController::class, 'kirimTelegramManual'])->name('pekerjas.kirimManual');
