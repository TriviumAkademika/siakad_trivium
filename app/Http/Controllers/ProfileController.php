<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
// No need to import Mahasiswa or Dosen models if accessed via User relationship
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Keep if used elsewhere, not directly in show/updatePassword with $request->user()
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form with role-specific data and password change form.
     */
    public function show(Request $request): View
    {
        // Use the correct relationship names: 'mahasiswa' and 'dosen'
        // Eager load 'mahasiswa.kelas' if the user is a mahasiswa
        $user = $request->user();
        $role = $user->getRoleAttribute(); // Use the accessor from your User model

        if ($role === 'mahasiswa') {
            $user->loadMissing('mahasiswa.kelas');
        } elseif ($role === 'dosen') {
            $user->loadMissing('dosen');
        }
        // If there are other roles or general user info to load, you can add them.
        // For instance, if 'name' and 'email' are directly on $user and always needed, no extra load is required for those.

        $profileData = null;

        if ($role === 'mahasiswa' && $user->mahasiswa) { // Use 'mahasiswa'
            $profileData = $user->mahasiswa;           // Use 'mahasiswa'
        } elseif ($role === 'dosen' && $user->dosen) {  // Use 'dosen'
            $profileData = $user->dosen;              // Use 'dosen'
        }

        return view('profile.show', [
            'user' => $user,
            'profileData' => $profileData,
            'role' => $role,
            'status' => session('status'), // For password update status
        ]);
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route('profile.show')->with('success', 'Password berhasil diperbarui.'); // Changed to 'success' for toast
    }
}