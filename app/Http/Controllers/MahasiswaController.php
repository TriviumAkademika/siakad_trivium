<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Mahasiswa::with(['kelas']);

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nrp', 'like', '%' . $searchTerm . '%')
                  ->orWhere('no_hp', 'like', '%' . $searchTerm . '%')
                  ->orWhere('alamat', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('kelas', function ($kelasQuery) use ($searchTerm) {
                      $kelasQuery->where('prodi', 'like', '%' . $searchTerm . '%')
                                ->orWhere('paralel', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Status filter functionality
        if ($request->filled('status')) {
            $statusFilters = is_array($request->status) ? $request->status : [$request->status];
            $query->whereIn('status', $statusFilters);
        }

        // Order by nama
        $query->orderBy('id_mahasiswa', 'asc');

        // Pagination with search and filter parameters preserved
        $mahasiswa = $query->paginate(10)->withQueryString();

        return view('mahasiswa.index', compact('mahasiswa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::orderBy('prodi')->orderBy('paralel')->get();
        return view('mahasiswa.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama' => [
                    'required',
                    'string',
                    'max:100',
                    'regex:/^[a-zA-Z\s\.]+$/',
                ],
                'nrp' => [
                    'required',
                    'string',
                    'size:10',
                    'unique:mahasiswa,nrp',
                    'regex:/^[0-9]+$/',
                ],
                'id_kelas' => [
                    'required',
                    'exists:kelas,id_kelas'
                ],
                'semester' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:14'
                ],
                'gender' => [
                    'required',
                    'in:L,P'
                ],
                'no_hp' => [
                    'required',
                    'string',
                    'max:15',
                    'regex:/^[0-9+\-\s]+$/',
                    'unique:mahasiswa,no_hp'
                ],
                'alamat' => [
                    'required',
                    'string',
                    'max:500'
                ],
            ], [
                // Custom error messages
                'nama.required' => 'Nama mahasiswa wajib diisi.',
                'nama.string' => 'Nama harus berupa teks.',
                'nama.max' => 'Nama tidak boleh lebih dari 100 karakter.',
                'nama.regex' => 'Nama hanya boleh berisi huruf, spasi, dan titik.',
                
                'nrp.required' => 'NRP wajib diisi.',
                'nrp.size' => 'NRP harus tepat 10 digit.',
                'nrp.unique' => 'NRP sudah terdaftar, gunakan NRP lain.',
                'nrp.regex' => 'NRP hanya boleh berisi angka.',
                
                'id_kelas.required' => 'Kelas wajib dipilih.',
                'id_kelas.exists' => 'Kelas yang dipilih tidak valid.',
                
                'semester.required' => 'Semester wajib diisi.',
                'semester.integer' => 'Semester harus berupa angka.',
                'semester.min' => 'Semester minimal 1.',
                'semester.max' => 'Semester maksimal 14.',
                
                'gender.required' => 'Jenis kelamin wajib dipilih.',
                'gender.in' => 'Jenis kelamin harus Laki-laki atau Perempuan.',
                
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.max' => 'Nomor HP tidak boleh lebih dari 15 karakter.',
                'no_hp.regex' => 'Nomor HP hanya boleh berisi angka, +, -, dan spasi.',
                'no_hp.unique' => 'Nomor HP sudah terdaftar, gunakan nomor lain.',
                
                'alamat.required' => 'Alamat wajib diisi.',
                'alamat.string' => 'Alamat harus berupa teks.',
                'alamat.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
            ]);

            // Set default status
            $validatedData['status'] = 'AKTIF';
            
            Mahasiswa::create($validatedData);

            return redirect()->route('mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Gagal menambahkan mahasiswa. Periksa kembali data yang diisi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mahasiswa = Mahasiswa::with(['kelas'])->findOrFail($id);
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $kelas = Kelas::orderBy('prodi')->orderBy('paralel')->get();
        return view('mahasiswa.edit', compact('mahasiswa', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);
            
            $validatedData = $request->validate([
                'nama' => [
                    'required',
                    'string',
                    'max:100',
                    'regex:/^[a-zA-Z\s\.]+$/',
                ],
                'nrp' => [
                    'required',
                    'string',
                    'size:10',
                    Rule::unique('mahasiswa', 'nrp')->ignore($mahasiswa->id_mahasiswa, 'id_mahasiswa'),
                    'regex:/^[0-9]+$/',
                ],
                'id_kelas' => [
                    'required',
                    'exists:kelas,id_kelas'
                ],
                'semester' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:14'
                ],
                'gender' => [
                    'required',
                    'in:L,P'
                ],
                'no_hp' => [
                    'required',
                    'string',
                    'max:15',
                    'regex:/^[0-9+\-\s]+$/',
                    Rule::unique('mahasiswa', 'no_hp')->ignore($mahasiswa->id_mahasiswa, 'id_mahasiswa'),
                ],
                'alamat' => [
                    'required',
                    'string',
                    'max:500'
                ],
                'status' => [
                    'required',
                    'in:AKTIF,CUTI,LULUS,NONAKTIF'
                ],
            ], [
                // Custom error messages (same as store method)
                'nama.required' => 'Nama mahasiswa wajib diisi.',
                'nama.string' => 'Nama harus berupa teks.',
                'nama.max' => 'Nama tidak boleh lebih dari 100 karakter.',
                'nama.regex' => 'Nama hanya boleh berisi huruf, spasi, dan titik.',
                
                'nrp.required' => 'NRP wajib diisi.',
                'nrp.size' => 'NRP harus tepat 10 digit.',
                'nrp.unique' => 'NRP sudah digunakan mahasiswa lain.',
                'nrp.regex' => 'NRP hanya boleh berisi angka.',
                
                'id_kelas.required' => 'Kelas wajib dipilih.',
                'id_kelas.exists' => 'Kelas yang dipilih tidak valid.',
                
                'semester.required' => 'Semester wajib diisi.',
                'semester.integer' => 'Semester harus berupa angka.',
                'semester.min' => 'Semester minimal 1.',
                'semester.max' => 'Semester maksimal 14.',
                
                'gender.required' => 'Jenis kelamin wajib dipilih.',
                'gender.in' => 'Jenis kelamin harus Laki-laki atau Perempuan.',
                
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.max' => 'Nomor HP tidak boleh lebih dari 15 karakter.',
                'no_hp.regex' => 'Nomor HP hanya boleh berisi angka, +, -, dan spasi.',
                'no_hp.unique' => 'Nomor HP sudah digunakan mahasiswa lain.',
                
                'alamat.required' => 'Alamat wajib diisi.',
                'alamat.string' => 'Alamat harus berupa teks.',
                'alamat.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
                
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status harus salah satu dari: Aktif, Cuti, Lulus, atau Non Aktif.',
            ]);

            $mahasiswa->update($validatedData);

            return redirect()->route('mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil diupdate.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Gagal mengupdate mahasiswa. Periksa kembali data yang diisi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);
            $nama = $mahasiswa->nama;
            $mahasiswa->delete();

            return redirect()->route('mahasiswa.index')
                ->with('success', "Data mahasiswa {$nama} berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.index')
                ->with('error', 'Gagal menghapus data mahasiswa: ' . $e->getMessage());
        }
    }
}