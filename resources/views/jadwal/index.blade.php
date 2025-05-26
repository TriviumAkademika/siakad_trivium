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

                    {{-- Search, Filter, dan Tombol Tambah Jadwal --}}
                    <div class="flex justify-between items-center w-full gap-4">
                        <div class="flex items-center gap-4 flex-1">
                            {{-- Search Form --}}
                            <form method="GET" action="{{ route('jadwal.index') }}" class="flex-1 max-w-md" id="searchForm">
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
                                                    $hariOptions = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
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
                                                        <input type="checkbox" name="hari[]" id="hari-{{ strtolower($hari) }}" value="{{ $hari }}"
                                                            {{ is_array(request('hari')) && in_array($hari, request('hari')) ? 'checked' : '' }}
                                                            class="hari-filter w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                        <label for="hari-{{ strtolower($hari) }}" class="ml-3 flex items-center">
                                                            <span class="text-sm font-medium text-gray-900">{{ $hari }}</span>
                                                            <span class="ml-2 text-xs font-medium {{ $hariColors[$hari] }} px-2 py-0.5 rounded">{{ $hari }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-200">
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

                                {{-- Filter Prodi --}}
                                <div class="relative">
                                    <select name="prodi" id="prodiFilter" 
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        onchange="this.form.submit()">
                                        <option value="">Semua Prodi</option>
                                        @foreach ($prodiList as $prodi)
                                            <option value="{{ $prodi }}" {{ request('prodi') == $prodi ? 'selected' : '' }}>
                                                {{ $prodi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>

                        {{-- PERMISSION UNTUK ADMIN --}}
                        {{-- Button Tambah Jadwal --}}
                        @if (auth()->user()->role === 'admin')
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
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full {{ $hariColors[$hari] ?? 'bg-gray-100 text-gray-800' }}">
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
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                                    Prodi: {{ request('prodi') }}
                                    <a href="{{ request()->fullUrlWithQuery(['prodi' => '']) }}"
                                        class="ml-1.5 text-current hover:text-gray-600">
                                        <i class="ph ph-x text-xs"></i>
                                    </a>
                                </span>
                            @endif

                            <a href="{{ route('jadwal.index', array_diff_key(request()->query(), ['hari' => '', 'prodi' => ''])) }}"
                                class="text-sm text-blue-600 hover:text-blue-800 underline">
                                Hapus Semua
                            </a>
                        </div>
                    @endif

                    {{-- Tabel Data Jadwal --}}
                    @if ($jadwal->count() > 0)
                        <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                            <thead class="bg-brand-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-hitam">#</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-hitam">Kelas</th>
                                    <th class="w-48 px-4 py-3 text-center text-sm font-semibold text-hitam">Mata Kuliah</th>
                                    <th class="w-40 px-4 py-3 text-center text-sm font-semibold text-hitam">Dosen</th>
                                    <th class="w-40 px-4 py-3 text-center text-sm font-semibold text-hitam">Dosen Pendamping</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-hitam">Ruangan</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-hitam">Waktu</th>
                                    {{-- PERMISSION UNTUK ADMIN --}}
                                    {{-- Kolom Aksi --}}
                                    @if (auth()->user()->role === 'admin')
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-hitam">Aksi</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody class="bg-putih divide-y divide-gray-200">
                                @foreach ($jadwal as $index => $j)
                                    <tr class="hover:bg-gray-100">
                                        <x-table.table-td>{{ $jadwal->firstItem() + $index }}</x-table.table-td>
                                        <x-table.table-td>{{ $j->kelas->prodi }}-{{ $j->kelas->paralel }}</x-table.table-td>
                                        <x-table.table-td>{{ $j->matkul->jenis }} -
                                            {{ $j->matkul->nama_matkul }}</x-table.table-td>
                                        <x-table.table-td>{{ $j->dosen->nama_dosen }}</x-table.table-td>
                                        <x-table.table-td>{{ $j->dosen2 ? $j->dosen2->nama_dosen : '-' }}</x-table.table-td>
                                        <x-table.table-td class="text-center">{{ $j->ruangan->kode_ruangan }}</x-table.table-td>
                                        <x-table.table-td class="text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                    @if($j->waktu->hari == 'Senin') bg-red-100 text-red-800
                                                    @elseif($j->waktu->hari == 'Selasa') bg-orange-100 text-orange-800
                                                    @elseif($j->waktu->hari == 'Rabu') bg-yellow-100 text-yellow-800
                                                    @elseif($j->waktu->hari == 'Kamis') bg-green-100 text-green-800
                                                    @elseif($j->waktu->hari == 'Jumat') bg-blue-100 text-blue-800
                                                    @else bg-purple-100 text-purple-800 @endif">
                                                    {{ $j->waktu->hari }}
                                                </span>
                                                <span class="text-sm mt-1">
                                                    {{ substr($j->waktu->jam_mulai, 0, 5) }} -
                                                    {{ substr($j->waktu->jam_selesai, 0, 5) }}
                                                </span>
                                            </div>
                                        </x-table.table-td>
                                        {{-- PERMISSION UNTUK ADMIN --}}
                                        @if (auth()->user()->role === 'admin')
                                            <td class="px-2 py-2 text-center text-sm text-hitam">
                                                <div class="flex justify-center items-center space-x-1">
                                                    {{-- Button Edit --}}
                                                    <a href="{{ route('jadwal.edit', $j->id_jadwal) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-brand-700 hover:bg-brand-800 text-white text-sm rounded"
                                                        title="Edit">
                                                        <i class="ph ph-pencil-simple"></i>
                                                    </a>

                                                    {{-- Button Hapus --}}
                                                    {{-- <form action="{{ route('jadwal.destroy', $j->id_jadwal) }}" method="POST"
                                                        class="inline-block"
                                                        onsubmit="return confirm('Anda yakin ingin menghapus jadwal ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-merah-500 text-white text-sm rounded hover:bg-merah-600"
                                                            title="Hapus">
                                                            <i class="ph ph-trash-simple"></i>
                                                        </button>
                                                    </form> --}}
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div class="w-full">
                            {{ $jadwal->links() }}
                        </div>
                    @else
                        {{-- Pesan jika tidak ada data --}}
                        <div class="w-full text-center py-12 bg-white rounded-lg shadow">
                            <i class="ph ph-calendar-blank text-6xl text-gray-300 mb-4"></i>
                            @if (request('search') || request('hari') || request('prodi'))
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada hasil yang ditemukan</h3>
                                <p class="text-gray-500 mb-4">
                                    Tidak ditemukan jadwal yang sesuai dengan filter atau pencarian
                                </p>
                                <a href="{{ route('jadwal.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-brand-600 text-white text-sm rounded-md hover:bg-brand-700 transition-colors duration-200">
                                    <i class="ph ph-arrow-left mr-2"></i>
                                    Lihat Semua Jadwal
                                </a>
                            @else
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada jadwal</h3>
                                <p class="text-gray-500 mb-4">Mulai dengan menambahkan jadwal pertama</p>
                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('jadwal.create') }}"
                                        class="inline-flex items-center px-4 py-2 bg-brand-600 text-white text-sm rounded-md hover:bg-brand-700 transition-colors duration-200">
                                        <i class="ph ph-plus mr-2"></i>
                                        Tambah Jadwal
                                    </a>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk menangani form submissions --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hari filter dropdown elements
            const hariFilterButton = document.getElementById('hariFilterButton');
            const hariFilterDropdown = document.getElementById('hariFilterDropdown');
            const hariFilters = document.querySelectorAll('.hari-filter');
            const clearHariAllBtn = document.getElementById('clearHariAll');
            const applyHariFilterBtn = document.getElementById('applyHariFilter');
            const filterForm = document.getElementById('filterForm');

            // Toggle hari dropdown
            if (hariFilterButton) {
                hariFilterButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    hariFilterDropdown.classList.toggle('hidden');
                });
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (hariFilterButton && hariFilterDropdown && 
                    !hariFilterButton.contains(event.target) && 
                    !hariFilterDropdown.contains(event.target)) {
                    hariFilterDropdown.classList.add('hidden');
                }
            });

            // Close hari filter function
            window.closeHariFilter = function() {
                if (hariFilterDropdown) {
                    hariFilterDropdown.classList.add('hidden');
                }
            };

            // Clear all hari filters
            if (clearHariAllBtn) {
                clearHariAllBtn.addEventListener('click', function() {
                    hariFilters.forEach(filter => {
                        filter.checked = false;
                    });
                });
            }

            // Apply hari filter
            if (applyHariFilterBtn && filterForm) {
                applyHariFilterBtn.addEventListener('click', function() {
                    filterForm.submit();
                });
            }

            // Auto submit search form on input change with debounce
            let searchTimeout;
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        document.getElementById('searchForm').submit();
                    }, 500); // 500ms debounce
                });

                // Handle enter key in search input
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('searchForm').submit();
                    }
                });
            }
        });

        // Clear search
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchForm').submit();
        }

        // Clear all search and filters
        function clearAll() {
            window.location.href = "{{ route('jadwal.index') }}";
        }
    </script>
@endsection