<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\Matkul;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class NilaiApiController extends Controller
{
    /**
     * Get nilai list for dosen
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $id_matkul = $request->get('id_matkul');
            
            // Validasi dosen login
            $dosen = $user->dosen;
            if (!$dosen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya dosen yang dapat mengakses endpoint ini'
                ], 403);
            }
            
            // Ambil daftar matakuliah yang diajar oleh dosen ini
            $matkuls = Matkul::whereHas('jadwal', function($q) use ($dosen) {
                $q->where('id_dosen', $dosen->id_dosen)
                  ->orWhere('id_dosen_2', $dosen->id_dosen);
            })->get();
            
            $response = [
                'success' => true,
                'data' => [
                    'matkuls' => $matkuls,
                    'dosen' => $dosen
                ]
            ];
            
            // Jika ada id_matkul yang dipilih
            if ($id_matkul) {
                $isValidMatkul = $matkuls->contains('id_matkul', $id_matkul);
                
                if (!$isValidMatkul) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke matakuliah tersebut'
                    ], 403);
                }
                
                // Ambil mahasiswa yang mengambil matakuliah ini
                $mahasiswa = Mahasiswa::whereHas('frs.detailFrs.jadwal', function($q) use ($id_matkul, $dosen) {
                    $q->where('id_matkul', $id_matkul)
                      ->where(function($query) use ($dosen) {
                          $query->where('id_dosen', $dosen->id_dosen)
                                ->orWhere('id_dosen_2', $dosen->id_dosen);
                      });
                })->with(['nilai' => function($query) use ($id_matkul) {
                    $query->where('matakuliah_id', $id_matkul);
                }])->get();
                
                $response['data']['mahasiswa'] = $mahasiswa;
                $response['data']['id_matkul'] = $id_matkul;
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error('Error in NilaiApiController@index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    /**
     * Store nilai baru
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_jadwal' => 'required|exists:jadwal,id_jadwal',
                'nilai' => 'required|array',
                'nilai.*.id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
                'nilai.*.nilai' => 'nullable|numeric|min:0|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $stored = [];
            foreach ($request->nilai as $item) {
                $nilai = Nilai::updateOrCreate(
                    [
                        'id_jadwal' => $request->id_jadwal,
                        'id_mahasiswa' => $item['id_mahasiswa'],
                    ],
                    [
                        'nilai' => $item['nilai'],
                    ]
                );
                $stored[] = $nilai;
            }

            return response()->json([
                'success' => true,
                'message' => 'Nilai berhasil disimpan',
                'data' => $stored
            ]);

        } catch (\Exception $e) {
            Log::error('Error in NilaiApiController@store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    /**
     * Get detail nilai mahasiswa untuk edit
     */
    public function show($id_mahasiswa, Request $request): JsonResponse
    {
        try {
            $id_matkul = $request->query('id_matkul');
            
            if (!$id_matkul) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter id_matkul diperlukan'
                ], 400);
            }

            $mahasiswa = Mahasiswa::find($id_mahasiswa);
            $matkul = Matkul::find($id_matkul);
            
            if (!$mahasiswa || !$matkul) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mahasiswa atau matakuliah tidak ditemukan'
                ], 404);
            }
            
            // Ambil nilai UTS dan UAS yang sudah ada
            $nilaiUTS = Nilai::where('mahasiswa_id', $id_mahasiswa)
                            ->where('matakuliah_id', $id_matkul)
                            ->where('jenis_nilai', 'UTS')
                            ->first();

            $nilaiUAS = Nilai::where('mahasiswa_id', $id_mahasiswa)
                            ->where('matakuliah_id', $id_matkul)
                            ->where('jenis_nilai', 'UAS')
                            ->first();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'mahasiswa' => $mahasiswa,
                    'matkul' => $matkul,
                    'nilai_uts' => $nilaiUTS,
                    'nilai_uas' => $nilaiUAS
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in NilaiApiController@show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    /**
     * Update nilai mahasiswa
     */
    public function update(Request $request, $id_mahasiswa): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'mahasiswa_id' => 'required|exists:mahasiswa,id_mahasiswa',
                'matakuliah_id' => 'required|exists:matkuls,id_matkul',
                'nilai_uts' => 'nullable|in:A,B,C,D,E',
                'nilai_uas' => 'nullable|in:A,B,C,D,E',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            Log::info('Update request received:', [
                'mahasiswa_id' => $id_mahasiswa,
                'matakuliah_id' => $request->matakuliah_id,
                'nilai_uts' => $request->nilai_uts,
                'nilai_uas' => $request->nilai_uas,
            ]);

            $updatedGrades = [];

            // Process UTS grade
            if ($request->filled('nilai_uts')) {
                $uts = Nilai::updateOrCreate(
                    [
                        'mahasiswa_id' => $id_mahasiswa,
                        'matakuliah_id' => $request->matakuliah_id,
                        'jenis_nilai' => 'UTS',
                    ],
                    [
                        'nilai' => $request->nilai_uts,
                        'updated_at' => now()
                    ]
                );
                $updatedGrades['uts'] = $uts;
                Log::info('UTS grade updated:', $uts->toArray());
            } else {
                $deleted = Nilai::where('mahasiswa_id', $id_mahasiswa)
                    ->where('matakuliah_id', $request->matakuliah_id)
                    ->where('jenis_nilai', 'UTS')
                    ->delete();
                $updatedGrades['uts_deleted'] = $deleted;
                Log::info('UTS grade deleted:', ['deleted' => $deleted]);
            }

            // Process UAS grade
            if ($request->filled('nilai_uas')) {
                $uas = Nilai::updateOrCreate(
                    [
                        'mahasiswa_id' => $id_mahasiswa,
                        'matakuliah_id' => $request->matakuliah_id,
                        'jenis_nilai' => 'UAS',
                    ],
                    [
                        'nilai' => $request->nilai_uas,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
                $updatedGrades['uas'] = $uas;
                Log::info('UAS grade updated:', $uas->toArray());
            } else {
                $deleted = Nilai::where('mahasiswa_id', $id_mahasiswa)
                    ->where('matakuliah_id', $request->matakuliah_id)
                    ->where('jenis_nilai', 'UAS')
                    ->delete();
                $updatedGrades['uas_deleted'] = $deleted;
                Log::info('UAS grade deleted:', ['deleted' => $deleted]);
            }
            
            Log::info('Nilai updated via API:', [
                'mahasiswa_id' => $id_mahasiswa,
                'matakuliah_id' => $request->matakuliah_id,
                'nilai_uts' => $request->nilai_uts,
                'nilai_uas' => $request->nilai_uas,
                'time' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nilai berhasil diperbarui',
                'data' => $updatedGrades
            ]);

        } catch (\Exception $e) {
            Log::error('Error in NilaiApiController@update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    /**
     * Delete nilai
     */
    public function destroy($id): JsonResponse
    {
        try {
            $nilai = Nilai::find($id);
            
            if (!$nilai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nilai tidak ditemukan'
                ], 404);
            }

            $nilai->delete();

            return response()->json([
                'success' => true,
                'message' => 'Nilai berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in NilaiApiController@destroy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    /**
     * Get nilai mahasiswa (untuk mahasiswa yang login)
     */
    public function getNilaiMahasiswa(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $mahasiswa = $user->mahasiswa;
            
            if (!$mahasiswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya mahasiswa yang dapat mengakses endpoint ini'
                ], 403);
            }
            
            $search = $request->input('search');
            $nilaiList = collect();
            
            // Get all FRS data for the student with proper relations
            $frsList = \App\Models\Frs::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                ->with(['detailFrs.jadwal.matkul'])
                ->get();

            // Get all nilai data for the student
            $nilaiData = Nilai::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                ->whereIn('jenis_nilai', ['UTS', 'UAS'])
                ->get()
                ->groupBy(['matakuliah_id', 'jenis_nilai']);

            // Process all FRS records
            foreach ($frsList as $frs) {
                foreach ($frs->detailFrs as $detail) {
                    if ($detail->jadwal && $detail->jadwal->matkul) {
                        // Skip if search term doesn't match
                        $matkulNama = strtolower($detail->jadwal->matkul->nama_matkul ?? '');
                        $searchTerm = strtolower($search);
                        
                        if ($search && !str_contains($matkulNama, $searchTerm)) {
                            continue;
                        }

                        // Get nilai for this matakuliah
                        $matkulId = $detail->jadwal->matkul->id_matkul;
                        $nilaiUTS = $nilaiData->get($matkulId, collect())->get('UTS', collect())->first();
                        $nilaiUAS = $nilaiData->get($matkulId, collect())->get('UAS', collect())->first();

                        $nilaiList->push([
                            'matkul' => $detail->jadwal->matkul,
                            'sks' => $detail->jadwal->matkul->sks ?? 0,
                            'nilai_uts' => $nilaiUTS->nilai ?? null,
                            'nilai_uas' => $nilaiUAS->nilai ?? null,
                            'is_wajib' => ($detail->jadwal->matkul->jenis ?? '') === 'Wajib',
                            'tahun_ajaran' => $frs->tahun_ajaran
                        ]);
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'mahasiswa' => $mahasiswa,
                    'nilai_list' => $nilaiList,
                    'search' => $search,
                    'count' => $nilaiList->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in NilaiApiController@getNilaiMahasiswa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    /**
     * Get statistics nilai
     */
    public function getStatistics(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if ($user->dosen) {
                // Statistics for dosen
                $dosen = $user->dosen;
                $matkuls = Matkul::whereHas('jadwal', function($q) use ($dosen) {
                    $q->where('id_dosen', $dosen->id_dosen)
                      ->orWhere('id_dosen_2', $dosen->id_dosen);
                })->count();

                $totalMahasiswa = Mahasiswa::whereHas('frs.detailFrs.jadwal', function($q) use ($dosen) {
                    $q->where('id_dosen', $dosen->id_dosen)
                      ->orWhere('id_dosen_2', $dosen->id_dosen);
                })->distinct('id_mahasiswa')->count();

                $nilaiCount = Nilai::whereHas('matkul.jadwal', function($q) use ($dosen) {
                    $q->where('id_dosen', $dosen->id_dosen)
                      ->orWhere('id_dosen_2', $dosen->id_dosen);
                })->count();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'total_matkul' => $matkuls,
                        'total_mahasiswa' => $totalMahasiswa,
                        'total_nilai' => $nilaiCount
                    ]
                ]);

            } elseif ($user->mahasiswa) {
                // Statistics for mahasiswa
                $mahasiswa = $user->mahasiswa;
                $totalMatkul = \App\Models\Frs::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                    ->with('detailFrs')
                    ->get()
                    ->sum(function($frs) {
                        return $frs->detailFrs->count();
                    });

                $nilaiCount = Nilai::where('mahasiswa_id', $mahasiswa->id_mahasiswa)->count();
                
                $gradeDistribution = Nilai::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                    ->whereIn('jenis_nilai', ['UTS', 'UAS'])
                    ->selectRaw('nilai, COUNT(*) as count')
                    ->groupBy('nilai')
                    ->pluck('count', 'nilai')
                    ->toArray();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'total_matkul' => $totalMatkul,
                        'total_nilai' => $nilaiCount,
                        'grade_distribution' => $gradeDistribution
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'User tidak memiliki role yang valid'
            ], 403);

        } catch (\Exception $e) {
            Log::error('Error in NilaiApiController@getStatistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }
}