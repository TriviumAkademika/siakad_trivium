<?php

namespace App\Http\Controllers;

use App\Models\nilai;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\Matkul;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id_matkul = $request->get('id_matkul');
        $jadwals = Jadwal::with('matkul')->get();
        $matkuls = Matkul::all();

        // Ambil mahasiswa yang mengambil FRS dan detail_frs pada matkul ini
        $mahasiswa = collect(); // default kosong
        if ($id_matkul) {
            $mahasiswa = Mahasiswa::whereHas('frs.detailFrs.jadwal', function($q) use ($id_matkul) {
                $q->where('id_matkul', $id_matkul);
            })->with(['nilai' => function($query) use ($id_matkul) {
                // Load nilai hanya untuk matkul yang sedang dipilih
                $query->where('matakuliah_id', $id_matkul)
                      ->select('id', 'mahasiswa_id', 'matakuliah_id', 'jenis_nilai', 'nilai');
            }])->get();
            
            // Debug: Tampilkan data yang diambil
            \Log::info('Data mahasiswa dengan nilai:', ['data' => $mahasiswa->toArray()]);
        }

        return view('nilai.nilai-dosen', compact('jadwals', 'id_matkul', 'matkuls', 'mahasiswa'));
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
    public function edit($id_mahasiswa, Request $request)
    {
        $id_matkul = $request->query('id_matkul');
        $mahasiswa = Mahasiswa::findOrFail($id_mahasiswa);
        $matkul = Matkul::findOrFail($id_matkul);
        
        // Ambil nilai UTS dan UAS yang sudah ada
        $nilaiUTS = Nilai::where('mahasiswa_id', $id_mahasiswa)
                        ->where('matakuliah_id', $id_matkul)
                        ->where('jenis_nilai', 'UTS')
                        ->first();

        $nilaiUAS = Nilai::where('mahasiswa_id', $id_mahasiswa)
                        ->where('matakuliah_id', $id_matkul)
                        ->where('jenis_nilai', 'UAS')
                        ->first();
        
        return view('nilai.update', compact('mahasiswa', 'matkul', 'nilaiUTS', 'nilaiUAS'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_mahasiswa)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id_mahasiswa',
            'matakuliah_id' => 'required|exists:matkuls,id_matkul',
            'nilai_uts' => 'nullable|in:A,B,C,D,E',
            'nilai_uas' => 'nullable|in:A,B,C,D,E',
        ]);

        // Update atau buat nilai UTS jika diisi
        if ($request->filled('nilai_uts')) {
            Nilai::updateOrCreate(
                [
                    'mahasiswa_id' => $id_mahasiswa,
                    'matakuliah_id' => $request->matakuliah_id,
                    'jenis_nilai' => 'UTS',
                ],
                ['nilai' => $request->nilai_uts]
            );
        } else {
            // Hapus nilai UTS jika dikosongkan
            Nilai::where('mahasiswa_id', $id_mahasiswa)
                ->where('matakuliah_id', $request->matakuliah_id)
                ->where('jenis_nilai', 'UTS')
                ->delete();
        }

        // Update atau buat nilai UAS jika diisi
        if ($request->filled('nilai_uas')) {
            Nilai::updateOrCreate(
                [
                    'mahasiswa_id' => $id_mahasiswa,
                    'matakuliah_id' => $request->matakuliah_id,
                    'jenis_nilai' => 'UAS',
                ],
                ['nilai' => $request->nilai_uas]
            );
        } else {
            // Hapus nilai UAS jika dikosongkan
            Nilai::where('mahasiswa_id', $id_mahasiswa)
                ->where('matakuliah_id', $request->matakuliah_id)
                ->where('jenis_nilai', 'UAS')
                ->delete();
        }

        return redirect()->route('nilai-dosen', ['id_matkul' => $request->matakuliah_id])
            ->with('success', 'Nilai berhasil diperbarui');
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

    public function nilaiMhs(Request $request)
    {
        // Asumsi user login adalah mahasiswa, dan relasi user->mahasiswa ada
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa ?? null;
        $nilaiList = [];
        
        if ($mahasiswa) {
            // Ambil semua nilai mahasiswa dengan relasi matkul
            $nilaiList = \App\Models\nilai::with('matkul')
                ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                ->orderBy('matakuliah_id')
                ->orderBy('jenis_nilai')
                ->get();
            
            // Kelompokkan nilai berdasarkan matakuliah
            $groupedNilai = $nilaiList->groupBy('matakuliah_id');
            $processedNilai = [];
            
            foreach ($groupedNilai as $matkulId => $nilais) {
                $matkul = $nilais->first()->matkul;
                $nilaiUTS = $nilais->where('jenis_nilai', 'UTS')->first();
                $nilaiUAS = $nilais->where('jenis_nilai', 'UAS')->first();
                
                // Tambahkan entry UTS jika ada
                if ($nilaiUTS) {
                    $processedNilai[] = (object)[
                        'matkul' => $matkul,
                        'jenis_nilai' => 'UTS',
                        'nilai' => $nilaiUTS->nilai,
                        'created_at' => $nilaiUTS->created_at
                    ];
                }
                
                // Tambahkan entry UAS jika ada
                if ($nilaiUAS) {
                    $processedNilai[] = (object)[
                        'matkul' => $matkul,
                        'jenis_nilai' => 'UAS',
                        'nilai' => $nilaiUAS->nilai,
                        'created_at' => $nilaiUAS->created_at
                    ];
                }
            }
            
            // Urutkan berdasarkan nama matakuliah dan jenis nilai
            usort($processedNilai, function($a, $b) {
                $matkulCompare = strcmp($a->matkul->nama_matkul, $b->matkul->nama_matkul);
                if ($matkulCompare === 0) {
                    return strcmp($a->jenis_nilai, $b->jenis_nilai);
                }
                return $matkulCompare;
            });
            
            $nilaiList = collect($processedNilai);
        }
        
        return view('nilai.nilai-mhs', compact('mahasiswa', 'nilaiList'));
    }
}