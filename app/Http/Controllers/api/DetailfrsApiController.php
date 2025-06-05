<?php

namespace App\Http\Controllers\Api; // Changed namespace for API

use App\Http\Controllers\Controller;
use App\Models\DetailFrs;
use App\Models\Frs;
use App\Models\Jadwal;
use App\Models\Mahasiswa; // Assuming Mahasiswa model exists
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException; // Import ModelNotFoundException

class DetailFrsApiController extends Controller
{
    /**
     * Get the authenticated student's FRS details.
     * This includes their selected courses and other available courses.
     */
    public function getFrsDetails(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->role !== 'mahasiswa' || !$user->mahasiswa) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak. Pengguna bukan mahasiswa atau data mahasiswa tidak ditemukan.'], 403);
            }

            // Dapatkan FRS terbaru mahasiswa yang terautentikasi.
            // Method ini sekarang akan throw ModelNotFoundException jika FRS tidak ditemukan.
            $frs = $this->getAuthenticatedMahasiswaFrs($user); 
            // Jika baris di atas berhasil, $frs adalah instance Frs yang valid.
            Log::info('[API GetFRSDetails] FRS ID terbaru mahasiswa ditemukan: ' . $frs->id_frs . ' (Tahun Ajaran: ' . $frs->tahun_ajaran . ', Semester: ' . $frs->semester . ') untuk mahasiswa ID: ' . optional($frs->mahasiswa)->id_mahasiswa);

            // Ambil detail FRS beserta relasi yang dibutuhkan.
            $frsDetails = Frs::with([
                'mahasiswa',
                'detailFrs.jadwal.matkul',
                'detailFrs.jadwal.dosen',
                'detailFrs.jadwal.waktu',
                'detailFrs.jadwal.ruangan'
            ])->findOrFail($frs->id_frs); 
            
            Log::info('[API GetFRSDetails] Jumlah DetailFrs (dari relasi $frsDetails->detailFrs) yang ter-load untuk FRS ID ' . $frsDetails->id_frs . ': ' . $frsDetails->detailFrs->count());

            $id_frs_saat_ini = $frsDetails->id_frs; 
            Log::info('[API GetFRSDetails] FRS ID saat ini untuk filter jadwal: ' . $id_frs_saat_ini);

            $jadwalDiFrsIds = DetailFrs::where('id_frs', $id_frs_saat_ini)
                                     ->pluck('id_jadwal')
                                     ->filter() 
                                     ->all();   

            Log::info('[API GetFRSDetails] ID Jadwal yang SUDAH ADA (langsung query tabel detail_frs) untuk id_frs ' . $id_frs_saat_ini . ': ', $jadwalDiFrsIds);

            if (empty($jadwalDiFrsIds)) {
                Log::info('[API GetFRSDetails] Tidak ada jadwal yang terdeteksi sudah dipilih di FRS ini (id_frs: '.$id_frs_saat_ini.'), mengambil semua jadwal.');
                $jadwals = Jadwal::with('matkul', 'dosen', 'waktu', 'ruangan')
                                ->get();
            } else {
                Log::info('[API GetFRSDetails] Memfilter jadwal yang ID-nya TIDAK ADA di dalam array berikut: ', $jadwalDiFrsIds);
                $jadwals = Jadwal::whereNotIn('id_jadwal', $jadwalDiFrsIds)
                    ->with('matkul', 'dosen', 'waktu', 'ruangan')
                    ->get();
            }
            
            Log::info('[API GetFRSDetails] Jumlah Jadwal (tersedia) setelah filter: ' . $jadwals->count());

            return response()->json([
                'success' => true,
                'data' => [
                    'frs' => $frsDetails, 
                    'jadwal_tersedia' => $jadwals 
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            if ($e->getModel() === Frs::class) { 
                 Log::warning('[API GetFRSDetails] Tidak ada FRS yang ditemukan untuk mahasiswa ini.');
                 return response()->json(['success' => false, 'message' => 'Tidak ada FRS yang ditemukan untuk mahasiswa ini. Silakan hubungi bagian akademik jika seharusnya sudah ada.'], 404);
            }
            Log::error('[API GetFRSDetails] Data terkait tidak ditemukan.', ['user_id' => optional(Auth::user())->id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Data terkait FRS tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error('[API GetFRSDetails] Error fetching FRS details for student', [
                'user_id' => optional(Auth::user())->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil detail FRS: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Helper method to get the latest FRS for the authenticated student.
     * Orders by tahun_ajaran descending, then semester descending.
     * Does NOT create a new FRS if not found.
     * Throws ModelNotFoundException if no FRS is found for the student.
     */
    private function getAuthenticatedMahasiswaFrs($user)
    {
        $mahasiswa = $user->mahasiswa()->first();
        if (!$mahasiswa) {
            throw new \Exception('Data mahasiswa tidak ditemukan untuk pengguna yang diautentikasi.');
        }
        
        Log::info('[GetAuthenticatedMahasiswaFrs] Mencari FRS terbaru untuk mahasiswa_id: ' . $mahasiswa->id_mahasiswa);

        // Cari FRS berdasarkan id_mahasiswa, urutkan berdasarkan tahun_ajaran terbaru, lalu semester terbaru
        $frs = Frs::where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                    ->orderBy('tahun_ajaran', 'desc')
                    ->orderBy('semester', 'desc') // Asumsi format semester YYYYS (misal 20231, 20232) agar urutan benar
                    ->first(); 

        if (!$frs) {
            Log::warning('[GetAuthenticatedMahasiswaFrs] Tidak ada FRS yang ditemukan untuk mahasiswa_id: ' . $mahasiswa->id_mahasiswa);
            // Lempar ModelNotFoundException yang akan ditangkap oleh method pemanggil
            throw (new ModelNotFoundException)->setModel(Frs::class);
        }
        
        Log::info('[GetAuthenticatedMahasiswaFrs] FRS terbaru yang ditemukan memiliki id_frs: ' . $frs->id_frs . ', Tahun Ajaran: ' . $frs->tahun_ajaran . ', Semester: ' . $frs->semester);
        return $frs;
    }

    /**
     * Add selected courses (jadwal) to the student's FRS.
     * Operates on the student's latest FRS.
     */
    public function addCourses(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'mahasiswa' || !$user->mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        try {
            $validatedData = $request->validate([
                'jadwal_ids' => 'required|array|min:1',
                'jadwal_ids.*' => 'required|exists:jadwal,id_jadwal'
            ]);

            // Dapatkan FRS terbaru mahasiswa.
            // Akan throw ModelNotFoundException jika tidak ada.
            $frs = $this->getAuthenticatedMahasiswaFrs($user); 
            $id_frs = $frs->id_frs;
            Log::info('[API AddCourses] Akan menambahkan jadwal ke FRS terbaru mahasiswa, id_frs: ' . $id_frs . ' (Tahun Ajaran: ' . $frs->tahun_ajaran . ', Semester: ' . $frs->semester . ')');

            $jadwal_ids_to_add = $validatedData['jadwal_ids'];
            Log::info('[API AddCourses] Jadwal ID yang akan ditambahkan (dari request): ', $jadwal_ids_to_add);

            $existingJadwalIds = DetailFrs::where('id_frs', $id_frs)
                ->whereIn('id_jadwal', $jadwal_ids_to_add)
                ->pluck('id_jadwal')
                ->toArray();
            Log::info('[API AddCourses] Jadwal ID yang sudah ada sebelumnya untuk id_frs ' . $id_frs . ': ', $existingJadwalIds);

            $newJadwalIds = array_diff($jadwal_ids_to_add, $existingJadwalIds);
            Log::info('[API AddCourses] Jadwal ID yang benar-benar baru untuk ditambahkan: ', $newJadwalIds);

            if (empty($newJadwalIds)) {
                Log::info('[API AddCourses] Tidak ada jadwal baru untuk ditambahkan.');
                return response()->json([
                    'success' => false,
                    'message' => 'Semua jadwal yang dipilih sudah ada di FRS Anda.',
                    'type' => 'warning'
                ], 409);
            }

            $dataInsert = [];
            foreach ($newJadwalIds as $id_jadwal) {
                $dataInsert[] = [
                    'id_frs' => $id_frs,
                    'id_jadwal' => $id_jadwal,
                    'status' => false,
                    // 'created_at' dan 'updated_at' DIHAPUS karena tidak ada di tabel detail_frs
                ];
            }
            Log::info('[API AddCourses] Data yang akan di-insert ke detail_frs: ', $dataInsert);

            DetailFrs::insert($dataInsert);
            Log::info('[API AddCourses] DetailFrs::insert telah dipanggil.');

            $insertedDetailsCount = DetailFrs::where('id_frs', $id_frs)->whereIn('id_jadwal', $newJadwalIds)->count();
            Log::info('[API AddCourses] Verifikasi: Jumlah detail_frs yang baru saja di-insert untuk id_frs ' . $id_frs . ' dan id_jadwal terkait: ' . $insertedDetailsCount . ' (seharusnya sama dengan count newJadwalIds)');

            $this->updateTotalSks($id_frs);

            $message = count($newJadwalIds) . ' jadwal berhasil ditambahkan ke FRS.';
            if (count($existingJadwalIds) > 0) {
                $message .= ' ' . count($existingJadwalIds) . ' jadwal lainnya sudah ada sebelumnya dan tidak ditambahkan lagi.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'type' => 'success'
            ]);

        } catch (ModelNotFoundException $e) {
             if ($e->getModel() === Frs::class) {
                 Log::warning('[API AddCourses] Tidak ada FRS yang ditemukan untuk mahasiswa ini. Tidak dapat menambahkan jadwal.');
                 return response()->json(['success' => false, 'message' => 'Tidak ada FRS yang ditemukan untuk mahasiswa ini. Tidak dapat menambahkan jadwal.'], 404);
            }
            Log::error('[API AddCourses] Model lain tidak ditemukan saat mencoba menambah jadwal.', ['user_id' => optional(Auth::user())->id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Data terkait FRS tidak ditemukan.'], 404);
        } catch (ValidationException $e) {
            Log::warning('[API AddCourses] Validation error', [
                'user_id' => optional(Auth::user())->id,
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Data input tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('[API AddCourses] Error adding courses', [
                'user_id' => optional(Auth::user())->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan jadwal ke FRS: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove a course (detail_frs entry) from the student's FRS.
     * Allows deletion from any FRS owned by the student, regardless of FRS status.
     */
    public function removeCourse($id_detail_frs)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'mahasiswa' || !$user->mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak. Pengguna bukan mahasiswa.'], 403);
        }

        try {
            // Ambil entri DetailFrs beserta relasi FRS-nya
            $detailFrsEntry = DetailFrs::with('frs')->findOrFail($id_detail_frs); 
            $frsOfDetailEntry = $detailFrsEntry->frs; 

            // Jika karena suatu hal FRS terkait tidak ter-load (seharusnya tidak terjadi dengan with('frs'))
            if (!$frsOfDetailEntry) {
                Log::error('[API RemoveCourse] FRS terkait dengan DetailFRS tidak ditemukan secara internal.', [
                    'id_detail_frs' => $id_detail_frs,
                    'id_frs_from_detail' => $detailFrsEntry->id_frs
                ]);
                return response()->json(['success' => false, 'message' => 'FRS terkait jadwal ini tidak ditemukan.'], 404);
            }

            // Pastikan FRS tempat detail ini berada adalah milik mahasiswa yang login
            if ($frsOfDetailEntry->id_mahasiswa !== $user->mahasiswa->id_mahasiswa) {
                 Log::warning('[API RemoveCourse] Mahasiswa mencoba menghapus jadwal dari FRS yang bukan miliknya.', [
                    'user_id' => $user->id, 
                    'id_detail_frs_to_delete' => $id_detail_frs,
                    'owner_mahasiswa_id_of_frs' => $frsOfDetailEntry->id_mahasiswa,
                    'logged_in_mahasiswa_id' => $user->mahasiswa->id_mahasiswa
                ]);
                return response()->json(['success' => false, 'message' => 'Akses ditolak. Anda tidak dapat menghapus jadwal dari FRS ini.'], 403);
            }

            // Validasi status FRS DIHAPUS sesuai permintaan pengguna
            // if ($frsOfDetailEntry->status !== 'draft') { 
            //      Log::warning('[API RemoveCourse] Attempt to delete from non-draft FRS (LOG INI SEHARUSNYA TIDAK MUNCUL JIKA VALIDASI STATUS DIHAPUS)', [
            //         'user_id' => $user->id, 
            //         'id_detail_frs' => $id_detail_frs, 
            //         'frs_id' => $frsOfDetailEntry->id_frs,
            //         'frs_status' => $frsOfDetailEntry->status
            //     ]);
            //     return response()->json(['success' => false, 'message' => 'FRS sudah tidak dalam status draft, jadwal tidak dapat dihapus.'], 403);
            // }

            $detailFrsEntry->delete();
            Log::info('[API RemoveCourse] DetailFRS berhasil dihapus.', ['id_detail_frs' => $id_detail_frs, 'id_frs' => $frsOfDetailEntry->id_frs]);
            
            $this->updateTotalSks($frsOfDetailEntry->id_frs);

            return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus dari FRS.']);

        } catch (ModelNotFoundException $e) {
            // Jika DetailFrs::findOrFail($id_detail_frs) gagal
            if ($e->getModel() === DetailFrs::class) {
                Log::info('[API RemoveCourse] DetailFrs entry not found for deletion.', ['id_detail_frs' => $id_detail_frs, 'user_id' => optional(Auth::user())->id]);
                return response()->json(['success' => false, 'message' => 'Jadwal yang akan dihapus tidak ditemukan.'], 404);
            }
            Log::info('[API RemoveCourse] Data terkait tidak ditemukan (kemungkinan FRS dari DetailFRS, meski sudah di-handle)', ['id_detail_frs' => $id_detail_frs, 'user_id' => optional(Auth::user())->id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Jadwal yang akan dihapus atau FRS terkait tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error('[API RemoveCourse] Error removing course', [
                'user_id' => optional(Auth::user())->id, 'id_detail_frs' => $id_detail_frs, 'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Gagal menghapus jadwal dari FRS: ' . $e->getMessage()], 500);
        }
    }
    
    private function updateTotalSks($id_frs)
    {
        try {
            $frs = Frs::find($id_frs);
            if (!$frs) {
                Log::warning('[UpdateTotalSks] FRS not found', ['id_frs' => $id_frs]);
                return;
            }

            $total_sks_pending = DetailFrs::where('detail_frs.id_frs', $id_frs)
                ->join('jadwal', 'detail_frs.id_jadwal', '=', 'jadwal.id_jadwal')
                ->join('matkuls', 'jadwal.id_matkul', '=', 'matkuls.id_matkul')
                ->sum('matkuls.sks');
            
            $frs->total_sks = $total_sks_pending;
            $frs->save();

            Log::info('[UpdateTotalSks] Total SKS updated for FRS', ['id_frs' => $id_frs, 'new_total_sks' => $frs->total_sks]);

        } catch (\Exception $e) {
            Log::error('[UpdateTotalSks] Failed to update total SKS', [
                'id_frs' => $id_frs, 'error' => $e->getMessage()
            ]);
        }
    }
}
