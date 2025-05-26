@extends('master')

@section('title', 'Mata Kuliah')

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

                    {{-- Search, Filter, dan Tombol Tambah mata kuliah --}}
                    <div class="flex justify-between items-center w-full gap-4">
                        <div class="flex items-center gap-4 flex-1">
                            {{-- Search Form --}}
                            <form method="GET" action="{{ route('matkul.index') }}" class="flex-1 max-w-md" id="searchForm">
                                <div class="relative">
                                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                        placeholder="Cari nama mata kuliah, jenis, atau SKS..."
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
                                {{-- Preserve jenis filters --}}
                                @if (request('jenis'))
                                    @foreach (request('jenis') as $jenis)
                                        <input type="hidden" name="jenis[]" value="{{ $jenis }}">
                                    @endforeach
                                @endif
                            </form>

                            {{-- Jenis Filter --}}
                            <form method="GET" action="{{ route('matkul.index') }}" id="filterForm"
                                class="flex items-center gap-3">
                                {{-- Preserve search query --}}
                                @if (request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif

                                {{-- Filter Jenis --}}
                                <div class="relative">
                                    <button id="filterButton" type="button"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <i class="ph ph-funnel mr-2 text-sm"></i>
                                        Filter Jenis
                                        @if (request('jenis'))
                                            <span class="ml-2 bg-blue-500 text-white px-1.5 py-0.5 rounded-full text-xs">
                                                {{ is_array(request('jenis')) ? count(request('jenis')) : 1 }}
                                            </span>
                                        @endif
                                        <i class="ph ph-caret-down ml-1 text-xs"></i>
                                    </button>

                                    {{-- Dropdown Filter --}}
                                    <div id="filterDropdown"
                                        class="hidden absolute left-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                                        <div class="p-4">
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Pilih Jenis Mata Kuliah</h4>
                                            <div class="space-y-3">
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="jenis[]" id="wajib" value="Wajib"
                                                        {{ is_array(request('jenis')) && in_array('Wajib', request('jenis')) ? 'checked' : '' }}
                                                        class="jenis-filter w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                    <label for="wajib" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Wajib</span>
                                                        <span
                                                            class="ml-2 text-xs font-medium text-blue-800 bg-blue-100 px-2 py-0.5 rounded">WAJIB</span>
                                                    </label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="jenis[]" id="pilihan" value="Pilihan"
                                                        {{ is_array(request('jenis')) && in_array('Pilihan', request('jenis')) ? 'checked' : '' }}
                                                        class="jenis-filter w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                                    <label for="pilihan" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Pilihan</span>
                                                        <span
                                                            class="ml-2 text-xs font-medium text-green-800 bg-green-100 px-2 py-0.5 rounded">PILIHAN</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div
                                                class="flex justify-between items-center mt-4 pt-3 border-t border-gray-200">
                                                <button id="clearAll" type="button"
                                                    class="text-sm text-gray-500 hover:text-gray-700">
                                                    Clear All
                                                </button>
                                                <div class="flex gap-2">
                                                    <button type="button" onclick="closeFilter()"
                                                        class="px-3 py-1.5 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                                        Cancel
                                                    </button>
                                                    <button id="applyFilter" type="button"
                                                        class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                                        Apply
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- Tombol Tambah Mata Kuliah --}}
                        <a href="{{ route('matkul.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah Mata Kuliah
                            </x-button.submit>
                        </a>
                    </div>

                    {{-- Info hasil pencarian --}}
                    @if (request('search'))
                        <div class="w-full bg-blue-50 border border-blue-200 rounded-md p-3">
                            <div class="flex items-center">
                                <i class="ph ph-info text-blue-500 mr-2"></i>
                                <span class="text-blue-700 text-sm">
                                    Menampilkan hasil pencarian untuk "<strong>{{ request('search') }}</strong>"
                                    ({{ $matkul->total() ?? count($matkul) }} data ditemukan)
                                </span>
                                <a href="{{ route('matkul.index') }}"
                                    class="ml-2 text-blue-600 hover:text-blue-800 text-sm underline">
                                    Hapus Pencarian
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Active Filters Display --}}
                    @if (request('jenis'))
                        <div class="flex items-center flex-wrap gap-2 w-full">
                            <span class="text-sm text-gray-600">Filter aktif:</span>
                            @foreach ((array) request('jenis') as $jenis)
                                @php
                                    $jenisColors = [
                                        'Wajib' => 'bg-blue-100 text-blue-800',
                                        'Pilihan' => 'bg-green-100 text-green-800',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full {{ $jenisColors[$jenis] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $jenis }}
                                    <a href="{{ request()->fullUrlWithQuery(['jenis' => array_diff((array) request('jenis'), [$jenis])]) }}"
                                        class="ml-1.5 text-current hover:text-gray-600">
                                        <i class="ph ph-x text-xs"></i>
                                    </a>
                                </span>
                            @endforeach
                            <a href="{{ route('matkul.index', array_diff_key(request()->query(), ['jenis' => ''])) }}"
                                class="text-sm text-blue-600 hover:text-blue-800 underline">
                                Hapus Semua
                            </a>
                        </div>
                    @endif

                    {{-- Tabel Data Mata Kuliah --}}
                    @if (count($matkul) > 0)
                        <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                            <thead class="bg-brand-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Nama Mata Kuliah</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Jenis</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">SKS</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Kapasitas Kelas</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Action</th>
                                </tr>
                            </thead>

                            <tbody class="bg-putih divide-y divide-gray-200">
                                @foreach ($matkul as $index => $m)
                                    <tr>
                                        <x-table.table-td>{{ $index + 1 }}</x-table.table-td>
                                        <x-table.table-td>{{ $m->nama_matkul }}</x-table.table-td>
                                        <x-table.table-td class="text-center">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full
                                                @if ($m->jenis == 'Wajib') bg-blue-100 text-blue-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ $m->jenis }}
                                            </span>
                                        </x-table.table-td>
                                        <x-table.table-td class="text-center">{{ $m->sks }}</x-table.table-td>
                                        <x-table.table-td class="text-center">{{ $m->kapasitas_kelas }}</x-table.table-td>
                                        <td class="px-2 py-2 text-sm text-hitam">
                                            <div class="flex justify-center items-center space-x-1">
                                                {{-- Button Show --}}
                                                {{-- <a href="{{ route('matkul.show', $m->id_matkul) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-green-600 text-white text-sm rounded hover:bg-green-700"
                                                    title="Lihat Detail">
                                                    <i class="ph ph-eye"></i>
                                                </a> --}}
                                                {{-- Button Edit --}}
                                                @if (auth()->user()->role === 'admin')
                                                    <a href="{{ route('matkul.edit', $m->id_matkul) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-biru-600 text-white text-sm rounded hover:bg-biru-700"
                                                        title="Edit">
                                                        <i class="ph ph-pencil-simple"></i>
                                                    </a>
                                                    {{-- Button Hapus --}}
                                                    <form action="{{ route('matkul.destroy', $m->id_matkul) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-600 text-white text-sm rounded hover:bg-red-700"
                                                            title="Hapus">
                                                            <i class="ph ph-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        @if (method_exists($matkul, 'links'))
                            <div class="w-full">
                                {{ $matkul->links() }}
                            </div>
                        @endif
                    @else
                        {{-- No Results Message --}}
                        <div class="w-full text-center py-8 text-gray-500">
                            <i class="ph ph-magnifying-glass text-4xl mb-2"></i>
                            <p>Tidak ada data mata kuliah yang sesuai dengan pencarian atau filter.</p>
                            @if (request('search') || request('jenis'))
                                <button onclick="clearAll()"
                                    class="mt-2 px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                    Tampilkan Semua Data
                                </button>
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
            // Filter dropdown elements
            const filterButton = document.getElementById('filterButton');
            const filterDropdown = document.getElementById('filterDropdown');
            const jenisFilters = document.querySelectorAll('.jenis-filter');
            const clearAllBtn = document.getElementById('clearAll');
            const applyFilterBtn = document.getElementById('applyFilter');
            const filterForm = document.getElementById('filterForm');

            // Toggle dropdown
            filterButton.addEventListener('click', function(e) {
                e.preventDefault();
                filterDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!filterButton.contains(event.target) && !filterDropdown.contains(event.target)) {
                    filterDropdown.classList.add('hidden');
                }
            });

            // Close filter function
            window.closeFilter = function() {
                filterDropdown.classList.add('hidden');
            };

            // Clear all filters
            clearAllBtn.addEventListener('click', function() {
                jenisFilters.forEach(filter => {
                    filter.checked = false;
                });
            });

            // Apply filter
            applyFilterBtn.addEventListener('click', function() {
                filterForm.submit();
            });

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
            window.location.href = "{{ route('matkul.index') }}";
        }
    </script>
@endsection
