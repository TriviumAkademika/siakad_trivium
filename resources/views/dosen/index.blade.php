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

                    {{-- Judul dan Tombol Tambah dosen --}}
                    <div class="flex justify-between items-center w-full">
                        <h1 class="text-xl  ">Data Dosen</h1>
                        <a href="{{ route('dosen.create') }}">
                            <x-button.submit icon="ph ph-plus">
                                Tambah Dosen
                            </x-button.submit>
                        </a>
                    </div>

                    {{-- Button Tambah Data Mahasiswa
                    <h1>Data dosen</h1>
                    <a href="{{ route('dosen.create') }}">
                        <x-button.submit icon="ph ph-plus">
                            Tambah dosen
                        </x-button.submit>
                    </a> --}}

                    {{-- Tabel Data Mahasiswa --}}
                    <table class="min-w-full divide-y divide-hitam bg-putih shadow rounded-lg">
                        <thead class="bg-brand-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-hitam">#</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Nama Dosen</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">NIP</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Alamat</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">No HP</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-hitam">Action</th>
                            </tr>
                        </thead>

                        <tbody class="bg-putih divide-y divide-gray-200">
                            @foreach ($dosen as $index => $d)
                                <tr>
                                    <x-table.table-td>{{ $index + 1 }}</x-table.table-td>
                                    <x-table.table-td>{{ $d->nama_dosen }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $d->nip }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $d->alamat }}</x-table.table-td>
                                    <x-table.table-td class="text-center">{{ $d->no_hp }}</x-table.table-td>
                                    <td class="px-2 py-2 text-sm text-hitam">
                                        <div class="flex justify-center items-center space-x-1">
                                            {{-- Button Edit --}}
                                            <a href="{{ route('dosen.edit', $d->id_dosen) }}"
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
