<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    // Ambil semua user
    public function index()
    {
        $users = User::with(['mahasiswa', 'dosen'])->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    // Ambil user berdasarkan ID
    public function show($id)
    {
        $user = User::with(['mahasiswa', 'dosen'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    // Tambah user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'id_mahasiswa' => 'nullable|exists:mahasiswas,id_mahasiswa',
            'id_dosen' => 'nullable|exists:dosens,id_dosen',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ]);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id_user . ',id_user',
            'password' => 'sometimes|string|min:6',
            'id_mahasiswa' => 'nullable|exists:mahasiswas,id_mahasiswa',
            'id_dosen' => 'nullable|exists:dosens,id_dosen',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
