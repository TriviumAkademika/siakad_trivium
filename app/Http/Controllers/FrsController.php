<?php

namespace App\Http\Controllers;

use App\Models\Frs;
use App\Models\Mahasiswa;
use App\Models\Jadwal; // Tetap diperlukan untuk method show
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Tambahkan untuk logging error jika diperlukan

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
            $dosenId = $user->id_dosen; // Pastikan field ini ada di model User Anda

            if (!$dosenId) {
                return redirect()->back()->with([
                    'message' => 'Data dosen tidak ditemukan untuk user ini.',
                    'type' => 'error'
                ]);
            }

            // Query sesuai relasi yang diminta
            $frs = Frs::select('frs.*')
                ->join('mahasiswa as m', 'frs.id_mahasiswa', '=', 'm.id_mahasiswa')
                ->join('kelas as k', 'm.id_kelas', '=', 'k.id_kelas') // Asumsi mahasiswa punya relasi ke kelas, dan kelas ke dosen
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

    public function create()
    {
        // Hanya admin yang bisa mengakses form create FRS
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with([
                'message' => 'Anda tidak memiliki akses untuk menambah FRS.',
                'type' => 'error'
            ]);
        }

        // Ambil ID mahasiswa yang sudah memiliki FRS
        $mahasiswaWithFrsIds = Frs::pluck('id_mahasiswa')->unique()->toArray();

        // Ambil data mahasiswa yang BELUM memiliki FRS untuk dropdown
        // Pastikan 'id_mahasiswa' adalah nama kolom primary key di tabel mahasiswa
        $mahasiswa = Mahasiswa::whereNotIn('id_mahasiswa', $mahasiswaWithFrsIds)->get();

        if ($mahasiswa->isEmpty()) {
            return redirect()->route('frs.index')->with([
                'message' => 'Semua mahasiswa yang terdaftar sudah memiliki FRS, atau tidak ada data mahasiswa.',
                'type' => 'info'
            ]);
        }

        // Generate tahun ajaran list (5 tahun ke belakang dan 2 tahun ke depan)
        $currentYear = date('Y');
        $tahunAjaranList = [];

        for ($i = -5; $i <= 2; $i++) {
            $year = $currentYear + $i;
            $nextYear = $year + 1;
            $tahunAjaranList[] = $year . '/' . $nextYear;
        }

        return view('frs.create', compact('mahasiswa', 'tahunAjaranList'));
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

        // Cek apakah mahasiswa sudah memiliki FRS (secara umum, bukan per tahun ajaran)
        $existingFrs = Frs::where('id_mahasiswa', $validatedData['id_mahasiswa'])->first();

        if ($existingFrs) {
            return redirect()->back()->withInput()->with([
                'message' => 'Mahasiswa ini sudah memiliki FRS. Satu mahasiswa hanya diizinkan memiliki satu FRS.',
                'type' => 'error'
            ]);
        }

        // Ambil data mahasiswa untuk mendapatkan semester
        $mahasiswa = Mahasiswa::findOrFail($validatedData['id_mahasiswa']);

        // Set semester dari data mahasiswa
        $validatedData['semester'] = $mahasiswa->semester; // Pastikan model Mahasiswa memiliki atribut 'semester'

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
            Log::error('Error creating FRS: ' . $e->getMessage()); // Logging error
            return redirect()->back()->withInput()->with([
                'message' => 'Gagal menambahkan FRS. Silakan coba lagi atau hubungi administrator.',
                'type' => 'error'
            ]);
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $frs = null; // Inisialisasi $frs

        // Cek akses berdasarkan role
        if ($user->role === 'admin') {
            // Admin bisa melihat semua detail FRS
            $frs = Frs::with([
                'mahasiswa',
                'detailFrs.jadwal.matkul',
                'detailFrs.jadwal.dosen',
                'detailFrs.jadwal.ruangan',
                'detailFrs.jadwal.waktu'
            ])->findOrFail($id);
        } elseif ($user->role === 'dosen') {
            $dosenId = $user->id_dosen; // Pastikan field ini ada di model User Anda

            if (!$dosenId) {
                return redirect()->back()->with([
                    'message' => 'Data dosen tidak ditemukan untuk user ini.',
                    'type' => 'error'
                ]);
            }

            // Cek apakah FRS ini milik mahasiswa wali dosen
            $isMahasiswaWali = Frs::where('id_frs', $id)
                ->whereHas('mahasiswa.kelas', function ($query) use ($dosenId) {
                    $query->where('id_dosen', $dosenId);
                })
                ->exists();

            if (!$isMahasiswaWali) {
                return redirect()->back()->with([
                    'message' => 'Anda tidak memiliki akses untuk melihat FRS ini.',
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
        } else {
            return redirect()->back()->with([
                'message' => 'Anda tidak memiliki akses ke halaman ini.',
                'type' => 'error'
            ]);
        }

        // Pastikan $frs tidak null sebelum melanjutkan
        if (!$frs) {
             return redirect()->route('frs.index')->with([ // atau halaman lain yang sesuai
                'message' => 'FRS tidak ditemukan atau Anda tidak memiliki akses.',
                'type' => 'error'
            ]);
        }

        $jadwalTerpilih = $frs->detailFrs->pluck('id_jadwal')->toArray();
        $jadwals = Jadwal::whereNotIn('id_jadwal', $jadwalTerpilih)
            ->with(['matkul', 'dosen', 'ruangan', 'waktu'])
            ->get();

        return view('detail_frs.index', compact('frs', 'jadwals'));
    }


    public function edit($id)
    {
        $user = Auth::user();
        $frs = Frs::findOrFail($id);

        // Cek akses berdasarkan role
        if ($user->role === 'admin') {
            // Admin bisa edit semua FRS
        } elseif ($user->role === 'dosen') {
            $dosenId = $user->id_dosen; // Pastikan field ini ada di model User Anda

            if (!$dosenId) {
                return redirect()->back()->with([
                    'message' => 'Data dosen tidak ditemukan untuk user ini.',
                    'type' => 'error'
                ]);
            }

            // Cek apakah FRS ini milik mahasiswa wali dosen
            $isMahasiswaWali = Frs::where('id_frs', $id)
                ->whereHas('mahasiswa.kelas', function ($query) use ($dosenId) {
                    $query->where('id_dosen', $dosenId);
                })
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

        // Untuk edit, mahasiswa yang bersangkutan tetap bisa diedit,
        // jadi kita tidak perlu filter mahasiswa seperti di create.
        // Namun, jika Anda ingin id_mahasiswa tidak bisa diubah, maka field tersebut bisa di-disable di view.
        $mahasiswaList = Mahasiswa::all(); // Atau hanya mahasiswa yang bersangkutan jika tidak ingin bisa diubah
        // $mahasiswaYangBolehDipilih = Mahasiswa::where('id_mahasiswa', $frs->id_mahasiswa)->get();

        $currentYear = date('Y');
        $tahunAjaranList = [];

        for ($i = -5; $i <= 2; $i++) {
            $year = $currentYear + $i;
            $nextYear = $year + 1;
            $tahunAjaranList[] = $year . '/' . $nextYear;
        }

        // Jika ingin field 'mahasiswa' tidak bisa diganti saat edit,
        // Anda bisa mengirimkan hanya data mahasiswa yang bersangkutan ke view
        // dan di view, field tersebut ditampilkan sebagai teks atau dropdown yang disabled.
        // $mahasiswa = Mahasiswa::where('id_mahasiswa', $frs->id_mahasiswa)->get();
        // return view('frs.edit', compact('frs', 'mahasiswa', 'tahunAjaranList'));

        return view('frs.edit', compact('frs', 'mahasiswaList', 'tahunAjaranList'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $frs = Frs::findOrFail($id);

        // Cek akses berdasarkan role (sama seperti edit)
        if ($user->role === 'admin') {
            // Admin bisa update semua FRS
        } elseif ($user->role === 'dosen') {
            $dosenId = $user->id_dosen; // Pastikan field ini ada di model User Anda

            if (!$dosenId) {
                return redirect()->back()->with([
                    'message' => 'Data dosen tidak ditemukan untuk user ini.',
                    'type' => 'error'
                ]);
            }

            $isMahasiswaWali = Frs::where('id_frs', $id)
                ->whereHas('mahasiswa.kelas', function ($query) use ($dosenId) {
                    $query->where('id_dosen', $dosenId);
                })
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
            // id_mahasiswa mungkin tidak seharusnya diubah setelah FRS dibuat.
            // Jika bisa diubah, validasi di bawah ini relevan.
            // Jika tidak, hapus 'id_mahasiswa' dari validasi dan jangan update field ini.
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'tahun_ajaran' => 'required|string|max:255',
            'total_sks' => 'nullable|integer|min:0',
            'ips' => 'nullable|numeric|between:0,4.00',
            'ipk' => 'nullable|numeric|between:0,4.00',
        ]);

        // Jika id_mahasiswa diubah, cek apakah mahasiswa baru ini sudah punya FRS lain.
        // Ini hanya relevan jika Anda memperbolehkan id_mahasiswa diubah.
        if ($frs->id_mahasiswa != $validatedData['id_mahasiswa']) {
            $existingFrsForNewMahasiswa = Frs::where('id_mahasiswa', $validatedData['id_mahasiswa'])->first();
            if ($existingFrsForNewMahasiswa) {
                return redirect()->back()->withInput()->with([
                    'message' => 'Mahasiswa yang dipilih sudah memiliki FRS lain.',
                    'type' => 'error'
                ]);
            }
        }
        // Jika id_mahasiswa tidak berubah, tidak perlu cek existing FRS untuk mahasiswa yang sama.

        // Ambil data mahasiswa untuk mendapatkan semester jika id_mahasiswa berubah
        // atau jika Anda ingin selalu update semester berdasarkan data mahasiswa terkini.
        $mahasiswa = Mahasiswa::findOrFail($validatedData['id_mahasiswa']);
        $validatedData['semester'] = $mahasiswa->semester; // Pastikan model Mahasiswa memiliki atribut 'semester'
        $validatedData['tgl_perubahan'] = now();

        try {
            $frs->update($validatedData);

            return redirect()->route('frs.index')->with([
                'message' => 'FRS berhasil diperbarui!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating FRS: ' . $e->getMessage()); // Logging error
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

            // Hapus detail FRS terlebih dahulu (jika ada relasi onDelete cascade, ini otomatis)
            // Jika tidak ada onDelete cascade, Anda harus menghapusnya secara manual
            // $frs->detailFrs()->delete(); // Uncomment jika relasi DetailFrs ada dan perlu dihapus manual

            $frs->delete();

            return redirect()->route('frs.index')->with([
                'message' => 'FRS berhasil dihapus!',
                'type' => 'warning' // atau 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting FRS: ' . $e->getMessage()); // Logging error
            return redirect()->back()->with([
                'message' => 'Gagal menghapus FRS. Pastikan tidak ada data terkait atau coba lagi.',
                'type' => 'error'
            ]);
        }
    }

    public function drop($id)
    {
        $user = Auth::user();
        $frs = Frs::findOrFail($id); // Temukan FRS dulu untuk cek kepemilikan

        // Cek akses (sama seperti edit/update)
        if ($user->role === 'admin') {
            // Admin bisa drop semua FRS
        } elseif ($user->role === 'dosen') {
            $dosenId = $user->id_dosen; // Pastikan field ini ada di model User Anda

            if (!$dosenId) {
                return redirect()->back()->with([
                    'message' => 'Data dosen tidak ditemukan untuk user ini.',
                    'type' => 'error'
                ]);
            }

            $isMahasiswaWali = $frs->mahasiswa && $frs->mahasiswa->kelas && $frs->mahasiswa->kelas->id_dosen == $dosenId;

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
            // $frs sudah di-load di atas
            $frs->update([
                'tgl_drop' => now(),
                'tgl_perubahan' => now()
            ]);

            // Jika ada DetailFrs yang terkait dan perlu diupdate statusnya
            if ($frs->detailFrs()) { // Pastikan relasi detailFrs ada
                 $frs->detailFrs()->update([
                    'status' => false, // Asumsi ada field status di DetailFrs
                    'tgl_drop' => now()
                ]);
            }


            return redirect()->route('frs.index')->with([
                'message' => 'FRS berhasil di-drop!',
                'type' => 'warning'
            ]);
        } catch (\Exception $e) {
            Log::error('Error dropping FRS: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'Gagal melakukan drop FRS.',
                'type' => 'error'
            ]);
        }
    }

    public function reactivate($id)
    {
        $user = Auth::user();
        $frs = Frs::findOrFail($id); // Temukan FRS dulu untuk cek kepemilikan

        // Cek akses (sama seperti drop)
        if ($user->role === 'admin') {
            // Admin bisa reactivate semua FRS
        } elseif ($user->role === 'dosen') {
            $dosenId = $user->id_dosen; // Pastikan field ini ada di model User Anda

            if (!$dosenId) {
                return redirect()->back()->with([
                    'message' => 'Data dosen tidak ditemukan untuk user ini.',
                    'type' => 'error'
                ]);
            }

            $isMahasiswaWali = $frs->mahasiswa && $frs->mahasiswa->kelas && $frs->mahasiswa->kelas->id_dosen == $dosenId;

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
            // $frs sudah di-load di atas
            $frs->update([
                'tgl_drop' => null,
                'tgl_perubahan' => now()
            ]);

            // Reactivate detail FRS juga
             if ($frs->detailFrs()) { // Pastikan relasi detailFrs ada
                $frs->detailFrs()->update([
                    'status' => true, // Asumsi ada field status di DetailFrs
                    'tgl_drop' => null
                ]);
            }

            return redirect()->route('frs.index')->with([
                'message' => 'FRS berhasil direaktivasi!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Error reactivating FRS: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'Gagal melakukan reaktivasi FRS.',
                'type' => 'error'
            ]);
        }
    }
}