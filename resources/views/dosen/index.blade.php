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
                            {{-- Filter Component --}}
                            <x-status-filter />
                        </div>

                        @if (auth()->user()->role === 'admin')
                            {{-- Tombol Tambah Dosen --}}
                            <a href="{{ route('dosen.create') }}">
                                <x-button.submit icon="ph ph-plus">
                                    Tambah Dosen
                                </x-button.submit>
                            </a>
                        @endif
                    </div>

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
                                    @if (auth()->user()->role === 'admin')
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Action</th>
                                    @endif
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
                                        @if (auth()->user()->role === 'admin')
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
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div class="w-full">
                            {{ $dosen->links() }}
                        </div>
                    @else
                        {{-- No Results Message --}}
                        <div class="w-full text-center py-8 text-gray-500">
                            <i class="ph ph-magnifying-glass text-4xl mb-2"></i>
                            <p>Tidak ada data dosen yang sesuai dengan pencarian atau filter.</p>
                            @if (request('search') || request('status'))
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
        // Auto submit search form on input change with debounce
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                document.getElementById('searchForm').submit();
            }, 500); // 500ms debounce
        });

        // Submit filter form when checkbox changes
        function submitFilter() {
            document.getElementById('filterForm').submit();
        }

        // Clear search
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchForm').submit();
        }

        // Clear all filters
        function clearFilters() {
            const checkboxes = document.querySelectorAll('input[name="status[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
            document.getElementById('filterForm').submit();
        }

        // Clear all search and filters
        function clearAll() {
            window.location.href = "{{ route('dosen.index') }}";
        }

        // Handle enter key in search input
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchForm').submit();
            }
        });
    </script>
@endsection
