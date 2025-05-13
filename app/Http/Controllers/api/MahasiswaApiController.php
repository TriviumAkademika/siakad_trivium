<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaApiController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::with('kelas')->get();

        return response()->json([
            'status' => 'success',
            'data' => $mahasiswa
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'nama' => 'required|string|max:255',
            'nrp' => 'required|string|unique:mahasiswa,nrp',
            'semester' => 'required|string',
            'gender' => 'required|in:L,P',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
        ]);

        $mahasiswa = Mahasiswa::create($validated);
        $mahasiswa->load('kelas');

        return response()->json([
            'status' => 'success',
            'message' => 'Data mahasiswa berhasil ditambahkan',
            'data' => $mahasiswa
        ], 201);
    }

    public function show($id)
    {
        $mahasiswa = Mahasiswa::with('kelas')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $mahasiswa
        ]);
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $validated = $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'nama' => 'required|string|max:255',
            'nrp' => 'required|string|unique:mahasiswa,nrp,' . $mahasiswa->id_mahasiswa . ',id_mahasiswa',
            'semester' => 'required|string',
            'gender' => 'required|in:L,P',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
        ]);

        $mahasiswa->update($validated);
        $mahasiswa->load('kelas');

        return response()->json([
            'status' => 'success',
            'message' => 'Data mahasiswa berhasil diperbarui',
            'data' => $mahasiswa
        ]);
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data mahasiswa berhasil dihapus'
        ]);
    }
}
