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

                    {{-- Judul dan Tombol Tambah Ruangan --}}
                    <div class="flex justify-between items-center w-full">
                        <h1 class="text-xl  ">Data Ruangan</h1>
                        <a href="{{ route('ruangan.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah Ruangan
                            </x-button.submit>
                        </a>
                    </div>

                    {{-- Button Tambah Data Mahasiswa
                    <h1>Data Ruangan</h1>
                    <a href="{{ route('ruangan.create') }}">
                        <x-button.submit icon="ph ph-plus">
                            Tambah Ruangan
                        </x-button.submit>
                    </a> --}}

                    {{-- Tabel Data Mahasiswa --}}
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
                                    <x-table.table-td>{{ $index + 1 }}</x-table.table-td>
                                    <x-table.table-td>{{ $room->nama_ruangan }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $room->nama_gedung }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $room->kode_ruangan }}</x-table.table-td>
                                    <td class="px-2 py-2 text-sm text-hitam">
                                        <div class="flex justify-center items-center space-x-1">
                                            {{-- Button Edit --}}
                                            <a href="{{ route('ruangan.edit', $room->id_ruangan) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-biru-600 text-white text-sm rounded hover:bg-biru-700">
                                                <i class="ph ph-pencil-simple"></i>
                                            </a>

                                            {{-- Button Delete --}}
                                            {{-- <form action="{{ route('mahasiswa.destroy', $m->id_mahasiswa) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-merah-500 text-white text-sm rounded hover:bg-merah-600">
                                                    <i class="ph ph-trash-simple"></i>
                                                </button>
                                            </form> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
