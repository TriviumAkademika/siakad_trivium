@extends('master')

@section('title', 'FRS')

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
                    {{-- PERMISSION UNTUK ADMIN --}}
                    {{-- Button Tambah Mahasiswa yang Bisa Mengisi FRS --}}
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('frs.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah FRS
                            </x-button.submit>
                        </a>
                    @endif

                    {{-- Info untuk Dosen Wali --}}
                    @if (auth()->user()->role === 'dosen')
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4 w-full">
                            <div class="flex items-center">
                                <i class="ph ph-info text-blue-500 text-lg mr-2"></i>
                                <span class="text-blue-700 text-sm">
                                    Anda melihat FRS mahasiswa yang menjadi perwalian Anda
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Tabel Data FRS --}}
                    <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                        <thead class="bg-brand-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-hitam">#</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Nama Mahasiswa</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Tahun Ajaran</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Semester</th>
                                <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Total SKS</th>
                                
                                {{-- Kolom Status untuk Dosen --}}
                                @if (auth()->user()->role === 'dosen')
                                    <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Status</th>
                                @endif
                                
                                {{-- Kolom Aksi untuk Admin dan Dosen --}}
                                @if (auth()->user()->role === 'admin' || auth()->user()->role === 'dosen')
                                    <th class="px-4 py-3 text-sm font-semibold text-center text-hitam">Aksi</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody class="bg-putih divide-y divide-gray-100">
                            @forelse ($frs as $index => $item)
                                <tr onclick="window.location='{{ route('detail-frs.index', $item->id_frs) }}'"
                                    class="hover:bg-gray-100 cursor-pointer">
                                    <x-table.table-td>{{ $index + 1 }}</x-table.table-td>
                                    <x-table.table-td>{{ $item->mahasiswa->nama }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $item->tahun_ajaran }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $item->semester }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $item->total_sks }}</x-table.table-td>
                                    
                                    {{-- Status untuk Dosen --}}
                                    @if (auth()->user()->role === 'dosen')
                                        <x-table.table-td class="text-center">
                                            @if ($item->tgl_drop)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Dropped
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Aktif
                                                </span>
                                            @endif
                                        </x-table.table-td>
                                    @endif
                                    
                                    {{-- Kolom Aksi --}}
                                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'dosen')
                                        <td class="px-4 py-2 text-sm text-center text-hitam">
                                            <div class="flex justify-center items-center space-x-2"
                                                onclick="event.stopPropagation()">
                                                
                                                {{-- Button Edit (Admin dan Dosen) --}}
                                                <a href="{{ route('frs.edit', $item->id_frs) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-brand-700 hover:bg-brand-800 text-white text-sm rounded"
                                                    title="Edit FRS">
                                                    <i class="ph ph-pencil-simple"></i>
                                                </a>

                                                {{-- Button Drop/Reactivate (Admin dan Dosen) --}}
                                                @if ($item->tgl_drop)
                                                    {{-- Button Reactivate --}}
                                                    <form action="{{ route('frs.reactivate', $item->id_frs) }}" method="POST"
                                                        class="inline-block"
                                                        onsubmit="return confirm('Anda yakin ingin mengaktifkan kembali FRS ini?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-green-500 text-white text-sm rounded hover:bg-green-600"
                                                            title="Aktifkan FRS">
                                                            <i class="ph ph-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- Button Drop --}}
                                                    {{-- <form action="{{ route('frs.drop', $item->id_frs) }}" method="POST"
                                                        class="inline-block"
                                                        onsubmit="return confirm('Anda yakin ingin men-drop FRS ini?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600"
                                                            title="Drop FRS">
                                                            <i class="ph ph-x-circle"></i>
                                                        </button>
                                                    </form> --}}
                                                @endif

                                                {{-- Button Delete (Hanya Admin) --}}
                                                @if (auth()->user()->role === 'admin')
                                                    <form action="{{ route('frs.destroy', $item->id_frs) }}" method="POST"
                                                        class="inline-block"
                                                        onsubmit="return confirm('Anda yakin ingin menghapus FRS ini? Data yang terhapus tidak dapat dikembalikan.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-merah-500 text-white text-sm rounded hover:bg-merah-600"
                                                            title="Hapus FRS">
                                                            <i class="ph ph-trash-simple"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role === 'admin' ? '6' : (auth()->user()->role === 'dosen' ? '7' : '5') }}" 
                                        class="px-4 py-8 text-center text-gray-500">
                                        @if (auth()->user()->role === 'dosen')
                                            Tidak ada FRS untuk mahasiswa perwalian Anda
                                        @else
                                            Tidak ada data FRS
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
@endsection