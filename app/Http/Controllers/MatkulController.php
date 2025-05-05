<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use Illuminate\Http\Request;

class MatkulController extends Controller
{
    public function index()
    {
        $matkul = Matkul::all();
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
        ]);

        Matkul::create($request->all());

        return redirect()->route('matkul.index')->with('success', 'Data mata kuliah berhasil ditambahkan!');
    }

    public function show(Matkul $matkul)
    {
        return view('matkul.show', compact('matkul'));
    }

    public function edit($id)
    {
        $matkul = Matkul::findOrFail($id);
        return view('matkul.edit', compact('matkul'));
    }

    public function update(Request $request, $id)
    {
        $matkul = Matkul::findOrFail($id);

        $request->validate([
            'nama_matkul' => 'required|string|max:255',
            'jenis' => 'required|string|max:50',
            'sks' => 'required|integer|min:1|max:10',
            'kapasitas_kelas' => 'required|integer|min:1',
        ]);

        $matkul->update($request->all());

        return redirect()->route('matkul.index')->with('success', 'Data mata kuliah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $matkul = Matkul::findOrFail($id);
        $matkul->delete();

        return redirect()->route('matkul.index')->with('success', 'Data mata kuliah berhasil dihapus!');
    }
}
