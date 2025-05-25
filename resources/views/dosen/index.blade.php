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
                            {{-- Search Box --}}
                            <div class="relative flex-1 max-w-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ph ph-magnifying-glass text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       id="searchInput"
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-biru-500 focus:border-biru-500 text-sm" 
                                       placeholder="Cari nama, NIP, alamat, atau no HP..."
                                       value="{{ request('search') }}">
                            </div>

                            {{-- Filter Status --}}
                            <div class="relative">
                                <button type="button" 
                                        id="filterButton"
                                        class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-biru-500">
                                    <i class="ph ph-funnel"></i>
                                    Filter Status
                                    <span id="filterCount" class="hidden ml-1 px-2 py-0.5 text-xs bg-biru-100 text-biru-800 rounded-full">0</span>
                                </button>
                                
                                {{-- Dropdown Filter --}}
                                <div id="filterDropdown" 
                                     class="hidden absolute right-0 z-10 mt-2 w-56 bg-white rounded-md shadow-lg border border-gray-200">
                                    <div class="p-3">
                                        <div class="space-y-2">
                                            <label class="flex items-center">
                                                <input type="checkbox" 
                                                       class="status-filter rounded border-gray-300 text-biru-600 focus:ring-biru-500" 
                                                       value="AKTIF" 
                                                       {{ in_array('AKTIF', request('status', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm">
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                        AKTIF
                                                    </span>
                                                </span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" 
                                                       class="status-filter rounded border-gray-300 text-biru-600 focus:ring-biru-500" 
                                                       value="CUTI"
                                                       {{ in_array('CUTI', request('status', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm">
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                        CUTI
                                                    </span>
                                                </span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" 
                                                       class="status-filter rounded border-gray-300 text-biru-600 focus:ring-biru-500" 
                                                       value="PENSIUN"
                                                       {{ in_array('PENSIUN', request('status', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm">
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                        PENSIUN
                                                    </span>
                                                </span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" 
                                                       class="status-filter rounded border-gray-300 text-biru-600 focus:ring-biru-500" 
                                                       value="NONAKTIF"
                                                       {{ in_array('NONAKTIF', request('status', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm">
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                        NONAKTIF
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between">
                                            <button type="button" 
                                                    id="clearFilter"
                                                    class="text-xs text-gray-500 hover:text-gray-700">
                                                Clear All
                                            </button>
                                            <button type="button" 
                                                    id="applyFilter"
                                                    class="text-xs bg-biru-600 text-white px-3 py-1 rounded hover:bg-biru-700">
                                                Apply
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Tambah Dosen --}}
                        <a href="{{ route('dosen.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah Dosen
                            </x-button.submit>
                        </a>
                    </div>

                    {{-- Tabel Data Dosen --}}
                    <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                        <thead class="bg-brand-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Nama Dosen</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">NIP</th>
                                {{-- <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Alamat</th> --}}
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">No HP</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Status</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Action</th>
                            </tr>
                        </thead>

                        <tbody class="bg-putih divide-y divide-gray-200" id="dosenTableBody">
                            @foreach ($dosen as $index => $d)
                                <tr class="dosen-row" 
                                    data-search="{{ strtolower($d->nama_dosen . ' ' . $d->nip . ' ' . $d->alamat . ' ' . $d->no_hp) }}"
                                    data-status="{{ $d->status }}">
                                    <x-table.table-td>{{ $index + 1 }}</x-table.table-td>
                                    <x-table.table-td>{{ $d->nama_dosen }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $d->nip }}</x-table.table-td>
                                    {{-- <x-table.table-td class="text-center">{{ $d->alamat }}</x-table.table-td> --}}
                                    <x-table.table-td class="text-center">{{ $d->no_hp }}</x-table.table-td>
                                    <x-table.table-td class="text-center">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($d->status == 'AKTIF') bg-green-100 text-green-800
                                            @elseif($d->status == 'CUTI') bg-yellow-100 text-yellow-800
                                            @elseif($d->status == 'PENSIUN') bg-blue-100 text-blue-800
                                            @else bg-red-100 text-red-800
                                            @endif">
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
                                            <a href="{{ route('dosen.edit', $d->id_dosen) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-biru-600 text-white text-sm rounded hover:bg-biru-700"
                                                title="Edit">
                                                <i class="ph ph-pencil-simple"></i>
                                            </a>
                                            {{-- Button Delete --}}
                                            {{-- <form action="{{ route('dosen.destroy', $d->id_dosen) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- No Results Message --}}
                    <div id="noResults" class="hidden w-full text-center py-8 text-gray-500">
                        <i class="ph ph-magnifying-glass text-4xl mb-2"></i>
                        <p>Tidak ada data dosen yang sesuai dengan pencarian atau filter.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk Search dan Filter --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const filterButton = document.getElementById('filterButton');
            const filterDropdown = document.getElementById('filterDropdown');
            const filterCount = document.getElementById('filterCount');
            const statusFilters = document.querySelectorAll('.status-filter');
            const clearFilter = document.getElementById('clearFilter');
            const applyFilter = document.getElementById('applyFilter');
            const dosenRows = document.querySelectorAll('.dosen-row');
            const noResults = document.getElementById('noResults');
            const tableBody = document.getElementById('dosenTableBody');

            let activeStatusFilters = [];

            // Toggle dropdown filter
            filterButton.addEventListener('click', function(e) {
                e.stopPropagation();
                filterDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!filterButton.contains(e.target) && !filterDropdown.contains(e.target)) {
                    filterDropdown.classList.add('hidden');
                }
            });

            // Search functionality
            searchInput.addEventListener('input', function() {
                filterAndSearch();
            });

            // Status filter functionality
            statusFilters.forEach(filter => {
                filter.addEventListener('change', function() {
                    updateFilterCount();
                });
            });

            // Clear all filters
            clearFilter.addEventListener('click', function() {
                statusFilters.forEach(filter => {
                    filter.checked = false;
                });
                updateFilterCount();
            });

            // Apply filters
            applyFilter.addEventListener('click', function() {
                activeStatusFilters = Array.from(statusFilters)
                    .filter(filter => filter.checked)
                    .map(filter => filter.value);
                
                filterDropdown.classList.add('hidden');
                filterAndSearch();
            });

            function updateFilterCount() {
                const checkedFilters = Array.from(statusFilters).filter(filter => filter.checked);
                if (checkedFilters.length > 0) {
                    filterCount.textContent = checkedFilters.length;
                    filterCount.classList.remove('hidden');
                } else {
                    filterCount.classList.add('hidden');
                }
            }

            function filterAndSearch() {
                const searchTerm = searchInput.value.toLowerCase();
                let visibleCount = 0;

                dosenRows.forEach((row, index) => {
                    const searchData = row.getAttribute('data-search');
                    const statusData = row.getAttribute('data-status');
                    
                    // Check search criteria (all columns except status)
                    const matchesSearch = searchTerm === '' || searchData.includes(searchTerm);
                    
                    // Check status filter
                    const matchesStatus = activeStatusFilters.length === 0 || activeStatusFilters.includes(statusData);
                    
                    if (matchesSearch && matchesStatus) {
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
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                    tableBody.parentElement.classList.add('hidden');
                } else {
                    noResults.classList.add('hidden');
                    tableBody.parentElement.classList.remove('hidden');
                }
            }

            // Initialize filter count on page load
            updateFilterCount();
            
            // Set active filters from URL parameters if any
            const urlParams = new URLSearchParams(window.location.search);
            const statusParams = urlParams.getAll('status[]');
            if (statusParams.length > 0) {
                activeStatusFilters = statusParams;
                statusFilters.forEach(filter => {
                    if (statusParams.includes(filter.value)) {
                        filter.checked = true;
                    }
                });
                updateFilterCount();
                filterAndSearch();
            }
        });
    </script>
@endsection