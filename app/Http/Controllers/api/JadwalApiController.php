<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\DetailFrs;
use App\Models\Frs; // Pastikan model Frs di-import jika belum
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder; // Untuk type hinting pada query builder

class JadwalApiController extends Controller
{
    // Constructor tidak lagi mendaftarkan middleware di sini.
    // Middleware 'auth:sanctum' akan diterapkan pada level rute.
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    // }

    /**
     * Display a listing of the resource for the authenticated student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user(); // Mendapatkan user yang terautentikasi via middleware di rute

        // Pastikan user adalah mahasiswa dan memiliki id_mahasiswa
        // Middleware auth:sanctum sudah memastikan $user ada, tapi role check tetap penting.
        if (!$user || !$user->hasRole('mahasiswa') || !$user->id_mahasiswa) {
            return response()->json(['message' => 'Akses ditolak. Endpoint ini hanya untuk mahasiswa.'], 403);
        }

        // Ambil ID jadwal dari detail FRS yang sudah disetujui untuk mahasiswa ini
        $approvedJadwalIds = DetailFrs::join('frs', 'detail_frs.id_frs', '=', 'frs.id_frs')
            ->where('frs.id_mahasiswa', $user->id_mahasiswa)
            ->where('detail_frs.status', true) // status = true berarti disetujui
            ->pluck('detail_frs.id_jadwal')
            ->toArray();
        
        if (empty($approvedJadwalIds)) {
            return response()->json([
                'data' => [],
                // Menyertakan struktur paginasi dasar bahkan untuk data kosong demi konsistensi klien
                'links' => [
                    'first' => $request->url().'?page=1',
                    'last' => $request->url().'?page=1',
                    'prev' => null,
                    'next' => null,
                ],
                'meta' => [
                    'current_page' => 1,
                    'from' => null,
                    'last_page' => 1,
                    'links' => [],
                    'path' => $request->url(),
                    'per_page' => $request->input('per_page', 15),
                    'to' => null,
                    'total' => 0,
                ],
                'message' => 'Tidak ada jadwal yang disetujui ditemukan untuk Anda.'
            ], 200);
        }

        $query = Jadwal::with(['kelas', 'matkul', 'dosen', 'dosen2', 'waktu', 'ruangan'])
                       ->whereIn('jadwal.id_jadwal', $approvedJadwalIds);

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            $query->where(function (Builder $q) use ($searchTerm) {
                $q->whereHas('matkul', function (Builder $subQ) use ($searchTerm) {
                    $subQ->where('nama_matkul', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('jenis', 'LIKE', "%{$searchTerm}%");
                })
                ->orWhereHas('dosen', function (Builder $subQ) use ($searchTerm) {
                    $subQ->where('nama_dosen', 'LIKE', "%{$searchTerm}%");
                })
                ->orWhereHas('dosen2', function (Builder $subQ) use ($searchTerm) {
                    $subQ->where('nama_dosen', 'LIKE', "%{$searchTerm}%");
                })
                ->orWhereHas('kelas', function (Builder $subQ) use ($searchTerm) {
                    $subQ->where('prodi', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('paralel', 'LIKE', "%{$searchTerm}%");
                })
                ->orWhereHas('ruangan', function (Builder $subQ) use ($searchTerm) {
                    $subQ->where('kode_ruangan', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('nama_ruangan', 'LIKE', "%{$searchTerm}%");
                });
            });
        }

        // Filter by day (hari)
        if ($request->filled('hari')) {
            $hariValues = is_array($request->hari) ? $request->hari : [$request->hari];
            if (!empty(array_filter($hariValues))) {
                 $query->whereHas('waktu', function (Builder $q) use ($hariValues) {
                    $q->whereIn('hari', $hariValues);
                });
            }
        }
        
        $perPage = $request->input('per_page', 15);
        $jadwal = $query->orderBy('id_waktu')->paginate($perPage)->withQueryString();

        return response()->json($jadwal);
    }

    /**
     * Display the specified resource for the authenticated student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_jadwal  // Laravel akan otomatis melakukan route model binding jika tipe model Jadwal digunakan
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id_jadwal) // Bisa juga: public function show(Request $request, Jadwal $jadwal)
    {
        $user = $request->user();

        if (!$user || !$user->hasRole('mahasiswa') || !$user->id_mahasiswa) {
            return response()->json(['message' => 'Akses ditolak. Endpoint ini hanya untuk mahasiswa.'], 403);
        }

        // Verifikasi apakah jadwal ini ada dalam FRS mahasiswa yang disetujui
        $isApproved = DetailFrs::join('frs', 'detail_frs.id_frs', '=', 'frs.id_frs')
            ->where('frs.id_mahasiswa', $user->id_mahasiswa)
            ->where('detail_frs.id_jadwal', $id_jadwal) // Jika menggunakan route model binding, $jadwal->id_jadwal
            ->where('detail_frs.status', true)
            ->exists();

        if (!$isApproved) {
            return response()->json(['message' => 'Jadwal tidak ditemukan atau Anda tidak terdaftar pada jadwal ini.'], 404);
        }

        // Jika tidak menggunakan route model binding, cari manual
        $jadwalItem = Jadwal::with(['kelas', 'matkul', 'dosen', 'dosen2', 'waktu', 'ruangan'])
                        ->find($id_jadwal);
        
        // Jika menggunakan route model binding, $jadwal sudah merupakan instance Jadwal
        // $jadwalItem = $jadwal->loadMissing(['kelas', 'matkul', 'dosen', 'dosen2', 'waktu', 'ruangan']);


        if (!$jadwalItem) {
            return response()->json(['message' => 'Jadwal tidak ditemukan.'], 404);
        }

        return response()->json($jadwalItem);
    }
}