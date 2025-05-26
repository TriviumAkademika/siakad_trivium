<?php

namespace App\Http\Controllers;

use App\Models\Frs;
use App\Models\Mahasiswa;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class FrsController extends Controller
{
    public function index()
    {
        $frs = Frs::with('mahasiswa')->get();
        return view('frs.index', compact('frs'));
    }

    z
    public function store(Request $request)
    {
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
        $frs = Frs::findOrFail($id);
        $mahasiswa = Mahasiswa::all();
        return view('frs.edit', compact('frs', 'mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $frs = Frs::findOrFail($id);

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

        // Update timestamp perubahan
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
        try {
            $frs = Frs::findOrFail($id);

            // Hapus detail FRS terlebih dahulu (jika ada relasi)
            $frs->detailFrs()->delete();

            // Hapus FRS
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
        $frs = Frs::with([
            'mahasiswa',
            'detailFrs.jadwal.matkul',
            'detailFrs.jadwal.dosen',
            'detailFrs.jadwal.ruangan',
            'detailFrs.jadwal.waktu'
        ])->findOrFail($id);

        // Ambil ID jadwal yang sudah dipilih di FRS ini
        $jadwalTerpilih = $frs->detailFrs->pluck('id_jadwal')->toArray();

        // Filter jadwal yang belum dipilih
        $jadwals = Jadwal::whereNotIn('id_jadwal', $jadwalTerpilih)
            ->with(['matkul', 'dosen', 'ruangan', 'waktu'])
            ->get();

        return view('detail_frs.index', compact('frs', 'jadwals'));
    }

    /**
     * Drop FRS (set tgl_drop)
     */
    public function drop($id)
    {
        try {
            $frs = Frs::findOrFail($id);

            $frs->update([
                'tgl_drop' => now(),
                'tgl_perubahan' => now()
            ]);

            // Set semua detail FRS menjadi tidak aktif
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

    /**
     * Reaktivasi FRS yang sudah di-drop
     */
    public function reactivate($id)
    {
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
