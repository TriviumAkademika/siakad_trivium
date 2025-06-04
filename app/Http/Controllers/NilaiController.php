<?php

namespace App\Http\Controllers;

use App\Models\nilai; // Pastikan 'n' kecil sesuai nama model jika filenya 'nilai.php'
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\Matkul;
use App\Models\Frs; // Tambahkan use statement untuk Frs

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $id_matkul = $request->get('id_matkul');
        
        // Ambil data dosen yang login
        $dosen = $user->dosen;
        
        if (!$dosen) {
            return redirect()->back()->with('error', 'Hanya dosen yang dapat mengakses halaman ini');
        }
        
        // Ambil daftar matakuliah yang diajar oleh dosen ini
        $matkuls = Matkul::whereHas('jadwal', function($q) use ($dosen) {
            $q->where('id_dosen', $dosen->id_dosen)
              ->orWhere('id_dosen_2', $dosen->id_dosen);
        })->get();
        
        // Jika ada id_matkul yang dipilih, validasi apakah dosen berhak mengakses matkul tersebut
        $mahasiswa = collect();
        if ($id_matkul) {
            $isValidMatkul = $matkuls->contains('id_matkul', $id_matkul);
            
            if (!$isValidMatkul) {
                return redirect()->route('nilai-dosen')
                    ->with('error', 'Anda tidak memiliki akses ke matakuliah tersebut');
            }
            
            // Ambil mahasiswa yang mengambil matakuliah ini
            $mahasiswa = Mahasiswa::whereHas('frs.detailFrs.jadwal', function($q) use ($id_matkul, $dosen) {
                $q->where('id_matkul', $id_matkul)
                  ->where(function($query) use ($dosen) {
                      $query->where('id_dosen', $dosen->id_dosen)
                            ->orWhere('id_dosen_2', $dosen->id_dosen);
                  });
            })->with(['nilai' => function($query) use ($id_matkul) { // Perhatikan 'nilai' bukan 'Nilai' jika relasi di model Mahasiswa adalah 'nilai'
                $query->where('matakuliah_id', $id_matkul);
            }])->get();
            
            \Log::info('Data mahasiswa dengan nilai:', ['data' => $mahasiswa->toArray()]);
        }
        
        return view('nilai.nilai-dosen', [
            'matkuls' => $matkuls,
            'id_matkul' => $id_matkul,
            'mahasiswa' => $mahasiswa,
            'dosen' => $dosen
        ]);
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

        // Dapatkan id_matkul dari jadwal untuk redirect
        $jadwal = Jadwal::find($request->id_jadwal);
        $id_matkul_redirect = $jadwal ? $jadwal->id_matkul : null;


        foreach ($request->nilai as $item) {
            nilai::updateOrCreate( // Pastikan 'n' kecil sesuai nama model jika filenya 'nilai.php'
                [
                    'id_jadwal' => $request->id_jadwal,
                    'id_mahasiswa' => $item['id_mahasiswa'],
                    // Jika model nilai Anda memiliki matakuliah_id dan jenis_nilai, Anda mungkin perlu menyesuaikannya di sini
                    // atau memastikan bahwa id_jadwal sudah cukup unik untuk menentukan sebuah entri nilai.
                    // Untuk struktur tabel nilai yang lebih umum (mahasiswa_id, matakuliah_id, jenis_nilai),
                    // Anda mungkin perlu mengambil matakuliah_id dari $request->id_jadwal
                ],
                [
                    'nilai' => $item['nilai'],
                ]
            );
        }
        
        // Menggunakan $id_matkul_redirect yang telah diambil sebelumnya
        return redirect()->route('nilai.index', ['id_matkul' => $id_matkul_redirect]) 
            ->with('success', 'Nilai berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(nilai $nilai) // Pastikan 'n' kecil sesuai nama model jika filenya 'nilai.php'
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
        $nilaiUTS = Nilai::where('mahasiswa_id', $id_mahasiswa) // Pastikan 'N' besar sesuai nama model jika filenya 'Nilai.php'
                            ->where('matakuliah_id', $id_matkul)
                            ->where('jenis_nilai', 'UTS')
                            ->first();

        $nilaiUAS = Nilai::where('mahasiswa_id', $id_mahasiswa) // Pastikan 'N' besar sesuai nama model jika filenya 'Nilai.php'
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
            // 'mahasiswa_id' tidak perlu divalidasi karena sudah didapat dari parameter route $id_mahasiswa
            'matakuliah_id' => 'required|exists:matkuls,id_matkul',
            'nilai_uts' => 'nullable|in:A,B,C,D,E', // Jika nilai berupa huruf
            'nilai_uas' => 'nullable|in:A,B,C,D,E', // Jika nilai berupa huruf
             // Jika nilai berupa angka:
             // 'nilai_uts' => 'nullable|numeric|min:0|max:100',
             // 'nilai_uas' => 'nullable|numeric|min:0|max:100',
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
            $uts = Nilai::updateOrCreate( // Pastikan 'N' besar sesuai nama model jika filenya 'Nilai.php'
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
            // Jika tidak diisi dan ingin menghapus nilai UTS yang ada
            $deleted = Nilai::where('mahasiswa_id', $id_mahasiswa) // Pastikan 'N' besar
                ->where('matakuliah_id', $request->matakuliah_id)
                ->where('jenis_nilai', 'UTS')
                ->delete();
            \Log::info('UTS grade potentially deleted:', ['deleted_count' => $deleted]);
        }

        // Process UAS grade
        if ($request->filled('nilai_uas')) {
            $uas = Nilai::updateOrCreate( // Pastikan 'N' besar sesuai nama model jika filenya 'Nilai.php'
                [
                    'mahasiswa_id' => $id_mahasiswa,
                    'matakuliah_id' => $request->matakuliah_id,
                    'jenis_nilai' => 'UAS',
                ],
                [
                    'nilai' => $request->nilai_uas,
                    // 'created_at' => now(), // Sebaiknya tidak di set manual jika updateOrCreate, biarkan Eloquent yang mengatur
                    'updated_at' => now()
                ]
            );
            \Log::info('UAS grade updated:', $uas->toArray());
        } else {
             // Jika tidak diisi dan ingin menghapus nilai UAS yang ada
            $deleted = Nilai::where('mahasiswa_id', $id_mahasiswa) // Pastikan 'N' besar
                ->where('matakuliah_id', $request->matakuliah_id)
                ->where('jenis_nilai', 'UAS')
                ->delete();
            \Log::info('UAS grade potentially deleted:', ['deleted_count' => $deleted]);
        }
        
        \Log::info('Nilai updated logic finished:', [
            'mahasiswa_id' => $id_mahasiswa,
            'matakuliah_id' => $request->matakuliah_id,
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
        $nilai = nilai::findOrFail($id); // Pastikan 'n' kecil sesuai nama model jika filenya 'nilai.php'
        $nilai->delete();

        return back()->with('success', 'Nilai berhasil dihapus!');
    }

    public function nilaiMhs(Request $request)
    {
        // Initialize empty collection
        $nilaiList = collect();
        
        // Get authenticated user and student data
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        // Get search parameter
        $search = $request->input('search');
        
        if ($mahasiswa) {
            // Get all FRS data for the student with proper relations
            $frsList = Frs::where('id_mahasiswa', $mahasiswa->id_mahasiswa) // Menggunakan model Frs yang sudah di-import
                ->with(['detailFrs.jadwal.matkul']) // Memastikan relasi dimuat
                ->get();

            // Get all nilai data for the student
            $nilaiData = nilai::where('mahasiswa_id', $mahasiswa->id_mahasiswa) // Pastikan 'n' kecil sesuai nama model
                ->whereIn('jenis_nilai', ['UTS', 'UAS'])
                ->get()
                ->groupBy(['matakuliah_id', 'jenis_nilai']);

            // Process all FRS records
            foreach ($frsList as $frs) {
                foreach ($frs->detailFrs as $detail) {
                    // PERUBAHAN DI SINI: Hanya proses jika status detail FRS adalah true
                    if ($detail->status == true) { // atau bisa juga if ($detail->status)
                        if ($detail->jadwal && $detail->jadwal->matkul) {
                            // Skip if search term doesn't match
                            $matkulNama = strtolower($detail->jadwal->matkul->nama_matkul ?? '');
                            $searchTerm = strtolower($search ?? ''); // Tambahkan null coalescing untuk $search
                            
                            if ($search && !str_contains($matkulNama, $searchTerm)) {
                                continue;
                            }

                            // Get nilai for this matakuliah
                            $matkulId = $detail->jadwal->matkul->id_matkul;
                            // Mengambil nilai dari $nilaiData, pastikan key 'UTS' dan 'UAS' ada
                            $nilaiUTSCollection = $nilaiData->get($matkulId, collect())->get('UTS', collect());
                            $nilaiUASCollection = $nilaiData->get($matkulId, collect())->get('UAS', collect());

                            $nilaiUTS = $nilaiUTSCollection->first();
                            $nilaiUAS = $nilaiUASCollection->first();

                            $nilaiList->push((object)[
                                'matkul' => $detail->jadwal->matkul,
                                'sks' => $detail->jadwal->matkul->sks ?? 0,
                                'nilai_uts' => $nilaiUTS->nilai ?? null,
                                'nilai_uas' => $nilaiUAS->nilai ?? null,
                                'is_wajib' => ($detail->jadwal->matkul->jenis ?? '') === 'Wajib',
                                'tahun_ajaran' => $frs->tahun_ajaran // Pastikan model Frs memiliki atribut tahun_ajaran
                            ]);
                        }
                    }
                }
            }
        }
        
        return view('nilai.nilai-mhs', [
            'mahasiswa' => $mahasiswa,
            'nilaiList' => $nilaiList,
            'search' => $search
        ]);
    }
}