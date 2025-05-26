<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $totalRuangan = Ruangan::count();
        $query = Ruangan::query();

        // Handle search
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_ruangan', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('nama_gedung', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('kode_ruangan', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Paginate results (10 items per page)
        $ruangan = $query->paginate(10);

        // Append search parameter to pagination links
        $ruangan->appends($request->query());

        return view('ruangan.index', compact('ruangan'));
    }

    public function create()
    {
        return view('ruangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:100',
            'nama_gedung' => 'required|string|max:100',
        ]);

        Ruangan::create($request->all());

        return redirect()->route('ruangan.index')->with('success', 'Data ruangan berhasil ditambahkan!');
    }

    public function show(Ruangan $ruangan)
    {
        return view('ruangan.show', compact('ruangan'));
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $request->validate([
            'nama_ruangan' => 'required|string|max:100',
            'nama_gedung' => 'required|string|max:100',
        ]);

        $ruangan->update($request->all());

        return redirect()->route('ruangan.index')->with('success', 'Data ruangan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();

        return redirect()->route('ruangan.index')->with('success', 'Data ruangan berhasil dihapus!');
    }
}
