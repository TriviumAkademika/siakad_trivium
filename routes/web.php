<?php

use Illuminate\Support\Facades\Route;

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