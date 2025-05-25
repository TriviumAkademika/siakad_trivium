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
     * Validate phone number format
     */
    private function validatePhoneNumber($phoneNumber)
    {
        // Remove all spaces, dashes, and plus signs for validation
        $cleanPhone = preg_replace('/[\s\-+]/', '', $phoneNumber);
        
        // Check if it's all numeric after cleaning
        if (!preg_match('/^[0-9]+$/', $cleanPhone)) {
            return false;
        }
        
        // Check length (8-15 digits after cleaning)
        $length = strlen($cleanPhone);
        if ($length < 8 || $length > 15) {
            return false;
        }
        
        // Indonesian phone number patterns
        $patterns = [
            '/^08[0-9]{8,11}$/',     // Mobile: 08xx-xxxx-xxxx (10-13 digits)
            '/^62[0-9]{8,12}$/',     // International format: 62xxx
            '/^0[2-9][0-9]{7,10}$/', // Landline: 0xx-xxxx-xxx (9-12 digits)
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $cleanPhone)) {
                return true;
            }
        }
        
        return false;
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
                    'regex:/^[a-zA-Z\s\.\']+$/',
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
                    'min:10',
                    'max:18',
                    'regex:/^[\+]?[0-9\s\-]{8,18}$/',
                    'unique:mahasiswa,no_hp',
                    function ($attribute, $value, $fail) {
                        if (!$this->validatePhoneNumber($value)) {
                            $fail('Format nomor HP tidak valid. Gunakan format Indonesia (08xx-xxxx-xxxx) atau internasional (+62xxx).');
                        }
                    },
                ],
                'alamat' => [
                    'required',
                    'string',
                    'max:500',
                    'min:10'
                ],
            ], [
                // Custom error messages
                'nama.required' => 'Nama mahasiswa wajib diisi.',
                'nama.string' => 'Nama harus berupa teks.',
                'nama.max' => 'Nama tidak boleh lebih dari 100 karakter.',
                'nama.regex' => 'Nama hanya boleh berisi huruf, spasi, titik, dan tanda petik.',
                
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
                'no_hp.min' => 'Nomor HP minimal 10 karakter.',
                'no_hp.max' => 'Nomor HP tidak boleh lebih dari 18 karakter.',
                'no_hp.regex' => 'Format nomor HP tidak valid. Contoh: 08123456789, +6281234567890.',
                'no_hp.unique' => 'Nomor HP sudah terdaftar, gunakan nomor lain.',
                
                'alamat.required' => 'Alamat wajib diisi.',
                'alamat.string' => 'Alamat harus berupa teks.',
                'alamat.min' => 'Alamat minimal 10 karakter.',
                'alamat.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
            ]);

            // Clean and format phone number before saving
            $validatedData['no_hp'] = $this->formatPhoneNumber($validatedData['no_hp']);
            
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
     * Format phone number for consistent storage
     */
    private function formatPhoneNumber($phoneNumber)
    {
        // Remove all spaces and dashes
        $clean = preg_replace('/[\s\-]/', '', $phoneNumber);
        
        // If starts with +62, keep it
        if (strpos($clean, '+62') === 0) {
            return $clean;
        }
        
        // If starts with 62 (without +), add +
        if (strpos($clean, '62') === 0 && strlen($clean) > 10) {
            return '+' . $clean;
        }
        
        // If starts with 08, convert to +62
        if (strpos($clean, '08') === 0) {
            return '+62' . substr($clean, 1);
        }
        
        // Return as is for other formats
        return $clean;
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
                    'regex:/^[a-zA-Z\s\.\']+$/',
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
                    'min:10',
                    'max:18',
                    'regex:/^[\+]?[0-9\s\-]{8,18}$/',
                    Rule::unique('mahasiswa', 'no_hp')->ignore($mahasiswa->id_mahasiswa, 'id_mahasiswa'),
                    function ($attribute, $value, $fail) {
                        if (!$this->validatePhoneNumber($value)) {
                            $fail('Format nomor HP tidak valid. Gunakan format Indonesia (08xx-xxxx-xxxx) atau internasional (+62xxx).');
                        }
                    },
                ],
                'alamat' => [
                    'required',
                    'string',
                    'max:500',
                    'min:10'
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
                'nama.regex' => 'Nama hanya boleh berisi huruf, spasi, titik, dan tanda petik.',
                
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
                'no_hp.min' => 'Nomor HP minimal 10 karakter.',
                'no_hp.max' => 'Nomor HP tidak boleh lebih dari 18 karakter.',
                'no_hp.regex' => 'Format nomor HP tidak valid. Contoh: 08123456789, +6281234567890.',
                'no_hp.unique' => 'Nomor HP sudah digunakan mahasiswa lain.',
                
                'alamat.required' => 'Alamat wajib diisi.',
                'alamat.string' => 'Alamat harus berupa teks.',
                'alamat.min' => 'Alamat minimal 10 karakter.',
                'alamat.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
                
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status harus salah satu dari: Aktif, Cuti, Lulus, atau Non Aktif.',
            ]);

            // Clean and format phone number before saving
            $validatedData['no_hp'] = $this->formatPhoneNumber($validatedData['no_hp']);

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