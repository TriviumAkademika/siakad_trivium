<?php

namespace App\Http\Controllers\Api; // Pastikan namespace sudah benar

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;
use App\Http\Resources\DosenResource; // Digunakan untuk index dan show agar konsisten
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DosenApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // Mengambil semua data dosen tanpa filter dan pagination
        $dosen = Dosen::orderBy('id_dosen', 'asc')->get();
        return DosenResource::collection($dosen);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_dosen' => [
                'required', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-Z\s\.\,\-\']+$/',
                function ($attribute, $value, $fail) {
                    if (strlen(trim($value)) < 2) {
                        $fail('Nama dosen harus minimal 2 karakter.');
                    }
                }
            ],
            'nip' => [
                'required', 'string', 'min:8', 'max:50', 'regex:/^[0-9]+$/', Rule::unique('dosen', 'nip'),
                function ($attribute, $value, $fail) {
                    if (strlen($value) < 8) {
                        $fail('NIP harus minimal 8 digit.');
                    }
                }
            ],
            'alamat' => [
                'required', 'string', 'min:10', 'max:500',
                function ($attribute, $value, $fail) {
                    if (strlen(trim($value)) < 10) {
                        $fail('Alamat harus minimal 10 karakter.');
                    }
                }
            ],
            'no_hp' => [
                'required', 'string', 'min:10', 'max:20', 'regex:/^(\+62|62|0)[0-9]{8,13}$/',
                function ($attribute, $value, $fail) {
                    $exists = Dosen::where('no_hp', $value)->exists();
                    if ($exists) {
                        $fail('Nomor HP sudah digunakan oleh dosen lain.');
                    }
                }
            ],
            'status' => 'required|in:AKTIF,CUTI,PENSIUN,NONAKTIF',
        ], [
            // Custom messages (sama seperti di DosenController)
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
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            $validatedData = $validator->validated();
            // Sanitization (sama seperti di DosenController)
            $validatedData['nama_dosen'] = trim($validatedData['nama_dosen']);
            $validatedData['alamat'] = trim($validatedData['alamat']);
            $validatedData['nip'] = preg_replace('/[^0-9]/', '', $validatedData['nip']);
            $validatedData['no_hp'] = preg_replace('/[^0-9\+]/', '', $validatedData['no_hp']);

            $dosen = Dosen::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Data dosen berhasil ditambahkan!',
                'data' => new DosenResource($dosen) // Menggunakan resource untuk konsistensi
            ], 201); // 201 Created
        } catch (\Exception $e) {
            // Log::error('Error creating dosen: ' . $e->getMessage()); // Sebaiknya tambahkan logging
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data dosen!',
                'error_details' => $e->getMessage() // Opsional, untuk debugging
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id (asumsi $id adalah id_dosen)
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Validasi dasar untuk ID
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'ID dosen tidak valid!'
            ], 400); // 400 Bad Request
        }

        try {
            // Jika primary key bukan 'id', tapi 'id_dosen'
            $dosen = Dosen::where('id_dosen', $id)->first();

            if (!$dosen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dosen tidak ditemukan!'
                ], 404); // 404 Not Found
            }
            return response()->json([
                'success' => true,
                'message' => 'Data dosen berhasil dimuat.',
                'data' => new DosenResource($dosen)
            ]);
        } catch (\Exception $e) {
            // Log::error('Error fetching dosen: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data dosen!',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id (asumsi $id adalah id_dosen)
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'ID dosen tidak valid!'
            ], 400);
        }

        // Jika primary key bukan 'id', tapi 'id_dosen'
        $dosen = Dosen::where('id_dosen', $id)->first();

        if (!$dosen) {
            return response()->json([
                'success' => false,
                'message' => 'Data dosen tidak ditemukan!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_dosen' => [
                'sometimes', 'required', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-Z\s\.\,\-\']+$/',
                function ($attribute, $value, $fail) {
                    if (strlen(trim($value)) < 2) {
                        $fail('Nama dosen harus minimal 2 karakter.');
                    }
                }
            ],
            'nip' => [
                'sometimes', 'required', 'string', 'min:8', 'max:50', 'regex:/^[0-9]+$/',
                Rule::unique('dosen', 'nip')->ignore($dosen->id_dosen, 'id_dosen'), // Menggunakan $dosen->id_dosen
                function ($attribute, $value, $fail) {
                    if (strlen($value) < 8) {
                        $fail('NIP harus minimal 8 digit.');
                    }
                }
            ],
            'alamat' => [
                'sometimes', 'required', 'string', 'min:10', 'max:500',
                function ($attribute, $value, $fail) {
                    if (strlen(trim($value)) < 10) {
                        $fail('Alamat harus minimal 10 karakter.');
                    }
                }
            ],
            'no_hp' => [
                'sometimes', 'required', 'string', 'min:10', 'max:20', 'regex:/^(\+62|62|0)[0-9]{8,13}$/',
                function ($attribute, $value, $fail) use ($dosen) { // tambahkan use ($dosen)
                    $exists = Dosen::where('no_hp', $value)
                                   ->where('id_dosen', '!=', $dosen->id_dosen) // Gunakan $dosen->id_dosen
                                   ->exists();
                    if ($exists) {
                        $fail('Nomor HP sudah digunakan oleh dosen lain.');
                    }
                }
            ],
            'status' => 'sometimes|required|in:AKTIF,CUTI,PENSIUN,NONAKTIF',
        ], [
            // Custom messages (sama seperti sebelumnya)
            'nama_dosen.required' => 'Nama dosen wajib diisi.',
            'nama_dosen.min' => 'Nama dosen harus minimal 2 karakter.',
            // ... (tambahkan sisa custom messages)
            'nip.required' => 'NIP wajib diisi.',
            'nip.min' => 'NIP harus minimal 8 digit.',
            'nip.unique' => 'NIP sudah digunakan oleh dosen lain.',
            // ... dan seterusnya
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $validatedData = $validator->validated();
            // Sanitization (opsional jika 'sometimes' tidak ada, tapi baik untuk dilakukan jika ada data)
            if (isset($validatedData['nama_dosen'])) {
                $validatedData['nama_dosen'] = trim($validatedData['nama_dosen']);
            }
            if (isset($validatedData['alamat'])) {
                $validatedData['alamat'] = trim($validatedData['alamat']);
            }
            if (isset($validatedData['nip'])) {
                $validatedData['nip'] = preg_replace('/[^0-9]/', '', $validatedData['nip']);
            }
            if (isset($validatedData['no_hp'])) {
                $validatedData['no_hp'] = preg_replace('/[^0-9\+]/', '', $validatedData['no_hp']);
            }

            $dosen->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Data dosen berhasil diperbarui!',
                'data' => new DosenResource($dosen)
            ]);
        } catch (\Exception $e) {
            // Log::error('Error updating dosen: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data dosen!',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id (asumsi $id adalah id_dosen)
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'ID dosen tidak valid!'
            ], 400);
        }

        try {
            // Jika primary key bukan 'id', tapi 'id_dosen'
            $dosen = Dosen::where('id_dosen', $id)->first();

            if (!$dosen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dosen tidak ditemukan!'
                ], 404);
            }

            // Opsional: Tambahkan pengecekan relasi data jika diperlukan sebelum menghapus
            // if ($dosen->hasRelatedData()) { // Buat method hasRelatedData() di model Dosen
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Tidak dapat menghapus dosen yang masih memiliki data terkait!'
            //     ], 409); // 409 Conflict
            // }

            $dosen->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data dosen berhasil dihapus!'
            ]); // Bisa juga 204 No Content jika tidak ada body respons
        } catch (\Exception $e) {
            // Log::error('Error deleting dosen: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data dosen!',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }
}