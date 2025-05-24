<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan semua user
    public function index()
    {
        $users = User::with(['mahasiswa', 'dosen', 'roles'])->get();
        return view('users.index', compact('users'));
    }

    // Form tambah user
    public function create()
    {
        // Ambil mahasiswa dan dosen yang belum memiliki user
        $mahasiswa = Mahasiswa::doesntHave('user')->get();
        $dosen = Dosen::doesntHave('user')->get();
        return view('users.create', compact('mahasiswa', 'dosen'));
    }

    public function store(Request $request)
    {
        // Validasi dasar
        $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:mahasiswa,dosen,admin',
        ];

        // Validasi tambahan berdasarkan role
        if ($request->role === 'mahasiswa') {
            $rules['id_mahasiswa'] = 'required|exists:mahasiswa,id_mahasiswa|unique:users,id_mahasiswa';
        } elseif ($request->role === 'dosen') {
            $rules['id_dosen'] = 'required|exists:dosen,id_dosen|unique:users,id_dosen';
        } elseif ($request->role === 'admin') {
            $rules['nama_user'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        try {
            // Buat data user berdasarkan role
            $userData = [
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ];

            // Tambahkan field spesifik berdasarkan role
            if ($validated['role'] === 'mahasiswa') {
                $userData['id_mahasiswa'] = $validated['id_mahasiswa'];
            } elseif ($validated['role'] === 'dosen') {
                $userData['id_dosen'] = $validated['id_dosen'];
            } elseif ($validated['role'] === 'admin') {
                $userData['name'] = $validated['nama_user'];
            }

            // Buat user
            $user = User::create($userData);

            // Assign role menggunakan Spatie Permission (HANYA INI yang menentukan role)
            $user->assignRole($validated['role']);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // Form edit user
    public function edit($id)
    {
        $user = User::with(['mahasiswa', 'dosen', 'roles'])->findOrFail($id);
        
        // Ambil mahasiswa dan dosen yang belum memiliki user (kecuali yang sedang diedit)
        $mahasiswa = Mahasiswa::doesntHave('user')
            ->orWhere('id_mahasiswa', $user->id_mahasiswa)
            ->get();
        $dosen = Dosen::doesntHave('user')
            ->orWhere('id_dosen', $user->id_dosen)
            ->get();
            
        return view('users.edit', compact('user', 'mahasiswa', 'dosen'));
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

        $validated = $request->validate($rules);

        try {
            $user->email = $validated['email'];

            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }

            if ($currentRole === 'admin') {
                $user->name = $validated['nama_user'];
            }

            $user->save();

            return redirect()->route('users.index')
                ->with('success', 'User berhasil diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // Hapus user
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Hapus semua role yang terkait dengan user
            $user->removeRole($user->getRoleNames()->first());
            
            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}