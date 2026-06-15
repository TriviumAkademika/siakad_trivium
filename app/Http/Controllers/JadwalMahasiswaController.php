<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailFrs;
use App\Models\Frs;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth; // Tambahkan ini jika mengambil ID dari user login

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman jadwal mahasiswa
     */
    public function index()
    {
        // 1. Ambil ID mahasiswa yang sedang login
        // (Sesuaikan dengan cara kamu menyimpan session login, contoh jika menggunakan Auth)
        $id_mahasiswa = Auth::user()->id_mahasiswa; 

        // 2. Jalankan query dengan whereHas
        $jadwalMahasiswa = DetailFrs::whereHas('frs', function ($query) use ($id_mahasiswa) {
            $query->where('id_mahasiswa', $id_mahasiswa);
        })
        ->where('status', 0)
        ->get();

        // 3. Lempar datanya ke view dashboard kamu
        return view('dashboard.jadwal', compact('jadwalMahasiswa'));
    }
}