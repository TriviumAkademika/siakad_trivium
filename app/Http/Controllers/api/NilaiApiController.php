<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nilai; // Pastikan nama model Anda adalah 'Nilai.php' (huruf N besar)
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\Matkul;
use App\Models\Frs;
use App\Models\DetailFrs; // Pastikan model DetailFrs juga di-import
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class NilaiApiController extends Controller
{
    /**
     * Get nilai list for dosen.
     * API ini akan mengembalikan daftar matakuliah yang diajar oleh dosen.
     * Jika parameter id_matkul diberikan, API akan mengembalikan daftar mahasiswa
     * yang mengambil matakuliah tersebut dan DetailFRS-nya memiliki status true (aktif/disetujui),
     * beserta nilai UTS dan UAS mereka untuk matakuliah tersebut.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $id_matkul_param = $request->get('id_matkul');

            $dosen = $user->dosen; // Asumsi ada relasi 'dosen' pada model User
            if (!$dosen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya dosen yang dapat mengakses endpoint ini.'
                ], 403);
            }

            // Ambil daftar matakuliah yang diajar oleh dosen ini
            $matkuls = Matkul::whereHas('jadwal', function ($q) use ($dosen) {
                $q->where('id_dosen', $dosen->id_dosen)
                    ->orWhere('id_dosen_2', $dosen->id_dosen);
            })->get(['id_matkul', 'nama_matkul', 'kode_matkul', 'sks', 'jenis']); // Pilih kolom spesifik

            $response = [
                'success' => true,
                'data' => [
                    'matkuls' => $matkuls,
                    'dosen' => $dosen->only(['id_dosen', 'nama_dosen', 'nip']) // Pilih kolom spesifik
                ]
            ];

            if ($id_matkul_param) {
                $isValidMatkul = $matkuls->contains('id_matkul', $id_matkul_param);

                if (!$isValidMatkul) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke matakuliah tersebut.'
                    ], 403);
                }

                // Ambil mahasiswa yang DetailFRS-nya untuk matakuliah ini berstatus true (aktif)
                // dan mata kuliah tersebut diajar oleh dosen yang sedang login.
                $mahasiswa_list = Mahasiswa::whereHas('detailFrs', function ($detailFrsQuery) use ($id_matkul_param, $dosen) {
                    $detailFrsQuery->where('status', true) // <<< FILTER: Hanya DetailFRS dengan status true
                        ->whereHas('jadwal', function ($jadwalQuery) use ($id_matkul_param, $dosen) {
                            $jadwalQuery->where('id_matkul', $id_matkul_param)
                                ->where(function ($dosenSpecificQuery) use ($dosen) {
                                    $dosenSpecificQuery->where('id_dosen', $dosen->id_dosen)
                                        ->orWhere('id_dosen_2', $dosen->id_dosen);
                                });
                        });
                })->with(['nilai' => function ($nilaiQuery) use ($id_matkul_param) {
                    // Eager load nilai UTS dan UAS untuk matakuliah yang spesifik
                    // Pastikan nama relasi di Model Mahasiswa adalah 'nilai' (bukan 'Nilai')
                    $nilaiQuery->where('matakuliah_id', $id_matkul_param)
                        ->whereIn('jenis_nilai', ['UTS', 'UAS']);
                }])
                    ->select(['id_mahasiswa', 'nama', 'nrp']) // Pilih kolom spesifik untuk mahasiswa
                    ->get();

                $response['data']['mahasiswa'] = $mahasiswa_list;
                $response['data']['id_matkul_selected'] = (int)$id_matkul_param;
            }

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error in NilaiApiController@index: ' . $e->getMessage() . "\nStack Trace:\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id_mahasiswa_param, Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->dosen) {
                return response()->json(['success' => false, 'message' => 'Hanya dosen yang dapat mengakses endpoint ini.'], 403);
            }

            $id_matkul = $request->query('id_matkul');
            if (!$id_matkul) {
                return response()->json(['success' => false, 'message' => 'Parameter id_matkul diperlukan.'], 400);
            }

            $mahasiswa = Mahasiswa::find($id_mahasiswa_param);
            $matkul = Matkul::find($id_matkul);

            if (!$mahasiswa || !$matkul) {
                return response()->json(['success' => false, 'message' => 'Mahasiswa atau matakuliah tidak ditemukan.'], 404);
            }

            // Gunakan Nilai (N besar)
            $nilaiUTS = Nilai::where('mahasiswa_id', $id_mahasiswa_param)
                ->where('matakuliah_id', $id_matkul)
                ->where('jenis_nilai', 'UTS')
                ->first();

            // Gunakan Nilai (N besar)
            $nilaiUAS = Nilai::where('mahasiswa_id', $id_mahasiswa_param)
                ->where('matakuliah_id', $id_matkul)
                ->where('jenis_nilai', 'UAS')
                ->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'mahasiswa' => $mahasiswa->only(['id_mahasiswa', 'nama', 'nrp']),
                    'matkul' => $matkul->only(['id_matkul', 'nama_matkul', 'kode_matkul']),
                    'nilai_uts' => $nilaiUTS ? $nilaiUTS->nilai : null,
                    'nilai_uas' => $nilaiUAS ? $nilaiUAS->nilai : null
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in NilaiApiController@show: ' . $e->getMessage() . "\nStack Trace:\n" . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()], 500);
        }
    }

    // --- METHOD BARU UNTUK FLUTTER ---
    /**
     * Mendapatkan daftar nilai untuk mahasiswa yang sedang login (untuk aplikasi mobile).
     * Hanya akan menampilkan nilai dari matakuliah yang DetailFRS-nya berstatus true (aktif/disetujui).
     */
    public function getNilaiMahasiswa(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            // Pastikan ada relasi 'mahasiswa' pada model User Anda
            // contoh: public function mahasiswa() { return $this->hasOne(Mahasiswa::class, 'user_id'); }
            $mahasiswa = $user->mahasiswa;

            if (!$mahasiswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses hanya untuk mahasiswa.'
                ], 403);
            }

            $search = $request->input('search');

            // Mengambil semua FRS (header) milik mahasiswa yang login.
            $frsMahasiswaList = Frs::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                // Jika FRS (header) juga memiliki status yang perlu dicek (misal 'Disetujui'), tambahkan di sini.
                // ->where('status_frs_header', 'Disetujui') 
                ->with([
                    // Kemudian, memuat relasi 'detailFrs' HANYA yang statusnya true,
                    // beserta relasi 'jadwal' dan 'matkul' dari detailFrs yang aktif tersebut.
                    'detailFrs' => function ($queryDetailFrs) {
                        $queryDetailFrs->where('status', true) // <<< PENTING: Filter DetailFRS yang aktif
                            ->with([
                                'jadwal.matkul' => function ($matkulQuery) {
                                    $matkulQuery->select('id_matkul', 'nama_matkul', 'sks', 'jenis');
                                },
                                'jadwal' => function ($jadwalQuery) {
                                    // Pilih semua kolom yang dibutuhkan model Jadwal di Flutter
                                    $jadwalQuery->select('id_jadwal', 'id_kelas', 'id_matkul', 'id_dosen', 'id_dosen_2', 'id_waktu', 'id_ruangan')
                                        ->with(['dosen:id_dosen,nama_dosen', 'dosen2:id_dosen,nama_dosen']);
                                }
                            ]);
                    },
                ])
                // Memilih kolom spesifik dari FRS untuk efisiensi
                // Sesuaikan 'tahun_ajaran', 'semester' dengan kolom yang ada di tabel frs Anda
                ->get(['id_frs', 'id_mahasiswa', 'tahun_ajaran', 'semester']);

            // Mengambil semua data nilai (UTS dan UAS) mahasiswa dalam satu query
            // Gunakan Nilai (N besar) dan pastikan nama relasi 'nilai' di model Mahasiswa benar
            $semuaNilaiMahasiswaLookup = Nilai::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                ->whereIn('jenis_nilai', ['UTS', 'UAS'])
                ->get()
                // Grouping untuk memudahkan pencarian nilai
                ->groupBy(['matakuliah_id', 'jenis_nilai']);

            $nilaiListUntukResponse = collect();

            foreach ($frsMahasiswaList as $frs) {
                // Iterasi hanya pada detailFrs yang sudah difilter (status=true)
                foreach ($frs->detailFrs as $detail) {
                    if ($detail->jadwal && $detail->jadwal->matkul) {
                        $matkul = $detail->jadwal->matkul;
                        $matkulNamaLower = strtolower($matkul->nama_matkul ?? '');
                        $searchTermLower = strtolower($search ?? '');

                        if (!empty($search) && !str_contains($matkulNamaLower, $searchTermLower)) {
                            continue;
                        }

                        $matkulId = $matkul->id_matkul;

                        $nilaiUTSObj = $semuaNilaiMahasiswaLookup->get($matkulId, collect())->get('UTS', collect())->first();
                        $nilaiUASObj = $semuaNilaiMahasiswaLookup->get($matkulId, collect())->get('UAS', collect())->first();

                        // --- TAMBAHAN UNTUK MENYERTAKAN JADWAL KE MATKUL ---
                        $matkulOutput = $matkul->toArray(); // Ambil atribut dasar matkul
                        if ($detail->jadwal) {
                            // $detail->jadwal sudah memuat relasi dosen dan dosen2
                            // Konversi ke array agar bisa disisipkan
                            $matkulOutput['jadwal'] = $detail->jadwal->toArray();
                        } else {
                            $matkulOutput['jadwal'] = null;
                        }
                        // --- AKHIR TAMBAHAN ---

                        $nilaiListUntukResponse->push([
                            'matkul' => $matkulOutput, // Gunakan $matkulOutput yang sudah dimodifikasi
                            'sks' => $matkul->sks ?? 0,
                            'nilai_uts' => $nilaiUTSObj->nilai ?? null,
                            'nilai_uas' => $nilaiUASObj->nilai ?? null,
                            'jenis' => $matkul->jenis ?? 'Wajib',
                            'tahun_ajaran' => $frs->tahun_ajaran,
                            'semester' => $frs->semester,
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'mahasiswa' => $mahasiswa->only(['id_mahasiswa', 'nama', 'nrp', 'semester']),
                    'nilai_list' => $nilaiListUntukResponse,
                    'search' => $search,
                    'count' => $nilaiListUntukResponse->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in NilaiApiController@getNilaiMahasiswa: ' . $e->getMessage() . "\nStack Trace:\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}
