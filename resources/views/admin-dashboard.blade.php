@extends('master')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')
        {{-- Main --}}
        <div class="flex flex-col w-full bg-putih">
            {{-- Profil User di Header --}}
            @include('components.header')
            {{-- Content admin --}}
            <div class="flex flex-col px-6 pb-6 space-y-6">

                {{-- Toast Notification --}}
                <x-notification.toast-notification />

                {{-- Dashboard Title --}}
                <div class="flex flex-col space-y-2">
                    <h2 class="text-2xl text-hitam font-semibold">Dashboard Admin</h2>
                    <hr class="border-abu w-full">
                </div>

                {{-- Section 1: User Statistics --}}
                <div class="flex flex-col space-y-4">
                    <h3 class="text-xl text-hitam font-medium">Statistik Pengguna</h3>
                    <div class="flex flex-row space-x-4">
                        {{-- Card Mahasiswa --}}
                        <div class="flex flex-col w-1/3 p-4 bg-brand-200 rounded-3xl">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-base text-hitam">Mahasiswa</h4>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $mahasiswaStatus ?? 'Aktif' }}</span>
                            </div>
                            <h1 class="text-3xl text-hitam font-medium">{{ $totalMahasiswa ?? '1,245' }}</h1>
                            <p class="text-xs text-hitam mt-1">Total terdaftar</p>
                        </div>
                        
                        {{-- Card Dosen --}}
                        <div class="flex flex-col w-1/3 p-4 bg-brand-200 rounded-3xl">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-base text-hitam">Dosen</h4>
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $dosenStatus ?? 'Aktif' }}</span>
                            </div>
                            <h1 class="text-3xl text-hitam font-medium">{{ $totalDosen ?? '89' }}</h1>
                            <p class="text-xs text-hitam mt-1">Dosen tetap & kontrak</p>
                        </div>
                        
                        {{-- Card Users --}}
                        <div class="flex flex-col w-1/3 p-4 bg-brand-200 rounded-3xl">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-base text-hitam">Users</h4>
                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">{{ $usersStatus ?? 'Online' }}</span>
                            </div>
                            <h1 class="text-3xl text-hitam font-medium">{{ $totalUsers ?? '1,356' }}</h1>
                            <p class="text-xs text-hitam mt-1">Total pengguna sistem</p>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Academic Statistics --}}
                <div class="flex flex-col space-y-4">
                    <h3 class="text-xl text-hitam font-medium">Statistik Akademik</h3>
                    <div class="flex flex-row space-x-4">
                        {{-- Card Mata Kuliah --}}
                        <div class="flex flex-col w-1/4 p-4 bg-brand-50 rounded-3xl border border-brand-200">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-base text-hitam">Mata Kuliah</h4>
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $mataKuliahStatus ?? 'Tersedia' }}</span>
                            </div>
                            <h1 class="text-3xl text-hitam font-medium">{{ $totalMataKuliah ?? '156' }}</h1>
                            <p class="text-xs text-hitam mt-1">Semester aktif</p>
                        </div>
                        
                        {{-- Card Jadwal --}}
                        <div class="flex flex-col w-1/4 p-4 bg-brand-50 rounded-3xl border border-brand-200">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-base text-hitam">Jadwal</h4>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $jadwalStatus ?? 'Terjadwal' }}</span>
                            </div>
                            <h1 class="text-3xl text-hitam font-medium">{{ $totalJadwal ?? '342' }}</h1>
                            <p class="text-xs text-hitam mt-1">Sesi kuliah</p>
                        </div>
                        
                        {{-- Card Ruangan --}}
                        <div class="flex flex-col w-1/4 p-4 bg-brand-50 rounded-3xl border border-brand-200">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-base text-hitam">Ruangan</h4>
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $ruanganStatus ?? 'Tersedia' }}</span>
                            </div>
                            <h1 class="text-3xl text-hitam font-medium">{{ $totalRuangan ?? '45' }}</h1>
                            <p class="text-xs text-hitam mt-1">Ruang kelas & lab</p>
                        </div>
                        
                        {{-- Card Waktu --}}
                        <div class="flex flex-col w-1/4 p-4 bg-brand-50 rounded-3xl border border-brand-200">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-base text-hitam">Slot Waktu</h4>
                                <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800">{{ $waktuStatus ?? 'Aktif' }}</span>
                            </div>
                            <h1 class="text-3xl text-hitam font-medium">{{ $totalWaktu ?? '28' }}</h1>
                            <p class="text-xs text-hitam mt-1">Jam perkuliahan</p>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Class Management --}}
                <div class="flex flex-col space-y-4">
                    <h3 class="text-xl text-hitam font-medium">Manajemen Kelas</h3>
                    <div class="flex flex-row space-x-4">
                        {{-- Card Kelas Aktif --}}
                        <div class="flex flex-col w-1/2 p-4 bg-green-50 rounded-3xl border border-green-200">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-base text-hitam">Kelas Aktif</h4>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $kelasAktifStatus ?? 'Berlangsung' }}</span>
                            </div>
                            <h1 class="text-3xl text-hitam font-medium">{{ $totalKelasAktif ?? '89' }}</h1>
                            <p class="text-xs text-hitam mt-1">Kelas semester ini</p>
                        </div>
                        
                        {{-- Card Total Kelas --}}
                        <div class="flex flex-col w-1/2 p-4 bg-blue-50 rounded-3xl border border-blue-200">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-base text-hitam">Total Kelas</h4>
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $totalKelasStatus ?? 'Terdaftar' }}</span>
                            </div>
                            <h1 class="text-3xl text-hitam font-medium">{{ $totalKelas ?? '156' }}</h1>
                            <p class="text-xs text-hitam mt-1">Seluruh kelas</p>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Quick Actions --}}
                <div class="flex flex-col space-y-4">
                    <h3 class="text-xl text-hitam font-medium">Aksi Cepat</h3>
                    <div class="flex flex-row space-x-4">
                        <button class="flex flex-col items-center justify-center w-1/4 p-4 bg-brand-200 rounded-2xl hover:bg-brand-300 transition-colors">
                            <div class="w-8 h-8 bg-brand-500 rounded-full mb-2"></div>
                            <span class="text-sm text-hitam">Kelola Mahasiswa</span>
                        </button>
                        <button class="flex flex-col items-center justify-center w-1/4 p-4 bg-brand-200 rounded-2xl hover:bg-brand-300 transition-colors">
                            <div class="w-8 h-8 bg-blue-500 rounded-full mb-2"></div>
                            <span class="text-sm text-hitam">Kelola Dosen</span>
                        </button>
                        <button class="flex flex-col items-center justify-center w-1/4 p-4 bg-brand-200 rounded-2xl hover:bg-brand-300 transition-colors">
                            <div class="w-8 h-8 bg-green-500 rounded-full mb-2"></div>
                            <span class="text-sm text-hitam">Atur Jadwal</span>
                        </button>
                        <button class="flex flex-col items-center justify-center w-1/4 p-4 bg-brand-200 rounded-2xl hover:bg-brand-300 transition-colors">
                            <div class="w-8 h-8 bg-purple-500 rounded-full mb-2"></div>
                            <span class="text-sm text-hitam">Kelola Ruangan</span>
                        </button>
                    </div>
                </div>

                {{-- Section 5: Recent Activities --}}
                <div class="flex flex-col space-y-4">
                    <h3 class="text-xl text-hitam font-medium">Aktivitas Terbaru</h3>
                    <div class="flex flex-col w-full bg-brand-50 rounded-2xl p-4 space-y-3">
                        {{-- Activity Item 1 --}}
                        <div class="flex flex-row items-center justify-between p-3 bg-putih rounded-xl">
                            <div class="flex flex-col">
                                <h4 class="text-sm text-hitam font-medium">Mahasiswa baru terdaftar</h4>
                                <p class="text-xs text-hitam">{{ $latestStudentCount ?? '15' }} mahasiswa mendaftar hari ini</p>
                            </div>
                            <span class="text-xs text-hitam">{{ $lastActivityTime ?? '2 jam lalu' }}</span>
                        </div>
                        
                        {{-- Activity Item 2 --}}
                        <div class="flex flex-row items-center justify-between p-3 bg-putih rounded-xl">
                            <div class="flex flex-col">
                                <h4 class="text-sm text-hitam font-medium">Jadwal kuliah diperbarui</h4>
                                <p class="text-xs text-hitam">{{ $updatedSchedulesCount ?? '8' }} jadwal mengalami perubahan</p>
                            </div>
                            <span class="text-xs text-hitam">{{ $scheduleUpdateTime ?? '4 jam lalu' }}</span>
                        </div>
                        
                        {{-- Activity Item 3 --}}
                        <div class="flex flex-row items-center justify-between p-3 bg-putih rounded-xl">
                            <div class="flex flex-col">
                                <h4 class="text-sm text-hitam font-medium">Ruangan baru ditambahkan</h4>
                                <p class="text-xs text-hitam">{{ $newRoomsCount ?? '2' }} ruangan lab komputer tersedia</p>
                            </div>
                            <span class="text-xs text-hitam">{{ $roomAddTime ?? '1 hari lalu' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        .border-biru {
            border-left: 4px solid #3B82F6;
        }
    </style>
@endsection