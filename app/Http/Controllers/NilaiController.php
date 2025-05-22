<?php

namespace App\Http\Controllers;

use App\Models\nilai;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id_jadwal = $request->get('id_jadwal');
        $nilais = nilai::with(['mahasiswa', 'jadwal'])
            ->when($id_jadwal, function($q) use ($id_jadwal) {
                $q->where('id_jadwal', $id_jadwal);
            })
            ->get();

        $jadwals = Jadwal::with('matkul')->get();
        $matkuls = \App\Models\Matkul::all();
        $mahasiswas = \App\Models\Mahasiswa::all();

        return view('nilai.nilai-dosen', compact('nilais', 'jadwals', 'id_jadwal', 'matkuls', 'mahasiswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $id_jadwal = $request->get('id_jadwal');
        $jadwal = Jadwal::with('matkul', 'kelas')->findOrFail($id_jadwal);
        // Ambil mahasiswa yang terdaftar di kelas jadwal ini
        $mahasiswas = Mahasiswa::where('id_kelas', $jadwal->id_kelas)->get();

        return view('nilai.create', compact('jadwal', 'mahasiswas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'nilai' => 'required|array',
            'nilai.*.id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'nilai.*.nilai' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($request->nilai as $item) {
            nilai::updateOrCreate(
                [
                    'id_jadwal' => $request->id_jadwal,
                    'id_mahasiswa' => $item['id_mahasiswa'],
                ],
                [
                    'nilai' => $item['nilai'],
                ]
            );
        }

        return redirect()->route('nilai.index', ['id_jadwal' => $request->id_jadwal])
            ->with('success', 'Nilai berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(nilai $nilai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $nilai = nilai::with(['mahasiswa', 'jadwal'])->findOrFail($id);
        return view('nilai.edit', compact('nilai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $nilai = nilai::findOrFail($id);
        $nilai->update(['nilai' => $request->nilai]);

        return redirect()->route('nilai.index', ['id_jadwal' => $nilai->id_jadwal])
            ->with('success', 'Nilai berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $nilai = nilai::findOrFail($id);
        $nilai->delete();

        return back()->with('success', 'Nilai berhasil dihapus!');
    }
}
