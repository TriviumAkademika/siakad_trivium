<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Matkul;
use App\Models\Dosen;
use App\Models\Waktu;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = Jadwal::with(['kelas', 'matkul', 'dosen', 'waktu', 'ruangan'])->get();

        return view('jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $matkul = Matkul::all();
        $dosen = Dosen::all();
        $waktu = Waktu::all();
        $ruangan = Ruangan::all();

        return view('jadwal.create', compact('kelas', 'matkul', 'dosen', 'waktu', 'ruangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_matkul' => 'required|exists:matkuls,id_matkul',
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'id_dosen_2' => 'nullable|exists:dosen,id_dosen|different:id_dosen',
            'id_waktu' => 'required|exists:waktus,id_waktu',
            'id_ruangan' => 'required|exists:ruangans,id_ruangan',
        ]);


        Jadwal::create($request->all());

        return redirect()->route('jadwal.index')->with([
            'message' => 'Jadwal berhasil ditambahkan!',
            'type' => 'success' // atau 'error', 'warning', 'info'
        ]);
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $kelas = Kelas::all();
        $matkul = Matkul::all();
        $dosen = Dosen::all();
        $waktu = Waktu::all();
        $ruangan = Ruangan::all();

        return view('jadwal.edit', compact('jadwal', 'kelas', 'matkul', 'dosen', 'waktu', 'ruangan'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_matkul' => 'required|exists:matkuls,id_matkul',
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'id_dosen_2' => 'nullable|exists:dosen,id_dosen|different:id_dosen',
            'id_waktu' => 'required|exists:waktus,id_waktu',
            'id_ruangan' => 'required|exists:ruangans,id_ruangan',
        ]);


        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')->with([
            'message' => 'Jadwal berhasil dipebarui!',
            'type' => 'success' // atau 'error', 'warning', 'info'
        ]);
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with([
            'message' => 'Jadwal berhasil dihapus!',
            'type' => 'warning' // atau 'error', 'warning', 'info'
        ]);
    }
}
