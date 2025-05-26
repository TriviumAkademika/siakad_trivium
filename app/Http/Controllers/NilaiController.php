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
                $query->where('matakuliah_id', $id_matkul);
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

        // Debug log before update
        \Log::info('Update request received:', [
            'mahasiswa_id' => $id_mahasiswa,
            'matakuliah_id' => $request->matakuliah_id,
            'nilai_uts' => $request->nilai_uts,
            'nilai_uas' => $request->nilai_uas,
        ]);

        // Process UTS grade
        if ($request->filled('nilai_uts')) {
            $uts = Nilai::updateOrCreate(
                [
                    'mahasiswa_id' => $id_mahasiswa,
                    'matakuliah_id' => $request->matakuliah_id,
                    'jenis_nilai' => 'UTS',
                ],
                [
                    'nilai' => $request->nilai_uts,
                    'updated_at' => now()
                ]
            );
            \Log::info('UTS grade updated:', $uts->toArray());
        } else {
            $deleted = Nilai::where('mahasiswa_id', $id_mahasiswa)
                ->where('matakuliah_id', $request->matakuliah_id)
                ->where('jenis_nilai', 'UTS')
                ->delete();
            \Log::info('UTS grade deleted:', ['deleted' => $deleted]);
        }

        // Process UAS grade
        if ($request->filled('nilai_uas')) {
            $uas = Nilai::updateOrCreate(
                [
                    'mahasiswa_id' => $id_mahasiswa,
                    'matakuliah_id' => $request->matakuliah_id,
                    'jenis_nilai' => 'UAS',
                ],
                [
                    'nilai' => $request->nilai_uas,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
            \Log::info('UAS grade updated:', $uas->toArray());
        } else {
            $deleted = Nilai::where('mahasiswa_id', $id_mahasiswa)
                ->where('matakuliah_id', $request->matakuliah_id)
                ->where('jenis_nilai', 'UAS')
                ->delete();
            \Log::info('UAS grade deleted:', ['deleted' => $deleted]);
        }
        
        // Log the update for debugging
        \Log::info('Nilai updated:', [
            'mahasiswa_id' => $id_mahasiswa,
            'matakuliah_id' => $request->matakuliah_id,
            'nilai_uts' => $request->nilai_uts,
            'nilai_uas' => $request->nilai_uas,
            'time' => now()
        ]);

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
        // Initialize groupedNilai as an empty array at the start
        $groupedNilai = [];
        
        // Get authenticated user and student data
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        // Get filter parameters
        $search = $request->input('search');
        $tahunAjaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
        
        if ($mahasiswa) {
            // Get courses taken by the student through FRS
            $query = \App\Models\Frs::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                ->with(['detailFrs.jadwal.matkul', 'detailFrs.nilai' => function($q) use ($mahasiswa) {
                    $q->where('mahasiswa_id', $mahasiswa->id_mahasiswa);
                }])
                ->where('tahun_ajaran', $tahunAjaran);
            
            // Apply search filter if provided
            if ($search) {
                $query->whereHas('detailFrs.jadwal.matkul', function($q) use ($search) {
                    $q->where('nama_matkul', 'like', '%' . $search . '%')
                      ->orWhere('kode_matkul', 'like', '%' . $search . '%');
                });
            }
            
            $frsList = $query->get();
            
            // Process the data for the view
            foreach ($frsList as $frs) {
                foreach ($frs->detailFrs as $detail) {
                    if ($detail->jadwal && $detail->jadwal->matkul) {
                        $matkul = $detail->jadwal->matkul;
                        $matkulId = $matkul->id_matkul;
                        
                        if (!isset($groupedNilai[$matkulId])) {
                            $groupedNilai[$matkulId] = [
                                'matkul' => $matkul,
                                'sks' => $matkul->sks,
                                'UTS' => null,
                                'UAS' => null
                            ];
                        }
                        
                        // Get UTS and UAS scores if they exist
                        if (isset($detail->nilai)) {
                            foreach ($detail->nilai as $nilai) {
                                if ($nilai->jenis_nilai === 'UTS') {
                                    $groupedNilai[$matkulId]['UTS'] = $nilai->nilai;
                                } elseif ($nilai->jenis_nilai === 'UAS') {
                                    $groupedNilai[$matkulId]['UAS'] = $nilai->nilai;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Get distinct academic years for the filter
        $tahunAjaranOptions = \App\Models\Frs::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
            ->distinct()
            ->pluck('tahun_ajaran')
            ->filter()
            ->mapWithKeys(function($item) {
                return [$item => $item];
            });
        
        // Convert groupedNilai to a collection of objects for the view
        $nilaiList = collect($groupedNilai)->map(function($item) {
            return (object)$item;
        });
        
        // Generate current academic year if no FRS records exist
        if ($tahunAjaranOptions->isEmpty()) {
            $currentYear = (int)date('Y');
            $tahunAjaran = ($currentYear - 1) . '/' . $currentYear;
            $tahunAjaranOptions = [$tahunAjaran => $tahunAjaran];
        }
        
        return view('nilai.nilai-mhs', [
            'mahasiswa' => $mahasiswa,
            'nilaiList' => $nilaiList,
            'search' => $search,
            'tahunAjaran' => $tahunAjaran,
            'tahunAjaranOptions' => $tahunAjaranOptions
        ]);
    }
}