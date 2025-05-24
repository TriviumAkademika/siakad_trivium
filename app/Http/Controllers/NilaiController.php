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
        $id_matkul = $request->get('id_matkul');
        $nilais = nilai::with(['mahasiswa', 'matkul'])
            ->when($id_matkul, function($q) use ($id_matkul) {
                $q->where('matakuliah_id', $id_matkul);
            })
            ->get();

        $jadwals = Jadwal::with('matkul')->get();
        $matkuls = \App\Models\Matkul::all();

        // Ambil mahasiswa yang mengambil FRS dan detail_frs pada matkul ini
        $mahasiswas = [];
        if ($id_matkul) {
            $mahasiswas = \App\Models\Mahasiswa::whereHas('frs.detailFrs.jadwal', function($q) use ($id_matkul) {
                $q->where('id_matkul', $id_matkul);
            })->get();
        }

        return view('nilai.nilai-dosen', compact('nilais', 'jadwals', 'id_matkul', 'matkuls', 'mahasiswas'));
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

        return redirect()->route('nilai.index', ['id_matkul' => $request->id_matkul])
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

        return redirect()->route('nilai.index', ['id_matkul' => $nilai->id_matkul])
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

    // Form update nilai
    public function updateNilaiForm($id_mahasiswa, $id_matkul)
    {
        $mahasiswa = \App\Models\Mahasiswa::findOrFail($id_mahasiswa);
        $matkul = \App\Models\Matkul::findOrFail($id_matkul);
        return view('nilai.update-nilai', compact('mahasiswa', 'matkul'));
    }

    public function updateNilai(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id_mahasiswa',
            'matakuliah_id' => 'required|exists:matkuls,id_matkul',
            'jenis_nilai' => 'required|in:UTS,UAS',
            'nilai' => 'required|in:A,B,C,D,E',
        ]);

        // Simpan atau update nilai
        $nilai = \App\Models\nilai::updateOrCreate(
            [
                'mahasiswa_id' => $request->mahasiswa_id,
                'matakuliah_id' => $request->matakuliah_id,
                'jenis_nilai' => $request->jenis_nilai,
            ],
            [
                'nilai' => $request->nilai,
            ]
        );

        return back()->with('success', 'Nilai berhasil diperbarui!');
    }

    public function nilaiMhs(Request $request)
    {
        // Asumsi user login adalah mahasiswa, dan relasi user->mahasiswa ada
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa ?? null;
        $nilaiList = [];
        if ($mahasiswa) {
            $nilaiList = \App\Models\nilai::with('matkul')
                ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                ->get();
        }
        return view('nilai.nilai-mhs', compact('mahasiswa', 'nilaiList'));
    }
}
