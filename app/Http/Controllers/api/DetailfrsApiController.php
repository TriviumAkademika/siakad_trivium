<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailFrs;
use Illuminate\Http\Request;

class DetailFrsApiController extends Controller
{
    public function index()
    {
        $detailFrs = DetailFrs::with(['frs', 'jadwal'])->get();
        return response()->json($detailFrs, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'id_frs' => 'required|exists:frs,id_frs',
            'status' => 'required|boolean',
        ]);

        $detail = DetailFrs::create($request->all());
        return response()->json($detail, 201);
    }

    public function show($id)
    {
        $detail = DetailFrs::with(['frs', 'jadwal'])->find($id);
        if (!$detail) {
            return response()->json(['message' => 'Detail FRS not found'], 404);
        }
        return response()->json($detail);
    }

    public function update(Request $request, $id)
    {
        $detail = DetailFrs::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Detail FRS not found'], 404);
        }

        $request->validate([
            'id_jadwal' => 'sometimes|required|exists:jadwal,id_jadwal',
            'id_frs' => 'sometimes|required|exists:frs,id_frs',
            'status' => 'sometimes|required|boolean',
        ]);

        $detail->update($request->all());
        return response()->json($detail);
    }

    public function destroy($id)
    {
        $detail = DetailFrs::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Detail FRS not found'], 404);
        }

        $detail->delete();
        return response()->json(['message' => 'Detail FRS deleted successfully']);
    }
}
