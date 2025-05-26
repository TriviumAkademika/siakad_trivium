@extends('master')

@section('title', 'Dosen')

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

                    {{-- Search, Filter, dan Tombol Tambah dosen --}}
                    <div class="flex justify-between items-center w-full gap-4">
                        <div class="flex items-center gap-4 flex-1">
                            {{-- Search Form --}}
                            <form method="GET" action="{{ route('dosen.index') }}" class="flex-1 max-w-md" id="searchForm">
                                <div class="relative">
                                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                        placeholder="Cari nama, NIP, alamat, atau no HP..."
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
                                {{-- Preserve status filters --}}
                                @if (request('status'))
                                    @foreach (request('status') as $status)
                                        <input type="hidden" name="status[]" value="{{ $status }}">
                                    @endforeach
                                @endif
                            </form>

                            {{-- Status Filter --}}
                            <form method="GET" action="{{ route('dosen.index') }}" id="filterForm"
                                class="flex items-center gap-3">
                                {{-- Preserve search query --}}
                                @if (request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif

                                {{-- Filter Status --}}
                                <div class="relative">
                                    <button id="filterButton" type="button"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <i class="ph ph-funnel mr-2 text-sm"></i>
                                        Filter Status
                                        @if (request('status'))
                                            <span class="ml-2 bg-blue-500 text-white px-1.5 py-0.5 rounded-full text-xs">
                                                {{ is_array(request('status')) ? count(request('status')) : 1 }}
                                            </span>
                                        @endif
                                        <i class="ph ph-caret-down ml-1 text-xs"></i>
                                    </button>

                                    {{-- Dropdown Filter --}}
                                    <div id="filterDropdown"
                                        class="hidden absolute left-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                                        <div class="p-4">
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Pilih Status</h4>
                                            <div class="space-y-3">
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="status[]" id="aktif" value="AKTIF"
                                                        {{ is_array(request('status')) && in_array('AKTIF', request('status')) ? 'checked' : '' }}
                                                        class="status-filter w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                                    <label for="aktif" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Aktif</span>
                                                        <span
                                                            class="ml-2 text-xs font-medium text-green-800 bg-green-100 px-2 py-0.5 rounded">AKTIF</span>
                                                    </label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="status[]" id="cuti" value="CUTI"
                                                        {{ is_array(request('status')) && in_array('CUTI', request('status')) ? 'checked' : '' }}
                                                        class="status-filter w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500">
                                                    <label for="cuti" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Cuti</span>
                                                        <span
                                                            class="ml-2 text-xs font-medium text-yellow-800 bg-yellow-100 px-2 py-0.5 rounded">CUTI</span>
                                                    </label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="status[]" id="pensiun" value="PENSIUN"
                                                        {{ is_array(request('status')) && in_array('PENSIUN', request('status')) ? 'checked' : '' }}
                                                        class="status-filter w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                    <label for="pensiun" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Pensiun</span>
                                                        <span
                                                            class="ml-2 text-xs font-medium text-blue-800 bg-blue-100 px-2 py-0.5 rounded">PENSIUN</span>
                                                    </label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="status[]" id="nonaktif" value="NONAKTIF"
                                                        {{ is_array(request('status')) && in_array('NONAKTIF', request('status')) ? 'checked' : '' }}
                                                        class="status-filter w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                                                    <label for="nonaktif" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Non Aktif</span>
                                                        <span
                                                            class="ml-2 text-xs font-medium text-red-800 bg-red-100 px-2 py-0.5 rounded">NONAKTIF</span>
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

                        {{-- Tombol Tambah Dosen --}}
                        <a href="{{ route('dosen.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah Dosen
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
                                    ({{ $dosen->total() }} data ditemukan)
                                </span>
                                <a href="{{ route('dosen.index') }}"
                                    class="ml-2 text-blue-600 hover:text-blue-800 text-sm underline">
                                    Hapus Pencarian
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Active Filters Display --}}
                    @if (request('status'))
                        <div class="flex items-center flex-wrap gap-2 w-full">
                            <span class="text-sm text-gray-600">Filter aktif:</span>
                            @foreach ((array) request('status') as $status)
                                @php
                                    $statusColors = [
                                        'AKTIF' => 'bg-green-100 text-green-800',
                                        'CUTI' => 'bg-yellow-100 text-yellow-800',
                                        'PENSIUN' => 'bg-blue-100 text-blue-800',
                                        'NONAKTIF' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $status }}
                                    <a href="{{ request()->fullUrlWithQuery(['status' => array_diff((array) request('status'), [$status])]) }}"
                                        class="ml-1.5 text-current hover:text-gray-600">
                                        <i class="ph ph-x text-xs"></i>
                                    </a>
                                </span>
                            @endforeach
                            <a href="{{ route('dosen.index', array_diff_key(request()->query(), ['status' => ''])) }}"
                                class="text-sm text-blue-600 hover:text-blue-800 underline">
                                Hapus Semua
                            </a>
                        </div>
                    @endif

                    {{-- Info jumlah data --}}
                    {{-- <div class="w-full text-sm text-gray-600">
                        Menampilkan {{ $dosen->firstItem() ?? 0 }} sampai {{ $dosen->lastItem() ?? 0 }}
                        dari {{ $dosen->total() }} data dosen
                        @if (request('search') || request('status'))
                            (hasil pencarian/filter)
                        @endif
                    </div> --}}

                    {{-- Tabel Data Dosen --}}
                    @if ($dosen->count() > 0)
                        <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                            <thead class="bg-brand-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Nama Dosen</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">NIP</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">No HP</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Status</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="bg-putih divide-y divide-gray-200">
                                @foreach ($dosen as $index => $d)
                                    <tr>
                                        <x-table.table-td>{{ $dosen->firstItem() + $index }}</x-table.table-td>
                                        <x-table.table-td>{{ $d->nama_dosen }}</x-table.table-td>
                                        <x-table.table-td class="text-center">{{ $d->nip }}</x-table.table-td>
                                        <x-table.table-td class="text-center">{{ $d->no_hp }}</x-table.table-td>
                                        <x-table.table-td class="text-center">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full
                                                @if ($d->status == 'AKTIF') bg-green-100 text-green-800
                                                @elseif($d->status == 'CUTI') bg-yellow-100 text-yellow-800
                                                @elseif($d->status == 'PENSIUN') bg-blue-100 text-blue-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $d->status }}
                                            </span>
                                        </x-table.table-td>
                                        <td class="px-2 py-2 text-sm text-hitam">
                                            <div class="flex justify-center items-center space-x-1">
                                                {{-- Button Show --}}
                                                <a href="{{ route('dosen.show', $d->id_dosen) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-green-600 text-white text-sm rounded hover:bg-green-700"
                                                    title="Lihat Detail">
                                                    <i class="ph ph-eye"></i>
                                                </a>
                                                {{-- Button Edit --}}
                                                @if (auth()->user()->role === 'admin')
                                                    <a href="{{ route('dosen.edit', $d->id_dosen) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-biru-600 text-white text-sm rounded hover:bg-biru-700"
                                                        title="Edit">
                                                        <i class="ph ph-pencil-simple"></i>
                                                    </a>
                                                    {{-- Button Hapus --}}
                                                    <form action="{{ route('dosen.destroy', $d->id_dosen) }}"
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
                        <div class="w-full">
                            {{ $dosen->links() }}
                        </div>
                    @else
                        {{-- Pesan jika tidak ada data --}}
                        <div class="w-full text-center py-12 bg-white rounded-lg shadow">
                            <i class="ph ph-building-office text-6xl text-gray-300 mb-4"></i>
                            @if (request('search'))
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada hasil pencarian</h3>
                                <p class="text-gray-500 mb-4">
                                    Tidak ditemukan Dosen yang sesuai dengan pencarian "{{ request('search') }}"
                                </p>
                                <a href="{{ route('dosen.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-biru-600 text-white text-sm rounded-md hover:bg-biru-700 transition-colors duration-200">
                                    <i class="ph ph-arrow-left mr-2"></i>
                                    Lihat Semua Dosen
                                </a>
                            @else
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data dosen</h3>
                                <p class="text-gray-500 mb-4">Mulai dengan menambahkan dosen pertama Anda</p>
                                <a href="{{ route('dosen.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-biru-600 text-white text-sm rounded-md hover:bg-biru-700 transition-colors duration-200">
                                    <i class="ph ph-plus mr-2"></i>
                                    Tambah Dosen
                                </a>
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
            const statusFilters = document.querySelectorAll('.status-filter');
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
                statusFilters.forEach(filter => {
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
            window.location.href = "{{ route('dosen.index') }}";
        }
    </script>
@endsection
