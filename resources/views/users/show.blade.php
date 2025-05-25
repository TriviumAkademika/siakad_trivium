@extends('master')

@section('title', 'Detail User')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')

        <div class="flex flex-col w-full bg-putih">
            {{-- Header --}}
            @include('components.header')

            {{-- Toast Notification --}}
            <x-notification.toast-notification />

            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Header dengan tombol kembali --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('users.index') }}" 
                           class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="ph ph-arrow-left text-lg"></i>
                        </a>
                        <h2 class="text-2xl font-semibold text-hitam">Detail User</h2>
                    </div>
                </div>

                {{-- Card Detail User --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                    {{-- Header Card --}}
                    <div class="bg-brand-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 
                                    @if($user->role === 'admin') bg-blue-600
                                    @elseif($user->role === 'dosen') bg-green-600
                                    @elseif($user->role === 'mahasiswa') bg-purple-600
                                    @else bg-gray-600 @endif 
                                    rounded-full flex items-center justify-center">
                                    @if($user->role === 'admin')
                                        <i class="ph ph-user-circle-gear text-2xl text-white"></i>
                                    @elseif($user->role === 'dosen')
                                        <i class="ph ph-chalkboard-teacher text-2xl text-white"></i>
                                    @elseif($user->role === 'mahasiswa')
                                        <i class="ph ph-student text-2xl text-white"></i>
                                    @else
                                        <i class="ph ph-user text-2xl text-white"></i>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-hitam">{{ $user->email }}</h3>
                                    <p class="text-gray-600">{{ $user->display_name }}</p>
                                </div>
                            </div>
                            
                            {{-- Role Badge --}}
                            <div class="flex flex-col items-end gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($user->role === 'admin') bg-blue-100 text-blue-800
                                    @elseif($user->role === 'dosen') bg-green-100 text-green-800
                                    @elseif($user->role === 'mahasiswa') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($user->role ?? 'User') }}
                                </span>
                                <div class="text-center">
                                    <div class="text-sm text-gray-600">Bergabung</div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $user->created_at ? $user->created_at->format('M Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Content Card --}}
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Informasi Akun --}}
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-hitam border-b border-gray-200 pb-2">
                                    Informasi Akun
                                </h4>
                                
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-envelope text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Email</label>
                                            <p class="text-hitam">{{ $user->email }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-shield-check text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Role</label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                                @if($user->role === 'admin') bg-blue-100 text-blue-800
                                                @elseif($user->role === 'dosen') bg-green-100 text-green-800
                                                @elseif($user->role === 'mahasiswa') bg-purple-100 text-purple-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($user->role ?? 'User') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-calendar text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Tanggal Dibuat</label>
                                            <p class="text-hitam">{{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-clock text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Terakhir Diupdate</label>
                                            <p class="text-hitam">{{ $user->updated_at ? $user->updated_at->format('d M Y H:i') : '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Status & Verifikasi --}}
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-hitam border-b border-gray-200 pb-2">
                                    Status & Verifikasi
                                </h4>
                                
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-check-circle text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status Email</label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                                @if($user->email_verified_at) bg-green-100 text-green-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                @if($user->email_verified_at)
                                                    Terverifikasi
                                                @else
                                                    Belum Terverifikasi
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    @if($user->email_verified_at)
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-calendar-check text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Verifikasi pada</label>
                                            <p class="text-hitam">{{ $user->email_verified_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-activity text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status Akun</label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                Aktif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detail berdasarkan Role --}}
                @if($user->role === 'mahasiswa')
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="bg-purple-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-semibold text-hitam flex items-center">
                                    <i class="ph ph-student mr-2 text-purple-600"></i>
                                    Data Mahasiswa
                                </h4>
                                @if($user->mahasiswa)
                                <a href="{{ route('mahasiswa.show', $user->mahasiswa->id_mahasiswa) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition-colors">
                                    <i class="ph ph-eye mr-1"></i>
                                    Lihat Detail
                                </a>
                                @endif
                            </div>
                        </div>
                        
                        @if($user->mahasiswa)
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-user text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                            <p class="text-hitam">{{ $user->mahasiswa->nama }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-identification-card text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">NRP</label>
                                            <p class="text-hitam font-mono">{{ $user->mahasiswa->nrp }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-book text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Semester</label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                Semester {{ $user->mahasiswa->semester }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-gender-intersex text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                                @if($user->mahasiswa->gender == 'L') bg-blue-100 text-blue-800
                                                @else bg-pink-100 text-pink-800 @endif">
                                                {{ $user->mahasiswa->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-phone text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">No. HP</label>
                                            <p class="text-hitam">{{ $user->mahasiswa->no_hp ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-graduation-cap text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Kelas</label>
                                            <p class="text-hitam">{{ $user->mahasiswa->kelas->nama_kelas ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-map-pin text-gray-500"></i>
                                        </div>
                                        <div class="w-full">
                                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                            <p class="text-hitam">{{ $user->mahasiswa->alamat ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="p-6 text-center">
                            <i class="ph ph-warning text-4xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500">Data mahasiswa tidak ditemukan atau belum terhubung.</p>
                        </div>
                        @endif
                    </div>

                @elseif($user->role === 'dosen')
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="bg-green-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-semibold text-hitam flex items-center">
                                    <i class="ph ph-chalkboard-teacher mr-2 text-green-600"></i>
                                    Data Dosen
                                </h4>
                                @if($user->dosen)
                                <a href="{{ route('dosen.show', $user->dosen->id_dosen) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                                    <i class="ph ph-eye mr-1"></i>
                                    Lihat Detail
                                </a>
                                @endif
                            </div>
                        </div>
                        
                        @if($user->dosen)
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-user text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nama Dosen</label>
                                            <p class="text-hitam">{{ $user->dosen->nama_dosen }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-identification-card text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">NIP</label>
                                            <p class="text-hitam font-mono">{{ $user->dosen->nip ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-phone text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">No. HP</label>
                                            <p class="text-hitam">{{ $user->dosen->no_hp ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-check-circle text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status</label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                                @if($user->dosen->status === 'AKTIF') bg-green-100 text-green-800
                                                @elseif($user->dosen->status === 'CUTI') bg-yellow-100 text-yellow-800
                                                @elseif($user->dosen->status === 'PENSIUN') bg-gray-100 text-gray-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $user->dosen->status ?? 'TIDAK DIKETAHUI' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-map-pin text-gray-500"></i>
                                        </div>
                                        <div class="w-full">
                                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                            <p class="text-hitam">{{ $user->dosen->alamat ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="p-6 text-center">
                            <i class="ph ph-warning text-4xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500">Data dosen tidak ditemukan atau belum terhubung.</p>
                        </div>
                        @endif
                    </div>

                @else
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-hitam flex items-center">
                                <i class="ph ph-user-circle-gear mr-2 text-blue-600"></i>
                                Data Admin
                            </h4>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-user text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nama Admin</label>
                                            <p class="text-hitam">{{ $user->name ?? 'Administrator' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-identification-badge text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">ID User</label>
                                            <p class="text-hitam font-mono">{{ $user->id_user }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-shield-check text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Hak Akses</label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                Administrator
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="mt-6 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        <i class="ph ph-info mr-1"></i>
                        Data user dapat diubah melalui menu edit user
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('users.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-biru-500">
                            <i class="ph ph-list mr-2"></i>
                            Kembali ke Daftar
                        </a>
                        
                        {{-- Uncomment jika route edit tersedia
                        <a href="{{ route('users.edit', $user->id_user) }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-biru-600 hover:bg-biru-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-biru-500">
                            <i class="ph ph-pencil-simple mr-2"></i>
                            Edit User
                        </a>
                        --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection