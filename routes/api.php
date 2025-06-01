<?php

namespace App\Http\Controllers\API; // Pastikan namespace ini sesuai dengan letak controller Anda

// use App\Http\Controllers\Controller; // Biasanya tidak diperlukan di file routes

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Mengelompokkan 'use' statement agar lebih rapi
use App\Http\Controllers\API\MahasiswaApiController; // Asumsi namespace API, bukan Api
use App\Http\Controllers\API\DosenApiController;
use App\Http\Controllers\API\FrsApiController;
use App\Http\Controllers\API\JadwalApiController;
use App\Http\Controllers\API\DetailFrsApiController;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\NilaiApiController;
use App\Http\Controllers\API\ProfileApiController; // Controller profil yang baru

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rute publik untuk login
Route::post('/login', [UserApiController::class, 'login']);

// Grup rute yang memerlukan autentikasi Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Endpoint untuk mendapatkan detail user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rute untuk ProfileApiController
    Route::get('/profile', [ProfileApiController::class, 'show'])->name('api.profile.show');
    Route::put('/profile/password', [ProfileApiController::class, 'updatePassword'])->name('api.profile.updatePassword');

    Route::post('/logout', [UserApiController::class, 'logout'])->name('api.logout');

    Route::apiResource('berita', BeritaApiController::class);

    // apiResource untuk UserApiController (jika CRUD user memerlukan auth)
    // Jika 'store' (register) user tidak perlu auth, pindahkan rute spesifik itu keluar grup.
    // Namun, biasanya UserApiController untuk manajemen user (index, show, update, delete) memerlukan auth.
    Route::apiResource('users', UserApiController::class)->except(['store']); // Contoh: store (register) mungkin publik
    // Jika UserApiController Anda HANYA untuk login dan info user terautentikasi,
    // maka /user di atas sudah cukup dan apiResource 'user' mungkin tidak diperlukan di sini.
    // Sesuaikan berdasarkan fungsionalitas UserApiController Anda.

    // Rute apiResource lainnya yang memerlukan autentikasi
    Route::apiResource('mahasiswa', MahasiswaApiController::class);
    Route::apiResource('dosen', DosenApiController::class);
    Route::apiResource('frs', FrsApiController::class);
    Route::apiResource('jadwal', JadwalApiController::class);
    Route::apiResource('detail-frs', DetailFrsApiController::class);
    Route::apiResource('nilai', NilaiApiController::class);

    // Anda mungkin ingin menambahkan rute logout di sini juga
    // Route::post('/logout', [UserApiController::class, 'logout'])->name('api.logout');
});

// Jika ada rute UserApiController yang publik (misalnya, register user baru tanpa login)
// Route::post('/users', [UserApiController::class, 'store'])->name('api.users.store.public'); // Contoh