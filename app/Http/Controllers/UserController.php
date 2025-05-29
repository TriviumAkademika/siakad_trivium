<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Tampilkan semua user
    public function index(Request $request)
    {
        $totalUser = User::count();
        // Validate request parameters
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\s\-\+\(\)@\.]+$/',
            'roles' => 'nullable|array',
            'roles.*' => 'string|in:admin,dosen,mahasiswa',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.index')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Parameter pencarian tidak valid!');
        }

        try {
            $query = User::with(['mahasiswa', 'dosen']); // Eager loading

            // Search functionality with sanitization
            if ($request->filled('search')) {
                $searchTerm = trim($request->search);
                $searchTerm = preg_replace('/[^a-zA-Z0-9\s\-\+\(\)@\.]/', '', $searchTerm);

                if (!empty($searchTerm)) {
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('email', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('name', 'LIKE', "%{$searchTerm}%")
                            ->orWhereHas('mahasiswa', function ($subQuery) use ($searchTerm) {
                                $subQuery->where('nama', 'LIKE', "%{$searchTerm}%")
                                    ->orWhere('nrp', 'LIKE', "%{$searchTerm}%");
                            })
                            ->orWhereHas('dosen', function ($subQuery) use ($searchTerm) {
                                $subQuery->where('nama_dosen', 'LIKE', "%{$searchTerm}%")
                                    ->orWhere('nip', 'LIKE', "%{$searchTerm}%");
                            });
                    });
                }
            }

            // Role filter functionality
            if ($request->filled('roles')) {
                $roleFilters = is_array($request->roles) ? $request->roles : [$request->roles];
                $validRoles = array_intersect($roleFilters, ['admin', 'dosen', 'mahasiswa']);

                if (!empty($validRoles)) {
                    $query->whereHas('roles', function ($q) use ($validRoles) {
                        $q->whereIn('name', $validRoles);
                    });
                }
            }

            // Order by id_user
            $query->orderBy('id_user', 'asc');

            // Paginate with validation
            $perPage = $request->input('per_page', 10);
            $perPage = max(1, min(100, (int)$perPage)); // Ensure between 1-100

            $users = $query->paginate($perPage);

            // Append query parameters to pagination links
            $users->appends($request->query());

            return view('users.index', compact('users'));
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan saat memuat data user!');
        }
    }

    public function show($id)
    {
        // Validate ID parameter
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->route('users.index')
                ->with('error', 'ID user tidak valid!');
        }

        try {
            $user = User::with(['mahasiswa.kelas', 'dosen'])->findOrFail($id);
            return view('users.show', compact('user'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('users.index')
                ->with('error', 'Data user tidak ditemukan!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan saat memuat data user!');
        }
    }

    // Form tambah user
    public function create()
    {
        try {
            // Ambil mahasiswa dan dosen yang belum memiliki user
            $mahasiswa = Mahasiswa::doesntHave('user')->get();
            $dosen = Dosen::doesntHave('user')->get();
            return view('users.create', compact('mahasiswa', 'dosen'));
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan saat memuat halaman tambah user!');
        }
    }

    /**
     * Validasi domain email berdasarkan role
     */
    private function validateEmailDomain($email, $role)
    {
        $domains = [
            'admin' => '@trivium.ac.id',
            'dosen' => '@lecture.trivium.ac.id',
            'mahasiswa' => '@student.trivium.ac.id'
        ];

        if (!isset($domains[$role])) {
            return false;
        }

        return str_ends_with($email, $domains[$role]);
    }

    public function store(Request $request)
    {
        // Base validation rules
        $rules = [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('role') && !$this->validateEmailDomain($value, $request->role)) {
                        $domains = [
                            'admin' => '@trivium.ac.id',
                            'dosen' => '@lecture.trivium.ac.id',
                            'mahasiswa' => '@student.trivium.ac.id'
                        ];
                        $fail('Email harus menggunakan domain ' . $domains[$request->role] . ' untuk role ' . $request->role . '.');
                    }
                }
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'role' => 'required|in:mahasiswa,dosen,admin',
        ];

        // Additional validation based on role
        if ($request->role === 'mahasiswa') {
            $rules['id_mahasiswa'] = [
                'required',
                'integer',
                'exists:mahasiswa,id_mahasiswa',
                Rule::unique('users', 'id_mahasiswa')
            ];
        } elseif ($request->role === 'dosen') {
            $rules['id_dosen'] = [
                'required',
                'integer',
                'exists:dosen,id_dosen',
                Rule::unique('users', 'id_dosen')
            ];
        } elseif ($request->role === 'admin') {
            $rules['nama_user'] = [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s\.\,\-\']+$/'
            ];
        }

        // Custom error messages
        $messages = [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan oleh user lain.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',

            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',

            'id_mahasiswa.required' => 'Mahasiswa wajib dipilih.',
            'id_mahasiswa.integer' => 'ID mahasiswa harus berupa angka.',
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak ditemukan.',
            'id_mahasiswa.unique' => 'Mahasiswa sudah memiliki akun user.',

            'id_dosen.required' => 'Dosen wajib dipilih.',
            'id_dosen.integer' => 'ID dosen harus berupa angka.',
            'id_dosen.exists' => 'Dosen yang dipilih tidak ditemukan.',
            'id_dosen.unique' => 'Dosen sudah memiliki akun user.',

            'nama_user.required' => 'Nama user wajib diisi.',
            'nama_user.min' => 'Nama user harus minimal 2 karakter.',
            'nama_user.max' => 'Nama user maksimal 255 karakter.',
            'nama_user.regex' => 'Nama user hanya boleh berisi huruf, spasi, titik, koma, tanda hubung, dan apostrof.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form!');
        }

        try {
            // Sanitize and prepare user data
            $validatedData = $validator->validated();
            $userData = [
                'email' => trim($validatedData['email']),
                'password' => Hash::make($validatedData['password']),
            ];

            // Add role-specific fields
            if ($validatedData['role'] === 'mahasiswa') {
                $userData['id_mahasiswa'] = $validatedData['id_mahasiswa'];
            } elseif ($validatedData['role'] === 'dosen') {
                $userData['id_dosen'] = $validatedData['id_dosen'];
            } elseif ($validatedData['role'] === 'admin') {
                $userData['name'] = trim($validatedData['nama_user']);
            }

            // Create user
            $user = User::create($userData);

            // Assign role using Spatie Permission
            $user->assignRole($validatedData['role']);

            return redirect()->route('users.index')
                ->with('success', 'Data user berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data user!');
        }
    }

    // Form edit user
    public function edit($id)
    {
        // Validate ID parameter
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->route('users.index')
                ->with('error', 'ID user tidak valid!');
        }

        try {
            $user = User::with(['mahasiswa', 'dosen', 'roles'])->findOrFail($id);

            // Ambil mahasiswa dan dosen yang belum memiliki user (kecuali yang sedang diedit)
            $mahasiswa = Mahasiswa::doesntHave('user')
                ->orWhere('id_mahasiswa', $user->id_mahasiswa)
                ->get();
            $dosen = Dosen::doesntHave('user')
                ->orWhere('id_dosen', $user->id_dosen)
                ->get();

            return view('users.edit', compact('user', 'mahasiswa', 'dosen'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('users.index')
                ->with('error', 'Data user tidak ditemukan!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan saat memuat halaman edit user!');
        }
    }

    // Update user
    public function update(Request $request, $id)
    {
        // Validate ID parameter
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->route('users.index')
                ->with('error', 'ID user tidak valid!');
        }

        try {
            $user = User::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('users.index')
                ->with('error', 'Data user tidak ditemukan!');
        }

        $currentRole = $user->getRoleNames()->first(); // Ambil role dari Spatie

        $rules = [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($id, 'id_user'),
                function ($attribute, $value, $fail) use ($currentRole) {
                    if (!$this->validateEmailDomain($value, $currentRole)) {
                        $domains = [
                            'admin' => '@trivium.ac.id',
                            'dosen' => '@lecture.trivium.ac.id',
                            'mahasiswa' => '@student.trivium.ac.id'
                        ];
                        $fail('Email harus menggunakan domain ' . $domains[$currentRole] . ' untuk role ' . $currentRole . '.');
                    }
                }
            ],
        ];

        if ($request->filled('password')) {
            $rules['password'] = [
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ];
        }

        if ($currentRole === 'admin') {
            $rules['nama_user'] = [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s\.\,\-\']+$/'
            ];
        }

        // Custom error messages
        $messages = [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan oleh user lain.',

            'password.min' => 'Password harus minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',

            'nama_user.required' => 'Nama user wajib diisi.',
            'nama_user.min' => 'Nama user harus minimal 2 karakter.',
            'nama_user.max' => 'Nama user maksimal 255 karakter.',
            'nama_user.regex' => 'Nama user hanya boleh berisi huruf, spasi, titik, koma, tanda hubung, dan apostrof.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form!');
        }

        try {
            // Sanitize and update user data
            $validatedData = $validator->validated();
            $user->email = trim($validatedData['email']);

            if ($request->filled('password')) {
                $user->password = Hash::make($validatedData['password']);
            }

            if ($currentRole === 'admin') {
                $user->name = trim($validatedData['nama_user']);
            }

            $user->save();

            return redirect()->route('users.index')
                ->with('success', 'Data user berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data user!');
        }
    }

    // Hapus user
    public function destroy($id)
    {
        // Validate ID parameter
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->route('users.index')
                ->with('error', 'ID user tidak valid!');
        }

        try {
            $user = User::findOrFail($id);

            // Hapus semua role yang terkait dengan user
            $user->removeRole($user->getRoleNames()->first());

            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'Data user berhasil dihapus!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('users.index')
                ->with('error', 'Data user tidak ditemukan!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data user!');
        }
    }
}
