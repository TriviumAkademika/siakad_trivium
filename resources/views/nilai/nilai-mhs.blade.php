@extends('master')

@section('title', 'Nilai Mahasiswa')

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
                    {{-- Search --}}
                    <div class="flex justify-between items-center w-full gap-4">
                        <div class="flex items-center gap-4 flex-1">
                            {{-- Search Form --}}
                            <form method="GET" action="{{ route('nilai-mahasiswa') }}" class="flex-1 max-w-md" id="searchForm">
                                <div class="relative">
                                    <input type="text" name="search" id="searchInput" value="{{ $search ?? '' }}"
                                        placeholder="Cari mata kuliah..."
                                        class="w-full px-4 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-magnifying-glass text-gray-400"></i>
                                    </div>
                                    @if (!empty($search))
                                        <button type="button" onclick="clearSearch()"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <i class="ph ph-x text-gray-400 hover:text-gray-600"></i>
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="w-full bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-brand-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-hitam">Mata Kuliah</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Jenis</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">SKS</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">UTS</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">UAS</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $no = 1;
                                    @endphp
                                    
                                    @forelse($nilaiList as $nilaiData)
                                        @php
                                            $matkul = $nilaiData->matkul ?? null;
                                            $isWajib = $nilaiData->is_wajib ?? ($matkul && isset($matkul->jenis) && $matkul->jenis === 'Wajib');
                                            $jenisClass = $isWajib ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800';
                                            $jenisMatkul = $matkul->jenis ?? ($isWajib ? 'Wajib' : 'Pilihan');
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $no++ }}
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $matkul->nama_matkul ?? 'Mata Kuliah Tidak Ditemukan' }}</div>
                                                <div class="text-xs text-gray-500">{{ $matkul->kode_matkul ?? 'Kode MK' }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jenisClass }}">
                                                    {{ $jenisMatkul }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                                {{ $nilaiData->sks ?? ($matkul->sks ?? '0') }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-center {{ !empty($nilaiData->nilai_uts) ? 'text-gray-900 font-medium' : 'text-gray-400' }}">
                                                {{ $nilaiData->nilai_uts ?? '-' }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-center {{ !empty($nilaiData->nilai_uas) ? 'text-gray-900 font-medium' : 'text-gray-400' }}">
                                                {{ $nilaiData->nilai_uas ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                                @if (!empty($search) || !empty($tahunAjaran))
                                                    Tidak ada data nilai yang sesuai dengan filter yang dipilih.
                                                @else
                                                    Belum ada data nilai yang tersedia.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function clearSearch() {
            const form = document.getElementById('searchForm');
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.value = '';
            }
            form.submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Auto submit search form when typing
            const searchInput = document.getElementById('searchInput');
            const searchForm = searchInput?.closest('form');
            
            if (searchInput && searchForm) {
                let searchTimer;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        searchForm.submit();
                    }, 500);
                });
            }
        });
    </script>
    @endpush
@endsection