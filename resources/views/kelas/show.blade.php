{{-- SHOW.BLADE.PHP (UPDATED) --}}
@extends('master')

@section('title', 'Detail Kelas')

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
                        <a href="{{ route('kelas.index') }}" 
                           class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="ph ph-arrow-left text-lg"></i>
                        </a>
                        <h2 class="text-2xl font-semibold text-hitam">Detail Kelas</h2>
                    </div>
                    
                    {{-- Tombol Edit --}}
                    {{-- <a href="{{ route('kelas.edit', $kelas->id_kelas) }}">
                        <x-button.submit icon="ph ph-pencil-simple">
                            Edit Kelas
                        </x-button.submit>
                    </a> --}}
                </div>

                {{-- Card Detail Kelas --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                    {{-- Header Card --}}
                    <div class="bg-brand-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-biru-600 rounded-full flex items-center justify-center">
                                    <i class="ph ph-graduation-cap text-2xl text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-hitam">
                                        {{ $kelas->prodi }} {{ $kelas->tahun_masuk }}{{ $kelas->paralel }}
                                    </h3>
                                    <p class="text-gray-600">Wali Kelas: {{ $kelas->dosen->nama_dosen ?? 'Belum ditentukan' }}</p>
                                </div>
                            </div>
                            
                            {{-- Status Badge --}}
                            <div class="flex flex-col items-end gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($kelas->status == 'AKTIF') bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ $kelas->status }}
                                </span>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-biru-600">{{ $totalMahasiswa }}</div>
                                    <div class="text-sm text-gray-600">Total Mahasiswa</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Content Card --}}
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Informasi Kelas --}}
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-hitam border-b border-gray-200 pb-2">
                                    Informasi Kelas
                                </h4>
                                
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-graduation-cap text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                                            <p class="text-hitam">{{ $kelas->prodi }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-calendar text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Tahun Masuk</label>
                                            <p class="text-hitam">{{ $kelas->tahun_masuk }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-list text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Paralel</label>
                                            <p class="text-hitam font-mono">{{ $kelas->paralel }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-tag text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status</label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                                @if($kelas->status == 'AKTIF') bg-green-100 text-green-800
                                                @else bg-blue-100 text-blue-800
                                                @endif">
                                                {{ $kelas->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Informasi Wali Kelas & Statistik --}}
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-hitam border-b border-gray-200 pb-2">
                                    Wali Kelas & Statistik
                                </h4>
                                
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-user text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Wali Kelas</label>
                                            <p class="text-hitam">{{ $kelas->dosen->nama_dosen ?? 'Belum ditentukan' }}</p>
                                            @if($kelas->dosen)
                                                <p class="text-sm text-gray-500">NIP: {{ $kelas->dosen->nip }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 flex items-center justify-center mt-0.5">
                                            <i class="ph ph-users text-gray-500"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jumlah Mahasiswa</label>
                                            <div class="flex gap-4 mt-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                    Total: {{ $totalMahasiswa }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    L: {{ $mahasiswaLaki }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-pink-100 text-pink-800">
                                                    P: {{ $mahasiswaPerempuan }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Mahasiswa --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    {{-- Header Tabel --}}
                    <div class="bg-brand-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h4 class="text-lg font-semibold text-hitam">Daftar Mahasiswa</h4>
                            
                            {{-- Search Box untuk Mahasiswa --}}
                            <div class="relative max-w-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ph ph-magnifying-glass text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       id="searchMahasiswa"
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-biru-500 focus:border-biru-500 text-sm" 
                                       placeholder="Cari... ">
                            </div>
                        </div>
                    </div>

                    {{-- Content Tabel --}}
                    <div class="overflow-x-auto">
                        @if($mahasiswa->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NRP</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No HP</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="mahasiswaTableBody">
                                    @foreach ($mahasiswa as $index => $mhs)
                                        <tr class="mahasiswa-row hover:bg-gray-50" 
                                            data-search="{{ strtolower($mhs->nama . ' ' . $mhs->nrp . ' ' . $mhs->no_hp) }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $mhs->nama }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">{{ $mhs->nrp }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mhs->no_hp }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($mhs->gender == 'L') bg-blue-100 text-blue-800
                                                    @else bg-pink-100 text-pink-800
                                                    @endif">
                                                    {{ $mhs->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Semester {{ $mhs->semester }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- No Results Message --}}
                            <div id="noMahasiswaResults" class="hidden w-full text-center py-8 text-gray-500">
                                <i class="ph ph-magnifying-glass text-4xl mb-2"></i>
                                <p>Tidak ada mahasiswa yang sesuai dengan pencarian.</p>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="ph ph-users text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada mahasiswa</h3>
                                <p class="text-gray-500 mb-4">Kelas ini belum memiliki mahasiswa yang terdaftar.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-6 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        <i class="ph ph-info mr-1"></i>
                        Data kelas dan mahasiswa dapat diubah melalui menu masing-masing
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('kelas.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-biru-500">
                            <i class="ph ph-list mr-2"></i>
                            Kembali ke Daftar
                        </a>
                        
                        <a href="{{ route('kelas.edit', $kelas->id_kelas) }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-biru-600 hover:bg-biru-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-biru-500">
                            <i class="ph ph-pencil-simple mr-2"></i>
                            Edit Kelas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk Search Mahasiswa --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchMahasiswa = document.getElementById('searchMahasiswa');
            const mahasiswaRows = document.querySelectorAll('.mahasiswa-row');
            const noResults = document.getElementById('noMahasiswaResults');
            const tableBody = document.getElementById('mahasiswaTableBody');

            if (searchMahasiswa) {
                searchMahasiswa.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    let visibleCount = 0;

                    mahasiswaRows.forEach((row, index) => {
                        const searchData = row.getAttribute('data-search');
                        
                        if (searchTerm === '' || searchData.includes(searchTerm)) {
                            row.style.display = '';
                            // Update row number
                            const numberCell = row.querySelector('td:first-child');
                            if (numberCell) {
                                numberCell.textContent = visibleCount + 1;
                            }
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Show/hide no results message
                    if (noResults) {
                        if (visibleCount === 0 && mahasiswaRows.length > 0) {
                            noResults.classList.remove('hidden');
                            if (tableBody) tableBody.parentElement.classList.add('hidden');
                        } else {
                            noResults.classList.add('hidden');
                            if (tableBody) tableBody.parentElement.classList.remove('hidden');
                        }
                    }
                });
            }
        });
    </script>
@endsection
