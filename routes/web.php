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

/// PERMISSION ROLE ADMIN, DOSEN, MAHASISWA 
/// PERMISSION ALL ROLE
/// lihat tabel dosen, matkul, jadwal
Route::middleware(['auth', 'verified', 'role:admin|dosen|mahasiswa'])->group(function () {
    Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
    Route::get('/matkul', [MatkulController::class, 'index'])->name('matkul.index');
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/detail-frs/{id_frs}', [DetailFrsController::class, 'index'])->name('detail-frs.index');
    Route::post('/detail-frs', [DetailFrsController::class, 'store'])->name('detail-frs.store');
});


/// PERMISSION ROLE ADMIN, DOSEN
Route::middleware(['auth', 'verified', 'role:dosen|admin'])->group(function () {
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::get('/frs', [FrsController::class, 'index'])->name('frs.index');
    Route::get('/frs/{id}', [FrsController::class, 'show'])->name('frs.show');
    Route::patch('/detail-frs/update-status/{id}', [DetailFrsController::class, 'updateStatus'])->name('detail-frs.update-status');
});

/// PERMISSION ROLE DOSEN, MAHASISWA
Route::middleware(['auth', 'verified', 'role:dosen|mahasiswa'])->group(function () {});

/// PERMISSION ROLE ADMIN, MAHASISWA
Route::middleware(['auth', 'verified', 'role:admin|mahasiswa'])->group(function () {
    Route::delete('/detail-frs/delete/{id}', [DetailFrsController::class, 'destroy'])->name('detail-frs.destroy');
});

/// PERMISSIONS ROLE ADMIN
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Tabel Mahasiswa CRU
    Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::get('/mahasiswa/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');

    // tabel User CR
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    // Tabel Dosen CU
    Route::get('/dosen/create', [DosenController::class, 'create'])->name('dosen.create');
    Route::post('/dosen', [DosenController::class, 'store'])->name('dosen.store');
    Route::get('/dosen/{id}/edit', [DosenController::class, 'edit'])->name('dosen.edit');
    Route::put('/dosen/{id}', [DosenController::class, 'update'])->name('dosen.update');

    // Tabel Waktu CRU
    Route::get('/waktu', [WaktuController::class, 'index'])->name('waktu.index');
    Route::get('/waktu/create', [WaktuController::class, 'create'])->name('waktu.create');
    Route::post('/waktu', [WaktuController::class, 'store'])->name('waktu.store');
    Route::get('/waktu/{id}/edit', [WaktuController::class, 'edit'])->name('waktu.edit');
    Route::put('/waktu/{id}', [WaktuController::class, 'update'])->name('waktu.update');
    // Route::delete('/waktu/{id}', [WaktuController::class, 'destroy'])->name('waktu.destroy');

    // tabel Ruangan CRU
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::get('/ruangan/create', [RuanganController::class, 'create'])->name('ruangan.create');
    Route::post('/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
    Route::get('/ruangan/{id}/edit', [RuanganController::class, 'edit'])->name('ruangan.edit');
    Route::put('/ruangan/{id}', [RuanganController::class, 'update'])->name('ruangan.update');
    // Route::delete('/ruangan/{id}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');

    // tabel Mata Kuliah CRU
    Route::get('/matkul/create', [MatkulController::class, 'create'])->name('matkul.create');
    Route::post('/matkul', [MatkulController::class, 'store'])->name('matkul.store');
    Route::get('/matkul/{id}/edit', [MatkulController::class, 'edit'])->name('matkul.edit');
    Route::put('/matkul/{id}', [MatkulController::class, 'update'])->name('matkul.update');
    // Route::delete('/matkul/{id}', [MatkulController::class, 'destroy'])->name('matkul.destroy');

    // tabel Kelas CRU
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
    Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
    // Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');

    //tabel Jadwal CRU
    Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
    Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
    // Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

    // tabel frs CRU
    Route::get('/frs/create', [FrsController::class, 'create'])->name('frs.create');
    Route::post('/frs', [FrsController::class, 'store'])->name('frs.store');
    Route::get('/frs/{id}/edit', [FrsController::class, 'edit'])->name('frs.edit');
    Route::put('/frs/{id}', [FrsController::class, 'update'])->name('frs.update');
    // Route::delete('/frs/{id}', [FrsController::class, 'destroy'])->name('frs.destroy');
});


/// PERMISSION ROLE DOSEN
Route::middleware(['auth', 'verified', 'role:dosen'])->group(function () {

});


/// PERMISSION ROLE MAHASISWA
Route::middleware(['auth', 'verified', 'role:mahasiswa'])->group(function () {
    
});


/// Belum Rapi
Route::get('/nilai-mhs', function () {
    return view('nilai.nilai-mhs');
});

Route::get('/nilai-mhs', function () {
    return view('nilai.nilai-mhs');
});

Route::get('/nilai-dosen', [NilaiController::class, 'index'])->name('nilai-dosen');
Route::get('/nilai-dosen', [NilaiController::class, 'index'])->name('nilai-dosen');
Route::resource('nilai', NilaiController::class);
// Route::resource('users', UserController::class);

require __DIR__ . '/auth.php';