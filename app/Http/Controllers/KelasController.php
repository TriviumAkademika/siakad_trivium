<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Dosen;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::with('dosen')->get(); // Mengambil semua kelas beserta dosen yang terkait
        return view('kelas.index', compact('kelas')); // Kirim data kelas ke view
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
        ]);

        Kelas::create($request->all()); // Simpan data kelas yang diterima

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id); // Ambil data kelas berdasarkan id
        $dosen = Dosen::all(); // Ambil semua dosen untuk dropdown
        return view('kelas.edit', compact('kelas', 'dosen')); // Tampilkan form edit dengan data kelas dan dosen
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
