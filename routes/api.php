<?php

namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MahasiswaApiController;
use App\Http\Controllers\Api\DosenApiController;
use App\Http\Controllers\Api\FrsApiController;
use App\Http\Controllers\Api\JadwalApiController;
use App\Http\Controllers\Api\DetailFrsApiController;
use App\Http\Controllers\Api\UserApiController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::apiResource('mahasiswa', MahasiswaApiController::class);
Route::apiResource('dosen', DosenApiController::class);
Route::apiResource('frs', FrsApiController::class);
Route::apiResource('jadwal', JadwalApiController::class);
Route::apiResource('detail-frs', DetailFrsApiController::class);
Route::apiResource('user', UserApiController::class);
