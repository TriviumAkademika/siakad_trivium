<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use App\Http\Resources\DosenResource;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dosen::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_dosen', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('nip', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('alamat', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('no_hp', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Status filter functionality
        if ($request->has('status') && !empty($request->status)) {
            $statusFilters = is_array($request->status) ? $request->status : [$request->status];
            $query->whereIn('status', $statusFilters);
        }

        // Order by nama_dosen
        $query->orderBy('nama_dosen', 'asc');

        // Paginate with 10 items per page
        $dosen = $query->paginate(10);

        // Append query parameters to pagination links
        $dosen->appends($request->query());

        return view('dosen.index', compact('dosen'));
    }

    public function create()
    {
        return view('dosen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:dosen,nip',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'status' => 'required|in:AKTIF,CUTI,PENSIUN,TIDAK AKTIF',
        ]);

        Dosen::create($request->all());

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil ditambahkan!');
    }

    public function show($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('dosen.show', compact('dosen'));
    }

    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('dosen.edit', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:dosen,nip,' . $id . ',id_dosen',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'status' => 'required|in:AKTIF,CUTI,PENSIUN,TIDAK AKTIF',
        ]);

        $dosen->update($request->all());

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil dihapus!');
    }
}