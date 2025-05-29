<?php

namespace App\Http\Controllers;

use App\Models\DetailFrs;
use App\Models\Frs;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DetailFrsController extends Controller
{
    // Method existing untuk admin/dosen/mahasiswa melihat detail FRS berdasarkan ID
    public function index($id_frs)
    {
        // Jika mahasiswa dan id_frs adalah 0, cari atau buat FRS untuk mahasiswa
        if (Auth::user()->role === 'mahasiswa' && $id_frs == 0) {
            $frs = $this->getOrCreateMahasiswaFrs();
            return redirect()->route('detail-frs.index', $frs->id_frs);
        }

        $frs = Frs::with(
            'mahasiswa',
            'detailFrs.jadwal.matkul',
            'detailFrs.jadwal.dosen',
            'detailFrs.jadwal.waktu',
            'detailFrs.jadwal.ruangan'
        )->findOrFail($id_frs);

        // Jika mahasiswa, pastikan FRS adalah miliknya
        if (Auth::user()->role === 'mahasiswa') {
            if ($frs->id_mahasiswa !== Auth::user()->mahasiswa->id_mahasiswa) {
                abort(403, 'Akses ditolak');
            }
        }

        // Ambil ID jadwal yang sudah dipilih di FRS ini
        $jadwalTerpilih = $frs->detailFrs->pluck('id_jadwal')->toArray();

        // Filter jadwal yang belum dipilih
        $jadwals = Jadwal::whereNotIn('id_jadwal', $jadwalTerpilih)
            ->with('matkul', 'dosen', 'waktu', 'ruangan')
            ->get();

        return response()->view('detail_frs.index', compact('frs', 'jadwals'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    // Helper method untuk mendapatkan atau membuat FRS mahasiswa
    private function getOrCreateMahasiswaFrs()
    {
        if (Auth::user()->role !== 'mahasiswa' || !Auth::user()->mahasiswa) {
            abort(403, 'Akses ditolak');
        }

        return Frs::firstOrCreate(
            ['id_mahasiswa' => Auth::user()->mahasiswa->id_mahasiswa],
            [
                'semester' => date('Y') . (date('n') <= 6 ? '1' : '2'),
                'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
                'total_sks' => 0,
                'status' => 'draft'
            ]
        );
    }

    // Updated: Parameter $id dihapus, sekarang menggunakan id_frs dari request
    public function store(Request $request)
    {
        $request->validate([
            'id_frs' => 'required|exists:frs,id_frs',
            'jadwal_ids' => 'required|array|min:1',
            'jadwal_ids.*' => 'exists:jadwal,id_jadwal'
        ]);

        $id_frs = $request->input('id_frs');

        // Untuk mahasiswa, pastikan FRS milik mahasiswa yang login
        if (Auth::user()->role === 'mahasiswa') {
            $frs = Frs::where('id_frs', $id_frs)
                ->where('id_mahasiswa', Auth::user()->mahasiswa->id_mahasiswa)
                ->firstOrFail();
        } else {
            // Untuk admin/dosen, validasi FRS exists
            $frs = Frs::findOrFail($id_frs);
        }

        $jadwal_ids = $request->input('jadwal_ids', []);

        // Cek apakah ada jadwal yang sudah dipilih sebelumnya
        $jadwalSudahAda = DetailFrs::where('id_frs', $id_frs)
            ->whereIn('id_jadwal', $jadwal_ids)
            ->pluck('id_jadwal')
            ->toArray();

        if (!empty($jadwalSudahAda)) {
            return redirect()->back()
                ->with('message', 'Beberapa jadwal sudah dipilih sebelumnya.')
                ->with('type', 'warning');
        }

        // Tambahkan jadwal yang belum ada
        $dataInsert = [];
        foreach ($jadwal_ids as $id_jadwal) {
            $dataInsert[] = [
                'id_frs' => $id_frs,
                'id_jadwal' => $id_jadwal,
                'status' => false, // Default status tidak diterima
            ];
        }

        DetailFrs::insert($dataInsert);

        // Hitung ulang total SKS
        $this->updateTotalSks($id_frs);

        return redirect()->route('detail-frs.index', $id_frs)
            ->with('message', 'Jadwal berhasil ditambahkan.')
            ->with('type', 'success');
    }

    // Update status via AJAX - wajib selalu return JSON
    public function updateStatus(Request $request, $id)
    {
        try {
            // Log untuk debugging
            Log::info('UpdateStatus called', [
                'id' => $id,
                'request_data' => $request->all(),
                'user_role' => Auth::user()->role ?? 'unknown'
            ]);

            // Validasi input
            $request->validate([
                'status' => 'required|in:0,1,true,false'
            ]);

            $detail = DetailFrs::findOrFail($id);
            
            // Log detail yang ditemukan
            Log::info('DetailFrs found', [
                'detail_id' => $detail->id_detail_frs,
                'current_status' => $detail->status,
                'frs_id' => $detail->id_frs
            ]);

            // Jika mahasiswa, pastikan FRS adalah miliknya
            if (Auth::user()->role === 'mahasiswa') {
                $frs = Frs::findOrFail($detail->id_frs);
                if ($frs->id_mahasiswa !== Auth::user()->mahasiswa->id_mahasiswa) {
                    Log::warning('Access denied for mahasiswa', [
                        'user_id' => Auth::id(),
                        'frs_mahasiswa_id' => $frs->id_mahasiswa,
                        'user_mahasiswa_id' => Auth::user()->mahasiswa->id_mahasiswa ?? 'null'
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Akses ditolak.'
                    ], 403);
                }
            }

            // Konversi status dengan lebih robust
            $statusInput = $request->input('status');
            $newStatus = false; // default

            if (is_string($statusInput)) {
                $newStatus = in_array($statusInput, ['1', 'true', 'on'], true);
            } elseif (is_bool($statusInput)) {
                $newStatus = $statusInput;
            } elseif (is_numeric($statusInput)) {
                $newStatus = (int)$statusInput === 1;
            }

            Log::info('Status conversion', [
                'input' => $statusInput,
                'converted' => $newStatus,
                'input_type' => gettype($statusInput)
            ]);

            // Update status
            $oldStatus = $detail->status;
            $detail->status = $newStatus;
            
            // Pastikan save berhasil
            $saved = $detail->save();

            Log::info('Save result', [
                'saved' => $saved,
                'old_status' => $oldStatus,
                'new_status' => $detail->status,
                'fresh_from_db' => DetailFrs::find($id)->status ?? 'not_found'
            ]);

            if (!$saved) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan perubahan status ke database.'
                ], 500);
            }

            // Refresh dari database untuk memastikan
            $detail->refresh();
            
            // Update total SKS setelah mengubah status
            $this->updateTotalSks($detail->id_frs);

            Log::info('Status updated successfully', [
                'final_status' => $detail->status,
                'status_text' => $detail->status ? 'Diterima' : 'Tidak Diterima'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui menjadi ' . ($detail->status ? 'Diterima' : 'Tidak Diterima'),
                'status' => (bool)$detail->status,
                'status_text' => $detail->status ? 'Diterima' : 'Tidak Diterima'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in updateStatus', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Data input tidak valid: ' . implode(', ', $e->errors()['status'] ?? ['Unknown validation error'])
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('DetailFrs not found', ['id' => $id]);
            
            return response()->json([
                'success' => false,
                'message' => 'Data FRS tidak ditemukan.'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error updating status', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $id,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $detail = DetailFrs::findOrFail($id);
            $id_frs = $detail->id_frs;

            // Jika mahasiswa, pastikan FRS adalah miliknya
            if (Auth::user()->role === 'mahasiswa') {
                $frs = Frs::findOrFail($id_frs);
                if ($frs->id_mahasiswa !== Auth::user()->mahasiswa->id_mahasiswa) {
                    return redirect()->back()
                        ->with('message', 'Akses ditolak.')
                        ->with('type', 'error');
                }
            }

            $detail->delete();

            // Update total SKS setelah menghapus
            $this->updateTotalSks($id_frs);

            return redirect()->back()
                ->with('message', 'Jadwal berhasil dihapus dari FRS.')
                ->with('type', 'success');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('message', 'Gagal menghapus jadwal.')
                ->with('type', 'error');
        }
    }

    // Endpoint buat set session flash via AJAX supaya komponen toast kamu bisa muncul
    public function setSession(Request $request)
    {
        session()->flash('message', $request->message);
        session()->flash('type', $request->type ?? 'info');

        return response()->json(['success' => true]);
    }

    // Private method untuk update total SKS
    private function updateTotalSks($id_frs)
    {
        $total_sks = DetailFrs::where('id_frs', $id_frs)
            ->where('status', true) // Hanya hitung yang statusnya aktif
            ->join('jadwal', 'detail_frs.id_jadwal', '=', 'jadwal.id_jadwal')
            ->join('matkuls', 'jadwal.id_matkul', '=', 'matkuls.id_matkul')
            ->sum('matkuls.sks');

        Frs::where('id_frs', $id_frs)->update(['total_sks' => $total_sks]);
    }
}