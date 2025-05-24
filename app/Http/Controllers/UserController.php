<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Tampilkan semua user
    public function index()
    {
        $users = User::with(['mahasiswa', 'dosen'])->get();
        return view('users.index', compact('users'));
    }

    // Form tambah user
    public function create()
    {
        $mahasiswa = Mahasiswa::doesntHave('user')->get();
        $dosen = Dosen::doesntHave('user')->get();
        return view('users.create', compact('mahasiswa', 'dosen'));
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,mahasiswa,dosen',
            'nama_user' => 'required_if:role,admin|string|nullable|max:255',
            'id_mahasiswa' => 'required_if:role,mahasiswa|nullable|exists:mahasiswa,id_mahasiswa',
            'id_dosen' => 'required_if:role,dosen|nullable|exists:dosen,id_dosen',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        // Untuk nama admin
        if ($request->role === 'admin') {
            $user->name = $request->nama_user;
        }

        // Untuk relasi
        if ($request->role === 'mahasiswa') {
            $user->id_mahasiswa = $request->id_mahasiswa;
        } elseif ($request->role === 'dosen') {
            $user->id_dosen = $request->id_dosen;
        }

        $user->save();
        $user->syncRoles([$request->role]); // Gunakan Spatie role

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    // Form edit user
    public function edit($id)
    {
        $user = User::with(['mahasiswa', 'dosen'])->findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $currentRole = $user->getRoleNames()->first(); // Ambil role dari Spatie

        $rules = [
            'email' => 'required|email|unique:users,email,' . $id . ',id_user',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'min:6|confirmed';
        }

        if ($currentRole === 'admin') {
            $rules['nama_user'] = 'required|string|max:255';
        }

        $request->validate($rules);

        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($currentRole === 'admin') {
            $user->name = $request->nama_user;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
