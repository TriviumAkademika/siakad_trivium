@extends('master')

@section('title', 'Jadwal')

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
            <div class="flex flex-row px-6 pb-6 space-x-6">
                <div class="flex flex-col grow items-end space-y-4">

                    {{-- Info Filter Otomatis --}}
                    @if (auth()->user()->hasRole('dosen'))
                        <div class="w-full bg-green-50 border border-green-200 rounded-md p-3">
                            <div class="flex items-center">
                                <i class="ph ph-user-check text-green-500 mr-2"></i>
                                <span class="text-green-700 text-sm">
                                    <strong>Filter Otomatis:</strong> Menampilkan jadwal mengajar Anda sebagai
                                    {{ auth()->user()->dosen->nama_dosen ?? 'Dosen' }}
                                </span>
                            </div>
                        </div>
                    @elseif (auth()->user()->hasRole('mahasiswa'))
                        <div class="w-full bg-blue-50 border border-blue-200 rounded-md p-3">
                            <div class="flex items-center">
                                <i class="ph ph-graduation-cap text-blue-500 mr-2"></i>
                                <span class="text-blue-700 text-sm">
                                    <strong>Filter Otomatis:</strong> Menampilkan jadwal kuliah Anda yang sudah disetujui
                                    sebagai
                                    {{ auth()->user()->mahasiswa->nama ?? 'Mahasiswa' }}
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Search, Filter, dan Tombol Tambah Jadwal --}}
                    <div class="flex justify-between items-center w-full gap-4">
                        <div class="flex items-center gap-4 flex-1">
                            {{-- Search Form --}}
                            <form method="GET" action="{{ route('jadwal.index') }}" class="flex-1 max-w-md"
                                id="searchForm">
                                <div class="relative">
                                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                        placeholder="Cari mata kuliah, dosen, kelas, atau ruangan..."
                                        class="w-full px-4 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-magnifying-glass text-gray-400"></i>
                                    </div>
                                    @if (request('search'))
                                        <button type="button" onclick="clearSearch()"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <i class="ph ph-x text-gray-400 hover:text-gray-600"></i>
                                        </button>
                                    @endif
                                </div>
                                {{-- Preserve filters --}}
                                @if (request('hari'))
                                    @foreach (request('hari') as $hari)
                                        <input type="hidden" name="hari[]" value="{{ $hari }}">
                                    @endforeach
                                @endif
                                @if (request('prodi'))
                                    <input type="hidden" name="prodi" value="{{ request('prodi') }}">
                                @endif
                            </form>

                            {{-- Filter --}}
                            <form method="GET" action="{{ route('jadwal.index') }}" id="filterForm"
                                class="flex items-center gap-3">
                                {{-- Preserve search query --}}
                                @if (request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif

                                {{-- Filter Hari --}}
                                <div class="relative">
                                    <button id="hariFilterButton" type="button"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <i class="ph ph-calendar mr-2 text-sm"></i>
                                        Filter Hari
                                        @if (request('hari'))
                                            <span class="ml-2 bg-blue-500 text-white px-1.5 py-0.5 rounded-full text-xs">
                                                {{ is_array(request('hari')) ? count(request('hari')) : 1 }}
                                            </span>
                                        @endif
                                        <i class="ph ph-caret-down ml-1 text-xs"></i>
                                    </button>

                                    {{-- Dropdown Filter Hari --}}
                                    <div id="hariFilterDropdown"
                                        class="hidden absolute left-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                                        <div class="p-4">
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Pilih Hari</h4>
                                            <div class="space-y-3">
                                                @php
                                                    $hariOptions = [
                                                        'Senin',
                                                        'Selasa',
                                                        'Rabu',
                                                        'Kamis',
                                                        'Jumat',
                                                        'Sabtu',
                                                    ];
                                                    $hariColors = [
                                                        'Senin' => 'bg-red-100 text-red-800',
                                                        'Selasa' => 'bg-orange-100 text-orange-800',
                                                        'Rabu' => 'bg-yellow-100 text-yellow-800',
                                                        'Kamis' => 'bg-green-100 text-green-800',
                                                        'Jumat' => 'bg-blue-100 text-blue-800',
                                                        'Sabtu' => 'bg-purple-100 text-purple-800',
                                                    ];
                                                @endphp
                                                @foreach ($hariOptions as $hari)
                                                    <div class="flex items-center">
                                                        <input type="checkbox" name="hari[]"
                                                            id="hari-{{ strtolower($hari) }}" value="{{ $hari }}"
                                                            {{ is_array(request('hari')) && in_array($hari, request('hari')) ? 'checked' : '' }}
                                                            class="hari-filter w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                        <label for="hari-{{ strtolower($hari) }}"
                                                            class="ml-3 flex items-center">
                                                            <span
                                                                class="text-sm font-medium text-gray-900">{{ $hari }}</span>
                                                            <span
                                                                class="ml-2 text-xs font-medium {{ $hariColors[$hari] }} px-2 py-0.5 rounded">{{ $hari }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div
                                                class="flex justify-between items-center mt-4 pt-3 border-t border-gray-200">
                                                <button id="clearHariAll" type="button"
                                                    class="text-sm text-gray-500 hover:text-gray-700">
                                                    Clear All
                                                </button>
                                                <div class="flex gap-2">
                                                    <button type="button" onclick="closeHariFilter()"
                                                        class="px-3 py-1.5 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                                        Cancel
                                                    </button>
                                                    <button id="applyHariFilter" type="button"
                                                        class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                                        Apply
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Filter Prodi (hanya tampil jika ada lebih dari 1 prodi atau user adalah admin) --}}
                                @if ($prodiList->count() > 1 || auth()->user()->hasRole('admin'))
                                    <div class="relative">
                                        <select name="prodi" id="prodiFilter"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            onchange="this.form.submit()">
                                            <option value="">
                                                @if (auth()->user()->hasRole('dosen'))
                                                    Semua Prodi Anda
                                                @elseif (auth()->user()->hasRole('mahasiswa'))
                                                    Semua Prodi Kuliah Anda
                                                @else
                                                    Semua Prodi
                                                @endif
                                            </option>
                                            @foreach ($prodiList as $prodi)
                                                <option value="{{ $prodi }}"
                                                    {{ request('prodi') == $prodi ? 'selected' : '' }}>
                                                    {{ $prodi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </form>
                        </div>

                        {{-- PERMISSION UNTUK ADMIN --}}
                        {{-- Button Tambah Jadwal --}}
                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('jadwal.create') }}">
                                <x-button.submit icon="ph ph-plus">
                                    Tambah Jadwal
                                </x-button.submit>
                            </a>
                        @endif
                    </div>

                    {{-- Info hasil pencarian --}}
                    @if (request('search'))
                        <div class="w-full bg-blue-50 border border-blue-200 rounded-md p-3">
                            <div class="flex items-center">
                                <i class="ph ph-info text-blue-500 mr-2"></i>
                                <span class="text-blue-700 text-sm">
                                    Menampilkan hasil pencarian untuk "<strong>{{ request('search') }}</strong>"
                                    ({{ $jadwal->total() }} data ditemukan)
                                </span>
                                <a href="{{ route('jadwal.index') }}"
                                    class="ml-2 text-blue-600 hover:text-blue-800 text-sm underline">
                                    Hapus Pencarian
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Active Filters Display --}}
                    @if (request('hari') || request('prodi'))
                        <div class="flex items-center flex-wrap gap-2 w-full">
                            <span class="text-sm text-gray-600">Filter aktif:</span>

                            {{-- Hari filters --}}
                            @if (request('hari'))
                                @foreach ((array) request('hari') as $hari)
                                    @php
                                        $hariColors = [
                                            'Senin' => 'bg-red-100 text-red-800',
                                            'Selasa' => 'bg-orange-100 text-orange-800',
                                            'Rabu' => 'bg-yellow-100 text-yellow-800',
                                            'Kamis' => 'bg-green-100 text-green-800',
                                            'Jumat' => 'bg-blue-100 text-blue-800',
                                            'Sabtu' => 'bg-purple-100 text-purple-800',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full {{ $hariColors[$hari] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $hari }}
                                        <a href="{{ request()->fullUrlWithQuery(['hari' => array_diff((array) request('hari'), [$hari])]) }}"
                                            class="ml-1.5 text-current hover:text-gray-600">
                                            <i class="ph ph-x text-xs"></i>
                                        </a>
                                    </span>
                                @endforeach
                            @endif

                            {{-- Prodi filter --}}
                            @if (request('prodi'))
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                                    Prodi: {{ request('prodi') }}
                                    <a href="{{ request()->fullUrlWithQuery(['prodi' => '']) }}"
                                        class="ml-1.5 text-current hover:
                                        {{-- Lanjutan dari bagian yang terpotong --}}
                                    <a href="{{ request()->fullUrlWithQuery(['prodi' => '']) }}"
                                        class="ml-1.5 text-current hover:text-gray-600">
                                        <i class="ph ph-x text-xs"></i>
                                    </a>
                                </span>
                            @endif

                            {{-- Clear all filters --}}
                            @if (request('hari') || request('prodi'))
                                <a href="{{ route('jadwal.index', ['search' => request('search')]) }}"
                                    class="text-sm text-red-600 hover:text-red-800 underline">
                                    Hapus Semua Filter
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Table Container --}}
                    <div class="bg-white rounded-lg shadow overflow-hidden w-full">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mata Kuliah
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kelas
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Dosen
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Waktu
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ruangan
                                        </th>
                                        @if (auth()->user()->hasRole('admin'))
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($jadwal as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->matkul->nama_matkul }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $item->matkul->kode_matkul }} • {{ $item->matkul->sks }} SKS
                                                    </div>
                                                    @if ($item->matkul->jenis)
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1 w-fit">
                                                            {{ $item->matkul->jenis }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->kelas->prodi }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        Kelas {{ $item->kelas->paralel }} • Sem
                                                        {{ $item->kelas->semester }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->dosen->nama_dosen }}
                                                    </div>
                                                    @if ($item->dosen2)
                                                        <div class="text-sm text-gray-500">
                                                            {{ $item->dosen2->nama_dosen }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    @php
                                                        $hariColors = [
                                                            'Senin' => 'bg-red-100 text-red-800',
                                                            'Selasa' => 'bg-orange-100 text-orange-800',
                                                            'Rabu' => 'bg-yellow-100 text-yellow-800',
                                                            'Kamis' => 'bg-green-100 text-green-800',
                                                            'Jumat' => 'bg-blue-100 text-blue-800',
                                                            'Sabtu' => 'bg-purple-100 text-purple-800',
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $hariColors[$item->waktu->hari] ?? 'bg-gray-100 text-gray-800' }} mb-1 w-fit">
                                                        {{ $item->waktu->hari }}
                                                    </span>
                                                    <div class="text-sm text-gray-900">
                                                        {{ $item->waktu->jam_mulai }} - {{ $item->waktu->jam_selesai }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->ruangan->kode_ruangan }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $item->ruangan->nama_ruangan }}
                                                    </div>
                                                    @if ($item->ruangan->kapasitas)
                                                        <div class="text-xs text-gray-400">
                                                            Kapasitas: {{ $item->ruangan->kapasitas }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            @if (auth()->user()->hasRole('admin'))
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex items-center justify-end space-x-2">
                                                        <a href="{{ route('jadwal.edit', $item->id_jadwal) }}"
                                                            class="text-indigo-600 hover:text-indigo-900">
                                                            <i class="ph ph-pencil-simple"></i>
                                                        </a>
                                                        <form action="{{ route('jadwal.destroy', $item->id_jadwal) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')"
                                                            class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-red-600 hover:text-red-900">
                                                                <i class="ph ph-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ auth()->user()->hasRole('admin') ? '6' : '5' }}"
                                                class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <i class="ph ph-calendar-x text-gray-400 text-4xl mb-2"></i>
                                                    <h3 class="text-sm font-medium text-gray-900 mb-1">Tidak ada jadwal
                                                        ditemukan</h3>
                                                    <p class="text-sm text-gray-500">
                                                        @if (request('search'))
                                                            Coba ubah kata kunci pencarian atau hapus filter yang aktif.
                                                        @elseif (auth()->user()->hasRole('dosen'))
                                                            Anda belum memiliki jadwal mengajar yang terdaftar.
                                                        @elseif (auth()->user()->hasRole('mahasiswa'))
                                                            Anda belum memiliki jadwal kuliah yang disetujui. Silakan
                                                            hubungi admin untuk informasi lebih lanjut.
                                                        @else
                                                            Belum ada jadwal yang ditambahkan ke sistem.
                                                        @endif
                                                    </p>
                                                    @if (auth()->user()->hasRole('admin') && !request('search'))
                                                        <a href="{{ route('jadwal.create') }}"
                                                            class="mt-3 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            <i class="ph ph-plus mr-2"></i>
                                                            Tambah Jadwal Pertama
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($jadwal->hasPages())
                            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 flex justify-between sm:hidden">
                                        @if ($jadwal->onFirstPage())
                                            <span
                                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-white cursor-not-allowed">
                                                Previous
                                            </span>
                                        @else
                                            <a href="{{ $jadwal->previousPageUrl() }}"
                                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                Previous
                                            </a>
                                        @endif

                                        @if ($jadwal->hasMorePages())
                                            <a href="{{ $jadwal->nextPageUrl() }}"
                                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                Next
                                            </a>
                                        @else
                                            <span
                                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-white cursor-not-allowed">
                                                Next
                                            </span>
                                        @endif
                                    </div>
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Menampilkan
                                                <span class="font-medium">{{ $jadwal->firstItem() ?? 0 }}</span>
                                                sampai
                                                <span class="font-medium">{{ $jadwal->lastItem() ?? 0 }}</span>
                                                dari
                                                <span class="font-medium">{{ $jadwal->total() }}</span>
                                                hasil
                                            </p>
                                        </div>
                                        <div>
                                            {{ $jadwal->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk Filter --}}
    <script>
        // Toggle dropdown filter hari
        document.getElementById('hariFilterButton').addEventListener('click', function() {
            const dropdown = document.getElementById('hariFilterDropdown');
            dropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const button = document.getElementById('hariFilterButton');
            const dropdown = document.getElementById('hariFilterDropdown');

            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Function untuk close dropdown
        function closeHariFilter() {
            document.getElementById('hariFilterDropdown').classList.add('hidden');
        }

        // Apply filter hari
        document.getElementById('applyHariFilter').addEventListener('click', function() {
            document.getElementById('filterForm').submit();
        });

        // Clear all hari filters
        document.getElementById('clearHariAll').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.hari-filter');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        });

        // Auto-submit ketika checkbox berubah
        document.querySelectorAll('.hari-filter').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Optional: bisa langsung submit atau tunggu tombol apply
                // document.getElementById('filterForm').submit();
            });
        });

        // Clear search function
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchForm').submit();
        }

        // Auto-submit search dengan delay
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                document.getElementById('searchForm').submit();
            }, 500); // 500ms delay
        });
    </script>
@endsection
