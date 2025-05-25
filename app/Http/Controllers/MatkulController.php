<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use Illuminate\Http\Request;

class MatkulController extends Controller
{
    public function index(Request $request)
    {
        $query = Matkul::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_matkul', 'LIKE', "%{$search}%")
                  ->orWhere('jenis', 'LIKE', "%{$search}%")
                  ->orWhere('sks', 'LIKE', "%{$search}%");
            });
        }

        // Filter by jenis (multiple selection)
        if ($request->filled('jenis')) {
            $jenisArray = is_array($request->get('jenis')) ? $request->get('jenis') : [$request->get('jenis')];
            $query->whereIn('jenis', $jenisArray);
        }

        // Order by nama_matkul
        $query->orderBy('id_matkul', 'asc');

        // Paginate results (15 per page)
        $matkul = $query->paginate(10)->withQueryString();

        return view('matkul.index', compact('matkul'));
    }

    public function create()
    {
        return view('matkul.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_matkul' => 'required|string|max:255',
            'jenis' => 'required|string|max:50',
            'sks' => 'required|integer|min:1|max:10',
            'kapasitas_kelas' => 'required|integer|min:1',
        ], [
            'nama_matkul.required' => 'Nama mata kuliah wajib diisi.',
            'nama_matkul.max' => 'Nama mata kuliah maksimal 255 karakter.',
            'jenis.required' => 'Jenis mata kuliah wajib dipilih.',
            'jenis.max' => 'Jenis mata kuliah maksimal 50 karakter.',
            'sks.required' => 'SKS wajib diisi.',
            'sks.integer' => 'SKS harus berupa angka.',
            'sks.min' => 'SKS minimal 1.',
            'sks.max' => 'SKS maksimal 10.',
            'kapasitas_kelas.required' => 'Kapasitas kelas wajib diisi.',
            'kapasitas_kelas.integer' => 'Kapasitas kelas harus berupa angka.',
            'kapasitas_kelas.min' => 'Kapasitas kelas minimal 1.',
        ]);

        try {
            Matkul::create([
                'nama_matkul' => $request->nama_matkul,
                'jenis' => $request->jenis,
                'sks' => $request->sks,
                'kapasitas_kelas' => $request->kapasitas_kelas,
            ]);

            return redirect()->route('matkul.index')->with('success', 'Data mata kuliah berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data mata kuliah.')->withInput();
        }
    }

    public function show($id)
    {
        try {
            $matkul = Matkul::findOrFail($id);
            return view('matkul.show', compact('matkul'));
        } catch (\Exception $e) {
            return redirect()->route('matkul.index')->with('error', 'Data mata kuliah tidak ditemukan.');
        }
    }

    public function edit($id)
    {
        try {
            $matkul = Matkul::findOrFail($id);
            return view('matkul.edit', compact('matkul'));
        } catch (\Exception $e) {
            return redirect()->route('matkul.index')->with('error', 'Data mata kuliah tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $matkul = Matkul::findOrFail($id);

            $request->validate([
                'nama_matkul' => 'required|string|max:255',
                'jenis' => 'required|string|max:50',
                'sks' => 'required|integer|min:1|max:10',
                'kapasitas_kelas' => 'required|integer|min:1',
            ], [
                'nama_matkul.required' => 'Nama mata kuliah wajib diisi.',
                'nama_matkul.max' => 'Nama mata kuliah maksimal 255 karakter.',
                'jenis.required' => 'Jenis mata kuliah wajib dipilih.',
                'jenis.max' => 'Jenis mata kuliah maksimal 50 karakter.',
                'sks.required' => 'SKS wajib diisi.',
                'sks.integer' => 'SKS harus berupa angka.',
                'sks.min' => 'SKS minimal 1.',
                'sks.max' => 'SKS maksimal 10.',
                'kapasitas_kelas.required' => 'Kapasitas kelas wajib diisi.',
                'kapasitas_kelas.integer' => 'Kapasitas kelas harus berupa angka.',
                'kapasitas_kelas.min' => 'Kapasitas kelas minimal 1.',
            ]);

            $matkul->update([
                'nama_matkul' => $request->nama_matkul,
                'jenis' => $request->jenis,
                'sks' => $request->sks,
                'kapasitas_kelas' => $request->kapasitas_kelas,
            ]);

            return redirect()->route('matkul.index')->with('success', 'Data mata kuliah berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data mata kuliah.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $matkul = Matkul::findOrFail($id);
            $matkul->delete();

            return redirect()->route('matkul.index')->with('success', 'Data mata kuliah berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('matkul.index')->with('error', 'Terjadi kesalahan saat menghapus data mata kuliah.');
        }
    }
}