<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailFrsController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\FrsController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaktuController;

// Login
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/', [AuthenticatedSessionController::class, 'store']);

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Profile (untuk update password)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// routes/web.php

// // Admin & Dosen akses lihat mahasiswa
// Route::get('/mahasiswa', [MahasiswaController::class, 'index'])
//     ->middleware(['auth', 'permission:read-mahasiswa'])
//     ->name('mahasiswa.index');

// Admin only CRUD mahasiswa
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::get('/mahasiswa/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
});

Route::get('/nilai-mhs', function () {
    return view('nilai.nilai-mhs');
});

// Route::get('/nilai-dosen', function () {
//     return view('nilai.nilai-dosen');
// });

Route::get('/nilai-dosen', [NilaiController::class, 'index'])->name('nilai-dosen');


// Route::get('/dashboard', [DashboardController::class, 'index']);
// Route::get('/dashboard/{id}', [DashboardController::class, 'show']);
Route::resource('dosen', DosenController::class);
// Route::resource('mahasiswa', MahasiswaController::class);
Route::resource('kelas', KelasController::class);
// Route::resource('user', UserController::class);
Route::resource('matkul', MatkulController::class);
Route::resource('waktu', WaktuController::class);
Route::resource('ruangan', RuanganController::class);
Route::resource('jadwal', JadwalController::class);
Route::resource('frs', FrsController::class);
Route::get('/detail-frs/{id_frs}', [DetailFrsController::class, 'index'])->name('detail-frs.index');
Route::post('/detail-frs', [DetailFrsController::class, 'store'])->name('detail-frs.store');
Route::patch('/detail-frs/update-status/{id}', [DetailFrsController::class, 'updateStatus'])->name('detail-frs.update-status');
Route::delete('/detail-frs/delete/{id}', [DetailFrsController::class, 'destroy'])->name('detail-frs.destroy');
Route::resource('users', UserController::class);
Route::resource('nilai', NilaiController::class);

// Route::get('ruangan', [RuanganController::class] function(){
//     return view('pengunjung');
// })->middleware(['auth', 'verified', 'role_or_permission:admin|dosen|lihat-dosen']);

require __DIR__.'/auth.php';