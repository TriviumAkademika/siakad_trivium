<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $totalkelas = Kelas::count();

        $query = Kelas::with('dosen');

        // Handle search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('tahun_masuk', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('prodi', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('paralel', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('dosen', function ($dosenQuery) use ($searchTerm) {
                        $dosenQuery->where('nama_dosen', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        // Handle status filter
        if ($request->filled('status')) {
            $statusFilters = is_array($request->status) ? $request->status : [$request->status];
            $query->whereIn('status', $statusFilters);
        }

        // Order by id kelas
        $query->orderBy('id_kelas', 'asc');

        // Paginate with 10 items per page and preserve query parameters
        $kelas = $query->paginate(10)->withQueryString();

        return view('kelas.index', compact('kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dosen = Dosen::where('status', 'AKTIF')
            ->orderBy('nama_dosen', 'asc')
            ->get();
        return view('kelas.create', compact('dosen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_dosen' => [
                    'required',
                    'exists:dosen,id_dosen'
                ],
                'tahun_masuk' => [
                    'required',
                    'digits:4',                        // ✅ Memastikan tepat 4 digit
                    'integer',                         // ✅ Memastikan berupa integer
                    'between:2000,' . (date('Y') + 1)  // ✅ Validasi range yang tepat
                ],
                'prodi' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z0-9\s\-]+$/'
                ],
                'paralel' => [
                    'required',
                    'string',
                    'size:1',
                    'regex:/^[A-Z]$/'
                ],
                'status' => [
                    'required',
                    'in:AKTIF,LULUS'
                ],
            ], [
                // Custom error messages
                'id_dosen.required' => 'Wali kelas wajib dipilih.',
                'id_dosen.exists' => 'Dosen yang dipilih tidak valid.',

                'tahun_masuk.required' => 'Tahun masuk wajib diisi.',
                'tahun_masuk.string' => 'Tahun masuk harus berupa teks.',
                'tahun_masuk.size' => 'Tahun masuk harus tepat 4 digit.',
                'tahun_masuk.regex' => 'Tahun masuk hanya boleh berisi angka.',
                'tahun_masuk.min' => 'Tahun masuk tidak boleh kurang dari 2000.',
                'tahun_masuk.max' => 'Tahun masuk tidak boleh lebih dari ' . (date('Y') + 1) . '.',

                'prodi.required' => 'Program studi wajib diisi.',
                'prodi.string' => 'Program studi harus berupa teks.',
                'prodi.max' => 'Program studi tidak boleh lebih dari 255 karakter.',
                'prodi.regex' => 'Program studi hanya boleh berisi huruf, angka, spasi, dan tanda minus.',

                'paralel.required' => 'Paralel wajib diisi.',
                'paralel.string' => 'Paralel harus berupa teks.',
                'paralel.size' => 'Paralel harus tepat 1 karakter.',
                'paralel.regex' => 'Paralel harus berupa huruf kapital (A-Z).',

                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status harus Aktif atau Lulus.',
            ]);

            // Check for duplicate class (same prodi, tahun_masuk, and paralel)
            $existingKelas = Kelas::where('prodi', $validatedData['prodi'])
                ->where('tahun_masuk', $validatedData['tahun_masuk'])
                ->where('paralel', $validatedData['paralel'])
                ->first();

            if ($existingKelas) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kelas dengan program studi, tahun masuk, dan paralel yang sama sudah ada.');
            }

            Kelas::create($validatedData);

            return redirect()->route('kelas.index')
                ->with('success', 'Data kelas berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Gagal menambahkan kelas. Periksa kembali data yang diisi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Ambil data kelas dengan relasi dosen
            $kelas = Kelas::with('dosen')->where('id_kelas', $id)->firstOrFail();

            // Ambil semua mahasiswa dalam kelas tersebut
            $mahasiswa = Mahasiswa::where('id_kelas', $id)->get();

            // Hitung statistik
            $totalMahasiswa = $mahasiswa->count();
            $mahasiswaLaki = $mahasiswa->where('gender', 'L')->count();
            $mahasiswaPerempuan = $mahasiswa->where('gender', 'P')->count();

            return view('kelas.show', compact('kelas', 'mahasiswa', 'totalMahasiswa', 'mahasiswaLaki', 'mahasiswaPerempuan'));
        } catch (\Exception $e) {
            return redirect()->route('kelas.index')->with('error', 'Data kelas tidak ditemukan!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);

            // Pastikan data dosen diambil dengan benar
            $dosen = Dosen::where('status', 'AKTIF')
                ->orderBy('nama_dosen', 'asc')
                ->get();

            return view('kelas.edit', compact('kelas', 'dosen'));
        } catch (\Exception $e) {
            return redirect()->route('kelas.index')
                ->with('error', 'Data kelas tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $kelas = Kelas::findOrFail($id);

            $validatedData = $request->validate([
                'id_dosen' => [
                    'required',
                    'exists:dosen,id_dosen'
                ],
                'tahun_masuk' => [
                    'required',
                    'digits:4',                        // ✅ Memastikan tepat 4 digit
                    'integer',                         // ✅ Memastikan berupa integer
                    'between:2000,' . (date('Y') + 1)  // ✅ Validasi range yang tepat
                ],
                'prodi' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z0-9\s\-]+$/'
                ],
                'paralel' => [
                    'required',
                    'string',
                    'size:1',
                    'regex:/^[A-Z]$/'
                ],
                'status' => [
                    'required',
                    'in:AKTIF,LULUS'
                ],
            ], [
                // Custom error messages (same as store method)
                'id_dosen.required' => 'Wali kelas wajib dipilih.',
                'id_dosen.exists' => 'Dosen yang dipilih tidak valid.',

                'tahun_masuk.required' => 'Tahun masuk wajib diisi.',
                'tahun_masuk.string' => 'Tahun masuk harus berupa teks.',
                'tahun_masuk.size' => 'Tahun masuk harus tepat 4 digit.',
                'tahun_masuk.regex' => 'Tahun masuk hanya boleh berisi angka.',
                'tahun_masuk.min' => 'Tahun masuk tidak boleh kurang dari 2000.',
                'tahun_masuk.max' => 'Tahun masuk tidak boleh lebih dari ' . (date('Y') + 1) . '.',

                'prodi.required' => 'Program studi wajib diisi.',
                'prodi.string' => 'Program studi harus berupa teks.',
                'prodi.max' => 'Program studi tidak boleh lebih dari 255 karakter.',
                'prodi.regex' => 'Program studi hanya boleh berisi huruf, angka, spasi, dan tanda minus.',

                'paralel.required' => 'Paralel wajib diisi.',
                'paralel.string' => 'Paralel harus berupa teks.',
                'paralel.size' => 'Paralel harus tepat 1 karakter.',
                'paralel.regex' => 'Paralel harus berupa huruf kapital (A-Z).',

                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status harus Aktif atau Lulus.',
            ]);

            // Check for duplicate class (excluding current record)
            $existingKelas = Kelas::where('prodi', $validatedData['prodi'])
                ->where('tahun_masuk', $validatedData['tahun_masuk'])
                ->where('paralel', $validatedData['paralel'])
                ->where('id_kelas', '!=', $id)
                ->first();

            if ($existingKelas) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kelas dengan program studi, tahun masuk, dan paralel yang sama sudah ada.');
            }

            $kelas->update($validatedData);

            return redirect()->route('kelas.index')
                ->with('success', 'Data kelas berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Gagal memperbarui kelas. Periksa kembali data yang diisi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);

            // Check if there are students in this class
            $mahasiswaCount = Mahasiswa::where('id_kelas', $id)->count();

            if ($mahasiswaCount > 0) {
                return redirect()->route('kelas.index')
                    ->with('error', "Tidak dapat menghapus kelas karena masih ada {$mahasiswaCount} mahasiswa terdaftar di kelas ini.");
            }

            $kelasInfo = $kelas->prodi . ' ' . $kelas->tahun_masuk . ' ' . $kelas->paralel;
            $kelas->delete();

            return redirect()->route('kelas.index')
                ->with('success', "Data kelas {$kelasInfo} berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->route('kelas.index')
                ->with('error', 'Gagal menghapus data kelas: ' . $e->getMessage());
        }
    }
}
