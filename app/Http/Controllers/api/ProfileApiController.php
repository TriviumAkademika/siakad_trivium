<?php

namespace App\Http\Controllers\API; // Namespace tetap

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdatePasswordRequest;

class ProfileApiController extends Controller // NAMA KELAS BERUBAH
{
    /**
     * Display the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $role = null;

        // Asumsi User model punya accessor getRoleAttribute() atau method getRoleNames() jika pakai Spatie
        if (method_exists($user, 'getRoleAttribute')) {
            $role = $user->getRoleAttribute();
        } elseif (method_exists($user, 'roles') && $user->roles->isNotEmpty()) {
             $role = $user->roles->first()->name;
        } elseif (method_exists($user, 'getRoleNames') && $user->getRoleNames()->isNotEmpty()){ // Untuk Spatie
             $role = $user->getRoleNames()->first();
        }


        $profileData = null;

        if ($role === 'mahasiswa') {
            $user->loadMissing('mahasiswa.kelas');
            if ($user->mahasiswa) {
                $profileData = $user->mahasiswa;
            }
        } elseif ($role === 'dosen') {
            $user->loadMissing('dosen');
            if ($user->dosen) {
                $profileData = $user->dosen;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil dimuat.',
            'data' => [
                'user' => [
                    'id_user' => $user->id_user, // atau 'id'
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'role' => $role,
                'profile_details' => $profileData,
            ]
        ]);
    }

    /**
     * Update the authenticated user's password.
     *
     * @param  \App\Http\Requests\UpdatePasswordRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
        ]);
    }
}