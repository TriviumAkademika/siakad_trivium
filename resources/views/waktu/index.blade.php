@extends('master')

@section('title', 'Waktu')

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

                    {{-- Filter, Sort, dan Tombol Tambah waktu --}}
                    <div class="flex justify-between items-center w-full gap-4">
                        <div class="flex items-center gap-4 flex-1">
                            <form method="GET" action="{{ route('waktu.index') }}" id="filterForm" class="flex items-center gap-3">
                                {{-- Filter Hari --}}
                                <div class="relative">
                                    <button id="filterButton" type="button" 
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <i class="ph ph-funnel mr-2 text-sm"></i>
                                        Filter Hari
                                        @if(request('hari'))
                                            <span class="ml-2 bg-blue-500 text-white px-1.5 py-0.5 rounded-full text-xs">
                                                {{ is_array(request('hari')) ? count(request('hari')) : 1 }}
                                            </span>
                                        @endif
                                        <i class="ph ph-caret-down ml-1 text-xs"></i>
                                    </button>
                                    
                                    {{-- Dropdown Filter --}}
                                    <div id="filterDropdown" class="hidden absolute left-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                                        <div class="p-4">
                                            <h4 class="text-sm font-medium text-gray-900 mb-3">Pilih Hari</h4>
                                            <div class="space-y-3">
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="hari[]" id="senin" value="Senin" 
                                                        {{ is_array(request('hari')) && in_array('Senin', request('hari')) ? 'checked' : '' }}
                                                        class="day-filter w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                                    <label for="senin" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Senin</span>
                                                        <span class="ml-2 text-xs font-medium text-green-800 bg-green-100 px-2 py-0.5 rounded">SEN</span>
                                                    </label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="hari[]" id="selasa" value="Selasa" 
                                                        {{ is_array(request('hari')) && in_array('Selasa', request('hari')) ? 'checked' : '' }}
                                                        class="day-filter w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500">
                                                    <label for="selasa" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Selasa</span>
                                                        <span class="ml-2 text-xs font-medium text-yellow-800 bg-yellow-100 px-2 py-0.5 rounded">SEL</span>
                                                    </label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="hari[]" id="rabu" value="Rabu" 
                                                        {{ is_array(request('hari')) && in_array('Rabu', request('hari')) ? 'checked' : '' }}
                                                        class="day-filter w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                    <label for="rabu" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Rabu</span>
                                                        <span class="ml-2 text-xs font-medium text-blue-800 bg-blue-100 px-2 py-0.5 rounded">RAB</span>
                                                    </label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="hari[]" id="kamis" value="Kamis" 
                                                        {{ is_array(request('hari')) && in_array('Kamis', request('hari')) ? 'checked' : '' }}
                                                        class="day-filter w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                                    <label for="kamis" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Kamis</span>
                                                        <span class="ml-2 text-xs font-medium text-purple-800 bg-purple-100 px-2 py-0.5 rounded">KAM</span>
                                                    </label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="hari[]" id="jumat" value="Jumat" 
                                                        {{ is_array(request('hari')) && in_array('Jumat', request('hari')) ? 'checked' : '' }}
                                                        class="day-filter w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                                                    <label for="jumat" class="ml-3 flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">Jumat</span>
                                                        <span class="ml-2 text-xs font-medium text-red-800 bg-red-100 px-2 py-0.5 rounded">JUM</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-200">
                                                <button id="clearAll" type="button" class="text-sm text-gray-500 hover:text-gray-700">
                                                    Clear All
                                                </button>
                                                <div class="flex gap-2">
                                                    <button type="button" onclick="closeFilter()" class="px-3 py-1.5 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                                        Cancel
                                                    </button>
                                                    <button id="applyFilter" type="button" class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                                        Apply
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Sort By --}}
                                <select name="sort" id="sortSelect" onchange="this.form.submit()"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="hari" {{ request('sort') == 'hari' ? 'selected' : '' }}>Sort by Hari</option>
                                    <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>Sort by ID</option>
                                    <option value="jam_mulai" {{ request('sort') == 'jam_mulai' ? 'selected' : '' }}>Sort by Jam Mulai</option>
                                    <option value="jam_selesai" {{ request('sort') == 'jam_selesai' ? 'selected' : '' }}>Sort by Jam Selesai</option>
                                </select>

                                {{-- Hidden inputs to preserve current parameters --}}
                                <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
                            </form>
                        </div>
                        
                        {{-- Tombol Tambah waktu --}}
                        <a href="{{ route('waktu.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah waktu
                            </x-button.submit>
                        </a>
                    </div>

                    {{-- Active Filters Display --}}
                    @if(request('hari'))
                        <div class="flex items-center flex-wrap gap-2 w-full">
                            <span class="text-sm text-gray-600">Filter aktif:</span>
                            @foreach((array)request('hari') as $day)
                                @php
                                    $dayColors = [
                                        'Senin' => 'bg-green-100 text-green-800',
                                        'Selasa' => 'bg-yellow-100 text-yellow-800',
                                        'Rabu' => 'bg-blue-100 text-blue-800',
                                        'Kamis' => 'bg-purple-100 text-purple-800',
                                        'Jumat' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full {{ $dayColors[$day] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ strtoupper($day) }}
                                    <a href="{{ request()->fullUrlWithQuery(['hari' => array_diff((array)request('hari'), [$day])]) }}" 
                                       class="ml-1.5 text-current hover:text-gray-600">
                                        <i class="ph ph-x text-xs"></i>
                                    </a>
                                </span>
                            @endforeach
                            <a href="{{ route('waktu.index', array_diff_key(request()->query(), ['hari' => ''])) }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 underline">
                                Clear all
                            </a>
                        </div>
                    @endif

                    {{-- Tabel Data Waktu --}}
                    @if ($waktu->count() > 0)
                        <div class="w-full">
                            <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                                <thead class="bg-brand-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Hari</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Jam Mulai</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Jam Selesai</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Action</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-putih divide-y divide-gray-200">
                                    @foreach ($waktu as $index => $room)
                                        <tr>
                                            <x-table.table-td>{{ $waktu->firstItem() + $index }}</x-table.table-td>
                                            <x-table.table-td>
                                                @php
                                                    $dayColors = [
                                                        'Senin' => 'bg-green-100 text-green-800',
                                                        'Selasa' => 'bg-yellow-100 text-yellow-800',
                                                        'Rabu' => 'bg-blue-100 text-blue-800',
                                                        'Kamis' => 'bg-purple-100 text-purple-800',
                                                        'Jumat' => 'bg-red-100 text-red-800'
                                                    ];
                                                @endphp
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $dayColors[$room->hari] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ strtoupper($room->hari) }}
                                                </span>
                                            </x-table.table-td>
                                            <x-table.table-td class="text-center">{{ $room->jam_mulai }}</x-table.table-td>
                                            <x-table.table-td class="text-center">{{ $room->jam_selesai }}</x-table.table-td>
                                            <td class="px-2 py-2 text-sm text-hitam">
                                                <div class="flex justify-center items-center space-x-1">
                                                    {{-- Button Edit --}}
                                                    <a href="{{ route('waktu.edit', $room->id_waktu) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-biru-600 text-white text-sm rounded hover:bg-biru-700"
                                                        title="Edit">
                                                        <i class="ph ph-pencil-simple"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- Pagination --}}
                            <div class="w-full mt-4">
                                {{ $waktu->links() }}
                            </div>
                        </div>
                    @else
                        {{-- No Results Message --}}
                        <div class="w-full text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center space-y-2">
                                <i class="ph ph-calendar-x text-4xl text-gray-300"></i>
                                <p>Tidak ada data waktu yang ditemukan</p>
                                @if(request('hari'))
                                    <button onclick="clearAllFilters()"
                                        class="mt-2 px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                        Tampilkan Semua Data
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButton = document.getElementById('filterButton');
            const filterDropdown = document.getElementById('filterDropdown');
            const dayFilters = document.querySelectorAll('.day-filter');
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
                dayFilters.forEach(filter => {
                    filter.checked = false;
                });
            });

            // Apply filter
            applyFilterBtn.addEventListener('click', function() {
                filterForm.submit();
            });

            // Clear all filters and redirect
            window.clearAllFilters = function() {
                window.location.href = "{{ route('waktu.index') }}";
            };

            // Auto submit when checkbox changes (optional - for immediate feedback)
            dayFilters.forEach(filter => {
                filter.addEventListener('change', function() {
                    // Uncomment next line if you want auto-submit on change
                    // filterForm.submit();
                });
            });
        });
    </script>
@endsection