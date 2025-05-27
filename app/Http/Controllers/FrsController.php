<?php

namespace App\Http\Controllers;

use App\Models\Frs;
use App\Models\Mahasiswa;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Admin bisa melihat semua FRS
            $frs = Frs::with('mahasiswa')->get();
        } elseif ($user->role === 'dosen') {
            // Dosen hanya bisa melihat FRS mahasiswa yang menjadi walinya
            // Asumsi: tabel users memiliki id_dosen yang berelasi dengan tabel dosen
            $dosenId = $user->id_dosen;
            
            if (!$dosenId) {
                return redirect()->back()->with([
                    'message' => 'Data dosen tidak ditemukan untuk user ini.',
                    'type' => 'error'
                ]);
            }
            
            // Query sesuai relasi yang diminta
            $frs = Frs::select('frs.*')
                ->join('mahasiswa as m', 'frs.id_mahasiswa', '=', 'm.id_mahasiswa')
                ->join('kelas as k', 'm.id_kelas', '=', 'k.id_kelas')
                ->where('k.id_dosen', $dosenId)
                ->with('mahasiswa')
                ->get();
        } else {
            // Role lain tidak bisa mengakses
            return redirect()->back()->with([
                'message' => 'Anda tidak memiliki akses ke halaman ini.',
                'type' => 'error'
            ]);
        }
        
        return view('frs.index', compact('frs'));
    }

    public function store(Request $request)
    {
        // Hanya admin yang bisa menambah FRS
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with([
                'message' => 'Anda tidak memiliki akses untuk menambah FRS.',
                'type' => 'error'
            ]);
        }

        $validatedData = $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'tahun_ajaran' => 'required|string|max:255',
            'total_sks' => 'nullable|integer|min:0',
            'ips' => 'nullable|numeric|between:0,4.00',
            'ipk' => 'nullable|numeric|between:0,4.00',
        ]);

        // Ambil data mahasiswa untuk mendapatkan semester
        $mahasiswa = Mahasiswa::findOrFail($validatedData['id_mahasiswa']);

        // Set semester dari data mahasiswa
        $validatedData['semester'] = $mahasiswa->semester;

        // Set default values
        $validatedData['total_sks'] = $validatedData['total_sks'] ?? 0;
        $validatedData['tgl_pengisian'] = now();
        $validatedData['tgl_perubahan'] = now();
        $validatedData['tgl_drop'] = null;

        try {
            Frs::create($validatedData);

            return redirect()->route('frs.index')->with([
                'message' => 'FRS berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with([
                'message' => 'Gagal menambahkan FRS. Silakan coba lagi.',
                'type' => 'error'
            ]);
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        $frs = Frs::findOrFail($id);
        
        // Cek akses berdasarkan role
        if ($user->role === 'admin') {
            // Admin bisa edit semua FRS
        } elseif ($user->role === 'dosen') {
            // Dosen hanya bisa edit FRS mahasiswa walinya
            $dosenId = $user->id_dosen;
            
            if (!$dosenId) {
                return redirect()->back()->with([
                    'message' => 'Data dosen tidak ditemukan untuk user ini.',
                    'type' => 'error'
                ]);
            }
            
            // Cek apakah FRS ini milik mahasiswa wali dosen
            $isMahasiswaWali = Frs::select('frs.*')
                ->join('mahasiswa as m', 'frs.id_mahasiswa', '=', 'm.id_mahasiswa')
                ->join('kelas as k', 'm.id_kelas', '=', 'k.id_kelas')
                ->where('k.id_dosen', $dosenId)
                ->where('frs.id_frs', $id)
                ->exists();
                
            if (!$isMahasiswaWali) {
                return redirect()->back()->with([
                    'message' => 'Anda tidak memiliki akses untuk mengedit FRS ini.',
                    'type' => 'error'
                ]);
            }
        } else {
            return redirect()->back()->with([
                'message' => 'Anda tidak memiliki akses ke halaman ini.',
                'type' => 'error'
            ]);
        }
        
        $mahasiswa = Mahasiswa::all();
        return view('frs.edit', compact('frs', 'mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $frs = Frs::findOrFail($id);
        
        // Cek akses berdasarkan role (sama seperti edit)
        if ($user->role === 'admin') {
            // Admin bisa update semua FRS
        } elseif ($user->role === 'dosen') {
            $dosenId = $user->id_dosen;
            
            if (!$dosenId) {
                return redirect()->back()->with([
                    'message' => 'Data dosen tidak ditemukan untuk user ini.',
                    'type' => 'error'
                ]);
            }
            
            $isMahasiswaWali = Frs::select('frs.*')
                ->join('mahasiswa as m', 'frs.id_mahasiswa', '=', 'm.id_mahasiswa')
                ->join('kelas as k', 'm.id_kelas', '=', 'k.id_kelas')
                ->where('k.id_dosen', $dosenId)
                ->where('frs.id_frs', $id)
                ->exists();
                
            if (!$isMahasiswaWali) {
                return redirect()->back()->with([
                    'message' => 'Anda tidak memiliki akses untuk mengupdate FRS ini.',
                    'type' => 'error'
                ]);
            }
        } else {
            return redirect()->back()->with([
                'message' => 'Anda tidak memiliki akses ke halaman ini.',
                'type' => 'error'
            ]);
        }

        $validatedData = $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'tahun_ajaran' => 'required|string|max:255',
            'total_sks' => 'nullable|integer|min:0',
            'ips' => 'nullable|numeric|between:0,4.00',
            'ipk' => 'nullable|numeric|between:0,4.00',
        ]);

        // Ambil data mahasiswa untuk mendapatkan semester
        $mahasiswa = Mahasiswa::findOrFail($validatedData['id_mahasiswa']);
        $validatedData['semester'] = $mahasiswa->semester;
        $validatedData['tgl_perubahan'] = now();

        try {
            $frs->update($validatedData);

            return redirect()->route('frs.index')->with([
                'message' => 'FRS berhasil diperbarui!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with([
                'message' => 'Gagal memperbarui FRS. Silakan coba lagi.',
                'type' => 'error'
            ]);
        }
    }

    public function destroy($id)
    {
        // Hanya admin yang bisa menghapus FRS
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with([
                'message' => 'Anda tidak memiliki akses untuk menghapus FRS.',
                'type' => 'error'
            ]);
        }

        try {
            $frs = Frs::findOrFail($id);
            $frs->detailFrs()->delete();
            $frs->delete();

            return redirect()->route('frs.index')->with([
                'message' => 'FRS berhasil dihapus!',
                'type' => 'warning'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Gagal menghapus FRS. Pastikan tidak ada data terkait.',
                'type' => 'error'
            ]);
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        
        // Cek akses berdasarkan role
        if ($user->role === 'admin') {
            // Admin bisa melihat semua detail FRS
        } elseif ($user->role === 'dosen') {
            $dosenId = $user->id_dosen;
            
            if (!$dosenId) {
                return redirect()->back()->with([
                    'message' => 'Data dosen tidak ditemukan untuk user ini.',
                    'type' => 'error'
                ]);
            }
            
            // Cek apakah FRS ini milik mahasiswa wali dosen
            $isMahasiswaWali = Frs::select('frs.*')
                ->join('mahasiswa as m', 'frs.id_mahasiswa', '=', 'm.id_mahasiswa')
                ->join('kelas as k', 'm.id_kelas', '=', 'k.id_kelas')
                ->where('k.id_dosen', $dosenId)
                ->where('frs.id_frs', $id)
                ->exists();
                
            if (!$isMahasiswaWali) {
                return redirect()->back()->with([
                    'message' => 'Anda tidak memiliki akses untuk melihat FRS ini.',
                    'type' => 'error'
                ]);
            }
        } else {
            return redirect()->back()->with([
                'message' => 'Anda tidak memiliki akses ke halaman ini.',
                'type' => 'error'
            ]);
        }

        $frs = Frs::with([
            'mahasiswa',
            'detailFrs.jadwal.matkul',
            'detailFrs.jadwal.dosen',
            'detailFrs.jadwal.ruangan',
            'detailFrs.jadwal.waktu'
        ])->findOrFail($id);

        $jadwalTerpilih = $frs->detailFrs->pluck('id_jadwal')->toArray();
        $jadwals = Jadwal::whereNotIn('id_jadwal', $jadwalTerpilih)
            ->with(['matkul', 'dosen', 'ruangan', 'waktu'])
            ->get();

        return view('detail_frs.index', compact('frs', 'jadwals'));
    }

    public function drop($id)
    {
        $user = Auth::user();
        
        // Cek akses (sama seperti edit/update)
        if ($user->role === 'admin') {
            // Admin bisa drop semua FRS
        } elseif ($user->role === 'dosen') {
            $dosenId = $user->id_dosen;
            
            if (!$dosenId) {
                return redirect()->back()->with([
                    'message' => 'Data dosen tidak ditemukan untuk user ini.',
                    'type' => 'error'
                ]);
            }
            
            $isMahasiswaWali = Frs::select('frs.*')
                ->join('mahasiswa as m', 'frs.id_mahasiswa', '=', 'm.id_mahasiswa')
                ->join('kelas as k', 'm.id_kelas', '=', 'k.id_kelas')
                ->where('k.id_dosen', $dosenId)
                ->where('frs.id_frs', $id)
                ->exists();
                
            if (!$isMahasiswaWali) {
                return redirect()->back()->with([
                    'message' => 'Anda tidak memiliki akses untuk drop FRS ini.',
                    'type' => 'error'
                ]);
            }
        } else {
            return redirect()->back()->with([
                'message' => 'Anda tidak memiliki akses ke halaman ini.',
                'type' => 'error'
            ]);
        }

        try {
            $frs = Frs::findOrFail($id);

            $frs->update([
                'tgl_drop' => now(),
                'tgl_perubahan' => now()
            ]);

            $frs->detailFrs()->update([
                'status' => false,
                'tgl_drop' => now()
            ]);

            return redirect()->route('frs.index')->with([
                'message' => 'FRS berhasil di-drop!',
                'type' => 'warning'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Gagal melakukan drop FRS.',
                'type' => 'error'
            ]);
        }
    }

    public function reactivate($id)
    {
        $user = Auth::user();
        
        // Cek akses (sama seperti drop)
        if ($user->role === 'admin') {
            // Admin bisa reactivate semua FRS
        } elseif ($user->role === 'dosen') {
            $dosenId = $user->id_dosen;
            
            if (!$dosenId) {
                return redirect()->back()->with([
                    'message' => 'Data dosen tidak ditemukan untuk user ini.',
                    'type' => 'error'
                ]);
            }
            
            $isMahasiswaWali = Frs::select('frs.*')
                ->join('mahasiswa as m', 'frs.id_mahasiswa', '=', 'm.id_mahasiswa')
                ->join('kelas as k', 'm.id_kelas', '=', 'k.id_kelas')
                ->where('k.id_dosen', $dosenId)
                ->where('frs.id_frs', $id)
                ->exists();
                
            if (!$isMahasiswaWali) {
                return redirect()->back()->with([
                    'message' => 'Anda tidak memiliki akses untuk reaktivasi FRS ini.',
                    'type' => 'error'
                ]);
            }
        } else {
            return redirect()->back()->with([
                'message' => 'Anda tidak memiliki akses ke halaman ini.',
                'type' => 'error'
            ]);
        }

        try {
            $frs = Frs::findOrFail($id);

            $frs->update([
                'tgl_drop' => null,
                'tgl_perubahan' => now()
            ]);

            return redirect()->route('frs.index')->with([
                'message' => 'FRS berhasil direaktivasi!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Gagal melakukan reaktivasi FRS.',
                'type' => 'error'
            ]);
        }
    }
}