@extends('master')

@section('title', 'Kelas')

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

                    {{-- Search, Filter, dan Tombol Tambah kelas --}}
                    <div class="flex justify-between items-center w-full gap-4">
                        <form method="GET" action="{{ route('kelas.index') }}" class="flex items-center gap-4 flex-1"
                            id="searchForm">
                            {{-- Search Box --}}
                            <div class="relative flex-1 max-w-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ph ph-magnifying-glass text-gray-400"></i>
                                </div>
                                <input type="text" name="search" id="searchInput"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-biru-500 focus:border-biru-500 text-sm"
                                    placeholder="Cari nama dosen, tahun masuk, prodi, atau paralel..."
                                    value="{{ request('search') }}">
                            </div>

                            {{-- Hidden inputs for status filters --}}
                            @if (request('status'))
                                @foreach (request('status') as $status)
                                    <input type="hidden" name="status[]" value="{{ $status }}">
                                @endforeach
                            @endif

                            {{-- Filter Status --}}
                            <div class="relative">
                                <button type="button" id="filterButton"
                                    class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-biru-500">
                                    <i class="ph ph-funnel"></i>
                                    Filter Status
                                    <span id="filterCount"
                                        class="hidden ml-1 px-2 py-0.5 text-xs bg-biru-100 text-biru-800 rounded-full">0</span>
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
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                        AKTIF
                                                    </span>
                                                </span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox"
                                                    class="status-filter rounded border-gray-300 text-biru-600 focus:ring-biru-500"
                                                    value="LULUS"
                                                    {{ in_array('LULUS', request('status', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm">
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                        LULUS
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between">
                                            <button type="button" id="clearFilter"
                                                class="text-xs text-gray-500 hover:text-gray-700">
                                                Clear All
                                            </button>
                                            <button type="button" id="applyFilter"
                                                class="text-xs bg-biru-600 text-white px-3 py-1 rounded hover:bg-biru-700">
                                                Apply
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        {{-- Tombol Tambah Kelas --}}
                        <a href="{{ route('kelas.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah Kelas
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
                                    ({{ $kelas->total() }} data ditemukan)
                                </span>
                                <a href="{{ route('kelas.index') }}"
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
                                        'LULUS' => 'bg-blue-100 text-blue-800',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ strtoupper($status) }}
                                    <a href="{{ request()->fullUrlWithQuery(['status' => array_diff((array) request('status'), [$status])]) }}"
                                        class="ml-1.5 text-current hover:text-gray-600">
                                        <i class="ph ph-x text-xs"></i>
                                    </a>
                                </span>
                            @endforeach
                            <a href="{{ route('kelas.index', array_diff_key(request()->query(), ['status' => ''])) }}"
                                class="text-sm text-blue-600 hover:text-blue-800 underline">
                                Hapus Semua
                            </a>
                        </div>
                    @endif

                    {{-- Tabel Data Kelas --}}
                    @if ($kelas->count() > 0)
                        <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                            <thead class="bg-brand-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Nama Dosen</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Tahun Masuk</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Prodi</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Paralel</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Status</th>
                                    @if (auth()->user()->role === 'admin')
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Action</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody class="bg-putih divide-y divide-gray-200">
                                @foreach ($kelas as $index => $k)
                                    <tr>
                                        <x-table.table-td>{{ ($kelas->currentPage() - 1) * $kelas->perPage() + $index + 1 }}</x-table.table-td>
                                        <x-table.table-td>{{ $k->dosen->nama_dosen ?? 'N/A' }}</x-table.table-td>
                                        <x-table.table-td class="text-center">{{ $k->tahun_masuk }}</x-table.table-td>
                                        <x-table.table-td class="text-center">{{ $k->prodi }}</x-table.table-td>
                                        <x-table.table-td class="text-center">{{ $k->paralel }}</x-table.table-td>
                                        <x-table.table-td class="text-center">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full
                                                @if ($k->status == 'AKTIF') bg-green-100 text-green-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ $k->status }}
                                            </span>
                                        </x-table.table-td>
                                        @if (auth()->user()->role === 'admin')
                                            <td class="px-2 py-2 text-sm text-hitam">
                                                <div class="flex justify-center items-center space-x-1">
                                                    {{-- Button Show --}}
                                                    <a href="{{ route('kelas.show', $k->id_kelas) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-green-600 text-white text-sm rounded hover:bg-green-700"
                                                        title="Lihat Detail">
                                                        <i class="ph ph-eye"></i>
                                                    </a>
                                                    {{-- Button Edit --}}
                                                    <a href="{{ route('kelas.edit', $k->id_kelas) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-biru-600 text-white text-white text-sm rounded hover:bg-biru-700"
                                                        title="Edit">
                                                        <i class="ph ph-pencil-simple"></i>
                                                    </a>
                                                    {{-- Button Delete --}}
                                                    {{-- <form action="{{ route('kelas.destroy', $k->id_kelas) }}" method="POST"
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
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination Links --}}
                        <div class="w-full">
                            {{ $kelas->links() }}
                        </div>
                    @else
                        {{-- Pesan jika tidak ada data --}}
                        <div class="w-full text-center py-12 bg-white rounded-lg shadow">
                            <i class="ph ph-building-office text-6xl text-gray-300 mb-4"></i>
                            @if (request('search'))
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada hasil pencarian</h3>
                                <p class="text-gray-500 mb-4">
                                    Tidak ditemukan kelas yang sesuai dengan pencarian "{{ request('search') }}"
                                </p>
                                <a href="{{ route('kelas.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-biru-600 text-white text-sm rounded-md hover:bg-biru-700 transition-colors duration-200">
                                    <i class="ph ph-arrow-left mr-2"></i>
                                    Lihat Semua Kelas
                                </a>
                            @else
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data kelas</h3>
                                <p class="text-gray-500 mb-4">Mulai dengan menambahkan kelas pertama Anda</p>
                                <a href="{{ route('kelas.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-biru-600 text-white text-sm rounded-md hover:bg-biru-700 transition-colors duration-200">
                                    <i class="ph ph-plus mr-2"></i>
                                    Tambah Kelas
                                </a>
                            @endif
                        </div>
                    @endif
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
            const searchForm = document.getElementById('searchForm');

            let searchTimeout;

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

            // Search functionality with debounce
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    searchForm.submit();
                }, 500); // Submit after 500ms of no typing
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

                // Clear status filters from form and submit
                const statusInputs = searchForm.querySelectorAll('input[name="status[]"]');
                statusInputs.forEach(input => input.remove());
                searchForm.submit();
            });

            // Apply filters
            applyFilter.addEventListener('click', function() {
                // Remove existing status inputs
                const existingStatusInputs = searchForm.querySelectorAll('input[name="status[]"]');
                existingStatusInputs.forEach(input => input.remove());

                // Add new status filter inputs
                statusFilters.forEach(filter => {
                    if (filter.checked) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'status[]';
                        hiddenInput.value = filter.value;
                        searchForm.appendChild(hiddenInput);
                    }
                });

                filterDropdown.classList.add('hidden');
                searchForm.submit();
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

            // Clear all filters and redirect
            window.clearAllFilters = function() {
                window.location.href = "{{ route('kelas.index') }}";
            };

            // Initialize filter count on page load
            updateFilterCount();
        });
    </script>
@endsection
