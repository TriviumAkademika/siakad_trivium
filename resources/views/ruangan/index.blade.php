@extends('master')

@section('title', 'Ruangan')

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

                    {{-- Search dan Tombol Tambah Ruangan --}}
                    <div class="flex justify-between items-center w-full space-x-4">
                        {{-- Search Form --}}
                        <div class="flex-1 max-w-md">
                            <form method="GET" action="{{ route('ruangan.index') }}" class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ph ph-magnifying-glass text-gray-400"></i>
                                </div>
                                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                    placeholder="Cari ruangan, gedung, atau kode..."
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-biru-500 focus:border-biru-500 text-sm">
                            </form>
                        </div>

                        {{-- Tombol Tambah Ruangan --}}
                        <a href="{{ route('ruangan.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah Ruangan
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
                                    ({{ $ruangan->total() }} data ditemukan)
                                </span>
                                <a href="{{ route('ruangan.index') }}" class="ml-2 text-blue-600 hover:text-blue-800 text-sm underline">
                                    Hapus filter
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Tabel Data Ruangan --}}
                    @if ($ruangan->count() > 0)
                        <div class="w-full">
                            <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                                <thead class="bg-brand-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Nama Ruangan</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Nama Gedung</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Kode Ruangan</th>
                                        <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Action</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-putih divide-y divide-gray-200">
                                    @foreach ($ruangan as $index => $room)
                                        <tr>
                                            <x-table.table-td>{{ $ruangan->firstItem() + $index }}</x-table.table-td>
                                            <x-table.table-td>{{ $room->nama_ruangan }}</x-table.table-td>
                                            <x-table.table-td
                                                class="text-center">{{ $room->nama_gedung }}</x-table.table-td>
                                            <x-table.table-td
                                                class="text-center">{{ $room->kode_ruangan }}</x-table.table-td>
                                            <td class="px-2 py-2 text-sm text-hitam">
                                                <div class="flex justify-center items-center space-x-1">
                                                    {{-- Button Edit --}}
                                                    <a href="{{ route('ruangan.edit', $room->id_ruangan) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-biru-600 text-white text-sm rounded hover:bg-biru-700 transition-colors duration-200"
                                                        title="Edit">
                                                        <i class="ph ph-pencil-simple"></i>
                                                    </a>

                                                    {{-- Button Delete --}}
                                                    {{-- <form action="{{ route('ruangan.destroy', $room->id_ruangan) }}" method="POST"
                                                        class="inline-block"
                                                        onsubmit="return confirm('Anda yakin ingin menghapus ruangan {{ $room->nama_ruangan }}?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-merah-500 text-white text-sm rounded hover:bg-merah-600 transition-colors duration-200"
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
                            
                            {{-- Pagination --}}
                            <div class="w-full mt-4">
                                {{ $ruangan->links() }}
                            </div>
                        </div>
                    @else
                        {{-- Pesan jika tidak ada data --}}
                        <div class="w-full text-center py-12 bg-white rounded-lg shadow">
                            <i class="ph ph-building-office text-6xl text-gray-300 mb-4"></i>
                            @if (request('search'))
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada hasil pencarian</h3>
                                <p class="text-gray-500 mb-4">
                                    Tidak ditemukan ruangan yang sesuai dengan pencarian "{{ request('search') }}"
                                </p>
                                <a href="{{ route('ruangan.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-biru-600 text-white text-sm rounded-md hover:bg-biru-700 transition-colors duration-200">
                                    <i class="ph ph-arrow-left mr-2"></i>
                                    Lihat Semua Ruangan
                                </a>
                            @else
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data ruangan</h3>
                                <p class="text-gray-500 mb-4">Mulai dengan menambahkan ruangan pertama Anda</p>
                                <a href="{{ route('ruangan.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-biru-600 text-white text-sm rounded-md hover:bg-biru-700 transition-colors duration-200">
                                    <i class="ph ph-plus mr-2"></i>
                                    Tambah Ruangan
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk auto-submit search --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 500); // Delay 500ms untuk menghindari terlalu banyak request
            });

            // Submit form saat tekan Enter
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimeout);
                    this.form.submit();
                }
            });
        });
    </script>
@endsection