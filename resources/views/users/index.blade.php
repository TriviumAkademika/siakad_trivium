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
                            {{-- Search Component --}}
                            <x-search-box placeholder="Cari berdasarkan email atau nama..." />

                            {{-- Role Filter Component --}}
                            <x-role-filter />
                        </div>

                        {{-- Tombol Tambah User --}}
                        <a href="{{ route('users.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah User
                            </x-button.submit>
                        </a>
                    </div>

                    {{-- Tabel Data User --}}
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
                            @forelse ($users as $index => $user)
                                <tr class="user-row hover:bg-gray-50"
                                    data-search="{{ strtolower($user->email . ' ' . ($user->name ?? '') . ' ' . ($user->mahasiswa->nama ?? '') . ' ' . ($user->mahasiswa->nrp ?? '') . ' ' . ($user->dosen->nama_dosen ?? '')) }}"
                                    data-role="{{ $user->getRoleNames()->first() ?? '' }}">
                                    <x-table.table-td>{{ $loop->iteration }}</x-table.table-td>
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
                            @empty
                                <tr id="emptyState">
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        <i class="ph ph-magnifying-glass text-4xl mb-2"></i>
                                        <p>Tidak ada data user yang ditemukan</p>
                                        @if (request('search') || request('roles'))
                                            <a href="{{ route('users.index') }}"
                                                class="text-brand-600 hover:text-brand-700 text-sm mt-2 inline-block">
                                                Reset pencarian
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination (jika ada) --}}
                    @if (method_exists($users, 'links'))
                        <div class="w-full">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @endif

                    {{-- No Results Message --}}
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

    {{-- Main JavaScript untuk menangani real-time filtering dan searching --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userRows = document.querySelectorAll('.user-row');
            const noResults = document.getElementById('noResults');
            const tableBody = document.getElementById('usersTableBody');
            const emptyState = document.getElementById('emptyState');

            let currentSearchTerm = '';
            let currentActiveFilters = [];

            // Listen for search events from search component
            document.addEventListener('searchChanged', function(e) {
                currentSearchTerm = e.detail.searchTerm;

                // Untuk search, redirect ke server untuk mendapatkan hasil dari semua data
                if (currentSearchTerm.length >= 2 || currentSearchTerm === '') {
                    performServerSearch();
                }
            });

            function performServerSearch() {
                const url = new URL(window.location.href);
                if (currentSearchTerm) {
                    url.searchParams.set('search', currentSearchTerm);
                } else {
                    url.searchParams.delete('search');
                }

                // Tetap pertahankan role filter
                if (currentActiveFilters.length > 0) {
                    url.searchParams.set('roles', currentActiveFilters.join(','));
                }

                // Reset ke halaman pertama saat search
                url.searchParams.delete('page');

                window.location.href = url.toString();
            }

            // Listen for role filter events from role filter component
            document.addEventListener('roleFilterChanged', function(e) {
                currentActiveFilters = e.detail.activeFilters;
                performServerFilter();
            });

            function performServerFilter() {
                const url = new URL(window.location.href);

                if (currentActiveFilters.length > 0) {
                    url.searchParams.set('roles', currentActiveFilters.join(','));
                } else {
                    url.searchParams.delete('roles');
                }

                // Pertahankan search term jika ada
                const searchInput = document.getElementById('searchInput');
                if (searchInput && searchInput.value) {
                    url.searchParams.set('search', searchInput.value);
                }

                // Reset ke halaman pertama saat filter
                url.searchParams.delete('page');

                window.location.href = url.toString();
            }

            function filterAndSearch() {
                let visibleCount = 0;

                userRows.forEach((row, index) => {
                    const searchData = row.getAttribute('data-search');
                    const roleData = row.getAttribute('data-role');

                    // Check search criteria (email, name, etc.)
                    const matchesSearch = currentSearchTerm === '' || searchData.includes(
                        currentSearchTerm);

                    // Check role filter
                    const matchesRole = currentActiveFilters.length === 0 || currentActiveFilters.includes(
                        roleData);

                    if (matchesSearch && matchesRole) {
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
                if (visibleCount === 0 && userRows.length > 0) {
                    // Hide empty state if it exists
                    if (emptyState) {
                        emptyState.style.display = 'none';
                    }
                    noResults.classList.remove('hidden');
                    tableBody.parentElement.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                    // Show empty state if no data originally
                    if (emptyState && userRows.length === 0) {
                        emptyState.style.display = '';
                    }
                    tableBody.parentElement.classList.remove('hidden');
                }
            }

            // Initialize with current search value if any
            const searchInput = document.getElementById('searchInput');
            if (searchInput && searchInput.value) {
                currentSearchTerm = searchInput.value.toLowerCase();
                filterAndSearch();
            }

            // Global reset function
            window.resetFilters = function() {
                // Redirect ke halaman tanpa parameter search dan roles
                const url = new URL(window.location.href);
                url.searchParams.delete('search');
                url.searchParams.delete('roles');
                url.searchParams.delete('page');

                window.location.href = url.toString();
            };
        });
    </script>
@endsection
