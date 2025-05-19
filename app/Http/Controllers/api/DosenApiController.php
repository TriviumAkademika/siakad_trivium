<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenApiController extends Controller
{
    public function index()
    {
        return response()->json(Dosen::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dosen' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:dosen,nip',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
        ]);

        $dosen = Dosen::create($request->all());
        return response()->json($dosen, 201);
    }

    public function show($id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
            return response()->json(['message' => 'Dosen not found'], 404);
        }
        return response()->json($dosen);
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
            return response()->json(['message' => 'Dosen not found'], 404);
        }

        $request->validate([
            'nama_dosen' => 'sometimes|required|string|max:255',
            'nip' => 'sometimes|required|string|max:50|unique:dosen,nip,' . $id . ',id_dosen',
            'alamat' => 'sometimes|required|string',
            'no_hp' => 'sometimes|required|string|max:20',
        ]);

        $dosen->update($request->all());
        return response()->json($dosen);
    }

    public function destroy($id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
            return response()->json(['message' => 'Dosen not found'], 404);
        }

        $dosen->delete();
        return response()->json(['message' => 'Dosen deleted successfully']);
    }
}
