<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Ruangan;
use App\Models\Kelas;
use App\Models\Waktu;
use App\Models\Matkul;
use App\Models\Jadwal;
use App\Models\DetailFrs; // 1. PENTING: Tambahkan import ini agar tidak Class Not Found
use Illuminate\Support\Facades\Auth; // 2. Tambahkan ini untuk mendeteksi user login

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // --- LOGIKA UNTUK ADMIN (STATISTIK) ---
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $totalUser = User::count();
        $totalKelas = Kelas::count();
        $totalRuangan = Ruangan::count();
        $totalWaktu = Waktu::count();
        $totalMataKuliah = Matkul::count();
        $totalJadwal = Jadwal::count();

        // --- LOGIKA BARU: JADWAL MAHASISWA (Mencegah Error SQLSTATE Unknown Column) ---
        // Kita ambil ID mahasiswa dari user yang sedang login saat ini
        $id_mahasiswa = Auth::user()->id_mahasiswa ?? null;

        $jadwalMahasiswa = collect(); // Default berupa collection kosong jika tidak login/bukan mahasiswa

        if ($id_mahasiswa) {
            // Kita langsung query ke Model Jadwal yang dimiliki oleh mahasiswa ini lewat DetailFrs
            $jadwalMahasiswa = Jadwal::whereHas('detailFrs.frs', function ($query) use ($id_mahasiswa) {
                $query->where('id_mahasiswa', $id_mahasiswa);
            })
                // Pastikan status di detail_frs dipasang filter jika diperlukan, contoh:
                ->whereHas('detailFrs', function ($query) {
                    $query->where('status', true); // mengambil detail_frs yang disetujui/aktif
                })
                ->with(['matkul', 'dosen', 'ruangan', 'waktu', 'kelas'])
                ->get()
                ->groupBy('hari'); // Langsung group berdasarkan hari di sini
        }


        $user = Auth::user();
        $jadwalDosen = collect();

        if ($user->role === 'dosen') {
            $dosen = $user->dosen;
            if ($dosen) {
                $jadwalDosen = Jadwal::with(['matkul', 'ruangan', 'waktu', 'kelas']) // <-- Pastikan 'kelas' ada di sini
                    ->where('id_dosen', $dosen->id_dosen)
                    ->get()
                    ->groupBy('hari');
            }
        }

        // Kirim semua variabel ke view dashboard
        return view('dashboard', compact(
            'totalMahasiswa',
            'totalDosen',
            'totalUser',
            'totalKelas',
            'totalRuangan',
            'totalWaktu',
            'totalMataKuliah',
            'totalJadwal',
            'jadwalMahasiswa',
            'jadwalDosen'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mahasiswa = Mahasiswa::find($id);
        return view('dashboard', compact('mahasiswa'));
    }

    // method create, store, edit, update, destroy dikosongkan seperti bawaanmu...
}
