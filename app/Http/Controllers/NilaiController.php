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
    public function edit($id_mahasiswa, $id_matkul)
    {
        $mahasiswa = \App\Models\Mahasiswa::findOrFail($id_mahasiswa);
        $matkul = \App\Models\Matkul::findOrFail($id_matkul);

        // Opsional: Ambil data nilai UTS/UAS yang sudah ada jika ingin ditampilkan di form
        $nilaiUTS = \App\Models\nilai::where('mahasiswa_id', $id_mahasiswa)
                                     ->where('matakuliah_id', $id_matkul)
                                     ->where('jenis_nilai', 'UTS')
                                     ->first(); // Ambil record UTS jika ada
        $nilaiUAS = \App\Models\nilai::where('mahasiswa_id', $id_mahasiswa)
                                     ->where('matakuliah_id', $id_matkul)
                                     ->where('jenis_nilai', 'UAS')
                                     ->first(); // Ambil record UAS jika ada


        return view('nilai.update', compact('mahasiswa', 'matkul', 'nilaiUTS', 'nilaiUAS')); // Kirim data nilai yang sudah ada ke view
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_mahasiswa, $id_matkul)
    {
        // Validasi data
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id_mahasiswa',
            'matakuliah_id' => 'required|exists:matkuls,id_matkul',
            // Ubah validasi menjadi nullable dan pastikan nilai kosong diizinkan
            'nilai_uts' => 'nullable|in:A,B,C,D,E',
            'nilai_uas' => 'nullable|in:A,B,C,D,E',
        ]);

        // Validasi tambahan: Pastikan ID dari hidden input sesuai dengan parameter route
        if ($request->mahasiswa_id != $id_mahasiswa || $request->matakuliah_id != $id_matkul) {
            return back()->withErrors(['msg' => 'Data tidak konsisten dengan URL.']);
        }

        // --- Proses Nilai UTS ---
        // Jika nilai UTS dikirim dan TIDAK kosong
        if ($request->has('nilai_uts') && $request->nilai_uts !== '') {
            \App\Models\nilai::updateOrCreate(
                [
                    'mahasiswa_id' => $request->mahasiswa_id,
                    'matakuliah_id' => $request->matakuliah_id,
                    'jenis_nilai' => 'UTS',
                ],
                [
                    'nilai' => $request->nilai_uts,
                ]
            );
        }
        // Jika nilai UTS dikirim dan KOSONG ('')
        elseif ($request->has('nilai_uts') && $request->nilai_uts === '') {
             \App\Models\nilai::where('mahasiswa_id', $request->mahasiswa_id)
                                 ->where('matakuliah_id', $request->matakuliah_id)
                                 ->where('jenis_nilai', 'UTS')
                                 ->delete(); // Hapus record jika nilai dikosongkan
        }
        // Jika nilai UTS tidak dikirim sama sekali (tidak mungkin dengan dropdown biasa)

        // --- Proses Nilai UAS ---
        // Jika nilai UAS dikirim dan TIDAK kosong
        if ($request->has('nilai_uas') && $request->nilai_uas !== '') {
            \App\Models\nilai::updateOrCreate(
                [
                    'mahasiswa_id' => $request->mahasiswa_id,
                    'matakuliah_id' => $request->matakuliah_id,
                    'jenis_nilai' => 'UAS',
                ],
                [
                    'nilai' => $request->nilai_uas,
                ]
            );
        }
         // Jika nilai UAS dikirim dan KOSONG ('')
        elseif ($request->has('nilai_uas') && $request->nilai_uas === '') {
            \App\Models\nilai::where('mahasiswa_id', $request->mahasiswa_id)
                                ->where('matakuliah_id', $request->matakuliah_id)
                                ->where('jenis_nilai', 'UAS')
                                ->delete(); // Hapus record jika nilai dikosongkan
        }
         // Jika nilai UAS tidak dikirim sama sekali (tidak mungkin dengan dropdown biasa)


        // Redirect kembali ke halaman nilai-dosen setelah update
        return redirect()->route('nilai-dosen', ['id_matkul' => $request->matakuliah_id])
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