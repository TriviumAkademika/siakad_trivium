<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use App\http\Resources\DosenResource;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = Dosen::all();
        return DosenResource::collection($dosen);
    }
    // public function index()
    // {
    //     $dosen = Dosen::all();
    //     return view('dosen.index', compact('dosen'));
    // }

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
        ]);

        Dosen::create($request->all());

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dosen $dosen)
    {
        //
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
