<?php

// app/Http/Controllers/DetailFrsController.php
namespace App\Http\Controllers;

use App\Models\DetailFrs;
use App\Models\Frs;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class DetailFrsController extends Controller
{
    public function index($id_frs)
    {
        $frs = Frs::with('mahasiswa', 'detailFrs.jadwal.matkul', 'detailFrs.jadwal.dosen', 'detailFrs.jadwal.waktu', 'detailFrs.jadwal.ruangan')->findOrFail($id_frs);
        $jadwals = Jadwal::with('matkul', 'dosen', 'waktu', 'ruangan')->get();

        return view('detail_frs.index', compact('frs', 'jadwals'));
    }

    public function store(Request $request)
    {
        $id_frs = $request->input('id_frs');
        $jadwal_ids = $request->input('jadwal_ids', []);

        foreach ($jadwal_ids as $id_jadwal) {
            DetailFrs::firstOrCreate([
                'id_frs' => $id_frs,
                'id_jadwal' => $id_jadwal,
            ], [
                'status' => true
            ]);
        }

        // Hitung ulang total SKS
        $total_sks = Frs::find($id_frs)->detailFrs()
            ->join('jadwal', 'detail_frs.id_jadwal', '=', 'jadwal.id_jadwal')
            ->join('matkuls', 'jadwal.id_matkul', '=', 'matkuls.id_matkul')
            ->sum('matkuls.sks');

        Frs::where('id_frs', $id_frs)->update(['total_sks' => $total_sks]);

        // Redirect kembali ke halaman detail-frs/{id}
        return redirect()->route('detail-frs.index', $request->id_frs);

    }
}
