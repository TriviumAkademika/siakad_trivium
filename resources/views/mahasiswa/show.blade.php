@extends('master')

@section('title', 'Detail Mahasiswa')

@section('content')
    <div class="flex w-full grow">
        {{-- Sidebar --}}
        @include('components.sidebar')

        <div class="flex flex-col w-full bg-putih">
            {{-- Profil User di Header --}}
            @include('components.header')

            {{-- Toast Notification --}}
            <x-notification.toast-notification />

            {{-- Content --}}
            <div class="flex flex-col px-6 pb-6">
                {{-- Header dengan tombol kembali --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('mahasiswa.index') }}" 
                           class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="ph ph-arrow-left text-lg"></i>
                        </a>
                        <h2 class="text-2xl font-semibold text-hitam">Detail Mahasiswa</h2>
                    </div>
                </div>

                {{-- Card Detail Mahasiswa --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    {{-- Header Card --}}
                    <div class="bg-brand-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-biru-600 rounded-full flex items-center justify-center">
                                    <i class="ph ph-student text-2xl text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-hitam">{{ $mahasiswa->nama }}</h3>
                                    <p class="text-gray-600">NRP: {{ $mahasiswa->nrp }}</p>
                                </div>
                            </div>
                            
                            {{-- Status Badge --}}
                            <span class="px-3 py-1 text-sm font-medium rounded-full
                                @if($mahasiswa->status == 'aktif') bg-green-100 text-green-800
                                @elseif($mahasiswa->status == 'cuti') bg-yellow-100 text-yellow-800
                                @elseif($mahasiswa->status == 'lulus') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $mahasiswa->status_formatted }}
                            </span>
                        </div>
                    </div>

                    {{-- Content Card --}}
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Informasi Personal --}}
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-hitam border-b border-gray-200 pb-2">
                                    Informasi Personal
                                </h4>
                                
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-user text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                            <p class="text-hitam">{{ $mahasiswa->nama }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-identification-card text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">NRP</label>
                                            <p class="text-hitam font-mono">{{ $mahasiswa->nrp }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-gender-intersex text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                                            <p class="text-hitam">{{ $mahasiswa->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-phone text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">No HP</label>
                                            <p class="text-hitam">{{ $mahasiswa->no_hp }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Informasi Akademik --}}
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-hitam border-b border-gray-200 pb-2">
                                    Informasi Akademik
                                </h4>
                                
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-graduation-cap text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Kelas</label>
                                            <p class="text-hitam">{{ $mahasiswa->kelas ? $mahasiswa->kelas->prodi . ' ' . $mahasiswa->kelas->paralel : '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-calendar text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Semester</label>
                                            <p class="text-hitam">{{ $mahasiswa->semester }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-flag text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status</label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                                @if($mahasiswa->status == 'aktif') bg-green-100 text-green-800
                                                @elseif($mahasiswa->status == 'cuti') bg-yellow-100 text-yellow-800
                                                @elseif($mahasiswa->status == 'lulus') bg-blue-100 text-blue-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ $mahasiswa->status_formatted }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-map-pin text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                            <p class="text-hitam">{{ $mahasiswa->alamat }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    <i class="ph ph-info mr-1"></i>
                                    Data mahasiswa dapat diubah melalui tombol edit di samping
                                </div>
                                
                                <div class="flex gap-2">
                                    <a href="{{ route('mahasiswa.index') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-biru-500">
                                        <i class="ph ph-list mr-2"></i>
                                        Kembali ke Daftar
                                    </a>
                                    
                                    @if (auth()->user()->role === 'admin')
                                        <a href="{{ route('mahasiswa.edit', $mahasiswa->id_mahasiswa) }}"
                                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-biru-600 hover:bg-biru-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-biru-500">
                                            <i class="ph ph-pencil-simple mr-2"></i>
                                            Edit Mahasiswa
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection