<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use App\Http\Resources\DosenResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $totalDosen = Dosen::count();
        // Validate request parameters
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s\-\+\(\)]+$/',
            'status' => 'nullable|array',
            'status.*' => 'string|in:AKTIF,CUTI,PENSIUN,NONAKTIF',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->route('dosen.index')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Parameter pencarian tidak valid!');
        }

        try {
            $query = Dosen::query();

            // Search functionality with sanitization
            if ($request->filled('search')) {
                $searchTerm = trim($request->search);
                $searchTerm = preg_replace('/[^a-zA-Z0-9\s\-\+\(\)]/', '', $searchTerm);

                if (!empty($searchTerm)) {
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('nama_dosen', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('nip', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('alamat', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('no_hp', 'LIKE', '%' . $searchTerm . '%');
                    });
                }
            }

            // Status filter functionality
            if ($request->filled('status')) {
                $statusFilters = is_array($request->status) ? $request->status : [$request->status];
                $validStatuses = array_intersect($statusFilters, ['AKTIF', 'CUTI', 'PENSIUN', 'NONAKTIF']);

                if (!empty($validStatuses)) {
                    $query->whereIn('status', $validStatuses);
                }
            }

            // Order by id_dosen
            $query->orderBy('id_dosen', 'asc');

            // Paginate with validation
            $perPage = $request->input('per_page', 10);
            $perPage = max(1, min(100, (int)$perPage)); // Ensure between 1-100

            $dosen = $query->paginate($perPage);

            // Append query parameters to pagination links
            $dosen->appends($request->query());

            return view('dosen.index', compact('dosen'));
        } catch (\Exception $e) {
            return redirect()->route('dosen.index')
                ->with('error', 'Terjadi kesalahan saat memuat data dosen!');
        }
    }

    public function create()
    {
        return view('dosen.create');
    }

    public function store(Request $request)
    {
        // Comprehensive validation
        $validator = Validator::make($request->all(), [
            'nama_dosen' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s\.\,\-\']+$/',
                function ($attribute, $value, $fail) {
                    if (strlen(trim($value)) < 2) {
                        $fail('Nama dosen harus minimal 2 karakter.');
                    }
                }
            ],
            'nip' => [
                'required',
                'string',
                'min:8',
                'max:50',
                'regex:/^[0-9]+$/',
                Rule::unique('dosen', 'nip'),
                function ($attribute, $value, $fail) {
                    if (strlen($value) < 8) {
                        $fail('NIP harus minimal 8 digit.');
                    }
                }
            ],
            'alamat' => [
                'required',
                'string',
                'min:10',
                'max:500',
                function ($attribute, $value, $fail) {
                    if (strlen(trim($value)) < 10) {
                        $fail('Alamat harus minimal 10 karakter.');
                    }
                }
            ],
            'no_hp' => [
                'required',
                'string',
                'min:10',
                'max:20',
                'regex:/^(\+62|62|0)[0-9]{8,13}$/',
                function ($attribute, $value, $fail) {
                    // Check if phone number already exists
                    $exists = Dosen::where('no_hp', $value)->exists();
                    if ($exists) {
                        $fail('Nomor HP sudah digunakan oleh dosen lain.');
                    }
                }
            ],
            'status' => 'required|in:AKTIF,CUTI,PENSIUN,NONAKTIF',
        ], [
            'nama_dosen.required' => 'Nama dosen wajib diisi.',
            'nama_dosen.min' => 'Nama dosen harus minimal 2 karakter.',
            'nama_dosen.max' => 'Nama dosen maksimal 255 karakter.',
            'nama_dosen.regex' => 'Nama dosen hanya boleh berisi huruf, spasi, titik, koma, tanda hubung, dan apostrof.',

            'nip.required' => 'NIP wajib diisi.',
            'nip.min' => 'NIP harus minimal 8 digit.',
            'nip.max' => 'NIP maksimal 50 karakter.',
            'nip.regex' => 'NIP hanya boleh berisi angka.',
            'nip.unique' => 'NIP sudah digunakan oleh dosen lain.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.min' => 'Alamat harus minimal 10 karakter.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',

            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.min' => 'Nomor HP harus minimal 10 karakter.',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter.',
            'no_hp.regex' => 'Format nomor HP tidak valid. Gunakan format Indonesia (contoh: 081234567890).',

            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form!');
        }

        try {
            // Sanitize input data
            $validatedData = $validator->validated();
            $validatedData['nama_dosen'] = trim($validatedData['nama_dosen']);
            $validatedData['alamat'] = trim($validatedData['alamat']);
            $validatedData['nip'] = preg_replace('/[^0-9]/', '', $validatedData['nip']);
            $validatedData['no_hp'] = preg_replace('/[^0-9\+]/', '', $validatedData['no_hp']);

            Dosen::create($validatedData);

            return redirect()->route('dosen.index')
                ->with('success', 'Data dosen berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data dosen!');
        }
    }

    public function show($id)
    {
        // Validate ID parameter
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->route('dosen.index')
                ->with('error', 'ID dosen tidak valid!');
        }

        try {
            $dosen = Dosen::findOrFail($id);
            return view('dosen.show', compact('dosen'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('dosen.index')
                ->with('error', 'Data dosen tidak ditemukan!');
        } catch (\Exception $e) {
            return redirect()->route('dosen.index')
                ->with('error', 'Terjadi kesalahan saat memuat data dosen!');
        }
    }

    public function edit($id)
    {
        // Validate ID parameter
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->route('dosen.index')
                ->with('error', 'ID dosen tidak valid!');
        }

        try {
            $dosen = Dosen::findOrFail($id);
            return view('dosen.edit', compact('dosen'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('dosen.index')
                ->with('error', 'Data dosen tidak ditemukan!');
        } catch (\Exception $e) {
            return redirect()->route('dosen.index')
                ->with('error', 'Terjadi kesalahan saat memuat data dosen!');
        }
    }

    public function update(Request $request, $id)
    {
        // Validate ID parameter
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->route('dosen.index')
                ->with('error', 'ID dosen tidak valid!');
        }

        try {
            $dosen = Dosen::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('dosen.index')
                ->with('error', 'Data dosen tidak ditemukan!');
        }

        // Comprehensive validation
        $validator = Validator::make($request->all(), [
            'nama_dosen' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s\.\,\-\']+$/',
                function ($attribute, $value, $fail) {
                    if (strlen(trim($value)) < 2) {
                        $fail('Nama dosen harus minimal 2 karakter.');
                    }
                }
            ],
            'nip' => [
                'required',
                'string',
                'min:8',
                'max:50',
                'regex:/^[0-9]+$/',
                Rule::unique('dosen', 'nip')->ignore($id, 'id_dosen'),
                function ($attribute, $value, $fail) {
                    if (strlen($value) < 8) {
                        $fail('NIP harus minimal 8 digit.');
                    }
                }
            ],
            'alamat' => [
                'required',
                'string',
                'min:10',
                'max:500',
                function ($attribute, $value, $fail) {
                    if (strlen(trim($value)) < 10) {
                        $fail('Alamat harus minimal 10 karakter.');
                    }
                }
            ],
            'no_hp' => [
                'required',
                'string',
                'min:10',
                'max:20',
                'regex:/^(\+62|62|0)[0-9]{8,13}$/',
                function ($attribute, $value, $fail) use ($id) {
                    // Check if phone number already exists (excluding current record)
                    $exists = Dosen::where('no_hp', $value)
                        ->where('id_dosen', '!=', $id)
                        ->exists();
                    if ($exists) {
                        $fail('Nomor HP sudah digunakan oleh dosen lain.');
                    }
                }
            ],
            'status' => 'required|in:AKTIF,CUTI,PENSIUN,NONAKTIF',
        ], [
            'nama_dosen.required' => 'Nama dosen wajib diisi.',
            'nama_dosen.min' => 'Nama dosen harus minimal 2 karakter.',
            'nama_dosen.max' => 'Nama dosen maksimal 255 karakter.',
            'nama_dosen.regex' => 'Nama dosen hanya boleh berisi huruf, spasi, titik, koma, tanda hubung, dan apostrof.',

            'nip.required' => 'NIP wajib diisi.',
            'nip.min' => 'NIP harus minimal 8 digit.',
            'nip.max' => 'NIP maksimal 50 karakter.',
            'nip.regex' => 'NIP hanya boleh berisi angka.',
            'nip.unique' => 'NIP sudah digunakan oleh dosen lain.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.min' => 'Alamat harus minimal 10 karakter.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',

            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.min' => 'Nomor HP harus minimal 10 karakter.',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter.',
            'no_hp.regex' => 'Format nomor HP tidak valid. Gunakan format Indonesia (contoh: 081234567890).',

            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form!');
        }

        try {
            // Sanitize input data
            $validatedData = $validator->validated();
            $validatedData['nama_dosen'] = trim($validatedData['nama_dosen']);
            $validatedData['alamat'] = trim($validatedData['alamat']);
            $validatedData['nip'] = preg_replace('/[^0-9]/', '', $validatedData['nip']);
            $validatedData['no_hp'] = preg_replace('/[^0-9\+]/', '', $validatedData['no_hp']);

            $dosen->update($validatedData);

            return redirect()->route('dosen.index')
                ->with('success', 'Data dosen berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data dosen!');
        }
    }

    public function destroy($id)
    {
        // Validate ID parameter
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->route('dosen.index')
                ->with('error', 'ID dosen tidak valid!');
        }

        try {
            $dosen = Dosen::findOrFail($id);

            // Check if dosen has related data (if applicable)
            // Example: Check if dosen is assigned to any classes, subjects, etc.
            // if ($dosen->classes()->exists() || $dosen->subjects()->exists()) {
            //     return redirect()->route('dosen.index')
            //         ->with('error', 'Tidak dapat menghapus dosen yang masih memiliki data terkait!');
            // }

            $dosen->delete();

            return redirect()->route('dosen.index')
                ->with('success', 'Data dosen berhasil dihapus!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('dosen.index')
                ->with('error', 'Data dosen tidak ditemukan!');
        } catch (\Exception $e) {
            return redirect()->route('dosen.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data dosen!');
        }
    }
}
