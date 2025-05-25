<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kelas::with('dosen');

        // Handle search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('tahun_masuk', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('prodi', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('paralel', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('dosen', function($dosenQuery) use ($searchTerm) {
                      $dosenQuery->where('nama_dosen', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        // Handle status filter
        if ($request->filled('status')) {
            $statusFilters = $request->status;
            if (is_array($statusFilters) && count($statusFilters) > 0) {
                $query->whereIn('status', $statusFilters);
            }
        }

        // Order by id kelas
        $query->orderBy('id_kelas', 'asc');

        // Paginate with 10 items per page and preserve query parameters
        $kelas = $query->paginate(10)->appends($request->query());

        return view('kelas.index', compact('kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dosen = Dosen::all(); // Ambil semua dosen untuk ditampilkan di dropdown
        return view('kelas.create', compact('dosen')); // Tampilkan form create dengan data dosen
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_dosen' => 'required|exists:dosen,id_dosen', // Pastikan id_dosen ada dalam tabel dosen
            'tahun_masuk' => 'required|string|max:4',
            'prodi' => 'required|string|max:255',
            'paralel' => 'required|string|max:1',
            'status' => 'required|in:AKTIF,LULUS',
        ]);

        Kelas::create($request->all()); // Simpan data kelas yang diterima

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Ambil data kelas dengan relasi dosen
            $kelas = Kelas::with('dosen')->where('id_kelas', $id)->firstOrFail();
            
            // Ambil semua mahasiswa dalam kelas tersebut
            $mahasiswa = Mahasiswa::where('id_kelas', $id)->get();
            
            // Hitung statistik
            $totalMahasiswa = $mahasiswa->count();
            $mahasiswaLaki = $mahasiswa->where('gender', 'L')->count();
            $mahasiswaPerempuan = $mahasiswa->where('gender', 'P')->count();
            
            return view('kelas.show', compact('kelas', 'mahasiswa', 'totalMahasiswa', 'mahasiswaLaki', 'mahasiswaPerempuan'));
        } catch (\Exception $e) {
            return redirect()->route('kelas.index')->with('error', 'Data kelas tidak ditemukan!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $kelas = Kelas::findOrFail($id);
    
    // Pastikan data dosen diambil dengan benar
    $dosen = Dosen::where('status', 'AKTIF')
                  ->orderBy('nama_dosen', 'asc')
                  ->get();
    
    return view('kelas.edit', compact('kelas', 'dosen'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'id_dosen' => 'required|exists:dosen,id_dosen', // Validasi id_dosen
            'tahun_masuk' => 'required|string|max:4',
            'prodi' => 'required|string|max:255',
            'paralel' => 'required|string|max:1',
            'status' => 'required|in:AKTIF,LULUS',
        ]);

        $kelas->update($request->all()); // Update data kelas

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete(); // Hapus data kelas

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil dihapus!');
    }
}