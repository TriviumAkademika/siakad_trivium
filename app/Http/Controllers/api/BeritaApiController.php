<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaApiController extends Controller
{
    public function index()
    {
        return response()->json(Berita::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gambar' => 'nullable|string',
            'isi_berita' => 'required|string',
            'judul' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'penulis' => 'nullable|string',
        ]);

        $berita = Berita::create($validated);

        return response()->json($berita, 201);
    }

    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        return response()->json($berita);
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $validated = $request->validate([
            'gambar' => 'nullable|string',
            'isi_berita' => 'sometimes|required|string',
            'judul' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'penulis' => 'nullable|string',
        ]);

        $berita->update($validated);

        return response()->json($berita);
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();

        return response()->json(null, 204);
    }
}
