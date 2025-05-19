<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/nilai', function () {
    return view('nilai.nilai');
});

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard/{id}', [DashboardController::class, 'show']);
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
