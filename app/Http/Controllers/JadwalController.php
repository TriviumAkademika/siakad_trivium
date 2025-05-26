<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Matkul;
use App\Models\Dosen;
use App\Models\Waktu;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::with(['kelas', 'matkul', 'dosen', 'dosen2', 'waktu', 'ruangan']);

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('matkul', function ($q) use ($searchTerm) {
                    $q->where('nama_matkul', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('jenis', 'LIKE', "%{$searchTerm}%");
                })
                ->orWhereHas('dosen', function ($q) use ($searchTerm) {
                    $q->where('nama_dosen', 'LIKE', "%{$searchTerm}%");
                })
                ->orWhereHas('dosen2', function ($q) use ($searchTerm) {
                    $q->where('nama_dosen', 'LIKE', "%{$searchTerm}%");
                })
                ->orWhereHas('kelas', function ($q) use ($searchTerm) {
                    $q->where('prodi', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('paralel', 'LIKE', "%{$searchTerm}%");
                })
                ->orWhereHas('ruangan', function ($q) use ($searchTerm) {
                    $q->where('kode_ruangan', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('nama_ruangan', 'LIKE', "%{$searchTerm}%");
                });
            });
        }

        // Filter by day
        if ($request->filled('hari') && is_array($request->hari)) {
            $query->whereHas('waktu', function ($q) use ($request) {
                $q->whereIn('hari', $request->hari);
            });
        }

        // Filter by prodi
        if ($request->filled('prodi')) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('prodi', $request->prodi);
            });
        }

        // Get paginated results
        $jadwal = $query->paginate(10)->withQueryString();

        // Get unique prodi for filter dropdown
        $prodiList = Kelas::distinct()->pluck('prodi')->sort()->values();

        return view('jadwal.index', compact('jadwal', 'prodiList'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $matkul = Matkul::all();
        $dosen = Dosen::all();
        $waktu = Waktu::all();
        $ruangan = Ruangan::all();

        return view('jadwal.create', compact('kelas', 'matkul', 'dosen', 'waktu', 'ruangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_matkul' => 'required|exists:matkuls,id_matkul',
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'id_dosen_2' => 'nullable|exists:dosen,id_dosen|different:id_dosen',
            'id_waktu' => 'required|exists:waktus,id_waktu',
            'id_ruangan' => 'required|exists:ruangans,id_ruangan',
        ]);

        // Custom validation: Check if ruangan + waktu combination already exists
        $existingRuanganWaktu = Jadwal::where('id_ruangan', $request->id_ruangan)
            ->where('id_waktu', $request->id_waktu)
            ->exists();

        if ($existingRuanganWaktu) {
            return back()->withErrors([
                'id_ruangan' => 'Kombinasi ruangan dan waktu ini sudah digunakan.',
                'id_waktu' => 'Kombinasi ruangan dan waktu ini sudah digunakan.'
            ])->withInput();
        }

        // Custom validation: Check if dosen is already scheduled at the same time
        $dosenConflict = Jadwal::where('id_waktu', $request->id_waktu)
            ->where(function ($query) use ($request) {
                $query->where('id_dosen', $request->id_dosen)
                      ->orWhere('id_dosen_2', $request->id_dosen);
                
                if ($request->filled('id_dosen_2')) {
                    $query->orWhere('id_dosen', $request->id_dosen_2)
                          ->orWhere('id_dosen_2', $request->id_dosen_2);
                }
            })
            ->exists();

        if ($dosenConflict) {
            return back()->withErrors([
                'id_dosen' => 'Dosen sudah memiliki jadwal pada waktu ini.',
                'id_dosen_2' => 'Dosen pendamping sudah memiliki jadwal pada waktu ini.'
            ])->withInput();
        }

        // Custom validation: Check if kelas + matkul combination already exists
        $existingKelasMatkulCombo = Jadwal::where('id_kelas', $request->id_kelas)
            ->where('id_matkul', $request->id_matkul)
            ->exists();

        if ($existingKelasMatkulCombo) {
            return back()->withErrors([
                'id_kelas' => 'Kelas ini sudah memiliki mata kuliah yang sama.',
                'id_matkul' => 'Mata kuliah ini sudah dijadwalkan untuk kelas yang sama.'
            ])->withInput();
        }

        // Custom validation: Check if kelas has conflict at the same time
        $kelasTimeConflict = Jadwal::where('id_kelas', $request->id_kelas)
            ->where('id_waktu', $request->id_waktu)
            ->exists();

        if ($kelasTimeConflict) {
            return back()->withErrors([
                'id_kelas' => 'Kelas ini sudah memiliki jadwal pada waktu yang sama.',
                'id_waktu' => 'Waktu ini sudah digunakan oleh kelas ini.'
            ])->withInput();
        }

        Jadwal::create($request->all());

        return redirect()->route('jadwal.index')->with([
            'message' => 'Jadwal berhasil ditambahkan!',
            'type' => 'success'
        ]);
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $kelas = Kelas::all();
        $matkul = Matkul::all();
        $dosen = Dosen::all();
        $waktu = Waktu::all();
        $ruangan = Ruangan::all();

        return view('jadwal.edit', compact('jadwal', 'kelas', 'matkul', 'dosen', 'waktu', 'ruangan'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_matkul' => 'required|exists:matkuls,id_matkul',
            'id_dosen' => 'required|exists:dosen,id_dosen',
            'id_dosen_2' => 'nullable|exists:dosen,id_dosen|different:id_dosen',
            'id_waktu' => 'required|exists:waktus,id_waktu',
            'id_ruangan' => 'required|exists:ruangans,id_ruangan',
        ]);

        // Custom validation: Check if ruangan + waktu combination already exists (excluding current record)
        $existingRuanganWaktu = Jadwal::where('id_ruangan', $request->id_ruangan)
            ->where('id_waktu', $request->id_waktu)
            ->where('id_jadwal', '!=', $id)
            ->exists();

        if ($existingRuanganWaktu) {
            return back()->withErrors([
                'id_ruangan' => 'Kombinasi ruangan dan waktu ini sudah digunakan.',
                'id_waktu' => 'Kombinasi ruangan dan waktu ini sudah digunakan.'
            ])->withInput();
        }

        // Custom validation: Check if dosen is already scheduled at the same time (excluding current record)
        $dosenConflict = Jadwal::where('id_waktu', $request->id_waktu)
            ->where('id_jadwal', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->where('id_dosen', $request->id_dosen)
                      ->orWhere('id_dosen_2', $request->id_dosen);
                
                if ($request->filled('id_dosen_2')) {
                    $query->orWhere('id_dosen', $request->id_dosen_2)
                          ->orWhere('id_dosen_2', $request->id_dosen_2);
                }
            })
            ->exists();

        if ($dosenConflict) {
            return back()->withErrors([
                'id_dosen' => 'Dosen sudah memiliki jadwal pada waktu ini.',
                'id_dosen_2' => 'Dosen pendamping sudah memiliki jadwal pada waktu ini.'
            ])->withInput();
        }

        // Custom validation: Check if kelas + matkul combination already exists (excluding current record)
        $existingKelasMatkulCombo = Jadwal::where('id_kelas', $request->id_kelas)
            ->where('id_matkul', $request->id_matkul)
            ->where('id_jadwal', '!=', $id)
            ->exists();

        if ($existingKelasMatkulCombo) {
            return back()->withErrors([
                'id_kelas' => 'Kelas ini sudah memiliki mata kuliah yang sama.',
                'id_matkul' => 'Mata kuliah ini sudah dijadwalkan untuk kelas yang sama.'
            ])->withInput();
        }

        // Custom validation: Check if kelas has conflict at the same time (excluding current record)
        $kelasTimeConflict = Jadwal::where('id_kelas', $request->id_kelas)
            ->where('id_waktu', $request->id_waktu)
            ->where('id_jadwal', '!=', $id)
            ->exists();

        if ($kelasTimeConflict) {
            return back()->withErrors([
                'id_kelas' => 'Kelas ini sudah memiliki jadwal pada waktu yang sama.',
                'id_waktu' => 'Waktu ini sudah digunakan oleh kelas ini.'
            ])->withInput();
        }

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')->with([
            'message' => 'Jadwal berhasil diperbarui!',
            'type' => 'success'
        ]);
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with([
            'message' => 'Jadwal berhasil dihapus!',
            'type' => 'warning'
        ]);
    }
}