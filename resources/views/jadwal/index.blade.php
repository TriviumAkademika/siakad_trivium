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

                    {{-- Info Filter Otomatis untuk Dosen --}}
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
                    @endif

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

                                {{-- Filter Prodi (hanya tampil jika ada lebih dari 1 prodi atau user adalah admin) --}}
                                @if ($prodiList->count() > 1 || auth()->user()->hasRole('admin'))
                                    <div class="relative">
                                        <select name="prodi" id="prodiFilter" 
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            onchange="this.form.submit()">
                                            <option value="">
                                                @if (auth()->user()->hasRole('dosen'))
                                                    Semua Prodi Anda
                                                @else
                                                    Semua Prodi
                                                @endif
                                            </option>
                                            @foreach ($prodiList as $prodi)
                                                <option value="{{ $prodi }}" {{ request('prodi') == $prodi ? 'selected' : '' }}>
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
                                    @if (auth()->user()->hasRole('admin'))
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-hitam">Aksi</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody class="bg-putih divide-y divide-gray-200">
                                @foreach ($jadwal as $index => $j)
                                    <tr class="hover:bg-gray-100">
                                        {{-- Nomor urut --}}
                                        <x-table.table-td>{{ $jadwal->firstItem() + $index }}</x-table.table-td>
                                        
                                        {{-- Kelas --}}
                                        <x-table.table-td class="text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="font-semibold text-blue-600">{{ $j->kelas->prodi ?? '-' }}</span>
                                                <span class="text-sm text-gray-500">{{ $j->kelas->paralel ?? '-' }}</span>
                                            </div>
                                        </x-table.table-td>

                                        {{-- Mata Kuliah --}}
                                        <x-table.table-td class="text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="font-semibold text-gray-900">{{ $j->matkul->nama_matkul ?? '-' }}</span>
                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                    {{ $j->matkul->jenis ?? '-' }}
                                                </span>
                                            </div>
                                        </x-table.table-td>

                                        {{-- Dosen Utama --}}
                                        <x-table.table-td class="text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="font-medium text-gray-900">{{ $j->dosen->nama_dosen ?? '-' }}</span>
                                                <span class="text-xs text-gray-500">(Utama)</span>
                                            </div>
                                        </x-table.table-td>

                                        {{-- Dosen Pendamping --}}
                                        <x-table.table-td class="text-center">
                                            @if ($j->dosen2)
                                                <div class="flex flex-col items-center">
                                                    <span class="font-medium text-gray-700">{{ $j->dosen2->nama_dosen }}</span>
                                                    <span class="text-xs text-gray-500">(Pendamping)</span>
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-sm">-</span>
                                            @endif
                                        </x-table.table-td>

                                        {{-- Ruangan --}}
                                        <x-table.table-td class="text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="font-semibold text-purple-600">{{ $j->ruangan->kode_ruangan ?? '-' }}</span>
                                                <span class="text-xs text-gray-500">{{ $j->ruangan->nama_ruangan ?? '-' }}</span>
                                            </div>
                                        </x-table.table-td>

                                        {{-- Waktu --}}
                                        <x-table.table-td class="text-center">
                                            <div class="flex flex-col items-center">
                                                @php
                                                    $hariColors = [
                                                        'Senin' => 'bg-red-100 text-red-800',
                                                        'Selasa' => 'bg-orange-100 text-orange-800',
                                                        'Rabu' => 'bg-yellow-100 text-yellow-800',
                                                        'Kamis' => 'bg-green-100 text-green-800',
                                                        'Jumat' => 'bg-blue-100 text-blue-800',
                                                        'Sabtu' => 'bg-purple-100 text-purple-800',
                                                    ];
                                                    $hariColor = $hariColors[$j->waktu->hari ?? ''] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="text-xs font-medium px-2 py-1 rounded-full {{ $hariColor }}">
                                                    {{ $j->waktu->hari ?? '-' }}
                                                </span>
                                                <span class="text-sm font-medium mt-1">
                                                    {{ $j->waktu->jam_mulai ?? '-' }} - {{ $j->waktu->jam_selesai ?? '-' }}
                                                </span>
                                            </div>
                                        </x-table.table-td>

                                        {{-- PERMISSION UNTUK ADMIN --}}
                                        {{-- Aksi --}}
                                        @if (auth()->user()->hasRole('admin'))
                                            <x-table.table-td class="text-center">
                                                <div class="flex justify-center items-center space-x-2">
                                                    {{-- Edit --}}
                                                    <a href="{{ route('jadwal.edit', $j->id_jadwal) }}"
                                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 hover:text-blue-700 transition-colors">
                                                        <i class="ph ph-pencil mr-1"></i>
                                                        Edit
                                                    </a>
                                                    
                                                    {{-- Delete --}}
                                                    <form method="POST" action="{{ route('jadwal.destroy', $j->id_jadwal) }}" 
                                                          class="inline-block"
                                                          onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded hover:bg-red-100 hover:text-red-700 transition-colors">
                                                            <i class="ph ph-trash mr-1"></i>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </x-table.table-td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div class="w-full mt-4">
                            {{ $jadwal->links() }}
                        </div>
                    @else
                        {{-- No data message --}}
                        <div class="w-full bg-white rounded-lg shadow">
                            <div class="flex flex-col items-center justify-center py-12">
                                <i class="ph ph-calendar-x text-gray-400 text-6xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada jadwal ditemukan</h3>
                                <p class="text-gray-500 text-center max-w-md">
                                    @if (request('search') || request('hari') || request('prodi'))
                                        Tidak ada jadwal yang sesuai dengan kriteria pencarian atau filter Anda.
                                        <br>
                                        <a href="{{ route('jadwal.index') }}" class="text-blue-600 hover:text-blue-800 underline mt-2 inline-block">
                                            Hapus filter dan lihat semua jadwal
                                        </a>
                                    @else
                                        @if (auth()->user()->hasRole('dosen'))
                                            Anda belum memiliki jadwal mengajar yang terdaftar.
                                        @else
                                            Belum ada jadwal yang terdaftar dalam sistem.
                                        @endif
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk Filter --}}
    <script>
        // Search functionality
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchForm').submit();
        }

        // Auto submit search after typing (debounced)
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
        });

        // Filter Hari Dropdown
        document.getElementById('hariFilterButton').addEventListener('click', function() {
            const dropdown = document.getElementById('hariFilterDropdown');
            dropdown.classList.toggle('hidden');
        });

        function closeHariFilter() {
            document.getElementById('hariFilterDropdown').classList.add('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const button = document.getElementById('hariFilterButton');
            const dropdown = document.getElementById('hariFilterDropdown');
            
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Clear all hari filters
        document.getElementById('clearHariAll').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.hari-filter');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        });

        // Apply hari filter
        document.getElementById('applyHariFilter').addEventListener('click', function() {
            document.getElementById('filterForm').submit();
        });

        // Auto-submit on checkbox change
        document.querySelectorAll('.hari-filter').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Auto submit after short delay to allow multiple selections
                setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 300);
            });
        });
    </script>
@endsection