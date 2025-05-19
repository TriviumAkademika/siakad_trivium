<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalApiController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with(['kelas', 'matkul', 'dosen', 'dosen2', 'waktu', 'ruangan'])->get();
        return response()->json($jadwals, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_matkul' => 'required|exists:matkuls,id_matkul',
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'id_dosen_2' => 'nullable|exists:dosen,id_dosen',
            'id_waktu' => 'required|exists:waktus,id_waktu',
            'id_ruangan' => 'required|exists:ruangans,id_ruangan',
        ]);

        $jadwal = Jadwal::create($request->all());
        return response()->json($jadwal, 201);
    }

    public function show($id)
    {
        $jadwal = Jadwal::with(['kelas', 'matkul', 'dosen', 'dosen2', 'waktu', 'ruangan'])->find($id);
        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal not found'], 404);
        }
        return response()->json($jadwal);
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal not found'], 404);
        }

        $request->validate([
            'id_kelas' => 'sometimes|required|exists:kelas,id_kelas',
            'id_matkul' => 'sometimes|required|exists:matkuls,id_matkul',
            'id_dosen' => 'sometimes|required|exists:dosen,id_dosen',
            'id_dosen_2' => 'nullable|exists:dosen,id_dosen',
            'id_waktu' => 'sometimes|required|exists:waktus,id_waktu',
            'id_ruangan' => 'sometimes|required|exists:ruangans,id_ruangan',
        ]);

        $jadwal->update($request->all());
        return response()->json($jadwal);
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal not found'], 404);
        }

        $jadwal->delete();
        return response()->json(['message' => 'Jadwal deleted successfully']);
    }
}
