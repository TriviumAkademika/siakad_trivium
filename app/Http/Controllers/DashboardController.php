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

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Hitung semua statistik untuk admin
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $totalUser = User::count();
        $totalKelas = Kelas::count(); // sesuaikan dengan model kamu
        $totalRuangan = Ruangan::count();
        $totalWaktu = Waktu::count(); // sesuaikan dengan model kamu
        $totalMataKuliah = Matkul::count();
        $totalJadwal = Jadwal::count();

        return view('dashboard', compact(
            'totalMahasiswa',
            'totalDosen',
            'totalUser',
            'totalKelas',
            'totalRuangan',
            'totalWaktu',
            'totalMataKuliah',
            'totalJadwal'
        ));
    }
    // $mahasiswa = Mahasiswa::first();
    // return view('dashboard', compact('mahasiswa'));


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mahasiswa = Mahasiswa::find($id);
        return view('pages.dashboard-mahasiswa', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
