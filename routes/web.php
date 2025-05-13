<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaktuController;
use App\Http\Controllers\RuanganController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/app', function () {
    return view('app');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard-mahasiswa');
});

Route::resource('dosen', DosenController::class);
Route::resource('mahasiswa', MahasiswaController::class);
Route::resource('kelas', KelasController::class);
// Route::resource('user', UserController::class);
Route::resource('matkul', MatkulController::class);
Route::resource('waktu', WaktuController::class);
Route::resource('ruangan', RuanganController::class);