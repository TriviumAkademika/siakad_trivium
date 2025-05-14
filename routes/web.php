<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\FrsController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaktuController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\DetailFrsController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/app', function () {
    return view('app');
});

Route::get('/login', function () {
    return view('pages.login');
});

Route::get('/dashboard', function () {
    return view('mahasiswa.dashboard');
});

Route::resource('dosen', DosenController::class);
Route::resource('mahasiswa', MahasiswaController::class);
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