<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Frs;
use Illuminate\Http\Request;

class FrsApiController extends Controller
{
    public function index()
    {
        $frs = Frs::with('mahasiswa', 'detailFrs')->get();
        return response()->json($frs, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|string',
            'total_sks' => 'nullable|integer',
            'ips' => 'nullable|numeric',
            'ipk' => 'nullable|numeric',
            'tgl_pengisian' => 'nullable|date',
            'tgl_perubahan' => 'nullable|date',
            'tgl_drop' => 'nullable|date',
        ]);

        $frs = Frs::create($request->all());
        return response()->json($frs, 201);
    }

    public function show($id)
    {
        $frs = Frs::with('mahasiswa', 'detailFrs')->find($id);
        if (!$frs) {
            return response()->json(['message' => 'FRS not found'], 404);
        }
        return response()->json($frs);
    }

    public function update(Request $request, $id)
    {
        $frs = Frs::find($id);
        if (!$frs) {
            return response()->json(['message' => 'FRS not found'], 404);
        }

        $request->validate([
            'id_mahasiswa' => 'sometimes|exists:mahasiswa,id_mahasiswa',
            'tahun_ajaran' => 'sometimes|required|string',
            'semester' => 'sometimes|required|string',
            'total_sks' => 'nullable|integer',
            'ips' => 'nullable|numeric',
            'ipk' => 'nullable|numeric',
            'tgl_pengisian' => 'nullable|date',
            'tgl_perubahan' => 'nullable|date',
            'tgl_drop' => 'nullable|date',
        ]);

        $frs->update($request->all());
        return response()->json($frs);
    }

    public function destroy($id)
    {
        $frs = Frs::find($id);
        if (!$frs) {
            return response()->json(['message' => 'FRS not found'], 404);
        }

        $frs->delete();
        return response()->json(['message' => 'FRS deleted successfully']);
    }
}
