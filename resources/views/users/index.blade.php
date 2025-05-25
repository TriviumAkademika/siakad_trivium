@extends('master')

@section('title', 'User')

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

                    {{-- Search, Filter, dan Tombol Tambah User --}}
                    <div class="flex justify-between items-center w-full gap-4">
                        <div class="flex items-center gap-4 flex-1">
                            {{-- Search Form --}}
                            <form method="GET" action="{{ route('users.index') }}" class="flex-1 max-w-md" id="searchForm">
                                <div class="relative">
                                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                        placeholder="Cari berdasarkan email atau nama..."
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
                                {{-- Preserve role filters --}}
                                @if (request('roles'))
                                    @php
                                        $preserveRoles = is_array(request('roles'))
                                            ? request('roles')
                                            : (is_string(request('roles'))
                                                ? explode(',', request('roles'))
                                                : []);
                                    @endphp
                                    @foreach ($preserveRoles as $role)
                                        <input type="hidden" name="roles[]" value="{{ $role }}">
                                    @endforeach
                                @endif
                            </form>

                            {{-- Role Filter --}}
                            <form method="GET" action="{{ route('users.index') }}" id="filterForm"
                                class="flex items-center gap-3">
                                {{-- Preserve search query --}}
                                @if (request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif

                                {{-- Filter Role --}}
                                <div class="relative">
                                    <button id="filterButton" type="button"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <i class="ph ph-funnel mr-2 text-sm"></i>
                                        Filter Role
                                        @if (request('roles'))
                                            @php
                                                $activeRoles = is_array(request('roles'))
                                                    ? request('roles')
                                                    : (is_string(request('roles'))
                                                        ? explode(',', request('roles'))
                                                        : []);
                                            @endphp
                                            <span class="ml-2 bg-blue-500 text-white px-1.5 py-0.5 rounded-full text-xs">
                                                {{ count($activeRoles) }}
                                            </span>
                                        @endif
                                        <i class="ph ph-caret-down ml-1 text-xs"></i>
                                    </button>

                                    {{-- Dropdown Filter --}}
                                    <div id="filterDropdown"
                                        class="hidden absolute left-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                                        <div class="p-4">
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Pilih Role</h4>
                                            <div class="space-y-3">
                                                @php
                                                    $activeRoles = request('roles')
                                                        ? (is_array(request('roles'))
                                                            ? request('roles')
                                                            : (is_string(request('roles'))
                                                                ? explode(',', request('roles'))
                                                                : []))
                                                        : [];
                                                @endphp
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="roles[]" id="admin" value="admin"
                                                        {{ in_array('admin', $activeRoles) ? 'checked' : '' }}
                                                        class="role-filter w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                    <label for="admin" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Admin</span>
                                                        <span
                                                            class="ml-2 text-xs font-medium text-blue-800 bg-blue-100 px-2 py-0.5 rounded">ADMIN</span>
                                                    </label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="roles[]" id="dosen" value="dosen"
                                                        {{ in_array('dosen', $activeRoles) ? 'checked' : '' }}
                                                        class="role-filter w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                                    <label for="dosen" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Dosen</span>
                                                        <span
                                                            class="ml-2 text-xs font-medium text-green-800 bg-green-100 px-2 py-0.5 rounded">DOSEN</span>
                                                    </label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="roles[]" id="mahasiswa" value="mahasiswa"
                                                        {{ in_array('mahasiswa', $activeRoles) ? 'checked' : '' }}
                                                        class="role-filter w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                                    <label for="mahasiswa" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Mahasiswa</span>
                                                        <span
                                                            class="ml-2 text-xs font-medium text-purple-800 bg-purple-100 px-2 py-0.5 rounded">MAHASISWA</span>
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

                        {{-- Tombol Tambah User --}}
                        <a href="{{ route('users.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah User
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
                                    ({{ $users->total() }} data ditemukan)
                                </span>
                                <a href="{{ route('users.index') }}"
                                    class="ml-2 text-blue-600 hover:text-blue-800 text-sm underline">
                                    Hapus Pencarian
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Active Filters Display --}}
                    @if (request('roles'))
                        <div class="flex items-center flex-wrap gap-2 w-full">
                            <span class="text-sm text-gray-600">Filter aktif:</span>
                            @php
                                $activeRoles = is_array(request('roles'))
                                    ? request('roles')
                                    : (is_string(request('roles'))
                                        ? explode(',', request('roles'))
                                        : []);
                                $roleColors = [
                                    'admin' => 'bg-blue-100 text-blue-800',
                                    'dosen' => 'bg-green-100 text-green-800',
                                    'mahasiswa' => 'bg-purple-100 text-purple-800',
                                ];
                            @endphp
                            @foreach ($activeRoles as $role)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full {{ $roleColors[$role] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ strtoupper($role) }}
                                    @php
                                        $remainingRoles = array_diff($activeRoles, [$role]);
                                        $queryParams = request()->query();
                                        if (empty($remainingRoles)) {
                                            unset($queryParams['roles']);
                                        } else {
                                            $queryParams['roles'] = $remainingRoles;
                                        }
                                    @endphp
                                    <a href="{{ route('users.index', $queryParams) }}"
                                        class="ml-1.5 text-current hover:text-gray-600">
                                        <i class="ph ph-x text-xs"></i>
                                    </a>
                                </span>
                            @endforeach
                            <a href="{{ route('users.index', array_diff_key(request()->query(), ['roles' => ''])) }}"
                                class="text-sm text-blue-600 hover:text-blue-800 underline">
                                Hapus Semua
                            </a>
                        </div>
                    @endif

                    {{-- Tabel Data User --}}
                    @if ($users->count() > 0)
                        <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                            <thead class="bg-brand-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Email</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Role</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Nama User</th>
                                </tr>
                            </thead>

                            <tbody class="bg-putih divide-y divide-gray-200" id="usersTableBody">
                                @foreach ($users as $index => $user)
                                    <tr class="user-row hover:bg-gray-50"
                                        data-search="{{ strtolower($user->email . ' ' . ($user->name ?? '') . ' ' . ($user->mahasiswa->nama ?? '') . ' ' . ($user->mahasiswa->nrp ?? '') . ' ' . ($user->dosen->nama_dosen ?? '')) }}"
                                        data-role="{{ $user->getRoleNames()->first() ?? '' }}">
                                        <x-table.table-td>{{ $users->firstItem() + $index }}</x-table.table-td>
                                        <x-table.table-td>{{ $user->email }}</x-table.table-td>
                                        <x-table.table-td class="text-center">
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                @if ($user->getRoleNames()->first() === 'admin') bg-blue-100 text-blue-800
                                                @elseif($user->getRoleNames()->first() === 'dosen') bg-green-100 text-green-800
                                                @elseif($user->getRoleNames()->first() === 'mahasiswa') bg-purple-100 text-purple-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $user->getRoleNames()->first() ?? '-' }}
                                            </span>
                                        </x-table.table-td>
                                        <x-table.table-td class="text-center">
                                            @if ($user->getRoleNames()->first() === 'mahasiswa')
                                                {{ $user->mahasiswa->nama ?? '-' }} ({{ $user->mahasiswa->nrp ?? '-' }})
                                            @elseif ($user->getRoleNames()->first() === 'dosen')
                                                {{ $user->dosen->nama_dosen ?? '-' }}
                                            @elseif ($user->getRoleNames()->first() === 'admin')
                                                {{ $user->name ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </x-table.table-td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        @if (method_exists($users, 'links'))
                            <div class="w-full">
                                {{ $users->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        {{-- Pesan jika tidak ada data --}}
                        <div class="w-full text-center py-12 bg-white rounded-lg shadow">
                            <i class="ph ph-building-office text-6xl text-gray-300 mb-4"></i>
                            @if (request('search'))
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada hasil pencarian</h3>
                                <p class="text-gray-500 mb-4">
                                    Tidak ditemukan user yang sesuai dengan pencarian "{{ request('search') }}"
                                </p>
                                <a href="{{ route('users.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-biru-600 text-white text-sm rounded-md hover:bg-biru-700 transition-colors duration-200">
                                    <i class="ph ph-arrow-left mr-2"></i>
                                    Lihat Semua User
                                </a>
                            @else
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data user</h3>
                                <p class="text-gray-500 mb-4">Mulai dengan menambahkan user pertama Anda</p>
                                <a href="{{ route('users.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-biru-600 text-white text-sm rounded-md hover:bg-biru-700 transition-colors duration-200">
                                    <i class="ph ph-plus mr-2"></i>
                                    Tambah Ruangan
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- No Results Message for Client-side Filtering --}}
                    <div id="noResults" class="hidden w-full text-center py-8 text-gray-500">
                        <i class="ph ph-magnifying-glass text-4xl mb-2"></i>
                        <p>Tidak ada data user yang sesuai dengan pencarian atau filter.</p>
                        <button onclick="resetFilters()"
                            class="text-brand-600 hover:text-brand-700 text-sm mt-2 inline-block">
                            Reset pencarian
                        </button>
                    </div>
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
            const roleFilters = document.querySelectorAll('.role-filter');
            const clearAllBtn = document.getElementById('clearAll');
            const applyFilterBtn = document.getElementById('applyFilter');
            const filterForm = document.getElementById('filterForm');

            // Elements for client-side filtering
            const userRows = document.querySelectorAll('.user-row');
            const noResults = document.getElementById('noResults');
            const tableBody = document.getElementById('usersTableBody');

            let currentSearchTerm = '';
            let currentActiveFilters = [];

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
                roleFilters.forEach(filter => {
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
                    currentSearchTerm = this.value.toLowerCase();

                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        // For search with debounce, submit to server
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

                // Initialize current search term
                currentSearchTerm = searchInput.value.toLowerCase();
            }

            // Initialize current active filters
            const checkedFilters = document.querySelectorAll('.role-filter:checked');
            currentActiveFilters = Array.from(checkedFilters).map(filter => filter.value);

            // Client-side filtering function (for immediate feedback)
            function filterAndSearch() {
                let visibleCount = 0;

                userRows.forEach((row, index) => {
                    const searchData = row.getAttribute('data-search');
                    const roleData = row.getAttribute('data-role');

                    // Check search criteria
                    const matchesSearch = currentSearchTerm === '' || searchData.includes(
                    currentSearchTerm);

                    // Check role filter
                    const matchesRole = currentActiveFilters.length === 0 || currentActiveFilters.includes(
                        roleData);

                    if (matchesSearch && matchesRole) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (visibleCount === 0 && userRows.length > 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }

            // Clear search
            window.clearSearch = function() {
                document.getElementById('searchInput').value = '';
                document.getElementById('searchForm').submit();
            };

            // Clear all search and filters
            window.clearAll = function() {
                window.location.href = "{{ route('users.index') }}";
            };

            // Global reset function
            window.resetFilters = function() {
                window.location.href = "{{ route('users.index') }}";
            };

            // Initialize filters if any
            filterAndSearch();
        });
    </script>
@endsection
