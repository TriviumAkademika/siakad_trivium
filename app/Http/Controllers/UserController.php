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
    dd(Dosen::doesntHave('user')->get());

  }

  // Simpan user baru
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email|unique:users,email',
      'password' => 'required|min:6|confirmed',
      'role' => 'required|in:mahasiswa,dosen',

      // Mahasiswa
      'id_mahasiswa' => 'required_if:role,mahasiswa|nullable|exists:mahasiswa,id_mahasiswa',

      // Dosen
      'id_dosen' => 'required_if:role,dosen|nullable|exists:dosen,id_dosen',
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $userData = [
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role' => $request->role,
    ];

    if ($request->role === 'mahasiswa') {
      $userData['id_mahasiswa'] = $request->id_mahasiswa;
    } elseif ($request->role === 'dosen') {
      $userData['id_dosen'] = $request->id_dosen;
    }

    User::create($userData);

    return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
  }

  // Tampilkan detail user
  public function show($id)
  {
    $user = User::with(['mahasiswa', 'dosen'])->findOrFail($id);
    return view('users.show', compact('user'));
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

    $rules = [
      'email' => 'required|email|unique:users,email,' . $id . ',id_user',
      'role' => 'required|in:mahasiswa,dosen',
    ];

    if ($request->filled('password')) {
      $rules['password'] = 'min:6|confirmed';
    }

    $request->validate($rules);

    $user->email = $request->email;
    if ($request->filled('password')) {
      $user->password = Hash::make($request->password);
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